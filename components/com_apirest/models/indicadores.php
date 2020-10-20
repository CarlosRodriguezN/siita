<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

//  Adjunto libreria de gestion de Indicadores
jimport('ecorae.objetivos.objetivo.indicadores.indicadores');

//  Adjunto libreria de gestion de Hechos Indicadores
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'indicador.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'alineacion' . DS . 'alineacionexterna.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'enfoque.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'planinstitucion.php';

JTable::addIncludePath(JPATH_BASE . DS . 'components' . DS . 'com_apirest' . DS . 'tables');

/**
 * Modelo Fase
 */
class ApiRestModelIndicadores extends JModelAdmin
{

    /**
     * Method to get the record form.
     *
     * @param	array	$data		Data for the form.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	mixed	A JForm object on success, false on failure
     * @since	1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_apirest.url', 'url', array( 'control' => 'jform', 'load_data' => $loadData ));
        if( empty($form) ){
            return false;
        }

        return $form;
    }


    /**
     * 
     * Obtengo informacion de la institucion en funcion al token que esten
     * 
     * @return object
     */
    private function _getDtaUrlPorToken()
    {
        $input = new JInput;
        $tbUrl = $this->getTable( 'Url', 'ApiRestTable' );

        return $tbUrl->dtaUrlPorToken( $input->get( 'token' ) );
    }
    
    
    private function _validarIPCliente( $dtaUrl )
    {
        $ban        = false;
        $input      = new JInput;
        $ipCliente  = ip2long( $input->server->get( 'SERVER_ADDR' ) );
        $lstIPs     = explode( ',', $dtaUrl->ips );

        if( count( $lstIPs ) ){
            foreach( $lstIPs as $ip ){
                if( $ipCliente == (int)$ip ){
                    $ban = true;
                    break;
                }
            }
        }

        return $ban;
    }
    
    private function _validarFechas( $dtaUrl )
    {
        $ban = false;
        $fchActual  = new DateTime( date( "Y-m-d" ) );
        $fchInicio  = new DateTime( $dtaUrl->fchInicio );
        $fchFin     = new DateTime( $dtaUrl->fchFin );
        
        if( $fchInicio <= $fchActual && $fchFin >= $fchActual ){
            $ban = true;
        }
        
        return $ban;
    }
    
    
    private function _validarUrl()
    {
        $rst = true;
        $oDtaUrl = $this->_getDtaUrlPorToken();

        switch( true )
        {
            case( empty( $oDtaUrl ) == true ):
                $rst["status"]["statusCode"]= 403;
                $rst["status"]["statusDesc"]= 'Prohibido';
                $rst["status"]["message"]   = JText::_( 'COM_APIREST_ERROR_TOKEN' );
            break;
        
            case( $this->_validarIPCliente( $oDtaUrl ) == FALSE ):
                $rst["status"]["statusCode"]= 403;
                $rst["status"]["statusDesc"]= 'Prohibido';
                $rst["status"]["message"]   = JText::_( 'COM_APIREST_ERROR_IP' );
                
            break;
        
            case( $this->_validarFechas( $oDtaUrl ) == FALSE ):
                $rst["status"]["statusCode"]= 403;
                $rst["status"]["statusDesc"]= 'Prohibido';
                $rst["status"]["message"]   = JText::_( 'COM_APIREST_ERROR_FECHAS' );
            break;
        }

        
        return $rst;
    }

    public function getDataIndicadores()
    {
        $rst = $this->_validarUrl();

        if( $rst === true ){
            $rst = $this->_getDtaIndicadores();
            $message = JText::_( 'COM_APIREST_ACCESO_PERMITIDO' );
        }else{
            $message = $rst["status"]["message"];
        }
                
        //  Registro en lo comportamiento
        $this->_setLog( $message );
        
        return $rst;
    }
    
    
    private function _setLog( $rst )
    {
        $input = new JInput;

        //  Adjunto Libreria de gestion de Logs
        jimport('joomla.log.log');
        
        JLog::addLogger(    array(  'text_file' => 'com_apirest.log.php',
                                    'text_file_path' => JPATH_BASE.'/logs',
                                    'text_entry_format' => '{DATE};{TIME};{CLIENTIP};{MESSAGE}'
                             )
                        );

        $logEntry = new JLogEntry( 'ECORAE - APIREST' );
        $logEntry->clientip = $input->server->get( 'SERVER_ADDR' );
        $logEntry->message  = ( $rst === true ) ? JText::_( 'COM_APIREST_ACCESO_PERMITIDO' ) 
                                                : $rst;

        JLog::add( $logEntry );
        
        return;
    }
    
    
    
