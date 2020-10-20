<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
jimport('ecorae.uploadfile.upload');

// Adjunto libreria de gestion de Indicador
jimport('ecorae.objetivos.objetivo.indicadores.indicador');

//  Gestion de objetivos operativos
jimport('ecorae.objetivosOperativos.objetivoOperativo');
// Adjunto libreria de gestion de Indicadores
jimport('ecorae.objetivos.objetivo.indicadores.indicadores');
jimport('ecorae.entidad.entidad');
jimport('ecorae.unidadgestion.unidadgestion');
jimport('ecorae.organigrama.organigrama');
jimport( 'ecorae.entidad.EstadoEntidad' );

JTable::addIncludePath(JPATH_BASE . DS . 'components' . DS . 'com_contratos' . DS . 'tables');

/**
 * Modelo tipo obra
 */
class contratosModelConvenio extends JModelAdmin {

    private $_idContrato;
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
    public function getTable($type = 'Contrato', $prefix = 'contratosTable', $config = array()) {
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
    public function getForm($data = array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm('com_contratos.contrato', 'contrato', array('control' => 'jform', 'load_data' => $loadData));

        // $form->setFieldAttribute('dcmMonto_ctr','value', $monto);
        $form->setValue('intIdTipoContrato_tc', null, 2);

        //  Obtengo informacion sobre el identificador Id del registro
        $idContrato = $form->getField('intIdContrato_ctr')->value;

        //  Obtengo informacion de identificador de la entidad relacionada con el proyecto
        $idEntidad = $form->getField('intIdentidad_ent')->value;
        //  En caso que el identificador del programa sea diferente de "0"
        //  Accedo a Informacion referente a indicadores asociados a este programa
        if ($idContrato != 0) {
            $this->_setAributesValues($form, $idEntidad, $idContrato);
            // Instancio la Clase Indicadores
            $objIndicadores = new Indicadores();

            //  Recupero informacion de todos los indicadores registrados 
            //  para un deteminada entidad asociada a una entidad
            $dtaIndicadores = $objIndicadores->getLstIndicadores($idEntidad);

            //  Seteo informacion de Indicadores en el Formulario
            if ($dtaIndicadores) {

                //  Seteo Informacion de Indicadores Economicos en el formulario
                $this->_setDataIndEconomicos($form, $dtaIndicadores, 31);

                //  Seteo Informacion de Indicadores Financieros en el formulario
                $this->_setDataIndFinancieros($form, $dtaIndicadores, 32);

                //  Seteo Informacion de Beneficiarios Directos en el Formulario
                $this->_setDataBDirectos($form, $dtaIndicadores, 33);

                //  Seteo Informacion de Beneficiarios Directos en el Formulario
                $this->_setDataBIndirectos($form, $dtaIndicadores, 34);

                foreach ($dtaIndicadores as $key => $indicador) {
                    //  Agrego el tipo de indicador - Alias de Unidad de Analisis
                    $indicador->nombreIndicador = $indicador->dimension;

                    //  Agrego el tipo de indicador - Alias de Unidad de Analisis
                    $indicador->tpoIndicador = $indicador->alias;

                    $dtoIndicador["idIndEntidad"] = $indicador->idIndEntidad;
                    $dtoIndicador["idIndicador"] = $indicador->idIndicador;
                    $dtoIndicador["nombreIndicador"] = $indicador->nombreIndicador;
                    $dtoIndicador["modeloIndicador"] = $indicador->modeloIndicador;
                    $dtoIndicador["umbral"] = (int) $indicador->umbral;
                    $dtoIndicador["tendencia"] = (int) $indicador->tendencia;
                    $dtoIndicador["descripcion"] = $indicador->descripcion;
                    $dtoIndicador["idUndAnalisis"] = (int) $indicador->idUndAnalisis;
                    $dtoIndicador["undAnalisis"] = (int) $indicador->undAnalisis;
                    $dtoIndicador["idTpoUndMedida"] = (int) $indicador->idTpoUndMedida;
                    $dtoIndicador["idUndMedida"] = (int) $indicador->idUndMedida;
                    $dtoIndicador["undMedida"] = (int) $indicador->undMedida;
                    $dtoIndicador["idTpoIndicador"] = $indicador->idTpoIndicador;
                    $dtoIndicador["formula"] = $indicador->formula;
                    $dtoIndicador["fchHorzMimimo"] = $indicador->fchHorzMimimo;
                    $dtoIndicador["fchHorzMaximo"] = $indicador->fchHorzMaximo;
                    $dtoIndicador["umbMinimo"] = $indicador->umbMinimo;
                    $dtoIndicador["umbMaximo"] = $indicador->umbMaximo;
                    $dtoIndicador["idClaseIndicador"] = $indicador->idClaseIndicador;
                    $dtoIndicador["idFrcMonitoreo"] = $indicador->idFrcMonitoreo;
                    $dtoIndicador["idUGResponsable"] = $indicador->idUGResponsable;
                    $dtoIndicador["idResponsableUG"] = $indicador->idResponsableUG;
                    $dtoIndicador["idResponsable"] = $indicador->idResponsable;
                    $dtoIndicador["idDimension"] = $indicador->idDimension;
                    $dtoIndicador["idEnfoque"] = $indicador->idEnfoque;
                    $dtoIndicador["enfoque"] = $indicador->enfoque;

                    $dtoIndicador["lstUndTerritorial"] = $objIndicadores->getLstIndUndTerritorial($indicador->idIndEntidad);
                    $dtoIndicador["lstLineaBase"] = $objIndicadores->getLstLineasBase($indicador->idIndicador);
                    $dtoIndicador["lstRangos"] = $objIndicadores->getLstRangos($indicador->idIndEntidad);
                    $dtoIndicador["lstVariables"] = $objIndicadores->getLstVariables($indicador->idIndicador);

                    $dtoIndicador["published"] = 1;
                    switch (true) {

                        //  Armo informacion de indicadores Economicos
                        case( $indicador->categoriaInd == 0 && $indicador->idDimension == 31 ):
                            $lstIndEconomicos[] = $dtoIndicador;
                            break;

                        //  Armo informacion de indicadores Financieros
                        case( $indicador->categoriaInd == 0 && $indicador->idDimension == 32 ):
                            $lstIndFinancieros[] = $dtoIndicador;
                            break;

                        //  Armo informacion de indicadores Beneficiarios Directos
                        case( $indicador->categoriaInd == 0 && $indicador->idDimension == 33 ):
                            $lstBDirectos[] = $dtoIndicador;
                            break;

                        //  Armo informacion de indicadores Beneficiarios Indirectos
                        case( $indicador->categoriaInd == 0 && $indicador->idDimension == 34 ):
                            $lstBIndirectos[] = $dtoIndicador;
                            break;

                        //  Armo informacion de indicadores GAP
                        case( $indicador->categoriaInd == 1 && $indicador->idEnfoque == 7 ):
                            $lstTmpGAP[] = $dtoIndicador;
                            break;

                        //  Armo informacion de indicadores Enfoque de Igualdad
                        case( $indicador->categoriaInd == 1 && ( $indicador->idEnfoque == 3 || $indicador->idEnfoque == 4 || $indicador->idEnfoque == 6 ) ):
                            $lstTmpEI[] = $dtoIndicador;
                            break;

                        //  Armo informacion de indicadores Enfoque de Igualdad
                        case( $indicador->categoriaInd == 1 && $indicador->idEnfoque == 5 ):
                            $lstTmpEE[] = $dtoIndicador;
                            break;
                    }
                }

                $indicadores["indEconomicos"] = $lstIndEconomicos;
                $indicadores["indFinancieros"] = $lstIndFinancieros;
                $indicadores["indBDirectos"] = $lstBDirectos;
                $indicadores["indBIndirectos"] = $lstBIndirectos;

                $indicadores["lstGAP"] = ( count($lstTmpGAP) ) ? $objIndicadores->getLstGAP($lstTmpGAP) : '';

                $indicadores["lstEnfIgualdad"] = ( count($lstTmpEI) ) ? $objIndicadores->getLstEI($lstTmpEI) : '';

                $indicadores["lstEnfEcorae"] = ( count($lstTmpEE) ) ? $objIndicadores->getLstEE($lstTmpEE) : '';
            }
            $indicadores["lstOtrosIndicadores"] = $objIndicadores->getLstOtrosIndicadores($idEntidad);
            $form->setFieldAttribute('dataIndicadores', 'default', json_encode($indicadores));
        }
        if (empty($form)) {
            return false;
        }
        return $form;
    }

    /**
     *  retorna toda la informacion de una entidad.
     * @param int $idEntidad    Identificador de la entidad.
     * @return type
     */
    private function _getUrlTable($idEntidad) {
        $oEntidad = new Entidad();
        $dtaEntidad = $oEntidad->getEntidad($idEntidad);
        return $dtaEntidad;
    }

    /**
     * Agrega los valores de los atributos extras.
     */
    private function _setAributesValues($form, $idEntidad, $idContrato) {
        // doy formato al valor del monto del contrato.
        $monto = number_format($form->getField('dcmMonto_ctr')->value, 2, '.', '');
        $form->setValue('dcmMonto_ctr', null, $monto);

        // Agrego la URL del tableU
        $URLTable = $this->_getUrlTable($idEntidad);
        $form->setValue('strURLtableU', null, $URLTable->urlTableU);

        //  Doy formato al monto de un proyecto
        $dtaMonto = number_format($form->getField('dcmMonto_total_stmdoPry')->value, 2, ',', '.');
        $form->setValue('dcmMonto_total_stmdoPry', null, $dtaMonto);

        // set de la unidad de gestion 
        $funcionario = $this->getUnidadGestionContrato();
        if ($funcionario) {
            $form->setValue('intIdUndGestion', null, $funcionario->idUnidadGestion);
            $form->setValue('fchInicioPeriodoUG', null, $funcionario->fechaInicio);
        }


        //  lista de objetivos
        $lstObjetivos = $this->getLstObjetivos($idEntidad);
        $JSONLstObjetivos = json_encode($lstObjetivos);
        $form->setValue('lstObjetivos', null, $JSONLstObjetivos);


        // set de la unidad de gestion responsable del contrato.
        $responsable = $this->getResponsableContrato();
        if ($responsable) {
            $form->setValue('intIdUGResponsable', null, $responsable->idUnidadGestion);
            $form->setValue('idResponsable', null, $responsable->undGestionFuncionario);
            $form->setValue('fchInicioPeriodoFuncionario', null, $responsable->fechaInicio);
        }

        // set la informacion del organigrama
        $organigrama = $this->_getOrganigramaByEntidad($idEntidad);
        if ($organigrama) {
            $form->setValue('organigrama', null, $organigrama);
        }
    }

///////////////////////////////
//  INDICADORES - CONTRATO
///////////////////////////////

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
    private function _setDataIndEconomicos($form, $dtaIndicadores, $idDimension) {
        foreach ($dtaIndicadores as $indicador) {
            switch (true) {
                //  Tasa de Descuento
                case ( ( $indicador->idDimension == (string) $idDimension ) && ( $indicador->idUndAnalisis == "14" ) ):
                    $form->setFieldAttribute('intTasaDctoEco', 'default', (int) $indicador->umbral);
                    break;

                //  Valor Actual Neto
                case ( ( $indicador->idDimension == (string) $idDimension ) && ( $indicador->idUndAnalisis == "15" ) ):
                    $form->setFieldAttribute('intValActualNetoEco', 'default', (int) $indicador->umbral);
                    break;

                //  Tasa Interna de Retorno
                case ( ( $indicador->idDimension == (string) $idDimension ) && ( $indicador->idUndAnalisis == "16" ) ):
                    $form->setFieldAttribute('intTIREco', 'default', (int) $indicador->umbral);
                    break;
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
    private function _setDataIndFinancieros($form, $dtaIndicadores, $idDimension) {
        foreach ($dtaIndicadores as $indicador) {
            switch (true) {
                //  Tasa de Descuento
                case ( ( $indicador->idDimension == (string) $idDimension ) && ( $indicador->idUndAnalisis == "14" ) ):
                    $form->setFieldAttribute('intTasaDctoFin', 'default', (int) $indicador->umbral);
                    break;

                //  Valor Actual Neto
                case ( ( $indicador->idDimension == (string) $idDimension ) && ( $indicador->idUndAnalisis == "15" ) ):
                    $form->setFieldAttribute('intValActualNetoFin', 'default', (int) $indicador->umbral);
                    break;

                //  Tasa Interna de Retorno
                case ( ( $indicador->idDimension == (string) $idDimension ) && ( $indicador->idUndAnalisis == "16" ) ):
                    $form->setFieldAttribute('intTIRFin', 'default', (int) $indicador->umbral);
                    break;
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
    private function _setDataBDirectos($form, $dtaIndicadores, $idDimension) {
        foreach ($dtaIndicadores as $indicador) {
            switch (true) {
                //  Beneficiarios Directos Hombre
                case ( ( $indicador->idDimension == (string) $idDimension ) && ( $indicador->idUndAnalisis == "6" ) ):
                    $form->setFieldAttribute('intBenfDirectoHombre', 'default', (int) $indicador->umbral);
                    break;

                //  Beneficiarios Directos Mujer
                case ( ( $indicador->idDimension == (string) $idDimension ) && ( $indicador->idUndAnalisis == "7" ) ):
                    $form->setFieldAttribute('intBenfDirectoMujer', 'default', (int) $indicador->umbral);
                    break;

                //  Total Beneficiarios Directo
                case ( ( $indicador->idDimension == (string) $idDimension ) && ( $indicador->idUndAnalisis == "4" ) ):
                    $form->setFieldAttribute('intTotalBenfDirectos', 'default', (int) $indicador->umbral);
                    break;
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
    private function _setDataBIndirectos($form, $dtaIndicadores, $idDimension) {
        foreach ($dtaIndicadores as $indicador) {
            switch (true) {
                //  Beneficiarios Directos Hombre
                case ( ( $indicador->idDimension == (string) $idDimension ) && ( $indicador->idUndAnalisis == "6" ) ):
                    $form->setFieldAttribute('intBenfIndDirectoHombre', 'default', (int) $indicador->umbral);
                    break;

                //  Beneficiarios Directos Mujer
                case ( ( $indicador->idDimension == (string) $idDimension ) && ( $indicador->idUndAnalisis == "7" ) ):
                    $form->setFieldAttribute('intBenfIndDirectoMujer', 'default', (int) $indicador->umbral);
                    break;

                //  Total Beneficiarios Directo
                case ( ( $indicador->idDimension == (string) $idDimension ) && ( $indicador->idUndAnalisis == "4" ) ):
                    $form->setFieldAttribute('intTotalBenfIndDirectos', 'default', (int) $indicador->umbral);
                    break;
            }
        }

        return;
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
    private function _saveDataIndicadores($idEntidad, $dtaIndicadores) {
        //  $dtaIndicador = json_decode( $dtaIndicadores );
        $dtaIndicador = $dtaIndicadores; // solo para contratos ya viene decodificado.
        //  Gestion Indicadores Economicos
        $this->_registroIndicadoresFijos($idEntidad, $dtaIndicador->indEconomico, 0);

        //  Gestion Indicadores Financieros
        $this->_registroIndicadoresFijos($idEntidad, $dtaIndicador->indFinanciero, 0);

        //  Gestion Indicadores Beneficiarios Directos
        $this->_registroIndicadoresFijos($idEntidad, $dtaIndicador->indBDirecto, 0);

        //  Gestion Indicadores Beneficiarios Indirectos
        $this->_registroIndicadoresFijos($idEntidad, $dtaIndicador->indBIndirecto, 0);

        //  Gestion Indicadores Dinamicos - GAP
        $this->_registroIndicadoresGAP($idEntidad, $dtaIndicador->lstGAP);

        //  Gestion Indicadores Dinamicos - EI
        $this->_registroIndicadoresEI($idEntidad, $dtaIndicador->lstEnfIgualdad);

        //  Gestion Indicadores Dinamicos - EE
        $this->_registroIndicadoresEE($idEntidad, $dtaIndicador->lstEnfEcorae);

        //  Gestion de Otros Indicadores
        $this->_registroOtrosIndicadores($idEntidad, $dtaIndicador->lstOtrosIndicadores);

        return;
    }

    /**
     * 
     * Gestiono el Registro de Indicadores Fijos
     * 
     * @param type $idEntidad           Identificador de Entidad
     * @param type $dtaIndicadores      Datos del Indicador
     * @param type $banCategoriaInd     Categoria del Indicador 
     * 
     */
    private function _registroIndicadoresFijos($idEntidad, $dtaIndicadores, $banCategoriaInd = 0) {
        if (count($dtaIndicadores) > 0) {
            foreach ($dtaIndicadores as $indicador) {
                $objIndicador = new Indicador();
                $objIndicador->registroIndicador($idEntidad, $indicador, 0);
            }
        }
    }

    /**
     * 
     * Gestion de Registro de Indicadores GAP
     * 
     * @param type $idEntidad       Identificador de Indicador Entidad
     * @param type $dtaIndicador    Datos de Indicadores GAP
     */
    private function _registroIndicadoresGAP($idEntidad, $dtaIndicadores) {
        if (count($dtaIndicadores) > 0) {
            foreach ($dtaIndicadores as $indicador) {
                $objIndicador = new Indicador();
                $objIndicador->registroIndicador($idEntidad, $indicador->gapMasculino, 1);
                $objIndicador->registroIndicador($idEntidad, $indicador->gapFemenino, 1);
                $objIndicador->registroIndicador($idEntidad, $indicador->gapTotal, 1);
            }
        }
    }

    /**
     * 
     * Gestiono el registro de Enfoque de igualdad
     * 
     * @param type $idEntidad           Identificador del Indicador
     * @param type $dtaIndicadores      Datos del indicador Enfoque de Igualdad
     */
    private function _registroIndicadoresEI($idEntidad, $dtaIndicadores) {
        foreach ($dtaIndicadores as $indicador) {
            $objIndicador = new Indicador();
            $objIndicador->registroIndicador($idEntidad, $indicador->eiMasculino, 1);
            $objIndicador->registroIndicador($idEntidad, $indicador->eiFemenino, 1);
            $objIndicador->registroIndicador($idEntidad, $indicador->eiTotal, 1);
        }
    }

    /**
     * 
     * Gestiona el registro de Indicadores Ecorae
     * 
     * @param type $idEntidad
     * @param type $dtaIndicadores
     */
    private function _registroIndicadoresEE($idEntidad, $dtaIndicadores) {
        foreach ($dtaIndicadores as $indicador) {
            $objIndicador = new Indicador();
            $objIndicador->registroIndicador($idEntidad, $indicador->eeMasculino, 1);
            $objIndicador->registroIndicador($idEntidad, $indicador->eeFemenino, 1);
            $objIndicador->registroIndicador($idEntidad, $indicador->eeTotal, 1);
        }
    }

    /**
     * 
     * Gestiona Informacion de un nuevo indicador de tipo Otro Indicador
     * 
     * @param type $idEntidad
     * @param type $lstOtrosIndicadores
     */
    private function _registroOtrosIndicadores($idEntidad, $lstOtrosIndicadores) {
        foreach ($lstOtrosIndicadores as $oi) {
            $objIndicador = new Indicador();
            $objIndicador->registroIndicador($idEntidad, $oi, 2);
        }
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData() {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_contratos.edit.contrato.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }

    /**
     * Recupera la lista de atributos de un contrato
     * @return type
     */
    public function getAtributos() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');
        $ltsAtributos = false;

        if ((int) $idContrato != 0) {
            $tbAtributos = $this->getTable('Atributo', 'ContratosTable');
            $ltsAtributos = $tbAtributos->getAtributosContrato($idContrato);
        }

        return $ltsAtributos;
    }

    /**
     * Recupera una lista de multas del array
     * @return type
     */
    public function getMultasContrato() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');

        $ltsMultas = false;

        if ((int) $idContrato != 0) {
            $tbMulta = $this->getTable('Multa', 'ContratosTable');
            $ltsMultas = $tbMulta->getMultasContrato($idContrato);
        }
        return $ltsMultas;
    }

    /**
     * Recupera una lista de facturas del contrato
     * @return type
     */
    public function getFacturasContrato() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');
        $ltsFacturas = false;

        if ((int) $idContrato != 0) {
            $tbFacturas = $this->getTable('Factura', 'ContratosTable');
            $ltsFacturas = $tbFacturas->getFacturasContrato($idContrato);
            if ($ltsFacturas) {
                // pagos de una factura
                foreach ($ltsFacturas AS $factura) {
                    $factura->pagoFactura = $tbFacturas->getPagosFacturaContrato($factura->idFactura);
                    $factura->planilla = $tbFacturas->getPlanillaFacturaContrato($factura->idFactura);
                }
            }
        }
        return $ltsFacturas;
    }

    /**
     * 
     * @return type
     */
    public function getPagosContrato() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');

        $ltsPagos = false;

        if ((int) $idContrato != 0) {
            $tbPago = $this->getTable('pago', 'ContratosTable');
            $ltsPagos = $tbPago->getPagosContrato($idContrato);
        }
        return $ltsPagos;
    }

    /**
     * Recupera la lista de planes de pago de un contrato.
     * @return type
     */
    public function getPlanesPagoContrato() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');

        $ltsPlanesPago = false;

        if ((int) $idContrato != 0) {
            $tbPlanPagos = $this->getTable('PlanPago', 'ContratosTable');
            $ltsPlanesPago = $tbPlanPagos->getPlanesPagoContrato($idContrato);
        }
        return $ltsPlanesPago;
    }

    /**
     * Recupera la lista de fiscalizadores de un contrato
     * @return type
     */
    public function getFiscalizadoresContrato() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');
        $fiscalizadores = false;

        if ((int) $idContrato != 0) {
            $tbFiscalizador = $this->getTable('fiscalizador', 'ContratosTable');
            $fiscalizadores = $tbFiscalizador->getFiscalizadoresContratos($idContrato);
        }
        return $fiscalizadores;
    }

    /**
     * Recupera las prorrogas de un contrato
     * @return type
     */
    public function getProrrogasContrato() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');
        $prorrogas = array();
        if ((int) $idContrato != 0) {
            $tbProrroga = $this->getTable('Prorroga', 'ContratosTable');
            $prorrogas = $tbProrroga->getProrrogasContrato($idContrato);
        }
        return $prorrogas;
    }

    /**
     * Retorna el programa de un contrato
     * @return type
     */
    public function getProgramaContrato() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');
        $programa = array();
        if ((int) $idContrato != 0) {
            $tbProrroga = $this->getTable('Contrato', 'ContratosTable');
            $programa = $tbProrroga->getProgramaContrato($idContrato);
        }
        return $programa;
    }

    /**
     * Retorna los responsables de un contrato.
     * @return type
     */
    private function getResponsableContrato() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');
        $responsable = array();
        if ((int) $idContrato != 0) {
            $tbProrroga = $this->getTable('ResponsableContrato', 'ContratosTable');
            $responsable = $tbProrroga->getResponsableContrato($idContrato);
        }
        return $responsable;
    }

    /**
     * Recupera la UNIDAD DE GESTION DE UN CONTRATO.
     * @return type
     */
    private function getUnidadGestionContrato() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');
        $responsable = array();
        if ((int) $idContrato != 0) {
            $tbProrroga = $this->getTable('UnidadGestionContrato', 'ContratosTable');
            $responsable = $tbProrroga->getUnidadGestionContrato($idContrato);
        }
        return $responsable;
    }

    /**
     * 
     * @return type
     */
    public function getAnticipoContrato() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');
        $anticipo = false;
        if ((int) $idContrato != 0) {
            $tbpago = $this->getTable('Pago', 'ContratosTable');
            $anticipo = $tbpago->getAnticipoContrato((int) $idContrato);
            if ($anticipo) {
                $facturaPago = $tbpago->getFacturaPagoAnticipo($anticipo->idPago);
                if ($facturaPago) {
                    $anticipo->idFacturaPago = $facturaPago->idFacturaPago;
                    $anticipo->factura = $tbpago->getAnticipoFacturaContrato($anticipo->idFacturaPago);
                } else {
                    $anticipo->factura = null;
                }
            }
        }
        return $anticipo;
    }

    /**
     * Retorna la lista de graficos de un contrato con sus coordenadas
     * @return array
     */
    public function getGraficosContrato() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');
        $graficos = array();
        if ((int) $idContrato != 0) {
            $tbGrafico = $this->getTable('grafico', 'ContratosTable');
            $graficos = $tbGrafico->getGraficosContrato($idContrato);
            if ($graficos) {
                foreach ($graficos AS $grafico) {
                    $tbCoordenada = $this->getTable('coordenada', 'ContratosTable');
                    $lstCoordenadas = $tbCoordenada->getCoordenadasGrafico($grafico->idGrafico);
                    if ($lstCoordenadas) {
                        $grafico->lstCoordenadas = $lstCoordenadas;
                    } else {
                        $grafico->lstCoordenadas = array();
                    }
                }
            }
        }
        return $graficos;
    }

