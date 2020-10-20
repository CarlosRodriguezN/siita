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
class UnidadGestionTableUserGroups extends JTable
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
     *  Registra un nuevo grupo del sistema 
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
            $query->select( 'intIdGrupo_ug' );
            $query->from( '#__gen_unidad_gestion' );
            $query->where( 'intCodigo_ug = '. $idUG );
            $db->setQuery( (string)$query );
            $db->query();

            $result = ($db->getAffectedRows() > 0) ? $db->loadResult() : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.usergroup.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    public function getGroupIdCargo( $idCrgFnc )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select( 'intIdGrupo_cargo' );
            $query->from( '#__gen_ug_cargo' );
            $query->where( 'intId_ugc = '. $idCrgFnc );
            $db->setQuery( (string)$query );
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
     *  Inserta un registro en la base de datos para asignar un usuario a un grupo
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
     *  Elimna un grupo de los grupos de usuarios del sistema
     * @param type $pk
     * @return type
     */
    public function eliminarGrupo($pk)
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->delete( "#__usergroups" );
            $query->where( "id = {$pk}" );
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
     *  Elimina la ralcion de usuarios del sistema con un grupo eliminado
     * @param type $pk
     * @return type
     */
    public function eliminarUsersGrp($pk)
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->delete( "#__user_usergroup_map" );
            $query->where( "group_id = {$pk}" );
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
     *  elimina los registros relacionados con un usuario
     * @param type $idUsr
     * @return type
     */
    public function eliminarUsrGroups( $idUsr )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->delete( "#__user_usergroup_map" );
            $query->where( "user_id = {$idUsr}" );
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
     *  Retorna la lista de grupos a los que pertenece el ususario
     * @param type $idUsr
     * @return type
     */
    public function getGruposUser( $idUsr )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select( "group_id" );
            $query->from( "#__user_usergroup_map" );
            $query->where( "user_id = {$idUsr}" );
            $db->setQuery( $query );
            $db->query();
            $result = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : array();
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.usergroup.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
}