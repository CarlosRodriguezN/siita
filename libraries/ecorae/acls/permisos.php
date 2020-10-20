<?php


//  Adjunto Tablas asociadas 
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'acls' . DS . 'assets.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'acls' . DS . 'extensions.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'funcionario.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'planinstitucion' . DS . 'planinstitucion.php';

class Permisos
{
    public function __construct()
    {

    }
    
    /**
     *  Registra los permisos que tiene un grupo del sistema sobre los componentes
     */
    public function addPermisosGrpCom( $idGrp, $lstPermisos)
    {
        if ( !empty($lstPermisos) ) {
            foreach ( $lstPermisos AS $key=>$comRules ){
                $rules = (array)$comRules;
                $partsId = explode("-", $key);
                $comId = $partsId[1];
                $oldRules = $this->_getPermisosCom( $comId );
                if ( !empty($oldRules) ){
                    $result = $this->actualizarRules( $idGrp, $comId, $oldRules, $rules );
                } else {
                    $result = $this->registrarRules( $idGrp, $comId, $rules );
                }
            }
        }
        return $result;
    }
    
    
    /**
     *  Retorna la lista de permisos de lso grupos sobre un componente
     * @param type $com         Id de registro del componente en Assets
     * @return type
     */
    private function _getPermisosCom( $com )
    {
        $db = JFactory::getDBO();
        $tblAssets = new JTableAssets( $db );
        $rules = $tblAssets->getRulesComponent( $com );
        $oRules = ( $rules->rules === "{}" ) ? array() : json_decode($rules->rules);
        
        $listaCmp = array();
        foreach ( $oRules AS $keyC=>$rules ){
            $listaRgl = array();
            foreach ( $rules AS $key=>$op ){
                $listaRgl[$key]=$op;
            }
            $listaCmp[$keyC] = $listaRgl;
        }
        
        return $listaCmp;
    }
    
    /**
     *  Registra las el objeto JSON de reglas de un componente
     * @param type $com
     * @param type $rules
     * @return type
     */
    private function _registrarRulesCom( $com, $rules )
    {
        $db = JFactory::getDBO();
        $tblAssets = new JTableAssets( $db );
        return $tblAssets->registrarRules( $com, $rules );
    }
    
    /**
     *  Retorna el objeto de reglas para el grupo establecido en un componente 
     * @param type $idGrp
     * @param type $rules
     * @return type
     */
    public function registrarRules( $idGrp, $idCom, $rules ) 
    {
        $dataRules = array("core.admin"=>array(), 
                    "core.manage"=>array(),
                    "core.create"=>array(),
                    "core.delete"=>array(),
                    "core.edit"=>array());
        $allRules = $this->mergeRules( $idGrp, $dataRules, $rules );
        return $this->_registrarRulesCom( $idCom, json_encode($allRules) );
    }
    
    /**
     * 
     * @param type $idGrp
     * @param type $comId
     * @param type $oldRules
     * @param type $rules
     * @return type
     */
    public function actualizarRules( $idGrp, $comId, $oldRules, $rules ) 
    {
        $dtaRules = $this->mergeRules( $idGrp, $oldRules, $rules );
        return $this->_registrarRulesCom( $comId, json_encode($dtaRules) );
    }
    
    /**
     *  Actualiza los permizos de un componente
     * @param type $idGrp
     * @param type $oldList
     * @param type $newList
     * @return int
     */
    public function mergeRules( $idGrp, $oldList, $newList )
    {   
        $list = array();
        foreach ( $oldList AS $key=>$rules ){
            $rules[$idGrp] = 0;
            $list[$key] = $rules;
//            if ( array_key_exists($idGrp, $rules) ){
//                unset($rules[$idGrp]);
//            }
        }
        $oldList = $list;
        
        if ( !empty($newList) ) {
            $oldList = $this->mergeAddRules( $idGrp, $oldList, $newList );
        }
        return $oldList;
    }
    
    /**
     *  Agrega lso permisos a un componente a las estructrura joomla
     * @param type $idGrp
     * @param type $oldList
     * @param type $newList
     * @return int
     */
    public function mergeAddRules( $idGrp, $oldList, $newList )
    {
        foreach ( $newList AS $rule ){
            $partsRule = explode("-", $rule->permiso);
            $accion = $partsRule[1];
            switch ( $accion ) {
                case 1:
                    $oldList["core.admin"][$idGrp] = 1;
                    break;
                case 2:
                    $oldList["core.create"][$idGrp] = 1;
                    break;
                case 3:
                    $oldList["core.edit"][$idGrp] = 1;
                    break;
                case 4:
                    $oldList["core.delete"][$idGrp] = 1;
                    break;
            }
        }
        return $oldList;
    }

    /**
     *  Retorna un laista con todos los componentes de un determiando autor y con sus permisos de un grupo dado
     * @param type $autor
     * @param type $grupo
     * @return type
     */
    public function getComponentesPrm( $autor, $grupo )
    {
        $db = JFactory::getDBO();
        $tbExtensions = new JTableExtensions($db);
        $lstComponentes = $tbExtensions->getComponentesByAutor( $autor );
        foreach ( $lstComponentes AS $key=>$component ) {
            $component->idReg = $key;
            $component->dtageneral = json_decode($component->dtageneral);
            $component->permisos = $this->_getPermisosByGrp( $grupo, json_decode($component->permisos) );
        }
        return $lstComponentes;
    }
    
