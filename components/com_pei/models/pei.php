<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla modelform library
jimport( 'joomla.application.component.modeladmin' );
jimport( 'ecorae.objetivos.objetivo.acciones.acciones' );
jimport( 'ecorae.objetivos.objetivo.objetivos' );

//  Agrego Clase de Retorna informacion Especifica de un Indicador
jimport( 'ecorae.objetivos.objetivo.indicadores.indicador' );

//  Agrego Clase de Retorna informacion de Indicadores asociados a un Objetivo
jimport( 'ecorae.objetivos.objetivo.indicadores.indicadores' );

//  Agrego Clase de Gestion de los Planes Institucionales
jimport( 'ecorae.planinstitucion.planinstitucion' );

//  Agrego la libreria para organizar el organigrama
jimport( 'ecorae.organigrama.organigrama' );

//  Agrego la libreria para organizar el organigrama
jimport( 'ecorae.plan.AjusteFechasPlanes' );

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'grupoindicador.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'planinstitucion' . DS . 'pppp.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'AjusteFechaPlan.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'planinstitucion.php';

JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_pei' . DS . 'tables' );

/**
 * Modelo Plan Estratégico Institucional
 */
class PeiModelPei extends JModelAdmin
{

    private $testForm;

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable( $type = 'Pei', $prefix = 'PeiTable', $config = array () )
    {
        return JTable::getInstance( $type, $prefix, $config );
    }

    /**
     * Method to get the record form.
     *
     * @param	array	$data		Data for the form.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	mixed	A JForm object on success, false on failure
     * @since	1.6
     */
    public function getForm( $data = array (), $loadData = true )
    {
        // Get the form.
        $form = $this->loadForm( 'com_pei.pei', 'pei', array ( 'control' => 'jform', 'load_data' => $loadData ) );
        $this->testForm = $form;

        $idPlan = $form->getField( 'intId_pi' )->value;
        $idEntidad = $form->getField( 'intIdentidad_ent' )->value;

        if ( (int)$idEntidad ){
            $this->setAributesValues( $form, $idPlan, $idEntidad );
        }

        if ( empty( $form ) ){
            return false;
        }

        return $form;
    }

    /**
     * 
     * @param type $form
     * @param type $idPrograma
     * @param type $idEntidad
     */
    private function setAributesValues( $form, $idPrograma, $idEntidad )
    {
        // set la informacion del organigrama
        $organigrama = $this->_getOrganigramaByEntidad( $idEntidad );
        if ( $organigrama ){
            $form->setValue( 'organigrama', null, $organigrama );
        }
    }

    /**
     * Recupera la unidad de gestion de la entidad.
     * @param type $idEntidad
     * @return type
     */
    private function _getOrganigramaByEntidad( $idEntidad )
    {
        $oOrganigrama = new Organigrama();
        $organigrama = $oOrganigrama->getOrganigrama( (int)$idEntidad );
        $organigramaJSON = json_encode( $organigrama );
        return $organigramaJSON;
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
        $data = JFactory::getApplication()->getUserState( 'com_pei.edit.pei.data', array () );

        if ( empty( $data ) ){
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * 
     *  Actualiza el estado de vigencia de un determinado plan
     * 
     * @return type
     */
    public function updVigenciaPlan()
    {
        $idPadre = JRequest::getVar( 'idPadre' );
        $idPlan = JRequest::getVar( 'id' );
        $opVigencia = JRequest::getVar( 'op' );
        $tpo = JRequest::getVar( 'tpo' );

        $oPlan = new PlanInstitucion();
        $result = $oPlan->updVigenciaPlan( $idPlan, $opVigencia );

        //  Actualizo el estado de vigencia de los otros planes
        $vigenciaOtros = ( $opVigencia == 1 )
                ? 0
                : 1;

        $this->_updVigenciaOtrosPlanes( $idPlan, $vigenciaOtros, $tpo, $idPadre );

        return $tpo;
    }

    private function _updVigenciaOtrosPlanes( $idPlan, $opVigencia, $tpo, $idPadre )
    {
        $lstPlanes = $this->_getLstOtrosPlanes( $idPlan, $tpo );

        if ( count( $lstPlanes ) ){
            foreach ( $lstPlanes as $plan ){
                $oPlan = new PlanInstitucion();
                $result = $oPlan->updVigenciaPlan( $plan->idPlan, $opVigencia );
            }
        }

        return $result;
    }

    private function _getLstOtrosPlanes( $idPlan, $tpo )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );

        //  Actualizo la vigencia del plan seleccionado
        return $tbPlan->getLstOtrosPlanes( $idPlan, $tpo );
    }

