<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla modelform library
jimport( 'joomla.application.component.modeladmin' );

//  Adjunto libreria de gestion de Indicador
jimport( 'ecorae.objetivos.objetivo.indicadores.indicador' );

//  Adjunto libreria de gestion de Indicadores
jimport( 'ecorae.objetivos.objetivo.indicadores.indicadores' );

//  Gestion de objetivos operativos
jimport( 'ecorae.objetivosOperativos.objetivoOperativo' );

//  Adjunto libreria de gestion de carga de archivos
jimport( 'ecorae.uploadfile.upload' );

jimport( 'ecorae.entidad.proyecto' );
jimport( 'ecorae.entidad.programa' );
jimport( 'ecorae.entidad.contrato' );
jimport( 'ecorae.entidad.convenio' );
jimport( 'ecorae.entidad.entidad' );
jimport( 'ecorae.unidadgestion.unidadgestion' );
jimport( 'ecorae.database.table.planinstitucion' );
jimport( 'ecorae.entidad.EstadoEntidad' );


require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'planinstitucion'. DS .'PlanOperativo.php';

//  Agrego Clase de Retorna informacion de Indicadores asociados a un Objetivo
jimport( 'ecorae.common.TicketTableu' );

//  Clase de gestion de Objetivos Planificacion Operativa
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'GestionOPO.php';

//  Clase de gestion de Imagenes
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'common' . DS . 'imagen.php';

JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_proyectos' . DS . 'tables' );

/**
 * Modelo Fase
 */
class ProyectosModelProyecto extends JModelAdmin
{
    private     $_dtaMarcoLogico;
    private     $_idEntidad;
    protected   $_dtaIndicadores;

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable( $type = 'Proyecto', $prefix = 'ProyectosTable', $config = array() )
    {
        return JTable::getInstance( $type, $prefix, $config );
    }

    /**
     * 
     * Metodo de retorna el formulario, en caso que sea de un registro existente 
     * gestiona la informacion y retorna un formulario con datos
     * caso contrario retorna solo el formulario
     *
     * @param	array	$data		Data for the form.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	mixed	A JForm object on success, false on failure
     * @since	1.6
     * 
     */
    public function getForm( $data = array(), $loadData = true )
    {
        // Get the form.
        $form = $this->loadForm( 'com_proyectos.proyecto', 'proyecto', array('control' => 'jform', 'load_data' => $loadData) );
        
        //  Obtengo informacion sobre el identificador Id del registro
        $idProyecto = $form->getField( 'intCodigo_pry' )->value;

        //  Obtengo informacion de identificador de la entidad relacionada con 
        //  el proyecto
        $this->_idEntidad = $idEntidad = $form->getField( 'intIdEntidad_ent' )->value;

        //  Seteo Informacion de indicadores de un Proyecto
        $dtaIndicadores = $this->_setDataIndicadoresProyecto();

        //  Seteo Informacion de indicadores fijos en el formulario
        $this->_setDtaFrmIndFijos( $form, $dtaIndicadores );

        //  Datos de INDICADORES Registrados
        $form->setFieldAttribute( 'dataIndicadores', 'default', json_encode( $dtaIndicadores ) );
        
        //  Datos de los sectores de intervencion 
        $this->_setDtaSectoresIntervencion( $form, $idProyecto );
        
        //  En caso que el identificador del proyecto sea diferente de "0"
        //  Accedo a Informacion referente a otras tablas relacionadas con 
        //  el componente
        if( $idProyecto != 0 ){
            //  Seteo Informacion del valor Meta / Umbral de un proyecto
            $this->_setDataMeta( $form, $idProyecto );

            //  Datos de PLANIFICACION de un proyecto ( POA - Proyecto )
            $dtaPlanificacion = $this->_getDtaPlanes();
            $form->setFieldAttribute( 'dtaPlanificacion', 'default', json_encode( $dtaPlanificacion ) );
            
            //  Datos de MARCO LOGICO de un proyecto
            $this->_setDataMarcoLogico( $form, $idProyecto );
        }

        if( empty( $form ) ){
            return false;
        }

        return $form;
    }
    
    
    /**
     * 
     * @param type $form
     * @return type
     */
    private function _setDataMeta( $form, $idProyecto )
    {
        //  Instancio la Tabla Proyecto
        $tbProyecto = $this->getTable();

        //  Accedo a la tabla y recupero informacion de la meta de un determinado proyecto
        $dataMeta = $tbProyecto->getDataMeta( $idProyecto );

        //  Set de los atributos extas;
        $this->_setAributesValues( $form, $this->_idEntidad, $idProyecto );

        //  Seteo Informacion de Meta
        if( $dataMeta ){
            $form->setFieldAttribute( 'descripcionMeta', 'default', $dataMeta->meta );
            $form->setFieldAttribute( 'valorMeta', 'default', $dataMeta->valorMeta );
        }
        
        return;
    }
            
        
    /**
     * 
     * Seteo Informacion de Indicadores pertenecientes a un proyecto 
     * asociado por su entidad
     * 
     * @return type
     */
    private function _setDataIndicadoresProyecto()
    {
        $indicadores = array();

        //  Instancio la Clase Indicadores
        $objIndicadores = new Indicadores();
        $dtaIndicadores = $objIndicadores->getLstIndicadores( $this->_idEntidad );

        //  Seteo informacion de Indicadores en el Formulario
        if( count( $dtaIndicadores ) ){

            foreach( $dtaIndicadores as $indicador ){
                //  Agrego el tipo de indicador - Alias de Unidad de Analisis
                $indicador->tpoIndicador                = $indicador->alias;

                $dtoIndicador["idIndEntidad"]           = $indicador->idIndEntidad;
                $dtoIndicador["idIndicador"]            = $indicador->idIndicador;
                $dtoIndicador["nombreIndicador"]        = $indicador->nombreIndicador;
                $dtoIndicador["modeloIndicador"]        = $indicador->modeloIndicador;
                $dtoIndicador["umbral"]                 = $indicador->umbral;
                $dtoIndicador["tendencia"]              = (int) $indicador->tendencia;
                $dtoIndicador["descripcion"]            = $indicador->descripcion;
                $dtoIndicador["idUndAnalisis"]          = (int) $indicador->idUndAnalisis;
                $dtoIndicador["undAnalisis"]            = (int) $indicador->undAnalisis;
                $dtoIndicador["idTpoUndMedida"]         = (int) $indicador->idTpoUndMedida;
                $dtoIndicador["idUndMedida"]            = (int) $indicador->idUndMedida;
                $dtoIndicador["undMedida"]              = (int) $indicador->undMedida;
                $dtoIndicador["idTpoIndicador"]         = $indicador->idTpoIndicador;
                $dtoIndicador["formula"]                = $indicador->formula;
                $dtoIndicador["fchHorzMimimo"]          = $indicador->fchHorzMimimo;
                $dtoIndicador["fchHorzMaximo"]          = $indicador->fchHorzMaximo;
                $dtoIndicador["umbMinimo"]              = $indicador->umbMinimo;
                $dtoIndicador["idHorizonte"]            = $indicador->idHorizonte;
                $dtoIndicador["idClaseIndicador"]       = $indicador->idClaseIndicador;
                $dtoIndicador["idFrcMonitoreo"]         = $indicador->idFrcMonitoreo;
                $dtoIndicador["idUGResponsable"]        = $indicador->idUGResponsable;
                $dtoIndicador["idResponsableUG"]        = $indicador->idResponsableUG;
                $dtoIndicador["fchInicioUG"]            = $indicador->fchInicioUG;
                $dtoIndicador["idResponsable"]          = $indicador->idResponsable;
                $dtoIndicador["fchInicioFuncionario"]   = $indicador->fchInicioFR;
                $dtoIndicador["idDimension"]            = $indicador->idDimension;
                $dtoIndicador["idEnfoque"]              = $indicador->idEnfoque;
                $dtoIndicador["enfoque"]                = $indicador->enfoque;
                $dtoIndicador["idCategoria"]            = $indicador->categoriaInd;
                $dtoIndicador["idDimension"]            = $indicador->idDimension;
                $dtoIndicador["idDimIndicador"]         = $indicador->idDimIndicador;

                //  Informacion complementaria
                $dtoIndicador["metodologia"]            = $indicador->metodologia;
                $dtoIndicador["limitaciones"]           = $indicador->limitaciones;
                $dtoIndicador["interpretacion"]         = $indicador->interpretacion;
                $dtoIndicador["disponibilidad"]         = $indicador->disponibilidad;
                $dtoIndicador["senplades"]              = $indicador->senplades;

                $dtoIndicador["lstUndsTerritoriales"]   = $objIndicadores->getLstIndUndTerritorial( $indicador->idIndEntidad );
                $dtoIndicador["lstLineaBase"]           = $objIndicadores->getLstLineasBase( $indicador->idIndicador );
                $dtoIndicador["lstRangos"]              = $objIndicadores->getLstRangos( $indicador->idIndEntidad );
                $dtoIndicador["lstVariables"]           = $objIndicadores->getLstVariables( $indicador->idIndicador );
                $dtoIndicador["lstDimensiones"]         = $objIndicadores->getLstDimensiones( $indicador->idIndicador );
                $dtoIndicador["lstPlanificacion"]       = $objIndicadores->getLstPlanificacion( $indicador->idIndEntidad );

                $dtoIndicador["published"] = 1;

                switch( true ){
                    //  Armo informacion de indicadores Fijos - Economicos ( 1 )
                    case( $indicador->categoriaInd == 1 ):
                        $lstIndEconomicos[] = (object)$dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Fijos - Financieros ( 2 )
                    case( $indicador->categoriaInd == 2 ):
                        $lstIndFinancieros[] = (object)$dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Fijos - Beneficiarios Directos ( 1 )
                    case( $indicador->categoriaInd == 3 ):
                        $lstBDirectos[] = (object)$dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Fijos - Beneficiarios Indirectos ( 1 )
                    case( $indicador->categoriaInd == 4 ):
                        $lstBIndirectos[] = (object)$dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Dinamico - GAP ( 2 )
                    case( $indicador->categoriaInd == 5 ):
                        $lstTmpGAP[] = $dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Dinamico - Enfoque de Igualdad ( 2 )
                    case( $indicador->categoriaInd == 6 ):                        
                        $lstTmpEI[] = $dtoIndicador;
                    break;

                    //  Armo informacion de indicadores Dinamico - Enfoque de Ecorae ( 2 )
                    case( $indicador->categoriaInd == 7 ):
                        $lstTmpEE[] = $dtoIndicador;
                    break;
                }
            }

            $indicadores["indEconomicos"]   = $this->_procesoIndicadores( 31, $lstIndEconomicos );
            $indicadores["indFinancieros"]  = $this->_procesoIndicadores( 32, $lstIndFinancieros );
            $indicadores["indBDirectos"]    = $this->_procesoIndicadores( 33, $lstBDirectos );
            $indicadores["indBIndirectos"]  = $this->_procesoIndicadores( 34, $lstBIndirectos );

            //  Seteo indicadores de tipo GAP
            $indicadores["lstGAP"] = ( count( $lstTmpGAP ) )? $objIndicadores->getLstGAP( $lstTmpGAP ) 
                                                            : array();

            //  Seteo indicadores de tipo Enfoque de Igualdad
            $indicadores["lstEnfIgualdad"] = ( count( $lstTmpEI ) ) ? $objIndicadores->getLstEI( $lstTmpEI ) 
                                                                    : array();

            //  Seteo indicadores de tipo Enfoque de ECORAE
            $indicadores["lstEnfEcorae"] = ( count( $lstTmpEE ) )   ? $objIndicadores->getLstEE( $lstTmpEE ) 
                                                                    : array();
            
            //  Seteo informacion de INDICADORES especificos, catalogados como "Otros Indicadores"
            $indicadores["lstOtrosIndicadores"] = $this->getOtrosIndicadores();
        }else{
            $indicadores["indEconomicos"]       = $this->_getPtllaIndicador( 31 );
            $indicadores["indFinancieros"]      = $this->_getPtllaIndicador( 32 );
            $indicadores["indBDirectos"]        = $this->_getPtllaIndicador( 33 );
            $indicadores["indBIndirectos"]      = $this->_getPtllaIndicador( 34 );

            $indicadores["lstGAP"]              = array();
            $indicadores["lstEnfIgualdad"]      = array();
            $indicadores["lstEnfEcorae"]        = array(); 
            $indicadores["lstOtrosIndicadores"] = array();
        }

        return $indicadores;
    }
    
    
    /**
     * 
     * Seteo Informacion del marco Logico de un proyecto
     * 
     * @param type $form
     * @param type $idProyecto
     * 
     * @return type
     * 
     */
    private function _setDataMarcoLogico( $form, $idProyecto )
    {
        //  Obtengo informacion de marco logico
        $this->_dtaMarcoLogico = $this->dtaMarcoLogico( $idProyecto );

        if( $this->_dtaMarcoLogico ){
            //  Registro informacion de Fin
            $form->setFieldAttribute( 'txtNombreFin', 'default', $this->_dtaMarcoLogico["Fin"]["nombre"] );
            $form->setFieldAttribute( 'strMLFin', 'default', $this->_dtaMarcoLogico["Fin"]["descripcion"] );

            //  Registro informacion de proposito
            $form->setFieldAttribute( 'txtNombreProposito', 'default', $this->_dtaMarcoLogico["Proposito"]["nombre"] );
            $form->setFieldAttribute( 'strMLProposito', 'default', $this->_dtaMarcoLogico["Proposito"]["descripcion"] );

            $form->setFieldAttribute( 'dtaMarcoLogico', 'default', json_encode( $this->_dtaMarcoLogico ) );
        }
        
        return;
    }
    
