<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

//  Adjunto libreria de gestion de Indicador
jimport('ecorae.objetivos.objetivo.indicadores.indicador');

//  Adjunto libreria de gestion de Indicadores
jimport('ecorae.objetivos.objetivo.indicadores.indicadores');

JTable::addIncludePath(JPATH_BASE . DS . 'components' . DS . 'com_canastaproy' . DS . 'tables');

/**
 * Modelo tipo obra
 */
class CanastaproyModelPropuesta extends JModelAdmin
{
    private $_idEntidad;

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Propuesta', $prefix = 'CanastaproyTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

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
        $form = $this->loadForm('com_canastaproy.propuesta', 'propuesta', array( 'control' => 'jform', 'load_data' => $loadData ));

        //  Obtengo informacion de identificador de la entidad relacionada con 
        //  la propuesta de proyecto
        $this->_idEntidad = $idEntidad = $form->getField('intIdentidad_ent')->value;

        //  Seteo Informacion de indicadores de un Proyecto
        $dtaIndicadores = $this->_setDataIndicadoresProyecto();

        //  Seteo Informacion de indicadores fijos en el formulario
        $this->_setDtaFrmIndFijos($form, $dtaIndicadores);

        //  Datos de INDICADORES Registrados
        $form->setFieldAttribute('dataIndicadores', 'default', json_encode($dtaIndicadores));

        if( empty($form) ){
            return false;
        }

        return $form;
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
        $dtaIndicadores = $objIndicadores->getLstIndicadores($this->_idEntidad);

        //  Seteo informacion de Indicadores en el Formulario
        if( count($dtaIndicadores) ){

            foreach( $dtaIndicadores as $indicador ){
                //  Agrego el tipo de indicador - Alias de Unidad de Analisis
                $indicador->tpoIndicador = $indicador->alias;

                $dtoIndicador["idIndEntidad"]           = $indicador->idIndEntidad;
                $dtoIndicador["idIndicador"]            = $indicador->idIndicador;
                $dtoIndicador["nombreIndicador"]        = $indicador->nombreIndicador;
                $dtoIndicador["modeloIndicador"]        = $indicador->modeloIndicador;
                $dtoIndicador["umbral"]                 = $indicador->umbral;
                $dtoIndicador["tendencia"]              = (int)$indicador->tendencia;
                $dtoIndicador["descripcion"]            = $indicador->descripcion;
                $dtoIndicador["idUndAnalisis"]          = (int)$indicador->idUndAnalisis;
                $dtoIndicador["undAnalisis"]            = (int)$indicador->undAnalisis;
                $dtoIndicador["idTpoUndMedida"]         = (int)$indicador->idTpoUndMedida;
                $dtoIndicador["idUndMedida"]            = (int)$indicador->idUndMedida;
                $dtoIndicador["undMedida"]              = (int)$indicador->undMedida;
                $dtoIndicador["idTpoIndicador"]         = $indicador->idTpoIndicador;
                $dtoIndicador["formula"]                = $indicador->formula;
                $dtoIndicador["fchHorzMimimo"]          = $indicador->fchHorzMimimo;
                $dtoIndicador["fchHorzMaximo"]          = $indicador->fchHorzMaximo;
                $dtoIndicador["umbMinimo"]              = $indicador->umbMinimo;
                $dtoIndicador["umbMaximo"]              = $indicador->umbMaximo;
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

                $dtoIndicador["lstUndTerritorial"]      = $objIndicadores->getLstIndUndTerritorial($indicador->idIndEntidad);
                $dtoIndicador["lstLineaBase"]           = $objIndicadores->getLstLineasBase($indicador->idIndicador);
                $dtoIndicador["lstRangos"]              = $objIndicadores->getLstRangos($indicador->idIndEntidad);
                $dtoIndicador["lstVariables"]           = $objIndicadores->getLstVariables($indicador->idIndicador);
                $dtoIndicador["lstDimensiones"]         = $objIndicadores->getLstDimensiones($indicador->idIndicador);
                $dtoIndicador["lstPlanificacion"]       = $objIndicadores->getLstPlanificacion($indicador->idIndEntidad);

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

            $indicadores["indEconomicos"]   = $this->_procesoIndicadores(31, $lstIndEconomicos);
            $indicadores["indFinancieros"]  = $this->_procesoIndicadores(32, $lstIndFinancieros);
            $indicadores["indBDirectos"]    = $this->_procesoIndicadores(33, $lstBDirectos);
            $indicadores["indBIndirectos"]  = $this->_procesoIndicadores(34, $lstBIndirectos);

            //  Seteo indicadores de tipo GAP
            $indicadores["lstGAP"] = ( count($lstTmpGAP) )  ? $objIndicadores->getLstGAP($lstTmpGAP) 
                                                            : array();

            //  Seteo indicadores de tipo Enfoque de Igualdad
            $indicadores["lstEnfIgualdad"] = ( count($lstTmpEI) )   ? $objIndicadores->getLstEI($lstTmpEI) 
                                                                    : array();

            //  Seteo indicadores de tipo Enfoque de ECORAE
            $indicadores["lstEnfEcorae"] = ( count($lstTmpEE) ) ? $objIndicadores->getLstEE($lstTmpEE) 
                                                                : array();

            //  Seteo informacion de INDICADORES especificos, catalogados como "Otros Indicadores"
            $indicadores["lstOtrosIndicadores"] = array();
        } else{
            $indicadores["indEconomicos"] = $this->_getPtllaIndicador(31);
            $indicadores["indFinancieros"] = $this->_getPtllaIndicador(32);
            $indicadores["indBDirectos"] = $this->_getPtllaIndicador(33);
            $indicadores["indBIndirectos"] = $this->_getPtllaIndicador(34);

            $indicadores["lstGAP"] = array();
            $indicadores["lstEnfIgualdad"] = array();
            $indicadores["lstEnfEcorae"] = array();
            $indicadores["lstOtrosIndicadores"] = array();
        }

        return $indicadores;
    }