    /**
     *   Retorna true si el plan estrategico institucional si se puede eliminar
     * 
     * @return type
     */
    public function getAvalibleDel()
    {
        $id = $this->testForm->getValue( 'intId_pi' );

        $tbPlan = $this->getTable( 'Pei', 'PeiTable' );
        $result = $tbPlan->avalibleDel( (int)$id );

        return $result;
    }

    /**
     * 
     * Retorna el id del PEI 
     * 
     * @return type
     * 
     */
    public function guardarPei()
    {
        $dtaFrmPei = json_decode( JRequest::getvar( 'dtaFrm' ) );
        $lstObjPei = json_decode( JRequest::getvar( 'lstObjPei' ) );
        $banFecha = (int)JRequest::getvar( 'banFecha' );

        $db = &JFactory::getDbo();

        try{
            //  Inicio transaccion
            $db->transactionStart();

            //  Bandera que muestra si el plan es nuevo
            $banPln = ( (int)$dtaFrmPei->intId_pi == 0 )
                    ? true
                    : false;

            //  Registra informacion general del PEI
            $dtaPei = $data = $this->_registrarPei( JRequest::getvar( 'dtaFrm' ) );

            if ( $dtaPei["idPei"] && count( (array)$lstObjPei ) > 0 ){
                switch ( true ){
                    //  Si el Plan "NO" es nuevo "Y" existe cambio en fechas, 
                    //  inicio proceso de ajuste de fechas
                    case ( $banPln == false && (int)$banFecha != 0 ):
                        $this->_ajusteFechasPlanes( $dtaPei );
                        break;

                    //  Gestiono informacion complementaria del PEI
                    case ( count( $lstObjPei ) > 0 ):
                        $data = $this->_gestionPEI( $dtaPei, $lstObjPei );
                    break;
                }
            }

            //  Confirmo transaccion
            $db->transactionCommit();

            echo json_encode( $data );

            exit;
        } catch ( Exception $e ){
            //  Deshace las operaciones realizadas antes de encontrar un error
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );

            JErrorPage::render( $e );
        }
    }

    private function _gestionPEI( $dtaPei, $lstObjPei )
    {
        $idTpoPEI = 1;
        $objetivos = new Objetivos();
        $lstObjetivos = $objetivos->saveObjetivos(  $lstObjPei, 
                                                    $dtaPei["idPei"], 
                                                    $idTpoPEI );

        //  Gestiono el registro de Indicadores de tipo Contexto asociado a un PEI
        $this->_registroContextos(  $dtaPei["idEntidadPei"], 
                                    JRequest::getvar( 'dtaLstContextos' ) );

        $data["idPei"]          = $dtaPei["idPei"];
        $data["lstObjetivos"]   = $lstObjetivos;

        return $data;
    }

    private function _generoPlanesComplementarios( $idPei )
    {
        $pln = new PlanInstitucion( $idPei );

        //  Gestiona la Creacion de "PPPP's" de un PEI
        $pln->gestionarPPPP();

        //  Gestiona la Creacion de "PAPP's" de un PEI
        $pln->gestionarPAPP();

        //  Gestiono la creacion de POA's por Unidad de Gestion
        $pln->gestionarPoasxUG();

        //  Gestiono la creacion de POA's por Funcionario
        $pln->gestionarPoasXFuncionarios();

        return;
    }

    /**
     * 
     * Retorna el ID del PEI guardado  
     * 
     * @param type $dtaFrm  Datos generales del plan  
     * @return type
     */
    private function _registrarPei( $dtaPlan )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );

        //  Obtiene la data del JSON
        $dtaFrm = json_decode( $dtaPlan );

        //  Obtiene el Id de entidad de la institucion ecorae, ( 1 Id de la institucion )
        $idEntidadIns = ( (int)$dtaFrm->intIdentidad_ent == 0 )
                        ? $this->_crearEntidadInstitucional()
                        : (int)$dtaFrm->intIdentidad_ent;

        //  Arma el Objeto Plan de tipo PEI
        $frmPei["intId_pi"] = $dtaFrm->intId_pi;
        $frmPei["intId_tpoPlan"] = $dtaFrm->intId_tpoPlan;
        $frmPei["intCodigo_ins"] = $dtaFrm->intCodigo_ins;
        $frmPei["intIdentidad_ent"] = $idEntidadIns;
        $frmPei["strDescripcion_pi"] = $dtaFrm->strDescripcion_pi;
        $frmPei["dteFechainicio_pi"] = $dtaFrm->dteFechainicio_pi;
        $frmPei["dteFechafin_pi"] = $dtaFrm->dteFechafin_pi;
        $frmPei["strAlias_pi"] = $dtaFrm->strAlias_pi;
        $frmPei["blnVigente_pi"] = $this->_existePlnVigente( 1, $dtaFrm->intId_pi );
        $frmPei["published"] = $dtaFrm->published;

        $oPei = new PlanInstitucion( $dtaFrm->intId_pi );
        $idPei = $oPei->guardarPlan( $frmPei );

        $dtaPei["idPei"] = $idPei;
        $dtaPei["idEntidadPei"] = $idEntidadIns;

        return $dtaPei;
    }

    private function _crearEntidadInstitucional()
    {
        $db = JFactory::getDBO();
        $tbEntidad = new jTableEntidad( $db );

        return $tbEntidad->crearEntidad( 8 );
    }

    /**
     * 
     * Gestiono el registro de indicadores de tipo contexto asociados a un PEI
     * 
     * @param int   $idEntidadPei   Identificador Entidad del PEI
     * @param JSon  $dtaContextos   Lista de Contextos asociados a un indicador
     */
    private function _registroContextos( $idEntidadPei, $dtaContextos )
    {
        $lstContextos = json_decode( $dtaContextos );

        //  Gestion de Indicadores
        $objIndicador = new Indicador();

        foreach ( $lstContextos->lstIndicadores as $contexto ){
            $objIndicador->registroIndicador( $idEntidadPei, $contexto, 9 );
        }

        return;
    }

    /**
     *  Retorna una lista de objetivos de un determinado plan 
     * 
     * @param type $idPei
     * @param type $idTpoEntidad    Identificador del Tipo de PEI
     *                                  1: PEI
     *                                  2: POA
     *                                  3: Programas
     * 
     * @return type
     */
    public function lstObjetivos( $idPei, $idTpoEntidad )
    {
        $lstObjetivos = false;
        $objetivos = new Objetivos();

        if ( $objetivos ){
            $lstObjetivos = $objetivos->getLstObjetivos( $idPei, $idTpoEntidad );
        }

        return $lstObjetivos;
    }

    /**
     *  Retorna una lista de poas de un determinado plan 
     * 
     * @param type $idPei
     * @return type
     */
    public function lstPoasPei( $idPei )
    {
        $lstPoas = false;
        $tbPei = $this->getTable( 'Pei', 'PeiTable' );
        $lstPoas = $tbPei->getLstPoas( $idPei );

        if ( $lstPoas ){
            foreach ( $lstPoas AS $key => $poa ){
                $poa->registroPoa = $key;
            }
        }
        return $lstPoas;
    }

    /**
     * 
     * Retorna una lista de Indicadore de Tipo Contexto asociados a un determinado PEI
     * 
     * @param type $intId_pi    Identificador del PEI
     * @return type
     * 
     */
    public function lstContextos( $idEntidad )
    {
        $indicadores = new Indicadores();
        return $indicadores->getLstContextos( $idEntidad );
    }

    /**
     * Funcin encargada de eliminar los archivos 
     */
    public function delArchivo()
    {
        $archivo = JRequest::getVar( "infArchivo" );
        switch ( $archivo["tipo"] ){
            case 1:
                $dirName = 'peis';
                break;
            case 2:
                $dirName = 'poas';
                break;
            case 3:
                $dirName = 'programas';
                break;
        }

        $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "docs" . DS . $dirName . DS . $archivo["idPadre"] . DS . 'objetivos' . DS . $archivo["idObjetivo"] . DS . 'actividades' . DS . $archivo["idActividad"] . DS . $archivo["nameArchivo"];
        $retval = unlink( $path );
        echo $retval;
        exit();
    }

    /**
     * 
     * Gestiona el retorno de Unidades de Medida, asociadas a un determinado 
     * Tipo de Unidad de Medida
     * 
     * @param type $idTpoUM     Identificador del Tipo de Unidad de Medida
     * 
     * @return type
     * 
     */
    public function getUnidadMedida( $idTpoUM )
    {
        $objUM = new UnidadMedida();
        return $objUM->getDtaUnidadMedida( $idTpoUM );
    }

    /**
     * 
     * Retorna informacion de un Indicador de Tipo Plantilla
     * 
     * @param type $idPlantilla     Identificador de Plantilla
     * 
     * @return type
     * 
     */
    public function getDtaPlantilla( $idPlantilla )
    {
        $objIndicador = new Indicadores();
        return $objIndicador->getDtaPlantillaIndicador( $idPlantilla );
    }

    /**
     *  Genera el plan estrategico anual (POA) de las unidadas de gestion de la institucion Ecorae
     * 
     * @param int $anioPoa      Año del que se desea generar el POA
     * @param int $idPadre      Id del padre PEI del que se genera el poa
     * @return boolean
     */
    public function generarPoaUG( $anioPoa, $idPadre )
    {
        $tbPoaUG = $this->getTable( 'UnidadGestion', 'PeiTable' );

        //  Obtengo la lista de unidades de gestion de una institución
        //  1 id de la institucion ecorae
        $lstUG = $tbPoaUG->getLstUnidadesGestion( 1 );

        //  Recorro la lista de unidades de gestion obteniendo la lista de
        //  objetivos relacionas de a cada unidad
        foreach ( $lstUG AS $undGes ){
            //  Obtengo la lista de objetivos
            $lstObjUG = $tbPoaUG->getObjUG( $undGes->intCodigo_ug, $idPadre );
            if ( count( $lstObjUG ) > 0 ){
                //  Registra el POA como un nuevo plan y se obtiene el Id
                $idPoa = $this->_registrarPoa( $undGes, $idPadre, $anioPoa );
                if ( $idPoa ){
                    foreach ( $lstObjUG AS $objPoa ){
                        $objetivo = new Objetivo();
                        $idPlnObjetivo = 0;
                        $idPlnObjPoa = $objetivo->registroPlanObjetivo( $idPlnObjetivo, $objPoa->intId_ob, $idPoa );

                        $this->_accionesPoa( $idPlnObjPoa, $objPoa->intId_ob, $undGes->intCodigo_ug, $idPadre, $anioPoa );
                        $this->_indicadoresPoa( $objPoa->idEntidad, $undGes->intCodigo_ug, $idPoa, $idPadre, $anioPoa );
                    }
                }
            }
        }
        return true;
    }

    /**
     *  Registra la informacion de un nuevo Poa generado
     * 
     * @param int $undGes       Id de la unidad de gestion de la que se va a genetrar el Poa
     * @param int $idPadre      Id del padre Pei que dellque se guenera el Poa
     * @param int $anioPoa      Año del que se genera el Poa
     * @return type
     */
    private function _registrarPoa( $undGes, $idPadre, $anioPoa )
    {
        $tbPlan = $this->getTable( 'Pei', 'PeiTable' );

        $ugPoa['intId_tpoPlan'] = 2;      // 2 es el id del tipo de plan POA 
        $ugPoa['intCodigo_ins'] = 1;      // 1 Id de la intitucion ecorae por defecto
        $ugPoa['intIdPadre_pi'] = $idPadre;
        $ugPoa['intIdentidad_ent'] = $undGes->intIdentidad_ent;
        $ugPoa['strDescripcion_pi'] = 'POA - ' . $anioPoa . ' ' . $undGes->strNombre_ug;
        $ugPoa['dteFechainicio_pi'] = $anioPoa . '-01-01';
        $ugPoa['dteFechafin_pi'] = $anioPoa . '-12-31';
        $ugPoa['blnVigente_pi'] = $anioPoa;      // Año del Poa
        $ugPoa['strAlias_pi'] = 'POA - ' . $anioPoa . ' ' . $undGes->strAlias_ug;
        $ugPoa['published'] = 1;      // 1 por defecto para ser avilitado en el borrado logico

        $idPlan = $tbPlan->registroPlan( $ugPoa );

        return $idPlan;
    }

    /**
     *  Gestiona el registro de las acciones de un objetivo de un POA relacionado
     *  a una unidad de gestion.
     * 
     * @param int $idPlnObjPoa      Id del plan objetivo POA
     * @param int $idObj            Id del objetivo 
     * @param int $idUG             Id de la Unidad de Gestion.
     * @return boolean
     */
    private function _accionesPoa( $idPlnObjPoa, $idObj, $idUG, $idPadre, $anioPoa )
    {
        $tbUG = $this->getTable( 'UnidadGestion', 'PeiTable' );
        $tbPlanAccion = $this->getTable( 'PlnAccion', 'PeiTable' );
        $lstAccionesObj = $tbUG->getAccionesUG( $idObj, $idUG, $idPadre );

        foreach ( $lstAccionesObj AS $accion ){
            $idPlnAcc = $accion->intId_plnAccion;
            $objAccionObj = $tbPlanAccion->getAccionObj( $idPlnAcc );
            $anio = explode( "-", $objAccionObj->fechaInicioAccion );
            if ( $anio[0] == $anioPoa ){
                $objAccionObj->idAccion = 0;
                $objAccionObj->idPlnObjetivo = $idPlnObjPoa;
                $idPlanAccionObj = $tbPlanAccion->registroAccionObj( $objAccionObj );
                if ( $idPlanAccionObj ){
                    $objPoaUGR = $tbPlanAccion->getUndGesRep( $objAccionObj );
                    $tbPlanAccion->registroUndGesRep( $objPoaUGR, $idPlanAccionObj );
                    $objPoaFR = $tbPlanAccion->getFunRep( $objAccionObj );
                    $tbPlanAccion->registroFunRep( $objPoaFR, $idPlanAccionObj );
                }
            }
        }

        return true;
    }

    /**
     *  Gestiona el registro de los indicadores y su programacion de un objetivo de un POA relacionado
     *  a una unidad de gestion.
     * 
     * @param int $idEntObj     Id de la entidad el objetivo.
     * @param int $idUG         Id de la Unidad de Gestion. 
     * @param int $idPoa        Id del Poa generado.
     * @param int $idPadre      Id del padre Pei.
     * @param int $anioPoa      Año del que se genera el Poa.
     * @return boolean
     */
    private function _indicadoresPoa( $idEntObj, $idUG, $idPoa, $idPadre, $anioPoa )
    {
        $tbInd = $this->getTable( 'Indicador', 'PeiTable' );
        $lstIndEnt = $tbInd->getLstIndicadores( $idEntObj, $idUG );

        if ( $lstIndEnt ){
            foreach ( $lstIndEnt AS $indEnt ){
                $lstPrgInd = $tbInd->getPrgIndEnt( $indEnt->intIdIndEntidad, $idPadre );
                if ( $lstPrgInd ){
                    $tbPlan = $this->getTable( 'Pei', 'PeiTable' );
                    foreach ( $lstPrgInd AS $prgInd ){
                        $anio = explode( "-", $prgInd->fechaInico );
                        if ( $anio[0] == $anioPoa ){
                            $dtaPlan["intId_pi"] = 0;
                            $dtaPlan["intId_tpoPlan"] = 6;    //  6 Id de tipo de plan Programacion de POA
                            $dtaPlan["intCodigo_ins"] = 1;    //  1 Id de institucion ecorae
                            $dtaPlan["intIdPadre_pi"] = $idPoa;
                            $dtaPlan["intIdentidad_ent"] = 0;
                            $dtaPlan["strDescripcion_pi"] = "PROGRAMACION ANUAL DEL " . $anio[0];
                            $dtaPlan["strAlias_pi"] = "Prg - POA " . $anio[0];
                            $dtaPlan["dteFechainicio_pi"] = $prgInd->fechaInico;
                            $dtaPlan["dteFechafin_pi"] = $prgInd->fechaFin;
                            $dtaPlan["blnVigente_pi"] = $anio[0];

                            $idPrgPoa = $tbPlan->registroPlan( $dtaPlan );
                            $tbPrgInd = $this->getTable( 'IndProgramacion', 'PeiTable' );

                            $idPoaPrgInd = $tbPrgInd->registroPrgInd( $prgInd, $idPrgPoa );
                            $lstPrgIndDetalle = $tbPrgInd->getLstPrgDetalle( $prgInd->idProInd );

                            if ( $lstPrgIndDetalle ){
                                foreach ( $lstPrgIndDetalle AS $detalle ){
                                    $tbPrgInd->resitroDetallePrg( $detalle, $idPoaPrgInd );
                                }
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * 
     * Retorna una lista de Objetivos Estrategicos (OEI)
     * 
     * @param type $idEntidad   Identificador de la entidad
     * @return type
     * 
     */
    public function lstOEI( $intId_pi )
    {
        $lstObjetivos = false;
        $objetivos = new Objetivos();

        if ( $objetivos ){
            $lstObjetivos = $objetivos->getLstObjetivos( $intId_pi, 1 );
        }

        return $lstObjetivos;
    }

    public function getLstPPPPs( $idPlan )
    {
        $tbPei      = $this->getTable( 'Pei', 'PeiTable' );
        //  Id de tipo de plan PPPP
        $tpoPln     = 3;    
        $lstPPPPs   = $tbPei->getLstPlanes( $idPlan );

        if ( !empty( $lstPPPPs ) ){
            foreach ( $lstPPPPs as $plan ){
                $lstObjetivos = array ();
                $objetivos = new Objetivos();
                if ( $objetivos ){
                    $lstObjetivos = $objetivos->getLstObjetivos( $idPlan, $tpoPln );
                    $plan->lstObjetivos = $lstObjetivos;
                }
            }
        }

        return $lstPPPPs;
    }

    /**
     *  Retorna el objeto con la lista de planes de un Plan Padre (PEI)
     * 
     * @param int       $idPlan     Id del plan padre PEI
     * @param int       $tpoPln     Id del tipo de planes de la lista 
     * @return type
     */
    public function getLstPlanes( $idPlan, $tpoPln )
    {
        $db = JFactory::getDBO();
        $tbPei = new jTablePlanInstitucion( $db );
        $lstPlanes = $tbPei->getLstPlanesByTpo( $idPlan, $tpoPln );
        
        if ( !empty( $lstPlanes ) ){
            foreach ( $lstPlanes as $key => $plan ){
                $plan->idRegPln = $key;
                $lstObjetivos = array ();
                $objetivos = new Objetivos();

                if ( $objetivos ){
                    $lstObjetivos = $objetivos->getLstObjetivos( $plan->idPlan, $tpoPln );
                    $plan->lstObjetivos = $lstObjetivos;
                }
            }
        }

        return $lstPlanes;
    }

    /**
     * Lista de Planes de tipo PAPP
     * @param type $idPlan  Identificador del PLAN Vigente
     */
    public function getLstPAPP( $idPlan )
    {
        $db = JFactory::getDBO();
        $tbPei = new jTablePlanInstitucion( $db );

        //  Obtengo una lista de planes de tipo PPPP, 
        //  pertenecientes a un PEI Vigente
        $lstPlanesPPPP = $tbPei->getLstPlanesByTpo( $idPlan, 3 );

        $lstPlanesPAPP = array ();
        foreach ( $lstPlanesPPPP as $pppp ){
            //  Obtengo los PAPP de un determinado PPPP
            $lstPAPPofPPPP = $tbPei->getLstPlanesByTpo( $pppp->idPlan, 4 );
            $lstPlanesPAPP = array_merge( $lstPlanesPAPP, $lstPAPPofPPPP );
        }

        if ( !empty( $lstPlanesPAPP ) ){
            foreach ( $lstPlanesPAPP as $key => $plan ){
                $plan->idRegPln = $key;
                $lstObjetivos = array ();
                $objetivos = new Objetivos();

                if ( $objetivos ){
                    $lstObjetivos = $objetivos->getLstObjetivos( $plan->idPlan, 4 );
                    $plan->lstObjetivos = $lstObjetivos;
                }
            }
        }

        return $lstPlanesPAPP;
    }

    /**
     * 
     * Verifica la existencia de un plan vigente
     * 
     * @return type
     * 
     */
    private function _existePlnVigente( $idTpoPln = 1, $idPlan = null )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        return $tbPlan->existePlanVigente( $idTpoPln, $idPlan );
    }

    /**
     * 
     * Ajusto Informacion de planes a la nuevas fechas
     * 
     * @param object $dtaPei    Datos registro del PEI
     * 
     * @return int
     */
    private function _ajusteFechasPlanes( $dtaPei )
    {
        $tbAFP = new AjusteFechasPlanes();

        //  Elimino todos los planes complementarios al asociados al PEI
        $idPlan = $tbAFP->eliminarPlanes( $dtaPei["idPei"] );

        //  Genero Planes complementarios en funcion a la nueva informacion del PEI
        $this->_generoPlanesComplementarios( $dtaPei["idPei"] );

        return $idPlan;
    }

    /**
     * 
     * Valido si los indicadores asociados a un determinado plan estan con algun tipo de seguimiento
     * 
     * @param type $dtaPei
     * @return boolean
     */
    private function _tieneSeguimientoPlan( $dtaPei )
    {
        $ban = FALSE;

        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        $lstPoas = $tbPlan->lstPlanesPOA( $dtaPei["idPei"] );

        foreach ( $lstPoas as $poa ){
            if ( $this->_validarSeguimientoPlan( $poa->idEntidadObjetivo ) ){
                $ban = TRUE;
                break;
            }
        }

        return $ban;
    }

    private function _validarSeguimientoPlan( $idEntidad )
    {
        $db = JFactory::getDBO();
        $tbIE = new jTableIndicadorEntidad( $db );

        return $tbIE->verificarSeguimiento( $idEntidad );
    }

    /**
     * 
     * Gestiono la eliminacion fisica de un determinado PLAN
     * 
     * @param int $idPei    Identificador del plan a eliminar
     * 
     */
    public function eliminarPlan( $idPlan )
    {
        $oPlan = new PlanInstitucion();
        $result = $oPlan->eliminarPlanesAsociados( $idPlan );
    }

    public function __destruct()
    {
        return;
    }
}