    /**
     *  Seteo Informacion los Sectores de Intervencion de un proyecto
     * @param type $form
     * @param type $idProyecto
     * @return type
     */
    private function _setDtaSectoresIntervencion( $form, $idProyecto )
    {
        if ( $idProyecto != 0 ) {
            $idSSI = $form->getField( 'intIdStr_intervencion' )->value;
            $estructura = $this->getStrSecIntrv($idSSI);
            $subSector = $form->getField( 'inpCodigo_subsec' )->value;
            if ( $subSector ) {
                $tbSI = $this->getTable( 'SectoresIntervencion', 'ProyectosTable' );
                $invEstrustura = array_reverse($estructura);
                $sec = $tbSI->ownerSubSec($subSector);
                $owner = $sec->id;
                foreach ( $invEstrustura as $item ) {
                    $name = strtolower( str_replace(" ", "", $item->strDescripcion_esi ) );
                    if ($owner && !empty($owner)) {
                        switch ($name) {
                            case "subsector":
                                $form->setFieldAttribute( 'subsector', 'default', $subSector );
                            break;
                            case "sector":
                                $form->setFieldAttribute( 'sector', 'default', $owner );
                                $sec = $tbSI->ownerSector( $owner );
                                $owner = $sec->id;
                            break;
                            case "macrosector":
                                $form->setFieldAttribute( 'macrosector', 'default', $owner );
                            break;
                        }
                    }
                }
            }
        }
        return;
    }
    
    /**
     * 
     * Seteo Informacion de indicadores fijos en los formualrios
     * 
     * @param type $form
     * @param type $indicadores
     */
    private function _setDtaFrmIndFijos( $form, $indicadores )
    {
        //  Seteo Informacion de Indicadores Economicos en el formulario
        $this->_setDataIndEconomicos( $form, (object)$indicadores["indEconomicos"], 31 );

        //  Seteo Informacion de Indicadores Financieros en el formulario
        $this->_setDataIndFinancieros( $form, (object)$indicadores["indFinancieros"], 32 );

        //  Seteo Informacion de Beneficiarios Directos en el Formulario
        $this->_setDataBDirectos( $form, (object)$indicadores["indBDirectos"], 33 );

        //  Seteo Informacion de Beneficiarios Directos en el Formulario
        $this->_setDataBIndirectos( $form, (object)$indicadores["indBIndirectos"], 34 );
    }
    
    /**
     * 
     * Obtengo informacion especifica de un indicador 
     * 
     * @param int $idDimension      identificador de la dimension asociado al indicador
     * 
     * @return Object
     */
    private function _getPtllaIndicador( $idDimension )
    {
        $objIndicador = new Indicadores();
        return $objIndicador->getDtaPtllaPorDimension( $idDimension );
    }

    
    /**
     * 
     * Valido la creacion de indicadores 
     * 
     * @param int $idDimension     Identificador de dimension
     * @param array $lstIndicadores  Lista de indicadores a procesar
     * 
     * @return array
     */
    private function _procesoIndicadores( $idDimension, $lstIndicadores )
    {
        $lstInd = array();
        $ptllaIndicadores = $this->_getPtllaIndicador( $idDimension );
        
        foreach( $ptllaIndicadores as $pi ){
            //  Verifico al existencia del Indicador en funcion a su modelo
            $dtaInd = $this->_getIndPorModelo( $pi->modeloIndicador, $lstIndicadores );            
            if( $dtaInd ){
                $lstInd[] = $dtaInd;
            }else{
                $lstInd[] = $pi;
            }
        }
        
        return $lstInd;
    }
    
    
    private function _getIndPorModelo( $modeloIndicador, $lstIndicadores )
    {
        $dtaInd = false;
        
        foreach( $lstIndicadores as $ind ){
            if( $ind->modeloIndicador == $modeloIndicador ){
                $dtaInd = $ind;
            }
        }
        
        return $dtaInd;
    }
    
    
    
    /**
     *  
     *  Retorna informacion de Planificacion - Proyecto
     * 
     */
    private function _getDtaPlanes()
    {
        $opo = new GestionOPO();
        return $opo->getPlanesOperativo( $this->_idEntidad );
    }
    
    
    /**
     * Agrega los valores de los atributos extras.
     */
    private function _setAributesValues( $form, $idEntidad, $idProyecto )
    {
        //  Doy formato al monto de un proyecto
        $dtaMonto = round( $form->getField( 'dcmMonto_total_stmdoPry' )->value, 2 );
        $form->setValue( 'dcmMonto_total_stmdoPry', null, $dtaMonto );

        // set de la unidad de gestion responsable
        $funcionario = $this->getUnidadGestionProyecto();
        if( $funcionario ){
            $form->setValue( 'intIdUndGestion', null, $funcionario->idUnidadGestion );
            $form->setValue( 'fchInicioPeriodoUG', null, $funcionario->fechaInicio );
        }

        // set de la funcionario responsable del contrato.
        $responsable = $this->getResponsableProyecto();
        if( $responsable ){
            $form->setValue( 'intIdUGResponsable', null, $responsable->idUnidadGestion );
            $form->setValue( 'idResponsable', null, $responsable->undGestionFuncionario );
            $form->setValue( 'fchInicioPeriodoFuncionario', null, $responsable->fechaInicio );
        }
        
        // set la informacion del organigrama
        $organigrama = $this->_getOrganigramaByEntidad( $idEntidad );
        if( $organigrama ){
            $form->setValue( 'organigrama', null, $organigrama );
        }
        
        // Agrego la URL del tableU
        $URLTable = $this->_getUrlTable( $idEntidad );
        $form->setValue( 'strURLtableU', null, $URLTable->urlTableU );

        return;
    }

    /**
     * Retorna los responsables de un proyecto.
     * @return type
     */
    private function getResponsableProyecto()
    {
        $idProyecto = JRequest::getVar( 'intCodigo_pry' );
        $responsable = array();
        if( (int) $idProyecto != 0 ){
            $tbProrroga = $this->getTable( 'ResponsableProyecto', 'ProyectosTable' );
            $responsable = $tbProrroga->getResponsableProyecto( $idProyecto );
        }
        return $responsable;
    }

    /**
     *  retorna toda la informacion de una entidad.
     * @param int $idEntidad    Identificador de la entidad.
     * @return type
     */
    private function _getUrlTable( $idEntidad )
    {
        $oEntidad = new Entidad();
        $dtaEntidad = $oEntidad->getEntidad( $idEntidad );
        return $dtaEntidad;
    }

    /**
     * Recupera la UNIDAD DE GESTION DE UN CONTRATO.
     * @return type
     */
    private function getUnidadGestionProyecto()
    {
        $idProyecto = JRequest::getVar( 'intCodigo_pry' );
        $responsable = array();
        if( (int) $idProyecto != 0 ){
            $tbProrroga = $this->getTable( 'UnidadGestionProyecto', 'ProyectosTable' );
            $responsable = $tbProrroga->getUnidadGestionProyecto( $idProyecto );
        }
        return $responsable;
    }