    /**
     * 
     * Seteo Informacion de indicadores fijos en los formualrios
     * 
     * @param type $form
     * @param type $indicadores
     */
    private function _setDtaFrmIndFijos($form, $indicadores)
    {
        //  Seteo Informacion de Indicadores Economicos en el formulario
        $this->_setDataIndEconomicos($form, (object)$indicadores["indEconomicos"], 31);

        //  Seteo Informacion de Indicadores Financieros en el formulario
        $this->_setDataIndFinancieros($form, (object)$indicadores["indFinancieros"], 32);

        //  Seteo Informacion de Beneficiarios Directos en el Formulario
        $this->_setDataBDirectos($form, (object)$indicadores["indBDirectos"], 33);

        //  Seteo Informacion de Beneficiarios Directos en el Formulario
        $this->_setDataBIndirectos($form, (object)$indicadores["indBIndirectos"], 34);
    }

    /**
     * 
     * Obtengo informacion especifica de un indicador 
     * 
     * @param int $idDimension      identificador de la dimension asociado al indicador
     * 
     * @return Object
     */
    private function _getPtllaIndicador($idDimension)
    {
        $objIndicador = new Indicadores();
        return $objIndicador->getDtaPtllaPorDimension($idDimension);
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
    private function _setDataIndEconomicos($form, $dtaIndicadores, $idDimension)
    {
        if( count($dtaIndicadores) ){
            foreach( $dtaIndicadores as $indicador ){
                switch( true ){
                    //  Tasa de Descuento
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 14 ) ):
                        $form->setFieldAttribute('intTasaDctoEco', 'default', (int)$indicador->umbral);
                        break;

                    //  Valor Actual Neto
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 15 ) ):

                        $form->setFieldAttribute('intValActualNetoEco', 'default', round($indicador->umbral, 2));
                        break;

                    //  Tasa Interna de Retorno
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 16 ) ):
                        $form->setFieldAttribute('intTIREco', 'default', (int)$indicador->umbral);
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
     * @param type $form            Objet|o Formulario
     * @param type $dtaIndicadores  Datos de Indicadores
     * @param type $idDimension     Datos de Dimension
     * 
     * @return type
     * 
     */
    private function _setDataIndFinancieros($form, $dtaIndicadores, $idDimension)
    {
        if( count($dtaIndicadores) ){
            foreach( $dtaIndicadores as $indicador ){
                switch( true ){
                    //  Tasa de Descuento
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 14 ) ):
                        $form->setFieldAttribute('intTasaDctoFin', 'default', (int)$indicador->umbral);
                        break;

                    //  Valor Actual Neto
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 15 ) ):
                        $form->setFieldAttribute('intValActualNetoFin', 'default', round($indicador->umbral));
                        break;

                    //  Tasa Interna de Retorno
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( (int)$indicador->idUndAnalisis == 16 ) ):
                        $form->setFieldAttribute('intTIRFin', 'default', (int)$indicador->umbral);
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
    private function _setDataBDirectos($form, $dtaIndicadores, $idDimension)
    {
        if( count($dtaIndicadores) ){
            foreach( $dtaIndicadores as $indicador ){
                switch( true ){
                    //  Beneficiarios Directos Hombre
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 6 ) ):
                        $form->setFieldAttribute('intBenfDirectoHombre', 'default', (int)$indicador->umbral);
                        break;

                    //  Beneficiarios Directos Mujer
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 7 ) ):
                        $form->setFieldAttribute('intBenfDirectoMujer', 'default', (int)$indicador->umbral);
                        break;

                    //  Total Beneficiarios Directo
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 4 ) ):
                        $form->setFieldAttribute('intTotalBenfDirectos', 'default', (int)$indicador->umbral);
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
    private function _setDataBIndirectos($form, $dtaIndicadores, $idDimension)
    {
        if( count($dtaIndicadores) ){
            foreach( $dtaIndicadores as $indicador ){
                switch( true ){
                    //  Beneficiarios Directos Hombre
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 6 ) ):
                        $form->setFieldAttribute('intBenfIndDirectoHombre', 'default', (int)$indicador->umbral);
                        break;

                    //  Beneficiarios Directos Mujer
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 7 ) ):
                        $form->setFieldAttribute('intBenfIndDirectoMujer', 'default', (int)$indicador->umbral);
                        break;

                    //  Total Beneficiarios Directo
                    case ( ( (int)$indicador->idDimension == $idDimension ) && ( $indicador->idUndAnalisis == 4 ) ):
                        $form->setFieldAttribute('intTotalBenfIndDirectos', 'default', (int)$indicador->umbral);
                        break;
                }
            }
        }

        return;
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
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_canastaproy.edit.propuesta.data', array());

        if( empty($data) ){
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     *  Retorna una lista de cantones pertenecientes a una determinada Provincia
     * 
     *  @return type 
     */
    public function getCantones($idProvincia)
    {
        $tbUndTerritorial = $this->getTable('UnidadTerritorial', 'CanastaproyTable', $config = array());
        return $tbUndTerritorial->lstCantones($idProvincia);
    }

    /**
     *
     *  Gestiona una lista de Parroquias pertenecientes a un Canton de una determinada Provincia
     * 
     *  @return type 
     * 
     */
    public function getParroquias($idCanton)
    {
        $tbUndTerritorial = $this->getTable('UnidadTerritorial', 'CanastaproyTable', $config = array());
        return $tbUndTerritorial->lstParroquias($idCanton);
    }

    /**
     * 
     * Retorno lista de Politicas Nacionales
     * 
     * @return type
     */
    public function getPoliticaNacional($idObjNac)
    {
        $tbMNP = $this->getTable('AlineacionPropuestaProy', 'CanastaproyTable');
        return $tbMNP->getLstPoliticaNacional($idObjNac);
    }

    /**
     * 
     * Retorno Lista de Metas Nacionales vinculadas a una Politica Nacional
     * 
     * @return type
     */
    public function getMetaNacional($idObjNac, $idPolNac)
    {
        $tbMNP = $this->getTable('AlineacionPropuestaProy', 'CanastaproyTable');
        return $tbMNP->getLstMetaNacional($idObjNac, $idPolNac);
    }

    /**
     * 
     * Retorna el id del la propuesta guardada 
     * 
     * @return type
     */
    public function guardarPropuesta()
    {
        $idPropuesta = $this->_registrarPropuesta( JRequest::getvar( 'dtaFrm' ) );

        if( $idPropuesta ){
            $lstUniTerri        = $this->_registrarUbiTerritorial($idPropuesta, JRequest::getvar('dtaUbicacionTerritorial'));
            $lstUbiGeograficas  = $this->_registrarUbiGeografica($idPropuesta, JRequest::getvar('dtaUbicacionGeografica'));
            $lstAlineacionPNBV  = $this->_registrarAlineacion($idPropuesta, JRequest::getvar('dtaAlineacion'));

            //  Registro informacion de Indicadores de un proyecto
            $this->_saveDataIndicadores( $this->_idEntidad, JRequest::getVar( 'dtaIndicadores' ) );
        }

        return $idPropuesta;
    }

    /**
     * 
     * Retorna el ID de la propuesta de proyecto guardada  
     * 
     * @param type $dtaFrm  Datos generales de la propuesta  
     * @return type
     */
    private function _registrarPropuesta( $dtaFrm )
    {
        $dtaPropuesta = json_decode($dtaFrm);
        $tbPropuesta = $this->getTable('Propuesta', 'CanastaproyTable');

        if( $dtaPropuesta->intIdPropuesta_cp == 0 ){
            $tbEntidad = $this->getTable('Entidad', 'CanastaproyTable');

            $dtaEntidad["intIdentidad_ent"]     = 0;
            $dtaEntidad["intIdtipoentidad_te"]  = 14;
            $propuesta["dteFechaRegistro_cp"] = date("Y-m-d H:i:s");

            $idEntidad = $tbEntidad->registrarEntidadPropuesta( $dtaEntidad );
        } else{
            $this->_idEntidad = $idEntidad = $dtaPropuesta->intIdentidad_cp;
        }

        if( $idEntidad ){
            $propuesta["intIdPropuesta_cp"]         = $dtaPropuesta->intIdPropuesta_cp;
            $propuesta["intIdentidad_ent"]          = $idEntidad;
            $propuesta["inpCodigo_estado"]          = $dtaPropuesta->inpCodigo_estado;
            $propuesta["intCodigo_ins"]             = $dtaPropuesta->intCodigo_ins;
            $propuesta["strNombre_cp"]              = $dtaPropuesta->strNombre_cp;
            $propuesta["strDescripcion_cp"]         = $dtaPropuesta->strDescripcion_cp;
            $propuesta["strCodigoPropuesta_cp"]     = $dtaPropuesta->strCodigoPropuesta_cp;
            $propuesta["dcmMonto_cp"]               = $dtaPropuesta->dcmMonto_cp;
            $propuesta["intNumeroBeneficiarios"]    = $dtaPropuesta->intNumeroBeneficiarios;
            $propuesta["dteFechaModificacion_cp"]   = date("Y-m-d H:i:s");

            $idPropuesta = $tbPropuesta->registrarPropuesta($propuesta);
        }

        return $idPropuesta;
    }

    /**
     * 
     * Retorna True si registro almenos una relacion con unidad territorail
     * o False si no realizo ninguna relación
     * 
     * @param type $idPropuesta     Id de la propuesta relaciona con las unidades territoriales
     * @param type $unidadesTerritoriales   Lista de las unidades territoriales
     * @return type
     */
    private function _registrarUbiTerritorial($idPropuesta, $unidadesTerritoriales)
    {
        $tbProUbicTerritorial = $this->getTable('UnidadTerritorial', 'CanastaproyTable');
        $dtaUbicTerritorial = json_decode($unidadesTerritoriales);

        if( $tbProUbicTerritorial->deleteUndTerritorial($idPropuesta) ){
            foreach( $dtaUbicTerritorial as $undTerritorial ){
                if( $undTerritorial->published == 1 ){

                    switch( true ){
                        case( $undTerritorial->idParroquia != 0 ):
                            $idUndTerritoral = $undTerritorial->idParroquia;
                            break;

                        case( $undTerritorial->idParroquia == 0 && $undTerritorial->idCanton != 0 ):
                            $idUndTerritoral = $undTerritorial->idCanton;
                            break;

                        case( $undTerritorial->idParroquia == 0 && $undTerritorial->idCanton == 0 ):
                            $idUndTerritoral = $undTerritorial->idProvincia;
                            break;
                    }

                    $data["intIdPropuesta_cp"] = $idPropuesta;
                    $data["intID_ut"] = $idUndTerritoral;
                    $dta[] = $data;
                }
            }
            $rstUniTerri = $tbProUbicTerritorial->addUndTerritorial($dta);
        }
        return $rstUniTerri;
    }

    /**
     * 
     * Retorna la lista de Unidades Territoriales de una determinada propuesta de proyecto  
     * 
     * @param type $idPropuesta     Id de la propuesta relacionada con las unidades territoriales
     * @return type
     */
    public function lstUnidadesTerritoriales($idPropuesta)
    {
        $tbProUbicTerritorial = $this->getTable('UnidadTerritorial', 'CanastaproyTable');
        return $tbProUbicTerritorial->getLstUndTerritoriales($idPropuesta);
    }

    /**
     * 
     * Retorna la liata de Id's de Ubicaciones Geograficas relacionadas con una propuesta
     * 
     * @param type $idPropuesta     Id de la propuesta relacionada con las ubicaciones geograficas
     * @param type $ubicacionesGeograficas  Lista de Ubicaciones geograficas realcionadas con una propuesta
     * @return type
     */
    public function _registrarUbiGeografica($idPropuesta, $ubicacionesGeograficas)
    {

        $tbProUbicGeografica = $this->getTable('Graficocp', 'CanastaproyTable');
        $tbCoordenadasGrafico = $this->getTable('Coordgrafico', 'CanastaproyTable');
        $dtaUbicGeografica = json_decode($ubicacionesGeograficas);
        foreach( $dtaUbicGeografica as $ubiGeografica ){
            if( $ubiGeografica->published == 1 ){
                $graficoPropuesta["intId_gcp"] = $ubiGeografica->idGrafico;
                $graficoPropuesta["intId_tg"] = $ubiGeografica->tpoGrafico;
                $graficoPropuesta["intIdPropuesta_cp"] = $idPropuesta;
                $graficoPropuesta["strDescripcionGrafico_gcp"] = $ubiGeografica->descGrafico;

                $idGrafico = $tbProUbicGeografica->addGraficoPropuesta($graficoPropuesta);

                if( $idGrafico ){

                    foreach( $ubiGeografica->lstCoordenadas as $coordGrafico ){
                        if( $coordGrafico->published == 1 ){

                            $coordenada["intId_cgcp"] = $coordGrafico->idCoordenada;
                            $coordenada["intId_gcp"] = $idGrafico;
                            $coordenada["fltLatitud_cord"] = $coordGrafico->latitud;
                            $coordenada["fltLongitud_cord"] = $coordGrafico->longitud;

                            $lstCoordenadas[] = $tbCoordenadasGrafico->addCoordenadaGraficoCP($coordenada);
                        } else{
                            $tbCoordenadasGrafico->delCoordenadaGraficoCP($coordGrafico->idCoordenada);
                        }
                    }
                }
            } else{
                $tbCoordenadasGrafico->delCoordenadasGrafico($ubiGeografica->idGrafico);
                $tbProUbicGeografica->delGraficoPropuesta($ubiGeografica->idGrafico);
            }
        }
        return $lstCoordenadas;
    }

    /**
     * 
     * Retorna la lista de Ubicaciones Geograficas con sus respectivas coordenadas 
     * 
     * @param type $idPropuesta     Id de la propuesta relacionada con las ubicaciones geograficas
     * @return type
     */
    public function lstUbicacionesGeograficas($idPropuesta)
    {
        $tbProUbicGeografica = $this->getTable('Graficocp', 'CanastaproyTable');
        $lstGraficos = $tbProUbicGeografica->getLstGraficoPropuesta($idPropuesta);
        $tbProCoordenadasGrafico = $this->getTable('Coordgrafico', 'CanastaproyTable');
        $dtaLstGraficos = array();
        foreach( $lstGraficos as $grafico ){
            $lstCoordenadas = $tbProCoordenadasGrafico->getLstCoordenadasGrafico($grafico->intId_gcp);
            $grafico->lstCoordenadas = $lstCoordenadas;
            $dtaLstGraficos[] = $grafico;
        }
        return $dtaLstGraficos;
    }

    /**
     * 
     * @param type $idPropuesta     Id de la propuesta relacionada con las ubicaciones geograficas
     * @param type $lstAlineacion
     * @return type
     */
    public function _registrarAlineacion($idPropuesta, $lstAlineacion)
    {

        $tbProAlineacion = $this->getTable('AlineacionPropuestaProy', 'CanastaproyTable');
        $alineacionesPNBV = json_decode($lstAlineacion);


        foreach( $alineacionesPNBV as $Alineacion ){
            if( $Alineacion->published == 1 ){
                $dataAlineacion["intId_PropuestaPNBV"] = $Alineacion->idAlnPropPNBV;
                $dataAlineacion["intIdPropuesta_cp"] = $idPropuesta;
                $dataAlineacion["idCodigo_mn"] = $Alineacion->idMetaNacional;
                $lstIdAlineacion[] = $tbProAlineacion->registrarAlineacionPropuesta($dataAlineacion);
            } else{
                $tbProAlineacion->deleteAlineacion($Alineacion->idAlnPropPNBV);
            }
        }
        return $lstIdAlineacion;
    }

    public function lstAlineacionesPropuesta($idPropuesta)
    {
        $tbProAlnPropuesta = $this->getTable('AlineacionPropuestaProy', 'CanastaproyTable');
        return $tbProAlnPropuesta->getLstAlnPropueta($idPropuesta);
    }

    function delPropuesta($idPropuesta)
    {

        $tbUbicGeoPropuesta = $this->getTable('Graficocp', 'CanastaproyTable');
        $tbCoorGrafPropuesta = $this->getTable('Coordgrafico', 'CanastaproyTable');
        $lstGarfProp = $tbUbicGeoPropuesta->getLstGraficoPropuesta($idPropuesta);
        
        foreach( $lstGarfProp as $graf ){
            $tbCoorGrafPropuesta->delCoordenadasGrafico($graf->intId_gcp);
            $tbUbicGeoPropuesta->delGraficoPropuesta($graf->intId_gcp);
        }
        
        $tbAlnPropuesta = $this->getTable('AlineacionPropuestaProy', 'CanastaproyTable');
        $tbAlnPropuesta->delAlineacionPropuesta($idPropuesta);

        $tbUbicTerrPropuesta = $this->getTable('UnidadTerritorial', 'CanastaproyTable');
        $tbUbicTerrPropuesta->deleteUndTerritorial($idPropuesta);

        $tbPropuesta = $this->getTable('Propuesta', 'CanastaproyTable');
        $result = $tbPropuesta->eliminarPropuesta($idPropuesta);
        return $result;
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
    
    
    public function eliminarPropuesta() {
        $id = JRequest::getvar( 'id' );
        $tbPropuesta = $this->getTable('Propuesta', 'CanastaproyTable');
        if ($this->_availableDelPropuesta($id) ) {
            $result = $tbPropuesta->eliminarPropuesta($id);
        } else {
            $result = $tbPropuesta->unpublishedPrp($id);
        }
        return $result;
    }
    
    private function _availableDelPropuesta( $id ){
        $tbPropuesta = $this->getTable('Propuesta', 'CanastaproyTable');
        $flag = true;
        if ( $tbPropuesta->availableUndTrrPrp( $id ) ||
                $tbPropuesta->availableAlineacionPrp( $id ) ||
                $tbPropuesta->availableUbcGeoPrp( $id ) ) {
            $flag = false;
        }
        return $flag;
    }
}