    /**
     * Lista de unidades territoriales del contrato
     * @return type
     */
    public function getUnidadTerritorial() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');
        $unidadesTerritoriales = array();
        if ($idContrato != 0) {
            $tbvwdpa = $this->getTable('vwdpa', 'ContratosTable');
            $unidadesTerritoriales = $tbvwdpa->getLstUndTerritoriales($idContrato);
        }
        return $unidadesTerritoriales;
    }

    /**
     * 
     * @param array $dataGeneral                Datos generales del contrato.
     * @param array $dataAtributos              Lista de atributos
     * @param array $dataGarantias              Lista de Grantias 
     * @param array $dataMultas                 Lista de multas
     * @param array $dataContratistaContrato    LIsta de contratistas
     * @param array $dataFiscalizadores         Lista de fiscalizadores
     * @param array $dataProrrogas              Lista de prorrogas
     * @param array $dataPlanesPagos            Lista de planes de pagos
     * @param array $dataFacturas               Lista de facturas
     * @param array $anticipo                   Objeto Anticipo
     * @param array $graficos                   Lista de graficos con sus coordenadas
     * @param array $unidadesTerritoriales      Lista de unidades territoriales
     * @param array $lstIndicadores             Lista de indicadores
     * @param array $lstObjetivos               Lista de objetivos
     * @return int                              Identificador del contrato.
     */
    public function saveDataForm($dataGeneral, $dataAtributos, $dataGarantias, $dataMultas, $dataContratistaContrato, $dataFiscalizadores, $dataProrrogas, $dataPlanesPagos, $dataFacturas, $anticipo, $graficos, $unidadesTerritoriales, $lstIndicadores, $lstObjetivos) {
        $tbContratos = $this->getTable();
        // Gestiono la entidad.
        $idEntidad = $this->saveEntidad($dataGeneral["idEntidad"], $dataGeneral["urlTableU"]);
        // Gestiono el contrato
        $idContrato = $tbContratos->saveData($idEntidad, $dataGeneral);
        if ($idContrato != 0) {
            //  Registro los atributos
            $this->saveAtributoContrato($idContrato, $dataAtributos);

            $this->_registroEstadoEntidad( JRequest::getVar( 'idEstadoEntidad' ) );
            
            //  Registro de Objetivos de un proyecto
            $this->_registroObjetosOperativos($idEntidad, $lstObjetivos);

            //  Registro las garantias
            $this->saveGarantiasContrato($idContrato, $dataGarantias);

            //  Registro los Contratistas
            $this->saveContratistaContrato($idContrato, $dataContratistaContrato);

            //  Registro las multas
            $this->saveMultas($idContrato, $dataMultas);

            //  Registro las Fiscalizadores
            $this->saveFiscalizadores($idContrato, $dataFiscalizadores);

            //  Registro las Prorrogas
            $this->saveProrrogras($idContrato, $dataProrrogas);

            //  Registro los planes de pago $dataPlanesPagos
            $this->savePlaPago($idContrato, $dataPlanesPagos);

            //  Registro los planes de pago $dataFacturas
            $this->saveFacturasPagos($idContrato, $dataFacturas);

            //  Registro de anticipos.
            $this->saveAnticipoContrato($idContrato, $anticipo);

            //  Registo los Graficos y sus coordenadas de un contrato
            $this->saveGraficoContrato($idContrato, $graficos);

            //  Registo la unidad territorial del los contratos
            $this->saveUnidadTerritorial($idContrato, $unidadesTerritoriales);

            //  Resgsito los indicadores
            $this->_saveDataIndicadores($idEntidad, $lstIndicadores);

            // Registro del UNIDAD DE GESTION RESPONSABLE de un CONTRATO
            $this->saveUnidadGestionContrato($idContrato, $dataGeneral["idUGR"], $dataGeneral["fchIniciUGR"]);

            // Registro del FUNCIONARIO RESPONSABLE de un CONTRATO
            $this->saveResponsableContrato($idContrato, $dataGeneral["idResponsable"], $dataGeneral["fchIniciRes"]);
        }

        return $idContrato;
    }
    
    /**
     *  Guarda el estado entidad del contrato
     * @param type $idEstadoEntidad
     * @return type
     */
    private function _registroEstadoEntidad( $idEstadoEntidad )
    {
        $tbEstEntidad = new EstadoEntidad();
        $idRegEstEntidad = $tbEstEntidad->gestionEstadoEntidad( $this->_idEntidad, $idEstadoEntidad );
        return $idRegEstEntidad;
    }

    /**
     *  Registra la entidad de un contrato.
     * @param int $idEntidad    Identificador de la entidad.
     * @param int $tipo         Identificador del tipo de entidad.
     * @return type
     */
    public function saveEntidadContrato($idEntidad, $tipo) {
        $tbEntidad = $this->getTable('Entidad', 'ContratosTable');
        $idEntidad = $tbEntidad->saveEntidad($idEntidad, $tipo);
        return $idEntidad;
    }

    /**
     *  Gestiona el registro de la entidad de un programa
     * @param int    $idEntidad   Identificador de la entidad
     * @param String $urlTableU   URL tableU
     * @return int
     */
    public function saveEntidad($idEntidad, $urlTableU) {
        $entidad = new Entidad();
        $tpoEntidad = 3; // entidad tipo contrato.
        $idEntidad = $this->_idEntidad = $entidad->saveEntidad( $idEntidad, $tpoEntidad, $urlTableU );

        return $idEntidad;
    }

    /**
     * Registo de los Atributos de un contrato.
     * @param type $idContrato      Identificador del contrato
     * @param type $dataAtributos   Lista de atributos del contrato.
     */
    public function saveAtributoContrato($idContrato, $dataAtributos) {
        $tbAtributo = $this->getTable('Atributo', 'ContratosTable');
        if ($dataAtributos) {
            foreach ($dataAtributos AS $atributo) {
                $tbAtributo->saveAtributos($idContrato, $atributo);
            }
        }
    }

    /**
     *  Registro de las garantias de un contrato.
     * @param type $idContrato      Identidicador del contrato
     * @param type $dataGarantias
     */
    public function saveGarantiasContrato($idContrato, $dataGarantias) {
        $tbGarantia = $this->getTable('Garantia', 'ContratosTable');
        if ($dataGarantias) {
            foreach ($dataGarantias AS $garantia) {
                $idGarantia = $tbGarantia->saveGarantias($idContrato, $garantia);
                // gestionando la garantia del estado.
                $tbGarantiaEstado = $this->getTable('GarantiaEstado', 'ContratosTable');
                if ($garantia->estados) {
                    foreach ($garantia->estados AS $estado) {
                        $tbGarantiaEstado->saveGarantiaEstado($idGarantia, $estado);
                    }
                }
            }
        }
    }

    /**
     *  Registra los contratistas de un contrato.
     * @param int   $idContrato                 Identidicador del contrato
     * @param array $dataContratistaContrato    Lista de contratistas.
     */
    public function saveContratistaContrato($idContrato, $dataContratistaContrato) {
        $tbContratista = $this->getTable('ContratistaContrato', 'ContratosTable');
        if ($dataContratistaContrato) {
            foreach ($dataContratistaContrato AS $contratista) {
                $idContratistaContrato = $tbContratista->saveContratistasContrato($idContrato, $contratista);
                // gestionandolos contactos de un contratista.
                $tbContactos = $this->getTable('Contacto', 'ContratosTable');
                $idContratista = $contratista->idContratista;
                if ($contratista->contactos) {
                    foreach ($contratista->contactos AS $contacto) {
                        $tbContactos->saveContactosContratista($idContratista, $contacto);
                    }
                }
            }
        }
    }

    /**
     *  Registra las multas de un contrato
     * @param int   $idContrato         Identidicador del contrato.
     * @param array $dataMultas         Lista de multas.
     */
    public function saveMultas($idContrato, $dataMultas) {
        $tbMulta = $this->getTable('Multa', 'ContratosTable');
        if ($dataMultas) {
            foreach ($dataMultas AS $multa) {
                $tbMulta->saveMultas($idContrato, $multa);
            }
        }
    }

    /**
     *  Registra los fiscalizadores de un contrato
     * @param int   $idContrato             Identidicador del contrato.
     * @param array $dataFiscalizadores     Lista de fiscalizadores
     */
    public function saveFiscalizadores($idContrato, $dataFiscalizadores) {
        $tbFiscalizador = $this->getTable('FiscalizadorContrato', 'ContratosTable');
        if ($dataFiscalizadores) {
            foreach ($dataFiscalizadores AS $fiscalizador) {
                $tbFiscalizador->saveFiscalizador($idContrato, $fiscalizador);
            }
        }
    }

    /**
     *  Registra las prorrogas de un contrato.
     * @param int   $idContrato     Identidicador del contrato.
     * @param array $dataProrrogas  Lista de prorrogas
     */
    public function saveProrrogras($idContrato, $dataProrrogas) {
        $tbProrrogas = $this->getTable('Prorroga', 'ContratosTable');
        if ($dataProrrogas) {
            foreach ($dataProrrogas AS $prorroga) {
                $tbProrrogas->saveProrroga($idContrato, $prorroga);
            }
        }
    }

    /**
     *  Registra los planes de pago de un contrato.
     * @param int   $idContrato     Identidicador del contrato.
     * @param array $dataPlanesPagos Lista de planes de pago
     */
    public function savePlaPago($idContrato, $dataPlanesPagos) {
        $tbPlanPago = $this->getTable('PlanPago', 'ContratosTable');
        if ($dataPlanesPagos) {
            foreach ($dataPlanesPagos AS $planPago) {
                $tbPlanPago->savePlanPago($idContrato, $planPago);
            }
        }
    }

    /**
     *  Registra las facturas de un contrato.
     * @param int   $idContrato     Identidicador del contrato 
     * @param array $dataFacturas   Lista de planes de pago
     */
    public function saveFacturasPagos($idContrato, $dataFacturas) {
        if ($dataFacturas) {
            foreach ($dataFacturas AS $factura) {
                $tbFactura = $this->getTable('factura', 'ContratosTable');
                $idFactura = $tbFactura->saveFactura($idContrato, $factura);
                if ($idFactura) {
                    $tbPago = $this->getTable('Pago', 'ContratosTable');
                    $idPago = $tbPago->savePagoFactura($idContrato, $factura->pagoFactura);
                    // la relacion N a N
                    $tbFacturaPago = $this->getTable('FacturaPago', 'ContratosTable');
                    $idFacturaPago = $tbFacturaPago->saveFacturaPago($idFactura, $idPago, $factura->pagoFactura);
                    // factuara planilla
                    $tbFacturaPlanilla = $this->getTable('Planilla', 'ContratosTable');
                    $idFacturaPlanilla = $tbFacturaPlanilla->saveFacturaPlanilla($idFactura, $factura->planilla);
                }
            }
        }
    }

    /**
     * Registra los Anticipos de un contrato.
     * @param int       $idContrato Identidicador del contrato 
     * @param object    $anticipo   Anticipo
     */
    public function saveAnticipoContrato($idContrato, $anticipo) {
        //  Registrar Anticipo
        $tbPago = $this->getTable('Pago', 'ContratosTable');
        $idPagoAdelanto = $tbPago->saveAnticipoContrato($idContrato, $anticipo);
        $tbFactura = $this->getTable('factura', 'ContratosTable');
        $idFacturaAnticipo = $tbFactura->saveFactura($idContrato, $anticipo->factura);

        $tbFacturaPago = $this->getTable('FacturaPago', 'ContratosTable');
        $idFacturaPagoAnticipo = $tbFacturaPago->saveFacturaPago($idFacturaAnticipo, $idPagoAdelanto, $anticipo->idFacturaPago);
    }

    /**
     * 
     *  Registro la unidad territorial
     * @param int   $idContrato             Identificador del contrato.
     * @param array $unidadesTerritoriales  Lista de unidades Territoriales
     * @return int  $idUnidadTerritorial     Identificador de la unidad territorial
     */
    public function saveUnidadTerritorial($idContrato, $unidadesTerritoriales) {
        if (count($unidadesTerritoriales) > 0) {
            $tbUndTer = $this->getTable('UnidadTerritorial', 'ContratosTable');
            $tbUndTer->deleteUnidadesTerritoriales($idContrato);
        }
        if ($unidadesTerritoriales) {
            foreach ($unidadesTerritoriales AS $unidadTerritorial) {
                $tbUnidadTerritorial = $this->getTable('UnidadTerritorial', 'ContratosTable');
                $idUnidadTerritorial = $tbUnidadTerritorial->saveUnidadTerritorial($idContrato, $unidadTerritorial);
            }
        }
        return;
        $idUnidadTerritorial;
    }

    /**
     * 
     * @param int   $idContrato Identificador del contrato.
     * @param array $graficos   Lista de Graficos
     */
    public function saveGraficoContrato($idContrato, $graficos) {
        if (count($graficos) > 0)
            foreach ($graficos AS $grafico) {
                $tbGrafico = $this->getTable('grafico', 'ContratosTable');
                $idGrafico = $tbGrafico->saveGraficoContrato($idContrato, $grafico);
                foreach ($grafico->lstCoordenadas AS $coordenada) {
                    $tbCoordenada = $this->getTable('coordenada', 'ContratosTable');
                    $idCoordenada = $tbCoordenada->saveCoordenadaGrafico($idGrafico, $coordenada);
                }
            }
    }

    /**
     * Gestiona la informacion de una UNIDAD de GESTION RESPONSABLE del CONTRATO.
     * @param int   $idContrato         Identificador de contrato
     * @param int   $idUnidadGestion    Identificador de la Unidad de Gestión
     * @param date  $fchIniciUGR        Fecha de Iniciio de la gestion
     */
    public function saveUnidadGestionContrato($idContrato, $idUnidadGestion, $fchIniciUGR) {
        $tbUnidadGestioContrato = $this->getTable('UnidadGestionContrato', 'ContratosTable');
        if ($idUnidadGestion) {
            $tbUnidadGestioContrato->saveUnidadGestionContrato($idContrato, $idUnidadGestion, $fchIniciUGR);
        }
    }

    /**
     * 
     * @param type $idContrato
     * @param type $idResponsable
     */
    public function saveResponsableContrato($idContrato, $idResponsable, $fchIniciRes) {
        $tbUnidadGestioContrato = $this->getTable('ResponsableContrato', 'ContratosTable');
        if ($idResponsable) {
            $tbUnidadGestioContrato->saveResponsableContrato($idContrato, $idResponsable, $fchIniciRes);
        }
    }

    /**
     * 
     * @param type $idContrato
     */
    public function getGarantiaContrato() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');
        $tbContratos = $this->getTable();
        $ltsGarantias = false;

        if ((int) $idContrato != 0) {
            $ltsGarantias = $tbContratos->getGarantiasContrato($idContrato);

            if ($ltsGarantias) {
                foreach ($ltsGarantias AS $garantia) {
                    $garantia->estados = array_values($tbContratos->getGarantiaEstadoContrato($garantia->idGarantia));
                }
            }
        }

        return $ltsGarantias;
    }

    /**
     *  Retorna la data de un contratista y sus contactos. 
     * @return type
     */
    public function getContratistasContrato() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');
        $lstContratistas = false;

        if (isset($idContrato)) {
            $tbContratistaContrato = $this->getTable('ContratistaContrato', 'ContratosTable');

            $lstContratistas = $tbContratistaContrato->getContratistaContrato($idContrato);

            if ($lstContratistas) {
                foreach ($lstContratistas AS $contratista) {
                    $tbContacto = $this->getTable('Contacto', 'ContratosTable');
                    $contratista->contactos = $tbContacto->getContactosContratista($contratista->idContratista);
                }
            }
        }

        return $lstContratistas;
    }

    /**
     * Recupera una lista de subprogramas de un programa 
     * @param type $idSubPrograma identificador del programa
     */
    public function getSubProgramas($idSubPrograma) {
        $tbSubProgramas = $this->getTable('SubPrograma', 'ContratosTable');
        return $tbSubProgramas->getSubProgramas($idSubPrograma);
    }

    /**
     * Recupera la lista de proyectos de un subprograma
     * @param type $idSubPrograma
     * @return type
     */
    public function getProyectos($idProyecto) {
        $tbProyectos = $this->getTable('Proyecto', 'ContratosTable');
        return $tbProyectos->getProyectos($idProyecto);
    }

    /**
     * 
     * @param type $idPersona
     * @return type
     */
    public function getDataPersona($idPersona) {
        $tbPersona = $this->getTable('Persona', 'ContratosTable');
        return $tbPersona->getDataPersona($idPersona);
    }

    /**
     * 
     * @param type $idFiscalizador
     * @return type
     */
    public function getDataPersonaFicalizador($idFiscalizador) {
        $tbFiscalizador = $this->getTable('Fiscalizador', 'ContratosTable');
        return $tbFiscalizador->getDataPersonaFicalizador($idFiscalizador);
    }

    /**
     * 
     * @param type $idProvincia
     * @return type
     */
    public function getCantones($idProvincia) {
        $tbUndTerritorial = $this->getTable('vwdpa', 'ContratosTable', $config = array());
        return $tbUndTerritorial->lstCantones($idProvincia);
    }

    /**
     * 
     * @param type $idCanton
     * @return type
     */
    public function getParroquias($idCanton) {
        $tbUndTerritorial = $this->getTable('vwdpa', 'ContratosTable', $config = array());
        return $tbUndTerritorial->lstParroquias($idCanton);
    }

    /**
     * 
     * Recupera la lista de documentos que tiene el contrato.
     * @param type $idContrato
     */
    public function getDocsContratos() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');
        $lstArchivos = array();
        
        if( (int)$idContrato ){
            $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "docs" . DS . 'contratos' . DS . $idContrato;

            if (file_exists($path)) {
                $count = 0;
                $directorio = opendir($path);

                while ($archivo = readdir($directorio)) {
                    if ($archivo != "." && $archivo != "..") {
                        $docu["nameArchivo"] = $archivo;
                        $docu["published"] = 1;
                        $docu["regArchivo"] = $count;
                        $lstArchivos[] = $docu;
                        $count++;
                    }
                }
                
                closedir($directorio);
            }
        }
        
        
        return $lstArchivos;
    }

    /**
     * Elimina un archivo del contrato.
     * 
     * @param type $idContrato
     * @param type $nameArchivo
     */
    public function delArchivoContrato($idContrato, $nameArchivo) {
        $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "docs" . DS . 'contratos' . DS . $idContrato . DS . $nameArchivo;
        return unlink($path);
    }

    /**
     * Retorna la información correspondiente a un contrato.
     * @return Object
     */
    public function getContratoData() {
        $idContrato = JRequest::getVar('intIdContrato_ctr');
        $tbContrato = $this->getTable('Contrato', 'contratosTable', $config = array());
        $data = $tbContrato->getContratoById($idContrato);
        return $data;
    }

    public function lstIndicadores($idIndEntidad) {
        $indicadores = new Indicadores();
        $lstIndicadores = $indicadores->getLstIndicadores($idIndEntidad);

        return $lstIndicadores;
    }

    /**
     * Recupera la lista de objetivos de una entidad en este caso un convenio
     * @param int $idEntidad   identificador de la entidad de un programa
     * @return type
     */
    public function getLstObjetivos($idEntidad) {
        $oObjetivoOperativo = new ObjetivoOperativo();

        $lstObjetivos = $oObjetivoOperativo->getObjetivosOperativos($idEntidad);

        return $lstObjetivos;
    }

    /**
     * Gestiona el registro de los objetivos de un programa.
     * @param type $idEntidad       Id entidad del (programa).    
     * @param type $dtaObjetivos    lst de objetivo del programa.
     * @return boolean
     */
    private function _registroObjetosOperativos($idEntidad, $dtaObjetivos) {
        if ($dtaObjetivos->lstObjetivos) {
            foreach ($dtaObjetivos->lstObjetivos AS $objetivo) {
                $oObjetivoOperativo = new ObjetivoOperativo();
                $idObjetivoOperativo = $oObjetivoOperativo->saveObjetivoOperativo($objetivo, $idEntidad);
            }
        }
        return true;
    }

    /**
     * Recupera la unidad de gestion de la entidad.
     * @param type $idEntidad
     * @return type
     */
    private function _getOrganigramaByEntidad($idEntidad) {
        $oOrganigrama = new Organigrama();
        $organigrama = $oOrganigrama->getOrganigrama((int) $idEntidad);
        $organigramaJSON = json_encode($organigrama);
        return $organigramaJSON;
    }

}