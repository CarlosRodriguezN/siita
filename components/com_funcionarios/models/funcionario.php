<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

//  Importacion de clases necesarias 
jimport( 'ecorae.objetivos.objetivo.acciones.acciones' );
jimport( 'ecorae.objetivos.objetivo.objetivos' );
jimport( 'ecorae.planinstitucion.planinstitucion' );
jimport( 'ecorae.entidad.programa' );
jimport( 'ecorae.entidad.proyecto' );
jimport( 'ecorae.entidad.contrato' );
jimport( 'ecorae.entidad.convenio' );

// import Joomla modelform library
jimport( 'joomla.application.component.modeladmin' );
JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_funcionarios' . DS . 'tables' );

/**
 * Modelo tipo obra
 */
class FuncionariosModelFuncionario extends JModelAdmin
{

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable( $type = 'Funcionario', $prefix = 'FuncionariosTable', $config = array( ) )
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
    public function getForm( $data = array( ), $loadData = true )
    {
        // Get the form.
        $form = $this->loadForm( 'com_funcionarios.funcionario', 'funcionario', array( 'control' => 'jform', 'load_data' => $loadData ) );
        
        //  obtengo la informacion del unidad de gestion a la que pertenece el funcionario
        $tbFncUg = $this->getTable( 'UgFuncionario', 'FuncionariosTable' );
        $dtaFrm = new stdClass();
        if( (int)$form->getField('intCodigo_fnc')->value > 0 ){
            $dtaFrm = $tbFncUg->getDataUG( $form->getField('intCodigo_fnc')->value );
            $form->setValue('intId_ugf', null, $dtaFrm->idFncUG);
            $form->setValue('dteFechaInicio_ugf', null, $dtaFrm->fechaInicio);
            $form->setValue('dteFechaFin_ugf', null, $dtaFrm->fechaFin);
            $form->setValue('idCargoUG', null, $dtaFrm->idCargo);
        } else {
            $dtaFrm->idUG = 0;
        }
        
        $tbUG = $this->getTable( 'UnidadGestion', 'FuncionariosTable' );
        $lstGrpOpAdd = ( (int)$dtaFrm->idUG > 0 ) ? $tbUG->getOpAdd( $dtaFrm->idUG ) : json_encode(array());
        $form->setValue('lstOpsAddsUG', null, $lstGrpOpAdd);
        
        $tbUserGroup = $this->getTable( 'UserGroups', 'FuncionariosTable' );
        $idUserFnc = $form->getField('intIdUser_fnc')->value;
        $lstGruposFnc = ( (int)$idUserFnc > 0 ) ? $tbUserGroup->getGroupsFnc( $idUserFnc ) : array();
        $form->setValue('lstGruposFnc', null, json_encode($lstGruposFnc));
        
        if( empty( $form ) ) {
            return false;
        }

        return $form;
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
        $data = JFactory::getApplication()->getUserState( 'com_funcionarios.edit.funcionario.data', array( ) );

        if( empty( $data ) ) {
            $data = $this->getItem();
        }

        return $data;
    }
    