    private function _getIndicadores()
    {
        $db = JFactory::getDBO();
        $input = new JInput;

        $dtaFechas = $this->_getFechasPOAVigente();

        $idIndicador= $input->getInt( 'id', 0 );
        $fchInicio  = $input->getInt( 'fecha_inicio', $dtaFechas->fchInicio );
        $fchFin     = $input->getInt( 'fecha_fin', $dtaFechas->fchFin );
        $idEnfoque  = $input->getInt( 'identificador_enfoque', 0 );
        $idDimension= $input->getInt( 'identificador_dimension', 0 );

        $tbIndicadores = new jTableIndicador( $db );

        return $tbIndicadores->getIndicador(    $idIndicador, 
                                                $fchInicio, 
                                                $fchFin, 
                                                $idEnfoque, 
                                                $idDimension );
    }

    /**
     * 
     * Seteo Informacion de Indicadores pertenecientes a un proyecto 
     * asociado por su entidad
     * 
     * @return type
     */
    private function _getDtaIndicadores()
    {
        $lstIndicadores = array();
        
        $dtaIndicadores = $this->_getIndicadores();

        //  Seteo informacion de Indicadores en el Formulario
        if( count($dtaIndicadores) ){
            
            $rst["status"]["statusCode"]= 200;
            $rst["status"]["statusDesc"]= 'OK';

            foreach( $dtaIndicadores as $indicador ){
                //  Agrego el tipo de indicador - Alias de Unidad de Analisis
                $dtoIndicador["idIndicador"]                                = strtolower( $indicador->idIndicador );
                $dtoIndicador["nombre_del_Indicador"]                       = strtolower( $indicador->nombreIndicador );
                $dtoIndicador["definicion"]                                 = strtolower( $indicador->descripcionIndicador );
                $dtoIndicador["formula_de_calculo"]["formula"]              = strtolower( $indicador->Formula );
                $dtoIndicador["formula_de_calculo"]["lista_de_Variables"]   = $this->_getVariablesIndicador( $indicador->idIndicador );

                $dtoIndicador["valor_meta"]                                 = $indicador->valorMeta;
                $dtoIndicador["tendencia"]                                  = strtolower( $indicador->tendencia );
                
                $dtoIndicador["unidad_de_medida"]["tipo_unidad_medida"]     = strtolower( $indicador->tipoUnidadMedida );
                $dtoIndicador["unidad_de_medida"]["unidad_medida"]          = strtolower( $indicador->unidadMedida );
                
                $dtoIndicador["unidad_de_Analisis"]                         = (int)$indicador->unidadAnalisis;
                $dtoIndicador["fuente_de_datos"]                            = JText::_( 'COM_APIREST_FUENTE' );
                $dtoIndicador["periodicidad_del_indicador"]                 = strtolower( $indicador->frecuenciaMonitoreo );
                
                $dtoIndicador["disponiblidad_de_datos"]["fecha_de_inicio"]  = $indicador->fechaInicioIndicador;
                $dtoIndicador["disponiblidad_de_datos"]["fecha_de_fin"]     = $indicador->fechaFinIndicador;
                
                $dtoIndicador["nivel_de_desagregacion"]["general"]          = $this->_getEnfoquesIndicador( (int)$indicador->idIndicador );
                $dtoIndicador["nivel_de_desagregacion"]["geografico"]       = $this->_procesarUnidadTerritorial( (int)$indicador->idIndEntidad );
                $dtoIndicador["relacion_instrumentos_de_planificacion"]     = $this->_procesarAgendas( (int)$indicador->idIndicador );

                $dtoIndicador["fecha_creacion"]                             = is_null( $indicador->fechaCreacion ) 
                                                                                    ? '' 
                                                                                    : $indicador->fechaCreacion;
                
                $dtoIndicador["fecha_modificacion"]                         = is_null( $indicador->fechaModificacion ) 
                                                                                    ? '' 
                                                                                    : $indicador->fechaCreacion;

                $lstIndicadores[] = $dtoIndicador;
            }
            
            $rst["indicadores"] = $lstIndicadores;

        }else{
            $rst["status"]["statusCode"]= 404;
            $rst["status"]["statusDesc"]= 'Recurso no encontrado';
            $rst["status"]["message"]   = JText::_( 'COM_APIREST_INDICADOR_NO_REGISTRADO' );
        }

        return $rst;
    }
    
    
    