    /**
     *  Retorna los permisos de un determinado grupo sobre un componente, 
     * en una diferente estructura legible en el cliente
     * @param type $idGrp               Id del grupo
     * @param type $allPermisos         Lista de los permisos con la estructura Joomla 
     */
    private function _getPermisosByGrp( $idGrp, $allPermisos )
    {
        $idGrp = (string)$idGrp;
        $permisos = array();
        if ( !empty($allPermisos) ){
            foreach ( $allPermisos AS $key=>$action ){
                switch ( $key ){
                    case "core.admin":
                        $permisos["admin"] = ( !empty($action->$idGrp) ) ? $action->$idGrp : 0;
                        break;
                    case "core.manage":
                        $permisos["manage"] = ( !empty($action->$idGrp) ) ? $action->$idGrp : 0;
                        break;
                    case "core.create":
                        $permisos["add"] = ( !empty($action->$idGrp) ) ? $action->$idGrp : 0;
                        break;
                    case "core.delete":
                        $permisos["del"] = ( !empty($action->$idGrp) ) ? $action->$idGrp : 0;
                        break;
                    case "core.edit":
                        $permisos["upd"] = ( !empty($action->$idGrp) ) ? $action->$idGrp : 0;
                        break;
                }
            }
        }
        return $permisos;
    }
    
    /**
     * 
     * @param type $user
     * @return type
     */
    public function getAccesLogin( $user )
    {
        $result = new stdClass();
        $result->acces = "sinacceso";
        if ( !in_array( 9, $user->groups ) && (count($user->groups) != 0) ){
            
            switch ( true ) {
                case in_array( 6, $user->groups ):
                case in_array( 7, $user->groups ):
                case in_array( 8, $user->groups ):
                    $result->acces = "admin";
                    break;
                default :
                    $result = $this->getAccesOther( $user );
                    break;
            }
            
        } 
        return $result;
    }
    
    /**
     * 
     * @param type $user
     * @return \stdClass
     */
    public function getAccesOther( $user )
    {
        $acces = new stdClass();
        $dtaFuncionario = $this->getDataFuncionario( $user->id );
        
        $accesPanel = $this->getAccesCargo( $dtaFuncionario->groupId, "com_panel" );
        $accesPEI = $this->getAccesCargo( $dtaFuncionario->groupId, "com_pei" );
        $accesUG = $this->getAccesCargo( $dtaFuncionario->groupId, "com_unidadgestion" );
        $accesFnc = $this->getAccesCargo( $dtaFuncionario->groupId, "com_funcionarios" );
        
        switch (true) {
            // Panel
            case $accesPanel:
                $acces->acces = "panel";
                break;
            // PEI
            case !$accesPanel && $accesPEI:
                $oPlnVigente = new PlanInstitucion();
                $acces->acces = "pei";
                $plnVgt = $oPlnVigente->getPeiVigente();
                $acces->id = $plnVgt->idPln;
                break;
            // Unidad de gestion
            case !$accesPanel && !$accesPEI && $accesUG:
                $acces->acces = "ug";
                $acces->id = $dtaFuncionario->ugId;
                break;
            // Funcionario
            case !$accesPanel && !$accesPEI && !$accesUG && $accesFnc:
            default :
                $acces->acces = "fnc";
                $acces->id = $dtaFuncionario->fncId;
                break;
                
        }
        return $acces;
    }
    
    /**
     *  Retorna TRUE en caso de tener acceso a
     * @param type $idGrp
     * @param type $com
     * @return boolean
     */
    public function getAccesCargo( $idGrp, $com )
    {
        $result = false;
        $db = JFactory::getDBO();
        $tblAssets = new JTableAssets( $db );
        $dataCom = $tblAssets->getDataComByName( $com );
        
        if ( $dataCom ){
            $dataCom->rules = $this->_getPermisosByGrp( $idGrp, json_decode($dataCom->rules) );
            if ( $dataCom->rules["admin"] == 1 ){
                $result = true;
            }
        }
        return $result;
    }
    
    /**
     *  Retorna el objeto fucnionario de un ususario del sistema
     * @param type $userId
     * @return type
     */
    public function getDataFuncionario( $userId )
    {
        $db = JFactory::getDBO();
        $tbFuncionario = new JTableUnidadFuncionario( $db );
        return $tbFuncionario->getDataFncByUsr( $userId );
    }
    
    /**
     * 
     * @param type $usr
     * @param type $com
     * @return type
     */
    public function getAcctions( $usr, $com ) {
        
        $db = JFactory::getDBO();
        $tblAssets = new JTableAssets( $db );
        
        $dtaFnc = $this->getDataFuncionario($usr);
        $dataCom = $tblAssets->getDataComByName( $com );
        $dataCom->rules = $this->_getPermisosByGrp( $dtaFnc->groupId, json_decode($dataCom->rules) );
        
        return $dataCom->rules;
    }
    
}