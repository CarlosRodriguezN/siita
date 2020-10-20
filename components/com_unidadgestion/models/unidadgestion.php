<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla modelform library
jimport( 'joomla.application.component.modeladmin' );
jimport( 'ecorae.objetivos.objetivo.acciones.acciones' );
jimport( 'ecorae.objetivos.objetivo.objetivos' );
jimport( 'ecorae.planinstitucion.planinstitucion' );
jimport( 'ecorae.entidad.proyecto' );
jimport( 'ecorae.entidad.programa' );
jimport( 'ecorae.entidad.contrato' );
jimport( 'ecorae.entidad.convenio' );
jimport( 'ecorae.organigrama.organigrama' );
jimport( 'ecorae.unidadgestion.unidadgestion' );
jimport( 'ecorae.unidadgestion.funcionario' );

require_once JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'database' . DS . 'table' . DS . 'usergroup.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'acls' . DS . 'permisos.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'planinstitucion.php';
JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_unidadgestion' . DS . 'tables' );

/**
 * Modelo Unidad de Gestion
 */
class UnidadGestionModelUnidadGestion extends JModelAdmin
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
    public function getTable( $type = 'UnidadGestion', $prefix = 'UnidadGestionTable', $config = array() )
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
    public function getForm( $data = array(), $loadData = true )
    {
        // Get the form.
        $form = $this->loadForm( 'com_unidadgestion.unidadgestion', 'unidadgestion', array('control' => 'jform', 'load_data' => $loadData) );

        //  Obtengo informacion sobre el identificador Id del registro
        $idUnidadGestion = $form->getField( 'intCodigo_ug' )->value;
        $idEntidad = $form->getField( 'intIdentidad_ent' )->value;
        
        $this->setAributesValues( $form, $idUnidadGestion, $idEntidad );

        if(empty( $form )) {
            return false;
        }

        return $form;
    }

    /**
     * 
     * @param type $form
     * @param type $idUG
     * @param type $idEntidad
     */
    private function setAributesValues( $form, $idUG, $idEntidad )
    {
        // set la informacion del organigrama
        $organigrama = $this->_getOrganigramaByEntidad( $idEntidad );
        if($organigrama) {
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
        $organigrama = $oOrganigrama->getOrganigrama( $idEntidad );
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
        $data = JFactory::getApplication()->getUserState( 'com_unidadgestion.edit.unidadgestion.data', array() );

        if(empty( $data )) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     *  Lista las Poas que tiene una unidad de gestion
     * 
     * @param int       $idEntidadUg    Id de la entidad de unidad de gestión
     * @return type
     */
    public function lstPoasUG( $idEntidadUg )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        $lstPoasUg = array();

        $poaVigente = $tbPlan->getPoaVigenteByEnt( $idEntidadUg );
        
        if( count( $poaVigente ) ){
        
            $lstPoasUg = $tbPlan->getLstPoasByEntidad( $idEntidadUg, $poaVigente->fechaInicioPln );
            
            if(count( $lstPoasUg ) > 0) {
                foreach ( $lstPoasUg AS $key => $poaUg ){
                    $poaUg->idRegPoa = $key;

                    $objetivos = new Objetivos();
                    $idTpoEntidad = 2; //  Identificador del tipo de entidad POA

                    //  Listo los Objetivos con sus respectivos Acciones - Indicadores que son responsabilidad de una determinada Unidad de Gestion
                    $lstObjsPoa = $objetivos->getLstObjUG( $poaUg->idPoa, $idTpoEntidad, null, $idEntidadUg );

                    if( count( $lstObjsPoa ) ){
                        $poaUg->lstObjetivos = $lstObjsPoa;
                    }
                }
            }

        }

        return $lstPoasUg;
    }

    /**
     *  Lista los funcionarios relacionados con una unidad de gestión 
     * @param int       $idUG   Id de unidad de gestión
     * @return type
     */
    public function lstFuncionariosUG( $idUG )
    {
        $tbFuncionario = $this->getTable( 'Funcionario', 'UnidadGestionTable' );
        $lstFuncinariosUg = $tbFuncionario->getLstFuncionarios( $idUG );

        if(!empty( $lstFuncinariosUg )) {
            foreach ( $lstFuncinariosUg AS $key => $fncio ){
                $fncio->idRegFnci = $key;
            $xxx =$tbFuncionario->getOpAddFnc($fncio->idFuncionario);
                $fncio->lstGrupos = $tbFuncionario->getOpAddFnc($fncio->idFuncionario);
            }
        }

        return $lstFuncinariosUg;
    }

    /**
     *  Guarda un nuevo registro de unidad de gestión con sus objetivos, poas
     *  y sus funcionarios relacionados.
     * 
     */
    public function guardarUndGes()
    {
        $db = &JFactory::getDbo();

        try {
            //  Inicio transaccion
            $db->transactionStart();
            $idUG = $this->_registrarUndGes( JRequest::getvar( 'dtaFrm' ) );

            if($idUG) {
                //  Identificador del Tpo de Entidad POA
                $idTpoPOA = 2;
                //  Restra los funcionarios de la UG
                $lstFuncionarios = $this->_resgistrarFuncionarios( JRequest::getvar( 'lstFnci' ), $idUG, $idTpoPOA );

                //  Registra los POAs de la UG
                $lstPoasUg = json_decode( JRequest::getvar( 'lstPoas' ) );

                foreach ( $lstPoasUg As $poa ){                    
                    if ( $poa->published == 1 ){
                        $idPoa = $this->_registrarPoa( $poa );
                        
                        if($idPoa) {
                            $objetivos = new Objetivos();                            
                            $lstObjetivos = $objetivos->saveObjetivos( $poa->lstObjetivos, $idPoa, $idTpoPOA );
                            $poa->lstObjetivos = $lstObjetivos;
                        }
                    } else if ( $poa->idPoa != 0 ) {
                        $objetivos = new Objetivos();
                        $lstObjetivos = $objetivos->saveObjetivos( $poa->lstObjetivos, $idPoa, $idTpoPOA );
                        $this->_eliminarPlan( (int)$poa->idPoa );
                    }
                }
                
                //  exit;
            }
            $data["idUG"] = $idUG;
            $data["lstPoasUg"] = $lstPoasUg;
            $data["lstFuncionarios"] = $lstFuncionarios;

            //  Confirmo transaccion
            $db->transactionCommit();

            echo json_encode( $data );
            exit;
        } catch (Exception $e) {
            //  Deshace las operaciones realizadas antes de encontrar un error
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *  Refistra la información genaral de una unidad de gestión
     * @param json      $dtaFrm     Data general de la unidada de gestión.
     * @return type
     */
    private function _registrarUndGes( $dtaFrm )
    {
        $dtaUG = json_decode( $dtaFrm );
        $tbUndges = $this->getTable( 'UnidadGestion', 'UnidadGestionTable' );
        
        $undGestion["intCodigo_ug"]     = $dtaUG->intCodigo_ug;
        $undGestion["tb_intCodigo_ug"]  = $dtaUG->tb_intCodigo_ug;
        $undGestion["intCodigo_ins"]    = $dtaUG->intCodigo_ins;
        $undGestion["strNombre_ug"]     = $dtaUG->strNombre_ug;
        $undGestion["strAlias_ug"]      = $dtaUG->strAlias_ug;
        $undGestion["intTpoUG_ug"]      = $dtaUG->intTpoUG_ug;
        $undGestion["published"]        = $dtaUG->published;
        
        if($dtaUG->intCodigo_ug == 0) {
            //  Si es un nuevo registro se genera un nuevo ID de entidad
            //  de tipo de unidad de gestion, "7" id de tipo de unidad de gestion
            $idEntidadUG = $this->_getIdEntidadUG( 7 );
            $undGestion["intIdentidad_ent"] = $idEntidadUG;
            //  Registra coo grupo del sistema y se obtiene su identificador
            $idGroup = $this->registrarGrupo( $dtaUG );
            $undGestion["intIdGrupo_ug"] = $idGroup;
        } else {
            $undGestion["intIdentidad_ent"] = $dtaUG->intIdentidad_ent;
            $undGestion["intIdGrupo_ug"] = $dtaUG->intIdGrupo_ug;
        }

        $idUndGestion = $tbUndges->registroUndGes( $undGestion );

        //  Si la unidad de gestion es nueva, se registra el Sin Funcionario de la unidad de gestion 
        if( is_numeric( $idUndGestion ) && $dtaUG->intCodigo_ug == 0 ) {
            $tbUndges->resgistroSinFuncionarioUG( $idUndGestion );
        }

        return $idUndGestion;
    }

    /**
     *  Registra el el grupo del sistema para la unidad de gestion
     * @param type $dtaUG
     * @return type
     */
    public function registrarGrupo( $dtaUG )
    {
        $tbGrupo =  $this->getTable( 'Usergroup', 'JTable' );
        $data["id"]         = 0;
        $data["parent_id"]  = 6;
        $data["title"]      = "ug-" . $dtaUG->strNombre_ug;
        $result = $tbGrupo->save($data);
        if ( $result ){
            $result = $tbGrupo->id;
        }
        return $result;
    }
    
    /**
     *  Gestiona la eliminacion de un plan POA, eliminacion logica o fisica.
     * @param type $idPln
     */
    private function _eliminarPlan( $idPln )
    {
        $oPlan = new PlanInstitucion();
        // True eliminacion logica, False eliminacion fisica
        $control = $oPlan->controlEliminarPlan( $idPln );
        if ( $control ) {
            $result = $oPlan->eliminarLogicoPlan( $idPln );
        } else {
            $result = $oPlan->eliminarPlan( $idPln );
        }
        
        return $result;
    }
    
    
    /**
     *  Obtiene un nuevo Id de entidad de tipo Unidad de Gestión.
     * 
     * @param int       $idTpoEntidad   Ide de tipo de entidad unidad de gestión
     * @return type
     */
    private function _getIdEntidadUG( $idTpoEntidad )
    {
        $tbUGEnt = $this->getTable( 'UgEntidad', 'UnidadGestionTable' );
        $idEntidad = $tbUGEnt->registraEntidadUg( $idTpoEntidad );
        return $idEntidad;
    }

    /**
     * Regista los funcionnarios de una unidad de gestion
     * @param type $lstFnci
     * @return type
     */
    private function _resgistrarFuncionarios( $lstFnci, $idUG )
    {
        $dtaLstFnci = json_decode( $lstFnci );
        $tbFuncionario = $this->getTable( 'Funcionario', 'UnidadGestionTable' );
        
        if ( !empty($dtaLstFnci) ) {
            $lstFncs = array();
            foreach ( $dtaLstFnci AS $funcionario ){
                $funcionario->idUg = $idUG;
                if((int) $funcionario->published == 1) {
                    $dtaFuncionario["intId_ugf"]            = $funcionario->idUgFnci;
                    $dtaFuncionario["intCodigo_ug"]         = $funcionario->idUg;
                    $dtaFuncionario["intId_ugc"]            = $funcionario->idCargoFnci;
                    $dtaFuncionario["intCodigo_fnc"]        = $funcionario->idFuncionario;
                    $dtaFuncionario["dteFechaInicio_ugf"]   = $funcionario->fechaInicio;
                    $dtaFuncionario["dteFechaFin_ugf"]      = $funcionario->fechaFin;
                    $dtaFuncionario["dteFechaRegistro_ugf"] = date( "Y-m-d" );
                    $dtaFuncionario["published"]            = 1;
                    
                    if( $funcionario->fechaUpd ) {
                        $dtaFuncionario["dteFechaModificacion_ugf"] = date( "Y-m-d" );
                    }
                    
                    //  Desvicula el funcionario de la UG - SIN UNIDAD DE GESTION
                    if( $funcionario->idUgFnci == 0 || $funcionario->idUgFnci == "" ){
                        $tbFuncionario->updateFncUg( $funcionario->idFuncionario, 0 );
                    }
                    
                    //  Registro el funcionario de la unidad de gestion
                    $idFuncionarioUG = $tbFuncionario->saveFuncionarioUG( $dtaFuncionario );
                    $funcionario->idUgFnci = $idFuncionarioUG;
                    
                    //  Asignacion del funcionario a los grupos relacionados
                    $this->_setFncGroups( $funcionario );
                    $lstFncs[] = $funcionario;
                } else if( (int) $funcionario->idUgFnci != 0 ) {
                    //  Eliminacion logica del funconario de la unidad de gestion
                    $tbFuncionario->deleleteUGFnci( $funcionario->idUgFnci );
                    //  Asigno el funcionario a sin unidad de gestion 
                    $tbFuncionario->asignarFncSinUg( $funcionario->idFuncionario );
                    //  Elimina e ususario de los grupos del sistema y lo actualiza al grupo sin grupo
                    $this->_removeFncGroups( $funcionario->idFuncionario );
                    $this->_assingUsrSinGrp( $funcionario->idFuncionario );
                    
                    //  Cambio las responsabilidades del funcionario eliminado 
                    //  al sin funcionario de esa unidade de gestion 
                    //  Cambiar de funcionario responsable de Progamas, Proyectos, Contratos y Convenios
                    $objFnc = new Funcionario();
                    $sinFnc = $objFnc->getSinFunionarioUG($funcionario->idUg);
                    $objFnc->changeFuncionarioResPPCC( $funcionario->idUgFnci, $sinFnc->idFncUG, date("Y-m-d") );

                    //  Cambiar de funcionario responsable a Indicadores, Acciones y Vadiables de Indicadores

                    // TO DO -- METODO QUE CAMBIE FUNCIONARIOS
                }
            }
        }

        return $lstFncs;
    }
    
    /**
     * 
     * @param type $idFuncionario
     * @param type $idUG
     * @param type $lstGrupos
     * @return type
     */
    private function _setFncGroups( $objFnc )
    {
        $tbUsrGrp = $this->getTable( 'UserGroups', 'UnidadGestionTable' );
        
        $lstGrupos = array();
        foreach ( $objFnc->lstGrupos As $oGrp ){
            $lstGrupos[] = (int)$oGrp->idGrupo;
        }
        
        $gUG = $tbUsrGrp->getGroupIdUG( $objFnc->idUg );
        $lstGrupos[] = (int)$gUG;
        
        $gCargo = $tbUsrGrp->getGroupIdCargo( $objFnc->idCargoFnci );
        $lstGrupos[] = (int)$gCargo;
        $lstGrupos[] = 12;
        
        $userId = $tbUsrGrp->getUserIdFun( $objFnc->idFuncionario );
        $oldGrupos = $tbUsrGrp->getGruposUser( $userId );
        
        if ( !empty($oldGrupos) ) {
            $this->_removeFncGroups( $objFnc->idFuncionario );
        }
        
        foreach ( $lstGrupos As $grp ) {
            if ($grp){
                $tbUsrGrp->setUserGroup( $userId, $grp );
            }
        }
        
        return true;
    }
    
    /**
     *  Elimina la relacion del usuario de un funionario con el grupo de la unidad de gestion
     * @param type $idFuncionario
     * @param type $idUG
     * @return type
     */
    private function _assingUsrSinGrp( $idFuncionario )
    {
        $tbUsrGrp = $this->getTable( 'UserGroups', 'UnidadGestionTable' );
        $userId = $tbUsrGrp->getUserIdFun( $idFuncionario );
        // Asigno al grupo de funcionarios
        $tbUsrGrp->setUserGroup( (int)$userId, 9 );
        // Asigno al grupo de Sin Grupo
        $tbUsrGrp->setUserGroup( (int)$userId, 12 );
        return true;
    }
    
    /**
     *  Elimna la realcion de un fusuario a los grupos del sistema
     * @param type $idFuncionario
     * @return type
     */
    private function _removeFncGroups( $idFuncionario )
    {
        $tbUsrGrp = $this->getTable( 'UserGroups', 'UnidadGestionTable' );
        $userId = $tbUsrGrp->getUserIdFun( $idFuncionario );
        return $tbUsrGrp->eliminarUsrGroups( (int)$userId );
    }
    
    /**
     * 
     * @return boolean
     */
    public function validarDeleteFnci()
    {
        $result = true;
        //  Decodifica la data de funcionario
        $dtaFnci = json_decode( JRequest::getvar( 'dtaFnci' ) );

        //  Instancia la Tabla de funcionario
        $tbFnci = $this->getTable( 'Funcionario', 'UnidadGestionTable' );

        $resAcc = $tbFnci->getAccsFnciResp( $dtaFnci->idUgFnci );
        $resInd = $tbFnci->getIndsFnciResp( $dtaFnci->idUgFnci );

        if($resAcc || $resInd) {
            $result = false;
        }
        return $result;
    }

    //  Listo todos los programas asociados a esta unidad de gestion
    public function lstProgramasUG( $intIdentidad_ent )
    {
        $objPrograma = new Programa();
        return $objPrograma->getLstProgramasUG( $intIdentidad_ent );
    }

    //  Listo todos los proyectos asociados a esta unidad de gestion
    public function lstProyectosUG( $intIdentidad_ent )
    {
        $objProyecto = new Proyecto();
        return $objProyecto->getLstProyectosUG( $intIdentidad_ent );
    }

    //  Listo todos los contratos asociados a esta unidad de gestion
    public function lstContratosUG( $intIdentidad_ent )
    {
        $objContrato = new Contrato();
        return $objContrato->getLstContratosUG( $intIdentidad_ent );
    }

    //  Listo todos los convenios asociados a esta unidad de gestion
    public function lstConveniosUG( $intIdentidad_ent )
    {
        $objConvenio = new Convenio();
        return $objConvenio->getLstConveniosUG( $intIdentidad_ent );
    }

    /**
     * 
     * @param type $intUndGestion
     * @return type
     */
    public function lstObjetivosOEIUG( $intUndGestion )
    {
        $lstObjetivos = array();
        $objetivos = new Objetivos();

        if($objetivos) {
            //  Lista de PPPP asociados a una Unidad de Gestion
            $lstObjetivos = $objetivos->getLstObjetivosUG( $intUndGestion );
        }

        return $lstObjetivos;
    }

    private function _registrarPoa( $poa )
    {
        $objPlan = new PlanInstitucion();

        $ugPoa['intId_pi']          = $poa->idPoa;
        $ugPoa['intId_tpoPlan']     = $poa->idTipoPlanPoa;
        $ugPoa['intCodigo_ins']     = $poa->idInstitucionPoa;
        $ugPoa['intIdPadre_pi']     = $poa->idPadrePoa;
        $ugPoa['intIdentidad_ent']  = $poa->idEntidadPoa;
        $ugPoa['strDescripcion_pi'] = $poa->descripcionPoa;
        $ugPoa['dteFechainicio_pi'] = $poa->fechaInicioPoa;
        $ugPoa['dteFechafin_pi']    = $poa->fechaFinPoa;
        $ugPoa['blnVigente_pi']     = $poa->vigenciaPoa;
        $ugPoa['strAlias_pi']       = $poa->aliasPoa;
        $ugPoa['published']         = $poa->published;

        $idPlan = $objPlan->guardarPlan( $ugPoa );
        return $idPlan;
    }

    /**
     *  Retorna una lista de los Objetivos de los cuales la unidad de gestión
     * es responsable
     * 
     * @param type $idUG
     * @return type
     */
    public function getLstObjsUG( $idUG )
    {
        $lstObjetivos = array();
        $objetivos = new Objetivos();

        if($objetivos) {
            $lstObjetivos = $objetivos->getLstObjetivosUG( $idUG );
        }

        return $lstObjetivos;
    }

    /**
     *  Actuliza el estado de vigente o no de un POA
     * @return type
     */
    public function updVigenciaPoa()
    {
        $id = JRequest::getVar( 'id' );
        $op = JRequest::getVar( 'op' );
        $tpo = JRequest::getVar( 'tpoPln' );
        $entUG = JRequest::getVar( 'entUG' );

        $oPlan = new PlanInstitucion();
        $result = $oPlan->updVigenciaPlan( $id, $op, $tpo, $entUG );

        return $result;
    }

    /**
     *  Retorna el anio vigente del los planes vigentes
     *  @return type
     */
    public function getAnioPlnVigente()
    {
        $oPlan = new PlanInstitucion();
        $pei = $oPlan->getPeiVigente();
        $pppp = $this->_getPlanVigente( $pei->idPln, 3 );
        
        $result = strftime( "%Y", strtotime( $pppp->fechaInicioPln ) );
        return $result;
    }
    
    
    private function _getPlanVigente( $idPln, $tpo )
    {
        $oPi = new PlanInstitucion();
        return $oPi->getPlanVigente( $idPln, $tpo );
    }
    
    /**
     *  Elimina una unidad de gestion ya sea de forma logica o fisica 
     * @return type
     */
    public function eliminarUnidadGestion()
    {
        $tbUG = $this->getTable( 'UnidadGestion', 'UnidadGestionTable' );
        $tbUsrGrp = $this->getTable( 'UserGroups', 'UnidadGestionTable' );
        $idUG = json_decode( JRequest::getvar( 'id' ) );
        $pk = $tbUsrGrp->getGroupIdUG($idUG);
        if ( $this->_controlEliminarUG( $idUG ) ) {
            $result = $tbUG->eliminarUG( $idUG );
        } else {
            $result = $tbUG->delLogicaUG( $idUG );
        }
        
        //  Elimina el registro relacionado a la unidad de gestion de los grupos del sistema
        if ( $result && $pk ){
            $tbUsrGrp->eliminarGrupo($pk);
            $tbUsrGrp->eliminarUsersGrp($pk);
        }
        
        return $result;
    }
    
    /**
     *  Retorna True para lealisar un aeliminacion fisica de un registro de 
     * unidad de gestion, si no FALSE
     * @param type $idUG
     * @return string
     */
    private function _controlEliminarUG( $idUG )
    {
        $res = true;
        $tbUG = $this->getTable( 'UnidadGestion', 'UnidadGestionTable' );
        $undGestion = $tbUG->getUnidadGestion( $idUG );
        $lstPlnsUG = $tbUG->getPlnsUG( $undGestion->idEntidadUG );
        
        if ( !empty($lstPlnsUG) ){
            $res = flase;
        } 
        
//        $lstPrgUGRes = $tbFunciario->getPrgsUG( $idUG );
//        $lstPryUGRes = $tbFunciario->getPrysUG( $idUG );
//        $lstCtrUGRes = $tbFunciario->getCtrsUG( $idUG );
//        
//        if ( !empty($lstPlnsUG) || !empty($lstPrgUGRes) || !empty($lstPryUGRes) || !empty($lstCtrUGRes) ){
//            $res = flase;
//        } 
        
        return $res;
    }
    
    /**
     *  Retorna True si el usuario es abilitado para gestionar la opciones 
     *  adicionales caso contrario retorna FALSE
     * @return boolean
     */
    public function getOpAdd( )
    {
        $user = JFactory::getUser();
        $oPermisos = new Permisos();
        
        $acctions = $oPermisos->getAcctions( $user->id, 'com_unidadgestion' );
        $lstGruposUsr = $user->groups; 
        switch (true){
            case ( in_array( 8, $lstGruposUsr ) ):
            case ( $acctions["add"] == 1 ):
            case ( $acctions["upd"] == 1 ):
                $result = true;
                break;
            default:
                $result = false;
                break;
        }
        return $result;
    }
    
    /**
     *  Guarda una nueva opciona adicional de una unidad de gestion y retorna la
     *  lista actuliazada las opciones adicionales y fucionarios actulizada 
     * @return type
     */
    public function guardarOpAdd()
    {
        $dtaUG = json_decode( JRequest::getvar( 'dtaUG' ) );
        $task = json_decode( JRequest::getvar( 'task' ) );
        $idReg = json_decode( JRequest::getvar( 'idReg' ) );
        $lstOpsAdds = json_decode( JRequest::getvar( 'lstOpsAdds' ) );
        
        if ( $task == 0 ){
            $lstOpsAdds = $this->setGrupoOpAdd( $lstOpsAdds, $dtaUG->grupoUG, $idReg );
        }
        
        $jsonLstOpAdd = $this->jsonEncodeLstOpAdd($lstOpsAdds);
        
        $result = array();
        if ( $this->saveOpsAdds( $dtaUG->idUG, $jsonLstOpAdd ) ){
            $result["lstOpsAdds"]       = $this->lstOpAddUG( $dtaUG->idUG );
            $result["lstFuncionarios"]  = $this->lstFuncionariosUG( $dtaUG->idUG );
        }
        return $result;
        
    }
    
    /**
     *  Agrega un grupo para una nueva opcion adicional 
     * @param type $lstOpsAdds
     * @param type $gUG
     * @param type $registro
     * @return type
     */
    public function setGrupoOpAdd( $lstOpsAdds, $gUG, $registro ){
        foreach ( $lstOpsAdds AS $op) {
            if ( $op->registroOpAdd == $registro ) {
                $grupoUG = ($gUG != 0) ? $gUG : 1;
                $op->idGrupo = $this->_saveGroupOpAdd( $grupoUG, $op->nombreOpAdd );
            }
        }
        return $lstOpsAdds;
    }
    
    /**
     *  Retorna el JSON de la lista de opciones adicionales d euna unidad de gestion
     * @param type $lstOpsAdds
     * @return type
     */
    public function jsonEncodeLstOpAdd($lstOpsAdds) {
        $tbUsrGrp = $this->getTable( 'UserGroups', 'UnidadGestionTable' );
        $lstOpAdd = array();
        if ( !empty($lstOpsAdds) ){
            foreach ( $lstOpsAdds AS $op ) {
                if ( $op->published == 1 ){
                    $objOA["nombreOpAdd"]       = $op->nombreOpAdd;
                    $objOA["urlOpAdd"]          = $op->urlOpAdd;
                    $objOA["descripcionOpAdd"]  = $op->descripcionOpAdd;
                    $objOA["idGrupo"]           = $op->idGrupo;
                    $lstOpAdd[] = $objOA;
                } else {
                    if ( $tbUsrGrp->eliminarGrupo( $op->idGrupo) ){
                        $tbUsrGrp->eliminarUsersGrp( $op->idGrupo );
                    }
                }
            }
        }
        return json_encode($lstOpAdd);
    }
    
    /**
     * 
     * @param type $grupoUG
     * @param type $nameOpAdd
     * @return type
     */
    private function _saveGroupOpAdd( $grupoUG, $nameOpAdd )
    {
        $tbGrupo =  $this->getTable( 'Usergroup', 'JTable' );
        $data["id"]         = 0;
        $data["parent_id"]  = $grupoUG;
        $data["title"]      = "op-" . $nameOpAdd;
        $result = $tbGrupo->save($data);
        if ( $result ){
            $result = $tbGrupo->id;
        }
        return $result;
    }
    
    /**
     *  Instala la Table de unidade gestion y agrega las opcions adiciconales
     * @param type $idUg
     * @param type $lstOpsAdds
     * @return type
     */
    public function saveOpsAdds( $idUg, $lstOpsAdds )
    {
        $tbUG = $this->getTable( 'UnidadGestion', 'UnidadGestionTable' );
        return $tbUG->updateOpAdd( $idUg, $lstOpsAdds );
    }
    
    /**
     * 
     * @param type $opsAdds
     * @return type
     */
    public function getLstOpAdd( $opsAdds )
    {
        $lstGruposUsr = JFactory::getUser()->groups;
        $opAddUser = array();
        
        if ( in_array(8, $lstGruposUsr) ){
            $opAddUser = $opsAdds;
        } else {
            foreach ( $opsAdds AS $op ){
                if ( in_array($op->idGrupo, $lstGruposUsr)  ){
                    $opAddUser[] = $op;
                }
            }
        }
        
        return $opAddUser;
    }
    
    /**
     *  Retorna las opciones adicionales de una unidad de gestion
     * @param type $idUG
     * @return type
     */
    public function lstOpAddUG( $idUG )
    {
        $tbUG = $this->getTable( 'UnidadGestion', 'UnidadGestionTable' );
        $lstOpcionesAddUg = $tbUG->getLstOpcioneaAdd( $idUG );
        $lstGruposUsr = JFactory::getUser()->groups;
        if(!empty( $lstOpcionesAddUg )) {
            foreach ( $lstOpcionesAddUg AS $key => $op ){
                $op->registroOpAdd = $key;
                $op->disponibleUsr = ( in_array($op->idGrupo, $lstGruposUsr) || in_array(8, $lstGruposUsr)) ? 1 : 0;
                $op->published = 1;
            }
        }
        return $lstOpcionesAddUg;
    }
    
}