    /**
     * 
     * Retorna informacion de Marco Logico
     * 
     * @return type
     */
    public function getDataML()
    {
        return $this->_dtaMarcoLogico;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState( 'com_proyectos.edit.proyectos.data', array() );

        if( empty( $data ) ){
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * 
     * Retorno lista de Unidades Territorial
     * 
     * @return type
     */
    public function getLstUnidadTerritorial()
    {
        $tbUndTerritorial = $this->getTable( 'UnidadTerritorial', 'ProyectosTable', $config = array() );
        return $tbUndTerritorial->getLstUnidadTerritorial( JRequest::getInt( 'intCodigo_pry' ) );
    }

    /**
     * 
     * Retorna Informacion de un determinado indicador entidad
     *  
     * @param type $idIndEntidad    Identificador Indicador Entidad
     * @return type
     * 
     */
    public function getDtaIndicador( $idIndEntidad )
    {
        //  Instancio la Clase Indicadores
        $objIndicadores = new Indicadores();
        $dtaIndicador = $objIndicadores->getDtaIndEntidad( $idIndEntidad );
        $dtaIndEntidad = array();

        //  Seteo informacion de Indicadores en el Formulario
        if( $dtaIndicador ){
            $dtaIndEntidad["idIndEntidad"] = $dtaIndicador->idIndEntidad;
            $dtaIndEntidad["idIndicador"] = $dtaIndicador->idIndicador;
            $dtaIndEntidad["nombreIndicador"] = $dtaIndicador->nombreIndicador;
            $dtaIndEntidad["modeloIndicador"] = $dtaIndicador->modeloIndicador;
            $dtaIndEntidad["umbral"] = $dtaIndicador->umbral;
            $dtaIndEntidad["descripcion"] = $dtaIndicador->descripcion;
            $dtaIndEntidad["undAnalisis"] = $dtaIndicador->undAnalisis;
            $dtaIndEntidad["tpoUndMedida"] = $dtaIndicador->tpoUndMedida;
            $dtaIndEntidad["undMedida"] = $dtaIndicador->undMedida;
            $dtaIndEntidad["tpoIndicador"] = $dtaIndicador->tpoIndicador;

            $dtaIndEntidad["tendencia"] = is_null( $dtaIndicador->tendencia ) ? "" : $dtaIndicador->tendencia;

            $dtaIndEntidad["formula"] = is_null( $dtaIndicador->formula ) ? "" : $dtaIndicador->formula;

            $dtaIndEntidad["idFrcMonitoreo"] = $dtaIndicador->idFrcMonitoreo;
            $dtaIndEntidad["frcMonitoreo"] = $dtaIndicador->frcMonitoreo;

            $dtaIndEntidad["fchHorzMimimo"] = is_null( $dtaIndicador->fchHorzMimimo ) ? "" : $dtaIndicador->fchHorzMimimo;

            $dtaIndEntidad["fchHorzMaximo"] = is_null( $dtaIndicador->fchHorzMaximo ) ? "" : $dtaIndicador->fchHorzMaximo;

            $dtaIndEntidad["umbMinimo"] = is_null( $dtaIndicador->umbMinimo ) ? "" : $dtaIndicador->umbMinimo;

            $dtaIndEntidad["umbMaximo"] = is_null( $dtaIndicador->umbMaximo ) ? "" : $dtaIndicador->umbMaximo;

            $dtaIndEntidad["claseIndicador"] = is_null( $dtaIndicador->claseIndicador ) ? "" : $dtaIndicador->claseIndicador;

            $dtaIndEntidad["enfoque"] = is_null( $dtaIndicador->enfoque ) ? "" : $dtaIndicador->enfoque;

            $dtaIndEntidad["dimension"] = is_null( $dtaIndicador->dimension ) ? "" : $dtaIndicador->dimension;

            $dtaIndEntidad["idUGResponsable"] = $dtaIndicador->idUGResponsable;
            $dtaIndEntidad["idResponsableUG"] = $dtaIndicador->idResponsableUG;
            $dtaIndEntidad["idResponsable"] = $dtaIndicador->idResponsable;

            $dtaIndEntidad["UGResponsable"] = $dtaIndicador->UGResponsable;
            $dtaIndEntidad["UGFuncionario"] = $dtaIndicador->responsableUG;
            $dtaIndEntidad["funcionario"] = $dtaIndicador->responsable;

            $dtaIndEntidad["lstUndTerritorial"] = $objIndicadores->getLstIndUndTerritorial( $dtaIndicador->idIndEntidad );
            $dtaIndEntidad["lstLineaBase"] = $objIndicadores->getLstLineasBase( $dtaIndicador->idIndicador );
            $dtaIndEntidad["lstRangos"] = $objIndicadores->getLstRangos( $dtaIndicador->idIndEntidad );
            $dtaIndEntidad["lstVariables"] = $objIndicadores->getLstVariables( $dtaIndicador->idIndicador );
        }

        return $dtaIndEntidad;
    }

    /**
     *  Retorna una lista de cantones pertenecientes a una determinada Provincia
     * 
     *  @return type 
     */
    public function getCantones()
    {
        $tbUndTerritorial = $this->getTable( 'UnidadTerritorial', 'ProyectosTable', $config = array() );
        return $tbUndTerritorial->lstCantones( JRequest::getInt( 'idProvincia' ) );
    }

    /**
     * 
     * Retorna una lista de subProgramas pertenecientes a un determinado Programa
     * 
     * @param type $idPrograma  Identificador del Programa
     * 
     * @return type
     *              
     */
    public function getLstSubProgramas( $idPrograma )
    {
        $tbPrograma = $this->getTable( 'Programa', 'ProyectosTable', $config = array() );
        return $tbPrograma->lstSubProgramas( $idPrograma );
    }

    /**
     * 
     * Retorna una lista de Tipos de SubProgramas Pertenecientes a un SubPrograma
     * 
     * @param type $idSubPrograma   Identificador de SubPrograma
     * 
     * @return type
     */
    public function getLstTiposSubProgramas( $idSubPrograma )
    {
        $tbPrograma = $this->getTable( 'Programa', 'ProyectosTable', $config = array() );
        return $tbPrograma->lstTiposSubProgramas( $idSubPrograma );
    }

    /**
     *
     *  Gestiona una lista de Parroquias pertenecientes a un Canton de una determinada Provincia
     * 
     *  @return type 
     * 
     */
    public function getParroquias()
    {
        $tbUndTerritorial = $this->getTable( 'UnidadTerritorial', 'ProyectosTable', $config = array() );
        return $tbUndTerritorial->lstParroquias( JRequest::getInt( 'idCanton' ) );
    }

    /**
     *  Retorna informacion de sectores de un determinado MacroSector
     * @param type $idMacroSector    Identificador de un MacroSector
     * @return type
     */
    public function getSectores( $idMacroSector, $idSIV )
    {
        $tbSector = $this->getTable( 'Sector', 'ProyectosTable', $config = array() );
        return $tbSector->getSectores( $idMacroSector, $idSIV );
    }
    
    /**
     * 
     * Retorna informacion de subsectores de un determinado Sector
     * 
     * @param type $idSector    Identificador de un Sector
     * @return type
     */
    public function getSubSector( $idSector, $idSIV )
    {
        $tbSector = $this->getTable( 'Sector', 'ProyectosTable', $config = array() );
        return $tbSector->getSubSectores( $idSector, $idSIV );
    }

    /**
     * 
     * Retorno lista de Politicas Nacionales
     * 
     * @return type
     */
    public function getLstPoliticaNacional()
    {
        $tbMNP = $this->getTable( 'MetaNacionalProyecto', 'ProyectosTable' );
        return $tbMNP->getLstPoliticaNacional( JRequest::getInt( 'idObjNac' ) );
    }

    /**
     * 
     * Retorno Lista de Metas Nacionales vinculadas a una Politica Nacional
     * 
     * @return type
     */
    public function getLstMetaNacional()
    {
        $tbMNP = $this->getTable( 'MetaNacionalProyecto', 'ProyectosTable' );
        return $tbMNP->getLstMetaNacional( JRequest::getInt( 'idObjNac' ), JRequest::getInt( 'idPolNac' ) );
    }

    /**
     * 
     * Retono Lista de Unidades de Medida vinculada a un determinado 
     * Tipo de Medida
     * 
     * @return type
     * getTiposEnfoqueIgualdad
     */
    public function getUndMedidaTipo()
    {
        $tbProyecto = $this->getTable( 'Proyecto', 'ProyectosTable', $config = array() );
        return $tbProyecto->getLstUnidadMedida( JRequest::getVar( 'idTpoUM' ) );
    }

    /**
     * 
     * Retorna las Metas del PNBV relacionadas con un determinado proyecto
     * 
     * @param type $idProyecto  Identificador del proyecto
     * 
     * @return type
     * 
     */
    public function getListMetasNacionalesProyecto( $idProyecto )
    {
        $tbMNP = $this->getTable( 'MetaNacionalProyecto', 'ProyectosTable', $config = array() );
        return $tbMNP->getLstMNP( $idProyecto );
    }

    /**
     * 
     * Retorna una lista de Enfoques de Igualdad de acuerdo al tipo de Enfoque
     * 
     * @param type $idTipo  Identificador del tipo de Enfoque
     * 
     * @return type
     */
    public function getLstEnfoqueIgualdad( $idTipo )
    {
        $tbIndicador = $this->getTable( 'Indicador', 'ProyectosTable', $config = array() );
        return $tbIndicador->getLstEnfoqueIgualdad( $idTipo );
    }

    /**
     * 
     * Retorna idProvincia, idCanton, idParroquia de una determinada DPA
     * 
     * @param type $idDPA   Identificador de unidad territorial
     */
    public function getDPA( $idDPA )
    {
        $tbUndTerritorial = $this->getTable( 'UnidadTerritorial', 'ProyectosTable', $config = array() );
        return $tbUndTerritorial->dtaDPA( $idDPA );
    }

    /**
     * 
     * Retorno informacion de Indicadores Financieros
     * 
     * @param type $idProyecto  Identificador del Proyecto
     * @return type
     * 
     */
    public function getDataIndFinancieros( $idProyecto )
    {
        //  Instancio Tabla Indicadores
        $tbIndicadores = JTable::getInstance( 'Indicador', 'ProyectosTable', array() );
        return $tbIndicadores->getLstDataFinancieros( $idProyecto );
    }

    /**
     * 
     * Retorno informacion de indicadores de beneficiarios
     * 
     * @param type $idProyecto  Identificador del proyecto
     * 
     * @return type
     * 
     */
    public function getDataIndBeneficiarios( $idProyecto )
    {
        //  Instancio Tabla Indicadores
        $tbIndicadores = JTable::getInstance( 'Indicador', 'ProyectosTable', array() );
        return $tbIndicadores->getLstIndBeneficiarios( $idProyecto );
    }

    /**
     * 
     * Retorna un lista de Indicadores GAP
     * 
     * @param type $idProyecto  Identificador del Proyecto
     * 
     * @return type Lista de Objetos
     */
    public function getIndGAP( $idProyecto )
    {
        $tbIndicadores = JTable::getInstance( 'Indicador', 'ProyectosTable', array() );
        return $tbIndicadores->getIndGAP( $idProyecto );
    }

    /**
     * 
     * Retorna la lista de enfoques de igualdad.
     * 
     * @param type $idProyecto          Identificador del proyecto
     * 
     * @return type Lista de objetos.
     * 
     */
    public function getTiposEnfoqueIgualdad( $idProyecto )
    {
        $tbIndicadores = JTable::getInstance( 'Indicador', 'ProyectosTable', array() );
        return $tbIndicadores->getLstTiposEnfoqueIgualdad( $idProyecto );
    }

    /**
     * 
     * Retorna la lista de enfoques de Ecorae.
     * 
     * @param type $idProyecto
     * @return type Lista de objetos.
     * 
     */
    public function getEnfoqueIgualdad( $idProyecto )
    {
        $tbIndicadores = JTable::getInstance( 'Indicador', 'ProyectosTable', array() );
        return $tbIndicadores->getLstEnfoquesIgualdad( $idProyecto );
    }

    /**
     * 
     * Retorna informacion de indicadores de tipo Enfoque Ecorae
     * 
     * @param type $idProyecto  Identificador del proyecto
     * 
     * @return type
     * 
     */
    public function getEnfoqueEcorae( $idProyecto )
    {
        $tbIndicadores = JTable::getInstance( 'Indicador', 'ProyectosTable', array() );
        return $tbIndicadores->getLstEnfoqueEcorae( $idProyecto );
    }

    /**
     * 
     * Retorna Lista de Otros Indicadores 
     * 
     * @param int $idProyecto  Identificador del proyecto
     * 
     * @return array Lista de Proyectos
     * 
     */
    public function getOtrosIndicadores()
    {
        $objIndicadores = new Indicadores();
        return $objIndicadores->getLstOtrosIndicadores( $this->_idEntidad );
    }

    /**
     *
     * Retorna una lista de Graficos que estan relacionados con un proyecto
     * 
     * @param type $idProyecto  Identificador del proyecto
     * 
     * @return type 
     * 
     */
    public function getLstGraficosProyecto( $idProyecto )
    {
        $tbGraficos = $this->getTable( 'Grafico', 'ProyectosTable' );

        $graficos = $tbGraficos->getGraficosProyecto( $idProyecto );
        if( $graficos ){
            foreach( $graficos AS $grafico ){
                $grafico->lstCoordenadas = $this->getCoordenadasGrafico( $grafico->idGrafico );
            }
        }
        return $graficos;
    }

    /*
     * Retorna una lista de Coordenadas que hacen referencia a los Graficos 
     * que esta relacionado un proyecto
     * 
     * @param type $idProyecto  identificador de un proyecto
     * 
     * @return type 
     */

    public function getCoordenadasGrafico( $idProyecto )
    {
        $tbCoordenadas = $this->getTable( 'Coordenada', 'ProyectosTable' );
        return $tbCoordenadas->getCoordenadasGrafico( $idProyecto );
    }

    /**
     * Recupera la lista de objetivos de una entidad en este caso un programa
     * @param int $idEntidad   identificador de la entidad de un programa
     * @return type
     */
    public function getLstObjetivos( $idEntidad )
    {
        $oObjetivoOperativo = new ObjetivoOperativo();
        $lstObjetivos = $oObjetivoOperativo->getObjetivosOperativos( $idEntidad );

        return $lstObjetivos;
    }

    /**
     *  Recupera la lista de indicadores entidad relacionados a un proyecto.
     * @param type $idObjetivo 
     */
    private function getIndEntByObjetivo( $idObjetivo )
    {
        $tbPryObjInd = $this->getTable( 'PryObjInd', 'ProyectosTable' );
        $lstIdIndEnt = $tbPryObjInd->getIndEntByObjetivo( $idObjetivo );
        return $lstIdIndEnt;
    }

    /**
     * 
     * Retorno informacion de proyecto
     * 
     * @param type $idProyecto  Identificador de Proyecto
     * 
     * @return type
     */
    public function getInfoProyecto( $idProyecto )
    {
        //  Instancio la tabla Proyecto
        $tbProyecto = $this->getTable();
        return $tbProyecto->getDataProyecto( $idProyecto );
    }

    /**
     *
     * Retorna una lista con Unidades Territoriales Registradas
     * 
     * @param type $idProyecto  Identificador del Proyecto
     * 
     * @return type 
     */
    public function getDataUndTerritorial( $idProyecto )
    {
        //  Instancio la tabla Proyecto
        $tbUndTerritorial = $this->getTable( 'UnidadTerritorial', 'ProyectosTable', $config = array() );
        return $tbUndTerritorial->getLstUnidadTerritorial( $idProyecto );
    }

    /**
     *
     * Retorna una lista de Objetivos pertenecientes a un determinado proyecto
     * 
     * @param type $idProyecto  Identificador de un proyecto
     * 
     * @return type 
     */
    public function getDataObjetivos( $idProyecto )
    {
        //  Instancio la tabla Objetivo
        $tbObjetivo = $this->getTable( 'Objetivo', 'ProyectosTable', $config = array() );
        return $tbObjetivo->getObjetivos( $idProyecto );
    }

    /**
     * 
     * Retorna una lista de funcionarios de acuerdo a una determinada 
     * Unidad de Gestion
     * 
     * @param type $idUndGestion    Identificador de Unidad de Gestion
     * 
     * @return type
     * 
     */
    public function getResponsable( $idUndGestion )
    {
        $tbFuncionario = $this->getTable( 'Funcionario', 'ProyectosTable' );
        return $tbFuncionario->getLstFuncionarios( $idUndGestion );
    }

    /**
     * 
     * Retorna Informacion de Lineas Base asociada a una determinada Fuente
     * 
     * @param type $idFuente
     * @return type
     */
    public function getLineasBase( $idFuente )
    {
        $tbLineaBase = $this->getTable( 'LineaBase', 'ProyectosTable' );
        return $tbLineaBase->getLineasBase( $idFuente );
    }

    /**
     * 
     * @param type $idUM
     * @return type
     */
    public function getVarUndMedida( $idUM )
    {
        $tbVariable = $this->getTable( 'Variable', 'ProyectosTable' );
        return $tbVariable->getVariablesPorUndMedida( $idUM );
    }

    /**
     * 
     * Seteo Informacion de indicadores Estaticos como Economicos / Financieros
     * 
     * @param Objeto $form              Formulario
     * @param Objeto $dtaIndicadores    Datos de Indicador
     * @param Int $idDimension          Identificador de Dimension
     * 
     * @return type
     */
    private function _setDataIndEconomicos( $form, $dtaIndicadores, $idDimension )
    {
        if( count( $dtaIndicadores ) ){
            foreach( $dtaIndicadores as $indicador ){
                switch( true ){
                    //  Tasa de Descuento
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 14 ) ):
                        $form->setFieldAttribute( 'intTasaDctoEco', 'default', (int)$indicador->umbral );
                    break;

                    //  Valor Actual Neto
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 15 ) ):

                        $form->setFieldAttribute( 'intValActualNetoEco', 'default', round( $indicador->umbral, 2 ) );
                    break;

                    //  Tasa Interna de Retorno
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 16 ) ):
                        $form->setFieldAttribute( 'intTIREco', 'default', (int)$indicador->umbral );
                    break;
                }
            }
        }

        return;
    }

    /**
     * 
     * Indicadores Financieros
     * 
     * @param type $form            Objeto Formulario
     * @param type $dtaIndicadores  Datos de Indicadores
     * @param type $idDimension     Datos de Dimension
     * 
     * @return type
     * 
     */
    private function _setDataIndFinancieros( $form, $dtaIndicadores, $idDimension )
    {
        if( count( $dtaIndicadores ) ){
            foreach( $dtaIndicadores as $indicador ){
                switch( true ){
                    //  Tasa de Descuento
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 14 ) ):
                        $form->setFieldAttribute( 'intTasaDctoFin', 'default', (int) $indicador->umbral );
                    break;

                    //  Valor Actual Neto
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 15 ) ):
                        $form->setFieldAttribute( 'intValActualNetoFin', 'default', round( $indicador->umbral ) );
                    break;

                    //  Tasa Interna de Retorno
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 16 ) ):
                        $form->setFieldAttribute( 'intTIRFin', 'default', (int) $indicador->umbral );
                    break;
                }
            }
        }

        return;
    }

    /**
     * 
     * Indicadores Financieros
     * 
     * @param type $form            Objeto Formulario
     * @param type $dtaIndicadores  Datos de Indicadores
     * @param type $idDimension     Datos de Dimension
     * 
     * @return type
     * 
     */
    private function _setDataBDirectos( $form, $dtaIndicadores, $idDimension )
    {
        if( count( $dtaIndicadores ) ){
            foreach( $dtaIndicadores as $indicador ){
                switch( true ){
                    //  Beneficiarios Directos Hombre
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 6 ) ):
                        $form->setFieldAttribute( 'intBenfDirectoHombre', 'default', ( int ) $indicador->umbral );
                    break;

                    //  Beneficiarios Directos Mujer
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 7 ) ):
                        $form->setFieldAttribute( 'intBenfDirectoMujer', 'default', ( int ) $indicador->umbral );
                    break;

                    //  Total Beneficiarios Directo
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 4 ) ):
                        $form->setFieldAttribute( 'intTotalBenfDirectos', 'default', ( int ) $indicador->umbral );
                    break;
                }
            }
        }

        return;
    }

    /**
     * 
     * Muestra en interfaz informacion de indicadores Indirectos
     * 
     * @param type $form                Formulario
     * @param type $dtaIndicadores      Datos de indicadores
     * @param type $idDimension         Identificador de la dimension
     * 
     * @return type
     */
    private function _setDataBIndirectos( $form, $dtaIndicadores, $idDimension )
    {
        if( count( $dtaIndicadores ) ){
            foreach( $dtaIndicadores as $indicador ){
                switch( true ){
                    //  Beneficiarios Directos Hombre
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 6 ) ):
                        $form->setFieldAttribute( 'intBenfIndDirectoHombre', 'default', (int) $indicador->umbral );
                    break;

                    //  Beneficiarios Directos Mujer
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 7 ) ):
                        $form->setFieldAttribute( 'intBenfIndDirectoMujer', 'default', (int) $indicador->umbral );
                    break;

                    //  Total Beneficiarios Directo
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 4 ) ):
                        $form->setFieldAttribute( 'intTotalBenfIndDirectos', 'default', (int) $indicador->umbral );
                    break;
                }
            }
        }

        return;
    }