    /**
     * Guarda la informacion relacionada con el funcionario
     * 
     * @return type
     */
    public function guardarFuncionario()
    {
        $db = &JFactory::getDbo();
        //  crea un objeto vacio
        $dataFnc = new stdClass();
        try {
            $idUsuario = $this->_saveUsuario( JRequest::getvar( 'dtaFrm' ) );
            
            if( $idUsuario ){
                //  Identificador del Tpo de Entidad POA - Funcionario
                $idTpoPOA = 6;

                //  Registra la data del funcionario
                $idFuncionario = $this->_registrarFuncionario( $idUsuario, JRequest::getvar( 'dtaFrm' ) );

                //  Registra la relacion del funcionario con la unidad de gestion
                if( $idFuncionario ){
                    //  Obtiene la data del JSON
                    $dtaUG = json_decode( JRequest::getvar( 'dtaUG' ) );
                    $this->_registrarFncUG( $idFuncionario, $dtaUG );
                }
                
                //  Registra los POAs de un Funcionario
                $lstPoasFnc = json_decode( JRequest::getvar( 'lstPoas' ) );
                $resultPoasFnc = array();

                foreach( $lstPoasFnc As $poa ){                    
                    if ( (int)$poa->published == 1) {
                        $banPln = ((int)$poa->idPoa == 0 || $poa->idPoa == "" ) ? true 
                                                                                : false;

                        $idPoa = $this->_registrarPoa( $poa );
                        if( $idPoa ){

                            $objetivos = new Objetivos();
                            $lstObjetivos = $objetivos->saveObjetivos( $poa->lstObjetivos, $idPoa, $idTpoPOA, $banPln );
                            $poa->idPoa = $idPoa;
                            $poa->lstObjetivos = $lstObjetivos;
                            $resultPoasFnc[ (int)$poa->idRegPoa ] = $poa;
                        }
                    } else if ( (int)$poa->idPoa != 0 ){
                        $objetivos = new Objetivos();
                        $lstObjetivos = $objetivos->saveObjetivos( $poa->lstObjetivos, $idPoa, $idTpoPOA );
                        $this->_eliminarPlan( (int)$poa->idPoa );
                    }
                }
                
                $dataFnc->idFnc = $idFuncionario;
                $dataFnc->lstPoas = $resultPoasFnc;
                
            }
            
            $db->transactionCommit();
            
            return $dataFnc;
        } catch (Exception $ex) {
            //  Deshace las operaciones realizadas antes de encontrar un error
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Registra la informacion especifica del funcionario
     * 
     * @param type $idUsuario       Id del usuario del sistema
     * @param type $datFrmFnc       Data del formulario del funcionario
     * @return type
     */
    private function _registrarFuncionario ( $idUsuario, $datFrmFnc )
    {
        $tbFunciario = $this->getTable( 'Funcionario', 'FuncionariosTable' );
        
        //  Obtiene la data del JSON
        $dtaFrm = json_decode( $datFrmFnc );
        
        //  Arma el Objeto funcionario
        $funcionario["intCodigo_fnc"]               = $dtaFrm->intCodigo_fnc;
        $funcionario["intIdUser_fnc"]               = $idUsuario;
        $funcionario["strCI_fnc"]                   = $dtaFrm->strCI_fnc;
        $funcionario["strApellido_fnc"]             = $dtaFrm->strApellido_fnc;
        $funcionario["strNombre_fnc"]               = $dtaFrm->strNombre_fnc;
        $funcionario["strCorreoElectronico_fnc"]    = $dtaFrm->strCorreoElectronico_fnc;
        $funcionario["strTelefono_fnc"]             = $dtaFrm->strTelefono_fnc;
        $funcionario["strCelular_fnc"]              = $dtaFrm->strCelular_fnc;
        $funcionario["published"]                   = $dtaFrm->published;
        
        if ($dtaFrm->intCodigo_fnc == 0) {
            //  Si es un nuevo registro se genera un nuevo ID de entidad
            //  "11" id de tipo funcionario
            $idEntidadUG = $this->_getIdEntidadFnc(11);
            $funcionario["intIdentidad_ent"] = $idEntidadUG;
        } else {
            $funcionario["intIdentidad_ent"] = $dtaFrm->intIdentidad_ent;
        }
        
        $dtaFnc = $tbFunciario->guardarFuncionario( $funcionario );
        return $dtaFnc;
    }   
    
    /**
     *  Registra la relacion del funcionario con la unidad de gestion
     * 
     * @param type $idFnc       Id del funcionario
     * @param type $frmUG       Data del formulario de la unidada de gestion
     * @return type
     */
    private function _registrarFncUG( $idFnc, $dtaFrmUG )
    {
        $tbFncUg = $this->getTable( 'UgFuncionario', 'FuncionariosTable' );
        
        //  Arma el objeto para la relacion del funcionario con la unidada de gestion
        $fncUG["intId_ugf"]             = $dtaFrmUG->intId_ugf;
        $fncUG["intCodigo_ug"]          = $dtaFrmUG->intCodigo_ug;
        $fncUG["intId_ugc"]             = $dtaFrmUG->intId_ugc;
        $fncUG["intCodigo_fnc"]         = $idFnc;
        $fncUG["dteFechaInicio_ugf"]    = $dtaFrmUG->dteFechaInicio_ugf;
        $fncUG["dteFechaFin_ugf"]       = $dtaFrmUG->dteFechaFin_ugf;
        
        if ( $dtaFrmUG->intId_ugf == 0 ) {
            $fncUG["intId_ugf"]                 = 0;
            $fncUG["dteFechaRegistro_ugf"]      = date("Y-m-d");
            $fncUG["dteFechaModificacion_ugf"]  = date("Y-m-d");
            $idFncUG = $this->registrarFuncionarioUG( $fncUG, $dtaFrmUG->lstOpcionesAdd );
        } else if ( $dtaFrmUG->intId_ugf > 0 ) {
            $dtaUgF = $tbFncUg->getActulUgF( $dtaFrmUG->intId_ugf );
            $flagUPD = $this->_updFuncionarioUG( $dtaUgF, $dtaFrmUG );
            if( $flagUPD ) {
                $fncUG["dteFechaModificacion_ugf"]  = date("Y-m-d");
            }
            $idFncUG = $this->actualizarFuncionarioUG( $dtaUgF, $fncUG, $dtaFrmUG->lstOpcionesAdd );
        }
        
        return $idFncUG;
    }
        
    /**
     *  Registera un fucnionario a uni unidad de gestion y lo asigna a sus diferentes grupos
     * @param type $dtaUGF
     * @param type $opsAdds
     * @return type
     */
    public function registrarFuncionarioUG( $dtaUGF, $opsAdds )
    {
        $tbFncUg = $this->getTable( 'UgFuncionario', 'FuncionariosTable' );
        //  Asignacion del funcionario al grupo de la unidad de gestion
        $idFncUG = $tbFncUg->registrarFuncionarioUG( $dtaUGF );
        if ($idFncUG) {
            $dtaUGF = (object)$dtaUGF;
            //  Asigno al grupo de la unidad de gestion
            $this->_setFncGroupUG( $dtaUGF->intCodigo_fnc, $dtaUGF->intCodigo_ug );
            //  Asigno al grupo del cargo
            $this->_setFncGroupCargo( $dtaUGF->intCodigo_fnc, $dtaUGF->intId_ugc );
            //  Asigno a los grupos de las opciones adicionales de la unidad de gestion
            $this->_setFncGroupOpsAdds( $dtaUGF->intCodigo_fnc, $opsAdds );
        }
        return $idFncUG;
    }
    
    
    public function actualizarFuncionarioUG( $oldUgF, $newDtaFUG, $opsAdds ) 
    {
        $tbFncUg = $this->getTable( 'UgFuncionario', 'FuncionariosTable' );
        //  Asignacion del funcionario al grupo de la unidad de gestion
        $idFncUG = $tbFncUg->registrarFuncionarioUG( $newDtaFUG );
        if ( $idFncUG ){
            $dataUGF = (object) $newDtaFUG;
            if ( $oldUgF->idUg != $dataUGF->intCodigo_ug ) {
                $this->_removeFncGroupUG( $dataUGF->intCodigo_fnc, $oldUgF->idUg);
                $this->_setFncGroupUG( $dataUGF->intCodigo_fnc, $dataUGF->intCodigo_ug );
            }
            
            if ( $oldUgF->idCargoFncUg != $dataUGF->intId_ugc ){
                $this->_removeFncGroupCargo( $dataUGF->intCodigo_fnc, $oldUgF->idCargoFncUg);
                $this->_setFncGroupCargo( $dataUGF->intCodigo_fnc, $dataUGF->intId_ugc );
            }
            
            $this->actualizarOpsAddsFnc( $dataUGF->intCodigo_fnc, $oldUgF->idUg, $opsAdds);
            
        }
    }
    
    /**
     *  Asigna el funcionario al grupo del cargo asignado
     * @param type $idFnc
     * @param type $idCargo
     * @return boolean
     */
    private function _setFncGroupCargo( $idFnc, $idCargo )
    {
        $tbUsrGrp = $this->getTable( 'UserGroups', 'FuncionariosTable' );
        $groupIdCargo = $tbUsrGrp->getGrupoCargo( $idCargo );
        $userId = $tbUsrGrp->getUserIdFun( $idFnc );
        $result = false;
        if ( $groupIdCargo ){
            if ( !($tbUsrGrp->controlExist( $userId, $groupIdCargo )) ){
                $result = $tbUsrGrp->setUserGroup( $userId, $groupIdCargo );
            } else {
                $result = true;
            }
        }
        return $result;
    }
    
    /**
     *  Registra el funcionario al grupo de la unidad de gestion a la que pertenece
     * @param type $idFuncionario
     * @param type $idUG
     */
    private function _setFncGroupUG( $idFuncionario, $idUG )
    {
        $tbUsrGrp = $this->getTable( 'UserGroups', 'FuncionariosTable' );
        $userId = $tbUsrGrp->getUserIdFun( $idFuncionario );
        $groupId = $tbUsrGrp->getGroupIdUG( $idUG );
        
        if ( !($tbUsrGrp->controlExist( $userId, $groupId )) ){
            $result = $tbUsrGrp->setUserGroup( $userId, $groupId );
        } else {
            $result = true;
        }
        
        return $tbUsrGrp->setUserGroup( (int)$userId, (int)$groupId );
    }
    
    /**
     *  Asigna un funcionario a los grupos de los opciones adicionales
     * @param type $idFnc
     * @param type $opsAdds
     * @return boolean
     */
    private function _setFncGroupOpsAdds( $idFnc, $opsAdds )
    {
        if ( isset($opsAdds) && !empty($opsAdds) ){
            $tbUsrGrp = $this->getTable( 'UserGroups', 'FuncionariosTable' );
            $userId = $tbUsrGrp->getUserIdFun( $idFnc );
            $lstGrpOpAdd = explode(",", $opsAdds);
            foreach ( $lstGrpOpAdd AS $opAdd ){
                $tbUsrGrp->setUserGroup( (int)$userId, (int)$opAdd );
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
    private function _removeFncGroupUG( $idFuncionario, $idUG )
    {
        $tbUsrGrp = $this->getTable( 'UserGroups', 'FuncionariosTable' );
        $userId = $tbUsrGrp->getUserIdFun( $idFuncionario );
        $groupId = $tbUsrGrp->getGroupIdUG( $idUG );
        return $tbUsrGrp->removeUserGroup( (int)$userId, (int)$groupId );
    }
    
    /**
     *  Elimina la relacion del usuario de un funionario con el grupo del cargo que desempeñaba
     * @param type $idFnc
     * @param type $idCargo
     * @return type
     */
    private function _removeFncGroupCargo( $idFnc, $idCargo)
    {
        $tbUsrGrp = $this->getTable( 'UserGroups', 'FuncionariosTable' );
        $userId = $tbUsrGrp->getUserIdFun( $idFnc );
        $groupId = $tbUsrGrp->getGrupoCargo( $idCargo );
        return $tbUsrGrp->removeUserGroup( (int)$userId, (int)$groupId );
    }
    
    /**
     *  Retorna True si se a modificado la data de unidad de gestion, si no False
     * @param type $dtaActualUgF
     * @param type $dtaNuevaUgF
     * @return boolean
     */
    private function _updFuncionarioUG( $dtaActualUgF, $dtaNuevaUgF )
    {
        $resutl = false;
        if ($dtaActualUgF->idUg != $dtaNuevaUgF->intCodigo_ug ||
            $dtaActualUgF->idCargoFncUg != $dtaNuevaUgF->idCargoFncUg ||
            $dtaActualUgF->fchInicioFncUg != $dtaNuevaUgF->fchInicioFncUg ||
            $dtaActualUgF->fchFinFncUg != $dtaNuevaUgF->fchFinFncUg ) {
            $resutl = true;
        }
        
        return $resutl;
    }
    
    /**
     *  Registra el funcionario como usuario de Joomla
     * @param type $dtaForm
     * @return type
     */
    private function _saveUsuario( $dtaForm )
    {
        $tbUser = $this->getTable( 'Usuario', 'FuncionariosTable' );
        
        //  Obtiene la data del JSON
        $dtaUser = json_decode( $dtaForm );
        
        //  Arma el Objeto funcionario
        $dataUser["id"]         = $dtaUser->intIdUser_fnc;
        $dataUser["name"]       = $dtaUser->strNombre_fnc . ' ' . $dtaUser->strApellido_fnc;
        $dataUser["username"]   = $dtaUser->strCI_fnc;
        $dataUser["email"]      = $dtaUser->strCorreoElectronico_fnc;
        
        if ( $dtaUser->intIdUser_fnc == 0 ) {
            if ( $dtaUser->password != '' ) {
                $dataUser["password"]   = $this->makeHashPassword( $dtaUser->password );
            } else {
                $dataUser["password"]   = $this->makeHashPassword( $dtaUser->strCI_fnc );
            }
        }
        
        $idUser = $tbUser->guardarUsuario( $dataUser );
        
        if ( $idUser && $dtaUser->idUser == 0 ) {
            $tbUser->registrarGrupoUsuario( $idUser );
        }
        
        return $idUser;
    }
    
    /**
     *  Actualiza las ños grupos de las opciones adicionles del fucnionario
     * @param type $idFnc
     * @param type $oldUG
     * @param type $opsAdds
     * @return boolean
     */
    private function actualizarOpsAddsFnc( $idFnc, $oldUG, $opsAdds)
    {
        $tbUsrGrp = $this->getTable( 'UserGroups', 'FuncionariosTable' );
        $tbUG = $this->getTable( 'UnidadGestion', 'FuncionariosTable' );
        
        $userId = $tbUsrGrp->getUserIdFun( $idFnc );
        $jsonGruposOpAdd = $tbUG->getOpAdd( $oldUG );
        $oldGruposOpAdd = ($jsonGruposOpAdd) ? json_decode($jsonGruposOpAdd): array();
        
        
        if ( isset($opsAdds) && !empty($opsAdds) ){
            $this->_eliminarGruposOpAdd( $userId, $oldGruposOpAdd );
            $lstGrpOpAdd = explode(",", $opsAdds);
            foreach ( $lstGrpOpAdd AS $opAdd ){
                $tbUsrGrp->setUserGroup( (int)$userId, (int)$opAdd );
            }
        } else {
            $this->_eliminarGruposOpAdd( $userId, $oldGruposOpAdd );
        }
        return true;
    }
    
    /**
     *  Elimina la relacion de un usuario con una lista de grupos
     * @param type $userId
     * @param type $lstGrupos
     * @return boolean
     */
    private function _eliminarGruposOpAdd ( $userId, $lstGrupos )
    {
        $tbUsrGrp = $this->getTable( 'UserGroups', 'FuncionariosTable' );
        if (count($lstGrupos) > 0 ){
            foreach ( $lstGrupos AS $op ){
                $tbUsrGrp->removeUserGroup( (int)$userId, (int)$op->idGrupo );
            }
        }
        return true;
    }
    /**
     *  Retorna un ID de entidad de tipo Funcionario
     * 
     * @param type $tpoEntidad      Id del tipo de entidad
     * @return type
     */
    private function _getIdEntidadFnc( $tpoEntidad )
    {
        $tbEntFnc = $this->getTable( 'EntidadFnc', 'FuncionariosTable' );
        return $tbEntFnc->registraEntidadFnc($tpoEntidad);
    }
    
    /**
     *  Lista las Poas que tiene un funcionarios
     * 
     * @param int       $idEntFnc    Id de la entidad del funcionario
     * @return type
     */
    public function lstPoasFnc( $idEntFnc )
    {
        $tbPlan = $this->getTable( 'Plan', 'FuncionariosTable' );
        $lstPoasFnc = $tbPlan->getLstPoas( $idEntFnc );

        if( $lstPoasFnc ){
            foreach ( $lstPoasFnc AS $key => $poaFnc ) {

                $poaFnc->idRegPoa = $key;
                $objetivos = new Objetivos();
                //  Identificador del tipo de entidad POA
                $idTpoEntidad = 2;
                if( $objetivos ) {
                    $lstObjsPoa = $objetivos->getLstObjetivos(  $poaFnc->idPoa, 
                                                                $idTpoEntidad, 
                                                                $idEntFnc, 
                                                                $poaFnc->idTipoPlanPoa,
                                                                $poaFnc->fechaInicioPoa, 
                                                                $poaFnc->fechaFinPoa );

                    $poaFnc->lstObjetivos = $lstObjsPoa;
                }
            }
        }
        
        return $lstPoasFnc;
    }
    
    /**
     *  Retorna la lista de programas relacionados con el Funcionario
     * 
     * @param type $idFuncionario
     * @return type
     */
    public function lstProgramasFnc( $idFuncionario )
    {
        $objPrograma = new Programa();
        return $objPrograma->getLstProgramasFnc( $idFuncionario );
    }
    
    /**
     *  Retorna la lista de programas relacionados con el Funcionario
     * 
     * @param type $idFuncionario
     * @return type
     */
    public function lstProyectosFnc( $idFuncionario )
    {
        $objProyecto = new Proyecto();
        return $objProyecto->getLstProyectosFnc( $idFuncionario );
    }

    /**
     *  Listo todos los contratos asociados un Funcionario
     * 
     * @param type $idFuncionario
     * @return type
     */
    public function lstContratosFnc( $idFuncionario )
    {
        $objContrato = new Contrato();
        return $objContrato->getLstContratosFnc( $idFuncionario );
    }

    /**
     *  Listo todos los convenios asociados a un funcionario
     * 
     * @param type $idFuncionario
     * @return type
     */
    public function lstConveniosFnc( $idFuncionario )
    {
        $objConvenio = new Convenio();
        return $objConvenio->getLstConveniosFnc( $idFuncionario );
    }
    
    /**
     * 
     * @param type $poa
     * @return type
     */
    private function _registrarPoa( $poa )
    {
        $objPlan = new PlanInstitucion();
        
        $ugPoa['intId_pi']              = $poa->idPoa;
        $ugPoa['intId_tpoPlan']         = $poa->idTipoPlanPoa;
        $ugPoa['intCodigo_ins']         = $poa->idInstitucionPoa;
        $ugPoa['intIdPadre_pi']         = $poa->idPadrePoa;   
        $ugPoa['intIdentidad_ent']      = $poa->idEntidadPoa;
        $ugPoa['strDescripcion_pi']     = $poa->descripcionPoa;
        $ugPoa['dteFechainicio_pi']     = $poa->fechaInicioPoa;
        $ugPoa['dteFechafin_pi']        = $poa->fechaFinPoa;
        $ugPoa['blnVigente_pi']         = $poa->vigenciaPoa;
        $ugPoa['strAlias_pi']           = $poa->aliasPoa;
        $ugPoa['published']             = $poa->published;
        
        $idPlan = $objPlan->guardarPlan($ugPoa);
        return $idPlan;
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
     *  Retorna el anio vigente del los planes vigentes
     * @return type
     */
    public function getAnioPlnVigente() 
    {
        $oPlan = new PlanInstitucion();
        
        $pei = $oPlan->getPeiVigente();
        $pppp = $oPlan->getPlanVigente( $pei->idPln, 3 );
        
        $result =  ( count( $pppp ) )   ? strftime( "%Y", strtotime( $pppp->fechaInicioPln ) )
                                        : 'N/D';

        return $result;
    }
 
    public function eliminarFuncionario()
    {
        $tbFunciario = $this->getTable( 'Funcionario', 'FuncionariosTable' );
        $tbFncUg = $this->getTable( 'UgFuncionario', 'FuncionariosTable' );
        $idFnc = json_decode( JRequest::getvar( 'id' ) );
        $idFncUG = json_decode( JRequest::getvar( 'funUG' ) );
        
        if ( $this->_controlEliminarFnc( $idFnc ) ) {
            $result = $tbFunciario->eliminarFuncionario( $idFnc );
            if ($result) {
                $tbFncUg->eliminarFncUG($idFncUG);
            }
        } else {
            $result = $tbFunciario->delLogicalFuncionario( $idFnc );
            if ($result) {
                $tbFncUg->deleteLogicalFncUG($idFncUG);
            }
        }
        
        return $result;
    }
    
    
    private function _controlEliminarFnc( $idFnc )
    {
        $res = true;
        $tbFunciario = $this->getTable( 'Funcionario', 'FuncionariosTable' );
        $funcionario = $tbFunciario->getFuncionario( $idFnc );
        $lstPlnsFnc = $tbFunciario->getPlnsFnc( $funcionario->idEntidadFun );
        
        if ( !empty($lstPlnsFnc) ){
            $res = flase;
        } 
        
//        $lstPrgFncRes = $tbFunciario->getPrgsFnc( $idFnc );
//        $lstPryFncRes = $tbFunciario->getPrysFnc( $idFnc );
//        $lstCtrFncRes = $tbFunciario->getCtrsFnc( $idFnc );
//        
//        if ( !empty($lstPlnsFnc) || !empty($lstPrgFncRes) || !empty($lstPryFncRes) || !empty($lstCtrFncRes) ){
//            $res = flase;
//        } 
        
        return $res;
    }
    
    /**
     * Funcin encargada de eliminar los archivos 
     */
    public function delArchivo()
    {
        $archivo = JRequest::getVar( "infArchivo" );
        switch( $archivo["tipo"] ){
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
     *  Retorna las lista de opciones adicionales del funcionario
     * @return type
     */
    public function getOpAdd()
    {
        $idUG = JRequest::getVar( "idUG" );
        $tbUG = $this->getTable( 'UnidadGestion', 'FuncionariosTable' );
        $listOp = $tbUG->getOpAdd( $idUG );
        $result = ( !empty($listOp) ) ? json_decode($listOp) : array();
        return $result;
    }
    
    public function getCargosUG()
    {
        $idUG = JRequest::getVar( "idUG" );
        $tbUG = $this->getTable( 'UnidadGestion', 'FuncionariosTable' );
        $lstCargos = $tbUG->getCargosUG( $idUG );
        return $lstCargos;
    }
    
    
    /**
     *  Genera la sal para la contrasenia
     * @param type $length
     * @return string
     */
    public function makeSaltPass($length=8) {
        $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $makepass = '';
        mt_srand(10000000*(double)microtime());
        for ($i = 0; $i < $length; $i++)
            $makepass .= $salt[mt_rand(0,61)];
        return $makepass;
    } 
    
    /**
     *  Genera el password para joomla
     * @param type $pass
     * @return string
     */
    public function makeHashPassword($pass)
    {
        // Salt and hash the password
        $salt = $this->makeSaltPass(32); 
        $crypt = md5($pass.$salt);
        $hash = $crypt.':'.$salt; 
        return $hash;
    } 
    
}