    private function _getVariablesIndicador( $idIndicador )
    {
        $listaVariables = array();
        $infoIndicador = new Indicadores();
        $lstVariables = $infoIndicador->getLstVariables( $idIndicador );

        foreach( $lstVariables as $variable ){
            $dtaVariable["nombre"]                  = strtolower( $variable->nombre );
            $dtaVariable["descripcion"]             = strtolower( $variable->descripcion );
            $dtaVariable["unidad_de_Medida"]        = strtolower( $variable->undMedida );
            $dtaVariable["unidad_de_analisis"]      = strtolower( $variable->undAnalisis );
            $dtaVariable["seguimiento_de_variable"] = $this->_procesarValoresSeguimiento( $variable->lstSeguimiento );

            $listaVariables[] = $dtaVariable;
        }

        return $listaVariables;
    }
    
    
    private function _procesarValoresSeguimiento( $lstSeguimiento )
    {
        $dtaSeguimiento = array();

        foreach( $lstSeguimiento as $seguimiento ){
            $dta = array();
            $dta["fecha"] = $seguimiento->fecha;
            $dta["valor"] = $seguimiento->valor;
            
            $dtaSeguimiento[] = $dta;
        }
        
        return $dtaSeguimiento;
    }
    
    private function _getEnfoquesIndicador( $idIndicador )
    {
        $dtaEnfoques = array();
        $infoIndicador = new Indicadores();
        $lstEnfoques = $infoIndicador->getLstDimensiones( $idIndicador );

        foreach ( $lstEnfoques as $enfoque ){
            $dta["enfoque"]     = strtolower( $enfoque->enfoque );
            $dta["dimension"]   = strtolower( $enfoque->dimension );

            $dtaEnfoques[] = $dta;
        }

        return $dtaEnfoques;
    }
    
    
    
    private function _procesarUnidadTerritorial( $idIndEntidad )
    {
        $dtaUT = array();
        $infoIndicador = new Indicadores();
        $lstUT = $infoIndicador->getLstIndUndTerritorial( $idIndEntidad );
        
        foreach( $lstUT as $ut ){
            $dta["provincia"]   = strtolower( $ut->provincia );
            $dta["canton"]      = strtolower( $ut->canton );
            $dta["parroquia"]   = strtolower( $ut->parroquia );
            
            $dtaUT[] = $dta;
        }
        
        return $dtaUT;
    }
    
    
    private function _procesarAgendas( $idIndicador )
    {
        $dtaAgendas     = array();
        $infoIndicador  = new Indicadores();
        $lstAgendas     = $infoIndicador->getAgendas( $idIndicador );

        foreach( $lstAgendas as $agenda ){
            $dta["agenda"]  = strtolower( $agenda->agenda );
            $dta["nivel_1"] = strtolower( $agenda->gerarquia_1 );
            $dta["nivel_2"] = strtolower( $agenda->gerarquia_2 );
            $dta["nivel_3"] = strtolower( $agenda->gerarquia_3 );
            
            $dtaAgendas[] = $dta;
        }

        return $dtaAgendas;
    }
    
    
    private function _getFechasPOAVigente()
    {
        $db = & JFactory::getDBO();
        $tbPlnInstitucion = new jTablePlanInstitucion( $db );
        
        return $tbPlnInstitucion->getFechasPlanVigente();
    }
    
    
    public function getDataEnfoques()
    {
        $rst = $this->_validarUrl();

        if( $rst === true ){
            $rst = $this->_getDtaEnfoques();
        }

        //  Registro en lo comportamiento
        $this->_setLog( $rst );
        
        return $rst;
    }

    
    private function _getDtaEnfoques()
    {
        $db = & JFactory::getDBO();
        $tbEnfoque = new jTableEnfoque( $db );
        
        return $tbEnfoque->getLstEnfoques();
    }

}