/////////////////////////////
//  REGISTRO DE UN PROYECTO
/////////////////////////////    

    /**
     *  Gestiona el registro de la entidad de un programa
     * @param int    $idEntidad   Identificador de la entidad
     * @param String $urlTableU   URL tableU
     * @return int
     */
    public function saveEntidad( $idEntidad, $urlTableU )
    {
        $entidad = new Entidad();
        $tpoEntidad = 2; // entidad tipo proyecto.
        $idEntidad = $entidad->saveEntidad( $idEntidad, $tpoEntidad, $urlTableU );

        return $idEntidad;
    }

    /**
     * 
     * Registro informacion datos de un proyecto
     * 
     * @return type
     */
    public function registroDataProyecto()
    {
        $db = &JFactory::getDbo();

        try {
            //  Inicio transaccion
            $db->transactionStart();

            //  Registro de informacion general de un proyecto
            $idProyecto = $this->_registroDtaGralProyecto( JRequest::getVar( 'dtaProyecto' ) );

            $this->_registroEstadoEntidad( JRequest::getVar( 'idEstadoEntidad' ) );

            //  Registro la Meta de un proyecto
            $this->_registroMetaProyecto( $idProyecto, JRequest::getVar( 'dtaMetaPry' ) );

            //  Registro de Objetivos de un proyecto
            $this->_registroObjetivosOperativos( $this->_idEntidad, json_decode( JRequest::getVar( 'dtaObjetivos' ) ) );
            
            //  Registro la alineacion de un proyecto con el PNBV
            $this->_registroAlineacionProyectoPNBV( $idProyecto, JRequest::getVar( 'dtaPNBV' ) );
            
            //  Registro informacion de marco logico de un proyecto
            $this->_registroMarcoLogico( $idProyecto, JRequest::getVar( 'dtaMarcoLogico' ) );
            
            //  Registro unidades Territoriales
            $this->_saveDataUndTerritorial( $idProyecto, JRequest::getVar( 'dtaDPA' ) );
            
            //  Registro datos de coordenadas
            $this->_saveDataGrafico( $idProyecto, JRequest::getVar( 'dtaGraficos' ) );

            //  Registro las unidades de gestion responsable y funcionario responsable del Proyecto.
            $this->_gestionResponsable( $idProyecto, JRequest::getVar( 'dtaProyecto' ) );
            
            //  Registro Planes Operativos / POA - Proyectos
            $this->_saveDataPOAs( JRequest::getVar( 'dtaPlanes' ) );
            
            //  Registro informacion de Indicadores de un proyecto
            $this->_saveDataIndicadores( $this->_idEntidad, JRequest::getVar( 'dtaIndicadores' ) );

            //  Confirmo transaccion
            $db->transactionCommit();

            return (int) $idProyecto;
        } catch ( Exception $e ) {
            //  Deshace las operaciones realizadas antes de encontrar un error
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * Gestiona la informacion del responsable de un programa
     * @param type $proyecto
     */
    private function _gestionResponsable( $idPrograma, $dtaProyecto )
    {
        $responsable = json_decode( $dtaProyecto )->responsable;
        
        // Registro del UNIDAD DE GESTION RESPONSABLE de un PROGRAMA
        $this->saveUnidadGestionProyecto( $idPrograma, $responsable->idUGR, $responsable->fchIniciUGR );

        // Registro del FUNCIONARIO RESPONSABLE de un PROGRAMA
        $this->saveResponsableProyecto( $idPrograma, $responsable->idResponsable, $responsable->fchIniciRes );
        
        return;
    }

    /**
     * 
     * Registro informacion de POA - Proyectos
     * 
     * @param JSon $dtaPlanes   Datos de los POA - Proyecto
     * 
     * @return type
     * 
     */
    private function _saveDataPOAs( $dtaPlanes )
    {
        $lstPlanes = json_decode( $dtaPlanes );

        if( count( (array)$lstPlanes ) ){
            foreach( $lstPlanes as $plan ){
                $objPO = new PlanOperativo( $plan, $this->_idEntidad );
                $objPO->gestionDtaPlan();
            }
        }

        return;
    }
    
    
    
    /**
     * Gestiona la informacion de una UNIDAD de GESTION RESPONSABLE del CONTRATO.
     * @param int   $idPrograma         Identificador de contrato
     * @param int   $idUnidadGestion    Identificador de la Unidad de Gestión
     * @param date  $fchIniciUGR        Fecha de Iniciio de la gestion
     */
    public function saveUnidadGestionProyecto( $idPrograma, $idUnidadGestion, $fchIniciUGR )
    {
        if( $idUnidadGestion ){
            $tbUnidadGestioPrograma = $this->getTable( 'UnidadGestionProyecto', 'ProyectosTable' );
            $tbUnidadGestioPrograma->saveUnidadGestionProyecto( $idPrograma, $idUnidadGestion, $fchIniciUGR );
        }
    }

    /**
     * Regista los responsables del Programa
     * @param int $idPrograma       Identificador del Programa
     * @param int $idResponsable    Identificador del Responsable
     */
    public function saveResponsableProyecto( $idPrograma, $idResponsable, $fchIniciRes )
    {
        if( $idResponsable ){
            $tbUnidadGestioPrograma = $this->getTable( 'ResponsableProyecto', 'ProyectosTable' );
            $tbUnidadGestioPrograma->saveResponsableProyecto( $idPrograma, $idResponsable, $fchIniciRes );
        }
    }

    /**
     * Almacena los graficos de un proyecto
     * @param type $idProyecto
     * @param type $graficos
     */
    private function _saveDataGrafico( $idProyecto, $graficos )
    {
        $lstGraficos = json_decode( $graficos );
        $tbCoordenada = $this->getTable( 'Coordenada', 'ProyectosTable' );
        $tbGrafico = $this->getTable( 'Grafico', 'ProyectosTable' );

        if( count( $lstGraficos ) > 0 ){
            foreach( $lstGraficos AS $grafico ){
                if( $grafico->published == 1 ){

                    $idGrafico = $tbGrafico->saveGrafico( $idProyecto, $grafico );
                    if( count( $grafico->lstCoordenadas ) > 0 ){
                        foreach( $grafico->lstCoordenadas AS $coordenada ){
                            if( $coordenada->published == 1 ){
                                $idCoordenada = $tbCoordenada->saveCoordenadaGrafico( $idGrafico, $coordenada );
                            } else{
                                $tbCoordenada->delCoordenadaGrafico( $coordenada->idCoordenada );
                            }
                        }
                    }
                } else{
                    $tbCoordenada->delCoordenadasGrafico( $grafico->idGrafico );
                    $tbGrafico->delGrafico( $grafico->idGrafico );
                }
            }
        }

        return $idGrafico;
    }

    /**
     * 
     * Registro Datos Generales de un Proyecto
     * 
     * @param JSON $dtaGrlProyecto  Datos Generales de un proyecto en formato JSON
     * 
     * @return int Identificador de un proyecto
     * 
     */
    private function _registroDtaGralProyecto( $dtaGrlProyecto )
    {
        //  Transformo a objeto PHP los datos generales de un proyecto ( JSon )
        $dtaPry = json_decode( $dtaGrlProyecto );
        $this->_idEntidad = $this->saveEntidad( $dtaPry->intIdEntidad_ent, $dtaPry->urlTableU );

        $dtaProyecto['intCodigo_pry'] = $dtaPry->intCodigo_pry;

        //  En caso que sea un nuevo proyecto registro una nueva entidad, 
        //  y el identificador de esta nueva entidad 
        $dtaProyecto['intIdEntidad_ent']        = $this->_idEntidad;
        $dtaProyecto['inpCodigo_cb']            = $dtaPry->inpCodigo_cb;
        $dtaProyecto['intCodigo_prg']           = $dtaPry->idPrograma;
        $dtaProyecto['intCodigo_sprg']          = $dtaPry->idSubPrograma;
        $dtaProyecto['intCodigo_tsprg']         = $dtaPry->idTpoSubPrograma;
        $dtaProyecto['inpCodigo_subsec']        = $dtaPry->idSubSector;
        $dtaProyecto['intIdStr_intervencion']   = $dtaPry->idSIV;
        $dtaProyecto['strNombre_pry']           = $dtaPry->strNombre_pry;
        $dtaProyecto['strDescripcion_pry']      = $dtaPry->strDescripcion_pry;
        $dtaProyecto['strCodigoInterno_pry']    = $dtaPry->strCodigoInterno_pry;
        $dtaProyecto['dteFechaInicio_stmdoPry'] = $dtaPry->dteFechaInicio_stmdoPry;
        $dtaProyecto['dteFechaFin_stmdoPry']    = $dtaPry->dteFechaFin_stmdoPry;
        $dtaProyecto['inpDuracion_stmdoPry']    = $dtaPry->inpDuracion_stmdoPry;
        $dtaProyecto['intcodigo_unimed']        = $dtaPry->intcodigo_unimed;
        $dtaProyecto['strcup_pry']              = $dtaPry->strcup_pry;
        $dtaProyecto['strCargoResponsable_pry'] = $dtaPry->strCargoResponsable_pry;
        $dtaProyecto['dcmMonto_total_stmdoPry'] = floatval( $dtaPry->dcmMonto_total_stmdoPry );
        $dtaProyecto['inpDuracion_stmdoPry']    = $dtaPry->inpDuracion_stmdoPry;
        $dtaProyecto['intTotal_benDirectos_pry']= $dtaPry->intTotal_benDirectos_pry;
        $dtaProyecto['intCodigo_ug']            = $dtaPry->intCodigo_ug;

        //  Instancio la tabla Proyectos
        $tbProyecto = $this->getTable( 'Proyecto', 'ProyectosTable' );

        //  Ejecuto el metodo que registra informacion general de un proyecto
        $idProyecto = $tbProyecto->registroDtaGralProyecto( $dtaProyecto );
        
        return $idProyecto;
    }

    
    
    private function _registroEstadoEntidad( $idEstadoEntidad )
    {
        $tbEstEntidad = new EstadoEntidad();
        $idRegEstEntidad = $tbEstEntidad->gestionEstadoEntidad( $this->_idEntidad, $idEstadoEntidad );
        
        return $idRegEstEntidad;
    }
    
    
    
    /**
     * 
     * Registro informacion de indicadores pertenecienes a un determinado programa
     * 
     * @param int  $idEntidad       Identificador de la entidad del Proyecto
     * @param JSon $dtaIndicadores  Informacion de indicadores de un proyecto
     * 
     * @return type
     */
    private function _saveDataIndicadores( $idEntidad, $dtaIndicadores )
    {
        if( strlen( $dtaIndicadores ) ){
            $dtaIndicador = json_decode( $dtaIndicadores );

            //  Gestion Indicadores Economicos - Categoria # 1
            $this->_registroIndicadoresFijos( $idEntidad, $dtaIndicador->indEconomico, 1 );
            
            //  Gestion Indicadores Financieros - Categoria # 2
            $this->_registroIndicadoresFijos( $idEntidad, $dtaIndicador->indFinanciero, 2 );
            
            //  Gestion Indicadores Beneficiarios Directos - Categoria # 3
            $this->_registroIndicadoresFijos( $idEntidad, $dtaIndicador->indBDirecto, 3 );
            
            //  Gestion Indicadores Beneficiarios Indirectos - Categoria # 4
            $this->_registroIndicadoresFijos( $idEntidad, $dtaIndicador->indBIndirecto, 4 );
            
            //  Gestion Indicadores Dinamicos - GAP - Categoria # 5
            $this->_registroIndicadoresGAP( $idEntidad, $dtaIndicador->lstGAP, 5 );

            //  Gestion Indicadores Dinamicos - EI - Categoria # 6
            $this->_registroIndicadoresEI( $idEntidad, $dtaIndicador->lstEnfIgualdad, 6 );
            
            //  Gestion Indicadores Dinamicos - EE - Categoria # 7
            $this->_registroIndicadoresEE( $idEntidad, $dtaIndicador->lstEnfEcorae, 7 );
            
            //  Gestion de Otros Indicadores
            $this->_registroOtrosIndicadores( $idEntidad, $dtaIndicador->lstOtrosIndicadores, 0 );
        }
        
        return;
    }

    /**
     * 
     * Gestiono el Registro de Indicadores Fijos
     * 
     * @param type $idEntidad           Identificador de Entidad
     * @param type $dtaIndicadores      Datos del Indicador
     * @param type $banCategoriaInd     Categoria del Indicador 
     *                                  1:  Fijo            ( TD, VAN, TIR )
     *                                  2:  Dinamico        ( Enfoque ECORAE, GAP, etc )
     *                                  3:  OtrosInicadores ( Indicadores Creados por el usuario )
     * 
     */
    private function _registroIndicadoresFijos( $idEntidad, $dtaIndicadores, $banCategoriaInd )
    {
        if( $dtaIndicadores ){
            foreach( $dtaIndicadores as $indicador ){
                $objIndicador = new Indicador();
                $objIndicador->registroIndicador( $idEntidad, $indicador, $banCategoriaInd );
            }
        }
        
        return;
    }

    /**
     * 
     * Gestion de Registro de Indicadores GAP
     * 
     * @param int       $idEntidad          Identificador de Indicador Entidad
     * @param object    $dtaIndicadores     Datos de Indicadores GAP
     * 
     */
    private function _registroIndicadoresGAP( $idEntidad, $dtaIndicadores, $banCategoriaInd )
    {
        foreach( $dtaIndicadores as $indicador ){
            $objIndicador = new Indicador();
            
            $objIndicador->registroIndicador( $idEntidad, $indicador->gapMasculino, $banCategoriaInd );
            $objIndicador->registroIndicador( $idEntidad, $indicador->gapFemenino, $banCategoriaInd );
            $objIndicador->registroIndicador( $idEntidad, $indicador->gapTotal, $banCategoriaInd );
        }

        return;
    }

    /**
     * 
     * Gestiono el registro de Enfoque de igualdad
     * 
     * @param type $idEntidad           Identificador del Indicador
     * @param type $dtaIndicadores      Datos del indicador Enfoque de Igualdad
     * 
     */
    private function _registroIndicadoresEI( $idEntidad, $dtaIndicadores, $banCategoriaInd )
    {
        foreach( $dtaIndicadores as $indicador ){
            $objIndicador = new Indicador();

            $objIndicador->registroIndicador( $idEntidad, $indicador->eiMasculino, $banCategoriaInd );
            $objIndicador->registroIndicador( $idEntidad, $indicador->eiFemenino, $banCategoriaInd );
            $objIndicador->registroIndicador( $idEntidad, $indicador->eiTotal, $banCategoriaInd );
        }
        
        return;
    }

    /**
     * 
     * Gestiona el registro de Indicadores Ecorae
     * 
     * @param type $idEntidad
     * @param type $dtaIndicadores
     */
    private function _registroIndicadoresEE( $idEntidad, $dtaIndicadores, $banCategoriaInd )
    {
        foreach( $dtaIndicadores as $indicador ){
            $objIndicador = new Indicador();
            $objIndicador->registroIndicador( $idEntidad, $indicador->eeMasculino, $banCategoriaInd );
            $objIndicador->registroIndicador( $idEntidad, $indicador->eeFemenino, $banCategoriaInd );
            $objIndicador->registroIndicador( $idEntidad, $indicador->eeTotal, $banCategoriaInd );
        }
        
        return;
    }

    /**
     * 
     * Gestiona Informacion de un nuevo indicador de tipo Otro Indicador
     * 
     * @param type $idEntidad
     * @param type $lstOtrosIndicadores
     */
    private function _registroOtrosIndicadores( $idEntidad, $lstOtrosIndicadores, $banCategoriaInd )
    {
        if( count( (array)$lstOtrosIndicadores ) ){
            foreach( $lstOtrosIndicadores as $oi ){
                $objIndicador = new Indicador();
                $objIndicador->registroIndicador( $idEntidad, $oi, $banCategoriaInd );
            }
        }

        return;
    }
    
    /**
     * 
     * Gestiona Informacion de Indicador Meta Economico
     * 
     * @param type $idEntidad           Entidad
     * @param type $indIME              data Indicador Meta Economico
     * @param type $banCategoriaInd     Categoria del Indicador, en este caso seria de tipo 8
     * 
     */
    private function _registroIndicadorIME( $idEntidad, $indIME, $banCategoriaInd )
    {
        $objIndicador = new Indicador();
        $objIndicador->registroIndicador( $idEntidad, $indIME, $banCategoriaInd );
        
        return;
    }

    /**
     * 
     *  Registra relacion unidad territorial( DPA ), proyecto
     * 
     * @param int $idProyecto               Identificador del proyecto
     * @param array $dataUndTerritorial     Información de la unidad terrial
     * @return bool                         true si se registro correctamente.
     */
    private function _saveDataUndTerritorial( $idProyecto, $dataUndTerritorial )
    {
        $tbUndTerritorial = $this->getTable( 'UnidadTerritorial', 'ProyectosTable', $config = array() );
        return $tbUndTerritorial->registrarUnidadTerritorial( $idProyecto, $dataUndTerritorial );
    }

    /**
     * 
     * Registo Unidades Territoriales pertenecientes a propuestas de proyectos 
     * que crearan un nuevo proyecto
     * 
     * @param type $idProyecto          Identificador de un proyecto
     * @param type $dataUndTerritorial  Unidades territoriales pertenecientes a un conjunto de propuestas proyectos
     * 
     * @return type
     * 
     */
    private function _registroUndTerritorialCanasta( $idProyecto, $dataUndTerritorial )
    {
        $tbUndTerritorial = $this->getTable( 'UnidadTerritorial', 'ProyectosTable', $config = array() );
        return $tbUndTerritorial->registroUndTerritorialCanasta( $idProyecto, $dataUndTerritorial );
    }

    /**
     * 
     * Registro la meta de un proyecto
     *  
     * @param Int $idProyecto      Identificador de un proyecto
     * @param JSon $metaProyecto    datos de la meta de un proyecto
     * 
     * @return type
     * 
     */
    private function _registroMetaProyecto( $idProyecto, $metaProyecto )
    {
        $dtaMeta = json_decode( $metaProyecto );

        $dataMeta["intcodigo_pry"] = $idProyecto;
        $dataMeta["intcodigo_unimed"] = $dtaMeta->intcodigo_unimed;
        $dataMeta["inpcodigo_unianl"] = $dtaMeta->inpcodigo_unianl;
        $dataMeta["strdescripcion_metapry"] = $dtaMeta->strdescripcion_metapry;
        $dataMeta["intvalor_metapry"] = floatval( $dtaMeta->intvalor_metapry );

        $tbMeta = $this->getTable( 'Meta', 'ProyectosTable' );

        return $tbMeta->registroMetaProyecto( $dtaMeta );
    }

    /**
     * Gestiona el registro de los objetivos de un programa.
     * @param type $idEntidadOwn       Id entidad del (programa).    
     * @param type $dtaObjetivos    lst de objetivo del programa.
     * @return boolean
     */
    private function _registroObjetivosOperativos( $idEntidadOwn, $dtaObjetivos )
    {
        if( $dtaObjetivos->lstObjetivos ){
            foreach( $dtaObjetivos->lstObjetivos AS $objetivo ){
                $oObjetivoOperativo = new ObjetivoOperativo();
                $idObjetivoOperativo = $oObjetivoOperativo->saveObjetivoOperativo( $objetivo, $idEntidadOwn );
            }
        }
        return true;
    }

    /**
     * 
     * @param type $idObjpryInd
     * @param type $idEntPry
     * @param type $idIndEnt
     * @param type $idObjPry
     */
    private function _saveObjPryInd( $idObjpryInd, $idEntPry, $idIndEnt, $idObjPry )
    {
        $tbObjtPryInd = $this->getTable( 'PryObjInd', 'ProyectosTable' );
        return $tbObjtPryInd->savePryObjInd( $idObjpryInd, $idEntPry, $idIndEnt, $idObjPry );
    }

    /**
     * 
     * Registro alineacion del PNBV con el proyecto
     * 
     * @param type $idProyecto  Identificador del proyecto
     * @param type $dtaPNBV     Datos de relacion del proyecto con PNBV
     * 
     */
    private function _registroAlineacionProyectoPNBV( $idProyecto, $dtaPNBV )
    {
        $tbPNBV = $this->getTable( 'MetaNacionalProyecto', 'ProyectosTable' );
        $tbPNBV->registrarRelacionPNBV( $idProyecto, $dtaPNBV );

        return true;
    }

    /**
     * 
     * Registro informacion de un Marco Logico
     * 
     * @param int $idProyecto       Identificador de un identificador de un Proyecto
     * @param Json $dtaMarcoLogico  Datos un Marco logico en formato JSon
     * 
     * @return type
     */
    private function _registroMarcoLogico( $idProyecto, $dtaMarcoLogico )
    {
        //  Transforma notacion JSon de Marco Logico en un Objeto PHP
        $marcoLogico = json_decode( $dtaMarcoLogico );

        if( !empty( $marcoLogico->fin ) ){
            //  Registro MarcoLogico de tipo FIN
            $idMLFin = $this->_registroMLFin( $idProyecto, $marcoLogico->fin );

            //  Registro MarcoLogico de tipo PROPOSITO
            if( !empty( $marcoLogico->proposito ) ){
                $idMLProposito = $this->_registroMLProposito( $idProyecto, $idMLFin, $marcoLogico->proposito );

                //  Registro MarcoLogico de tipo COMPONENTE
                if( count( (array) $marcoLogico->lstComponentes ) > 0 ){
                    $idMLComponente = $this->_registroMLComponente( $idProyecto, $idMLProposito, $marcoLogico->lstComponentes );
                }
            }
        }

        return;
    }

    /**
     * 
     * Registro informacion de Marco Logico de tipo FIN
     * 
     * @param type $idProyecto  Identificador del proyecto
     * @param type $fin         datos
     * 
     * @return type
     */
    private function _registroMLFin( $idProyecto, $fin )
    {
        $dtaMlFin["intIdObjeto_oml"]            = $fin->idMlFin;
        $dtaMlFin["intIdTipoObjetoML_toml"]     = 1;
        $dtaMlFin["intIdPadre_oml"]             = null;
        $dtaMlFin["intCodigo_pry"]              = $idProyecto;
        $dtaMlFin["strNombre_oml"]              = $fin->nombreFin;
        $dtaMlFin["strDescripcion_oml"]         = $fin->descripcionFin;
        $dtaMlFin["dteFechaModificacion_oml"]   = 'now()';

        //  Registro informacion de MarcoLogico de tipo Fin
        $tbMarcoLogicoFN = $this->getTable( 'MarcoLogico', 'ProyectosTable' );
        $idMLFin = $tbMarcoLogicoFN->registrarMarcoLogico( $dtaMlFin );

        //  Registro Medio de Verificacion de Fin
        $this->_registroMedioVerificacion( $idMLFin, $fin->lstMedVerificacion );

        //  Registro Supuestos de Fin
        $this->_registroSupuestos( $idMLFin, $fin->lstSupuestos );
        
        return $idMLFin;
    }
    
    /**
     * 
     * Gestiona informacion de Marco Logico - Proposito
     * 
     * @param int       $idProyecto
     * @param int       $idMLFin
     * @param object    $proposito
     * 
     * @return type
     */
    private function _registroMLProposito( $idProyecto, $idMLFin, $proposito )
    {
        $dtaMlProposito["intIdObjeto_oml"]          = $proposito->idMlProposito;
        $dtaMlProposito["intIdTipoObjetoML_toml"]   = 2;
        $dtaMlProposito["intIdPadre_oml"]           = $idMLFin;
        $dtaMlProposito["intCodigo_pry"]            = $idProyecto;
        $dtaMlProposito["strNombre_oml"]            = $proposito->nombrePto;
        $dtaMlProposito["strDescripcion_oml"]       = $proposito->descripcionPto;
        $dtaMlProposito["dteFechaModificacion_oml"] = 'now()';

        //  Registro informacion de un marco logico de tipo PROPOSITO
        $tbMarcoLogicoPR = $this->getTable( 'MarcoLogico', 'ProyectosTable' );
        $idMLProposito = $tbMarcoLogicoPR->registrarMarcoLogico( $dtaMlProposito );

        //  Registro Medio de Verificacion de Proposito
        $this->_registroMedioVerificacion( $idMLProposito, $proposito->lstMedVerificacion );

        //  Registro Supuestos de Proposito
        $this->_registroSupuestos( $idMLProposito, $proposito->lstSupuestos );
        
        return $idMLProposito;
    }
    
    
    
    private function _registroMLComponente( $idProyecto, $idMLProposito, $lstComponentes )
    {
        foreach( $lstComponentes as $componente ){
            
            if( $componente->published == 1 ){
                $dtaMlComponente["intIdObjeto_oml"]         = $componente->idMlComponente;
                $dtaMlComponente["intIdTipoObjetoML_toml"]  = 3;
                $dtaMlComponente["intIdPadre_oml"]          = $idMLProposito;
                $dtaMlComponente["intCodigo_pry"]           = $idProyecto;
                $dtaMlComponente["strNombre_oml"]           = $componente->nombreCmp;
                $dtaMlComponente["strDescripcion_oml"]      = $componente->descripcionCmp;
                $dtaMlComponente["dteFechaModificacion_oml"]= 'now()';

                //  Registro informacion de un marco logico de tipo fin
                $tbMarcoLogicoCP = $this->getTable( 'MarcoLogico', 'ProyectosTable' );
                $idMLComponente = $tbMarcoLogicoCP->registrarMarcoLogico( $dtaMlComponente );

                //  Registro Medio de Verificacion de Proposito
                $this->_registroMedioVerificacion( $idMLComponente, $componente->lstMedVerificacion );

                //  Registro Supuestos de Proposito
                $this->_registroSupuestos( $idMLComponente, $componente->lstSupuestos );
                
                //  Gestiono el registro de actividades relacionadas con un componente
                if( count( (array) $componente->lstActividades ) > 0 ){
                    $this->_registroMLActividades( $idProyecto, $idMLComponente, $componente->lstActividades );                    
                }
                
            }elseif( $componente->published == 0 ){
                //  Elimino Medio de Verificacion
                $this->_delMedioVerificacion( $componente->idMlComponente );

                //  Elimino Supuestos
                $this->_delSupuestos( $componente->idMlComponente );

                //  Elimino Actividades asociadas a un marco logico de tipo componente
                $tbMarcoLogicoPR = $this->getTable( 'MarcoLogico', 'ProyectosTable' );
                $tbMarcoLogicoPR->deleteActividad( $componente->idMlComponente );

                //  Elimino Marco Logico de tipo Componente
                $tbMarcoLogicoPR->eliminarMarcoLogico( $componente->idMlComponente );
            }
        }

        return $idMLComponente;
    }
    
    
    
    private function _registroMLActividades( $idProyecto, $idMLComponente, $lstActividades )
    {
        foreach( $lstActividades as $actividad ){
            $tbMarcoLogicoAC = $this->getTable( 'MarcoLogico', 'ProyectosTable' );
            if( $actividad->published == 1 ){
                $dtaMlActividad["intIdObjeto_oml"]          = $actividad->idMlActividad;
                $dtaMlActividad["intIdTipoObjetoML_toml"]   = 4;
                $dtaMlActividad["intIdPadre_oml"]           = $idMLComponente;
                $dtaMlActividad["intCodigo_pry"]            = $idProyecto;
                $dtaMlActividad["strNombre_oml"]            = $actividad->nombreAct;
                $dtaMlActividad["strDescripcion_oml"]       = $actividad->descripcionAct;
                $dtaMlActividad["dteFechaModificacion_oml"] = 'now()';

                //  Registro informacion de un marco logico de tipo Actividad
                $idMLActividad = $tbMarcoLogicoAC->registrarMarcoLogico( $dtaMlActividad );

                //  Registro Medio de Verificacion de Proposito
                $this->_registroMedioVerificacion( $idMLActividad, $actividad->lstMedVerificacion );

                //  Registro Supuestos de Proposito
                $this->_registroSupuestos( $idMLActividad, $actividad->lstSupuestos );
            } elseif( $actividad->published == 0 ){
                //  Elimino Medio de Verificacion
                $this->_delMedioVerificacion( $actividad->idMlComponente );

                //  Elimino Supuestos
                $this->_delSupuestos( $actividad->idMlComponente );

                //  Elimino Marco Logico de tipo Actividad
                $tbMarcoLogicoAC->eliminarMarcoLogico( $actividad->idMlComponente );
            }
        }

        return $idMLActividad;
    }
    
    /**
     * 
     * Registro Medios de verificacion de un determinado objeto de marco logico
     * 
     * @param type $idML    Identificador de marco logico
     * @param type $dtaMv   Datos de medio de verificacion
     */
    private function _registroMedioVerificacion( $idML, $dtaMv )
    {
        if( $dtaMv ){
            foreach( $dtaMv AS $mv ){
                $infoMv["intIdObjeto_oml"]      = $idML;
                $infoMv["intIdMedVer_mver"]     = $mv->idMedVerificacion;
                $infoMv["strDescripcion_mver"]  = $mv->descMV;
                $infoMv["published"]            = $mv->published;

                $tbMedVerificacion = $this->getTable( 'MedioVerificacion', 'ProyectosTable' );
                $tbMedVerificacion->registrarMedioVerificacion( $infoMv );
            }
        }
        
        return;
    }

    /**
     * 
     * Elimino los medios de verificacion asociados a un determinado marco logico
     * 
     * @param type $idML    Identificador del medio de verificacion
     * 
     * @return type
     */
    private function _delMedioVerificacion( $idML )
    {
        $tbMedVerificacion = $this->getTable( 'MedioVerificacion', 'ProyectosTable' );

        //  Elimino los medios de verificacion asociados a un determinado marco logico
        return $tbMedVerificacion->eliminarMedVerificacion( $idML );
    }

    /**
     * 
     * Registro supuestos de un determinado marco logico
     * 
     * @param type $idSup   Identificador de supuestos
     * @param type $dtaSup  Datos de Supuestos
     */
    private function _registroSupuestos( $idML, $dtaSup )
    {
        //  ProyectosTableSupuesto
        if( $dtaSup ){
            foreach( $dtaSup as $sup ){
                $infoSup["intIdObjeto_oml"]     = $idML;
                $infoSup["intIdSupuesto_spto"]  = $sup->idSupuesto;
                $infoSup["strDescripcion_spto"] = $sup->descSupuesto;
                $infoSup["published"]           = $sup->published;

                $tbSupuesto = $this->getTable( 'Supuesto', 'ProyectosTable' );
                $tbSupuesto->registrarSupuesto( $infoSup );
            }
        }
        
        return;
    }

    /**
     * 
     * Gestiono la eliminacion de supuesto(s) relacionados a un determinado marco logico 
     * 
     * @param type $idML    Identificador de marco logico
     * @return type
     */
    private function _delSupuestos( $idML )
    {
        $tbSupuesto = $this->getTable( 'Supuesto', 'ProyectosTable' );

        return $tbSupuesto->eliminarSupuesto( $idML );
    }

    /**
     * 
     * Retorna una lista de actividades pertenecientes a un determinado componente
     * 
     * @param type $dtaMl   Identificador de un Marco logico
     * @param type $idPadre Identificador de Padre
     * 
     * @return type
     * 
     */
    private function _lstActividades( $dtaMl, $idPadre )
    {
        $lstAct = false;

        foreach( $dtaMl as $ml ){

            if( $ml->idTpoML == 4 && $ml->idPadre == $idPadre ){
                $dtaAct["idMLActividad"]        = $ml->idObjML;
                $dtaAct["nombre"]               = $ml->nombre;
                $dtaAct["descripcion"]          = $ml->descripcion;
                $dtaAct["lstMedioVerificacion"] = $this->_lstMedioVerificacion( $ml->idObjML );
                $dtaAct["lstSupuesto"]          = $this->_lstSupuestos( $ml->idObjML );

                $lstAct[] = $dtaAct;
            }

        }

        return $lstAct;
    }

    /**
     * 
     * Retorna medios de verificacion de un determinado Marco Logico
     * 
     * @param type $idObjML Identificador de marco logico
     * @return type
     */
    private function _lstMedioVerificacion( $idObjML )
    {
        $tbMedVerificacion = $this->getTable( 'MedioVerificacion', 'ProyectosTable' );
        return $tbMedVerificacion->dtaMedioVerificacion( $idObjML );
    }

    /**
     * 
     * Retorna medios de verificacion de un determinado Marco Logico
     * 
     * @param type $idObjML Identificador de marco logico
     * @return type
     */
    private function _lstSupuestos( $idObjML )
    {
        $tbSupuesto = $this->getTable( 'Supuesto', 'ProyectosTable' );
        return $tbSupuesto->dtaSupuestos( $idObjML );
    }

    /**
     * 
     * Gestiona el retorno una lista de informacion de marco logico
     * 
     * @param type $idProyecto  Identificador de un proyecto
     * 
     */
    private function dtaMarcoLogico( $idProyecto )
    {
        $tbMarcoLogico = $this->getTable( 'MarcoLogico', 'ProyectosTable' );
        $dtaML = $tbMarcoLogico->datosMarcoLogico( $idProyecto );
        $dtaMarcoLogico = array();
        
        if( $dtaML ){
            
            foreach( $dtaML as $ml ){
                switch( $ml->idTpoML ){
                    //  Fin
                    case '1':
                        $infoFin["idMLFin"]                 = $ml->idObjML;
                        $infoFin["nombre"]                  = $ml->nombre;
                        $infoFin["descripcion"]             = $ml->descripcion;
                        $infoFin["lstMediosVerificacion"]   = $this->_lstMedioVerificacion( $ml->idObjML );
                        $infoFin["lstSupuestos"]            = $this->_lstSupuestos( $ml->idObjML );

                        $dtaMarcoLogico["Fin"] = $infoFin;
                        break;

                    //  Proposito
                    case '2':
                        $infoProposito["idMLProposito"]         = $ml->idObjML;
                        $infoProposito["nombre"]                = $ml->nombre;
                        $infoProposito["descripcion"]           = $ml->descripcion;
                        $infoProposito["lstMediosVerificacion"] = $this->_lstMedioVerificacion( $ml->idObjML );
                        $infoProposito["lstSupuestos"]          = $this->_lstSupuestos( $ml->idObjML );

                        $dtaMarcoLogico["Proposito"] = $infoProposito;
                        break;

                    //  Componentes y sus actividades
                    case '3':
                        $infoCmp["idMLCmp"]                 = $ml->idObjML;
                        $infoCmp["nombre"]                  = $ml->nombre;
                        $infoCmp["descripcion"]             = $ml->descripcion;
                        $infoCmp["lstMediosVerificacion"]   = $this->_lstMedioVerificacion( $ml->idObjML );
                        $infoCmp["lstSupuestos"]            = $this->_lstSupuestos( $ml->idObjML );
                        $infoCmp["lstActividad"]            = $this->_lstActividades( $dtaML, $ml->idObjML );

                        $dtaMarcoLogico["lstComponentes"][] = $infoCmp;
                    break;
                }
            }
        }

        return $dtaMarcoLogico;
    }

    /**
     * 
     * @return boolean
     */
    public function saveImagesProyecto()
    {
        $ban = true;
        $idProyecto = JRequest::getVar( 'intCodigo_pry' );
        
        //  Ubicacion de un directorio de registro de Iconos e Imagenes pertenecientes a un determinado Proyecto
        $path = JPATH_SITE . DS . 'components' . DS . 'com_proyectos' . DS . 'images' . DS . $idProyecto;

        //  Verifico la existencia de un directorio que contiene las imagenes pertenecientes a un Proyecto
        if( !is_dir( $path ) ){
            //  Creo Directorio repositorio de imagenes
            mkdir( $path, 0777 );

            //  Directorio de Registro de Iconos
            $pathIcon = $path . DS . 'icon';
            mkdir( $pathIcon, 0777 );

            //  Directorio de Registro de Imagenes
            $pathImages = $path . DS . 'images';
            mkdir( $pathImages, 0777 );
        }
        
        switch( true ){
            //  Carga Iconos
            case( isset( $_FILES["iconoPry"] ) ):
                $pathIcon = $path . DS . 'icon';
                $ban = $this->_cargarArchivos( 'iconoPry', $pathIcon, $idProyecto );
            break;

            //  Carga Imagenes
            case( isset( $_FILES["imagesPry"] ) ):                
                $pathImages = $path . DS . 'images';
                
                //  Cuento el numero de registros existentes en el directorio y lo incremento en uno "1"
                $numImagen = count( glob( $pathImages . '/{*.jpg,*.gif,*.png}', GLOB_BRACE ) ) + 1;
                
                $ban = $this->_cargarArchivos( 'imagesPry', $pathImages, $numImagen );
            break;
        }

        exit;
    }

    /**
     * 
     * Gestiono la carga de archivos
     * 
     * @param FILE      $nameFile       Identificador del Archivo a cargar
     * @param String    $path           Lugar de carga del Archivo
     * @param Int       $idProyecto     Identificador del Archivo
     *  
     * @return Boolean
     * 
     */
    private function _cargarArchivos( $nameFile, $path, $idProyecto )
    {
        $up_file = new upload( $nameFile, NULL, $path, $idProyecto );
        return $up_file->save();
    }
    
    
    
    
    /**
     * 
     * Asingo a un determinado proyecto propuestas de proyectos
     * 
     * @param type $idProyecto      Identificador de Proyecto
     * @param type $lstPropuestas   Lista de propuestas
     * 
     * @return type
     * 
     */
    public function asignarPropuestasProyecto( $idProyecto, $lstPropuestas )
    {
        $tbCanastaProyecto = $this->getTable( 'CanastaProyecto', 'ProyectosTable' );
        return $tbCanastaProyecto->asignarPropuesta( $idProyecto, $lstPropuestas );
    }

    /**
     * 
     * Creo un "NUEVO" proyecto con propuestas de proyectos
     * 
     * @param type $lstPropuestas   Lista de propuestas de proyectos
     * 
     * @return type
     * 
     */
    public function crearProyectoConPropuestas( $lstPropuestas )
    {
        $db = &JFactory::getDbo();
        $tbCanastaProyecto = $this->getTable( 'CanastaProyecto', 'ProyectosTable' );

        try {
            //  Inicio transaccion
            $db->transactionStart();

            //  Registro de informacion general de un proyecto
            $idProyecto = $this->_registroDtaGralProyecto( $tbCanastaProyecto->dtaGeneralPropuesta( $lstPropuestas ) );

            //  Registro en la relacion entre una propuesta de proyecto y un NUEVO proyecto( tb_cp_propuesta_proyecto )
            $this->asignarPropuestasProyecto( $idProyecto, $lstPropuestas );

            //  Registro la alineacion de un proyecto con el PNBV
            $this->_registroAlineacionProyectoPNBV( $idProyecto, $tbCanastaProyecto->dtaPNBV( $lstPropuestas ) );

            //  Registro unidades Territoriales
            $this->_registroUndTerritorialCanasta( $idProyecto, $tbCanastaProyecto->dtaUndTerritorial( $lstPropuestas ) );

            //  Registro datos de coordenadas
            $this->_saveDataGrafico( $idProyecto, $tbCanastaProyecto->dtaUndCoordenadas( $lstPropuestas ) );

            //  Confirmo transaccion
            $db->transactionCommit();

            return (int) $idProyecto;
        } catch ( Exception $e ) {
            //  Deshace las operaciones realizadas antes de encontrar un error
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        
        return;
    }

    /**
     * Retorna la lista de imagenes de un proyecto
     * 
     * @return int
     */
    public function getImagenesProyecto()
    {
        $idProyecto = JRequest::getVar( 'intCodigo_pry' );

        $path = JPATH_SITE . DS . 'components' . DS . 'com_proyectos' . DS . 'images' . DS . $idProyecto . DS . 'images';

        if( file_exists( $path ) ){
            $count = 0;
            $directorio = opendir( $path );

            while( $archivo = readdir( $directorio ) ){
                if( $archivo != "." && $archivo != ".." ){
                    $docu["nameArchivo"] = $archivo;
                    $docu["published"] = 1;
                    $docu["regArchivo"] = $count;
                    $lstArchivos[] = $docu;
                    $count++;
                }
            }
            closedir( $directorio );
        }

        return ( $lstArchivos != null ) ? $lstArchivos : array();
    }

    public function getIconProyecto()
    {
        $idProyecto = JRequest::getVar( 'intCodigo_pry' );

        $path = JPATH_SITE . DS . 'components' . DS . 'com_proyectos' . DS . 'images' . DS . $idProyecto . DS . 'icon';
        if( file_exists( $path ) ){
            $count = 0;
            $directorio = opendir( $path );

            while( $archivo = readdir( $directorio ) ){
                if( $archivo != "." && $archivo != ".." ){
                    $docu["nameArchivo"] = $archivo;
                    $docu["published"] = 1;
                    $docu["regArchivo"] = $count;
                    $lstArchivos[] = $docu;
                    $count++;
                }
            }
            closedir( $directorio );
        }
        
        return ( $lstArchivos != null ) ? $lstArchivos 
                                        : array();
    }

    /**
     * 
     * Elimina el icono de un proyecto.
     * 
     * @param   int   $idProyecto   Identificador del proyecto.
     * @return  bool                TRUE si se elimino correctamente.
     */
    public function deleteIcon( $idProyecto )
    {
        $ban = FALSE;

        $path = JPATH_BASE . DS . 'components' . DS . 'com_proyectos' . DS . 'images' . DS . $idProyecto . DS . 'icon';
        
        if( file_exists( $path ) ){
            $count = 0;
            $directorio = opendir( $path );

            while( $archivo = readdir( $directorio ) ){
                
                if( $archivo != "." && $archivo != ".." ){
                    $ban = unlink( $path. DS .$archivo );
                }

            }

            closedir( $directorio );
        }
        
        return $ban;
    }

    /**
     * 
     * Elimina la imagen de un proyecto
     * 
     * @param   int     $idProyecto Identificador del proyecto.
     * @param   String  $nmbArchivo Nombre del archivo a ser eliminado.
     * @return  bool                TRUE si se elimino correctamente.
     */
    public function deleteImagen( $idProyecto, $nmbArchivo )
    {
        $path = JPATH_SITE . DS . 'components' . DS . 'com_proyectos' . DS . 'images' . DS . $idProyecto . DS . 'images' . DS . $nmbArchivo;
        return unlink( $path );
    }

    /**
     * 
     * Retorna una lista de Objetivos Estrategicos (OEI)
     * 
     * @param type $idEntidad   Identificador de la entidad
     * @return type
     * 
     */
    public function lstIndicadores( $idIndEntidad )
    {
        $indicadores = new Indicadores();
        return $indicadores->getLstIndicadores( $idIndEntidad );
    }

    /**
     * 
     * Listo todos los contratos asociados a este proyecto
     * 
     * @param type $idProyecto  Identificador del proyecto
     * 
     * @return type
     * 
     */
    public function lstContratosPry( $idProyecto )
    {
        $objContrato = new Contrato();
        return $objContrato->getLstContratosPry( $idProyecto );
    }

    /**
     * 
     * Listo todos los convenios asociados a este proyecto
     * 
     * @param type $idProyecto  Identificador del proyecto
     * @return type
     */
    public function lstConveniosPry( $idProyecto )
    {
        $objConvenio = new Convenio();
        return $objConvenio->getLstConveniosPry( $idProyecto );
    }

    /**
     * Recupera la unidad de gestion de la entidad.
     * @param type $idEntidad
     * @return type
     */
    private function _getOrganigramaByEntidad( $idEntidad )
    {
        return false;
    }
    
    /**
     * Retorna Informacion de funcionarios asociados a un determinada Unidad de Gestion
     * @param type $idUndGestion    Identificador Unidad de Gestion
     * @return type
     */
    public function getResponsablesUG( $idUndGestion )
    {
        $objIndicador = new UnidadGestion();
        return $objIndicador->getFuncionariosResponsables( $idUndGestion );
    }
    
    /**
     *  Retorna la estructura de una version de los sectores de intervencion
     * @param type $idSI
     * @return type
     */
    public function getStrSecIntrv( $idSI ){
        $tbSecIntrv = $this->getTable( 'SectoresIntervencion', 'ProyectosTable' );
        $list = $tbSecIntrv->getEstructuraSecIntrv( $idSI );
        
        //  Ordeno la estructura deacuerdo al Padre
        $result = array();
        $owner = 0;
        for($i = 0, $size = count($list); $i < $size; ++$i) {
            if ((int)$list[$i]->intOwner_esi == $owner) {
                $owner = (int)$list[$i]->intId_esi;
                $result[] = $list[$i];
                $i = -1;
            }
        } 
        
        return $result;
    }

    /**
     * 
     * @return type
     */
    public function getSctIntrvVigente(){
        $tbSecIntrv = $this->getTable( 'SectoresIntervencion', 'ProyectosTable' );
        return $tbSecIntrv->getSctIntrvVgn();
    }

    /**
     * 
     * Retorna url con ticket de confianza de Tableau
     * 
     * @param String $nombreDashBoard
     * @return type
     * 
     */
    public function getTicketTableuPorNombre( $nombreDashBoard )
    {
        $ticket = new TicketTableu( JText::_( 'COM_PROYECTOS_TABLEU_SERVER' ),
                                    JText::_( 'COM_PROYECTOS_TABLEU_USER' ),
                                    JText::_( 'COM_PROYECTOS_TABLEU_SITE' ),
                                    $nombreDashBoard );

        return $ticket->getUrl();
    }
    
    public function __destruct()
    {
        return;
    }
    
}