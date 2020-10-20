<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 *      _user_usergroup_map
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class FuncionariosTableUserGroups extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db)
    {
        parent::__construct('#__usergroups', 'id', $db);
    }

    /**
     *  Registra un nuevo grupo del sistema para una unidad de gestion
     * @param type $objeto
     * @return type
     */
    public function registroGroup( $objeto )
    {
        if (!$this->save($objeto)) {
            echo $this->getError();
            exit;
        }

        return $this->id;
    }
    
    /**
     *  Obtiene el Identificador de usuario de un fucnionario 
     * @param type $idFnc
     * @return type
     */
    public function getUserIdFun( $idFnc )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intIdUser_fnc AS userId");
            $query->from( "#__gen_funcionario" );
            $query->where( "intCodigo_fnc = {$idFnc}" );
            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0) ? $db->loadResult() : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.usergroup.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     *  Obtiene el Identificador del grupo de una unidad de gestion
     * @param type $idUG
     * @return type
     */
    public function getGroupIdUG( $idUG )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intIdGrupo_ug AS groupId");
            $query->from( "#__gen_unidad_gestion" );
            $query->where( "intCodigo_ug = {$idUG}" );
            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0) ? $db->loadResult() : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.usergroup.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Inseta un registro en la base de datos para asignar un usuario a un grupo
     * @param type $user
     * @param type $group
     * @return type
     */
    public function setUserGroup( $user, $group )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->insert( "#__user_usergroup_map" );
            $query->columns( "user_id, group_id" );
            $query->values( "{$user}, {$group}" );
            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0) ? true : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.usergroup.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Da de alta a un usuario de un grupo 
     * @param type $user
     * @param type $group
     * @return type
     */
    public function removeUserGroup( $user, $group )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->delete( "#__user_usergroup_map" );
            $query->where( "user_id = {$user}" );
            $query->where( "group_id = {$group}" );
            $db->setQuery( $query );
//            $db->query();

            $result = ( $db->query() ) ? true : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.usergroup.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna el ID del grupo para un cargo
     * @param type $idCargo
     * @return type
     */
    public function getGrupoCargo( $idCargo )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select( "intIdGrupo_cargo AS id" );
            $query->from( "#__gen_ug_cargo" );
            $query->where( "intId_ugc = {$idCargo}" );
            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0) ? $db->loadResult() : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.usergroup.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  retorna True si es que ya existe el registo de un uasuario al aun grupo caso contrari retorna False
     * @param type $idUsuario
     * @param type $idGrupo
     * @return type
     */
    public function controlExist( $idUsuario, $idGrupo )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select( "user_id, group_id" );
            $query->from( "#__user_usergroup_map" );
            $query->where( "user_id = {$idUsuario}" );
            $query->where( "group_id = {$idGrupo}" );
            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0) ? true : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.usergroup.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna las lista de grupos del user del funcionario
     * @param type $idUserFnc
     * @return type
     */
    public function getGroupsFnc( $idUserFnc )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select( "group_id" );
            $query->from( "#__user_usergroup_map" );
            $query->where( "user_id = {$idUserFnc}" );
            $db->setQuery( $query );
            $db->query();
            $result = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.usergroup.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
}