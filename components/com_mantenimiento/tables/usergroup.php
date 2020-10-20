<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla grupo ( #__gen_grupo)
 * 
 */
class mantenimientoTableUserGroup extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__usergroups', 'id', $db);
    }

    public function resgistrarGrupo( $data ) {
        if (!$this->save($data)) {
            echo 'error al guardar el user group para el cargo';
            exit;
        }
        return $this->id;
    }
    
    /**
     *  Retorna una lista de objetos en caso de esxistir coincidencias si no retorna un array vacio
     * @param type $name
     * @return type
     */
    public function existeGrupo( $name )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("id");
            $query->from( "#__usergroups" );
            $query->where("UPPER(title) = UPPER('op-{$name}')" );
            $db->setQuery($query);
            $db->query();
            $result = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : array();
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Edita el nombre de un grupo de usuario
     * @param type $idCargo
     * @param type $newName
     * @return type
     */
    public function updNameGroup( $idCargo, $newName )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update('#__usergroups');
            $query->set("title = 'cr-{$newName}'");
            $query->where("id = '{$idCargo}'");
            $db->setQuery($query);
            $result = $db->query();
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargoug.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Elimina un registro de los grupos de usuarios 
     * @param type $id
     * @return type
     */
    public function eliminarGrupo( $id )
    {
     try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->delete('#__usergroups');
            $query->where( "id = {$id}" );
            $db->setQuery( $query );
            $db->query();
            $result = ($db->getAffectedRows() > 0) ? true : false;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargoug.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
        
    }
    
    /**
     *  Actualisa los registros se usuraios a SIN GRUPO
     * @param type $id
     * @return type
     */
    public function updUserSG( $id )
    {
     try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update('#__user_usergroup_map');
            $query->set( "group_id = 9" );
            $query->where( "group_id = {$id}" );
            $db->setQuery( $query );
            $db->query();
            $result = ($db->getAffectedRows() > 0) ? true : false;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargoug.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
        
    }

    public function lastOwnerRgt()
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select( "MAX( rgt ) AS rgt" );
            $query->from( "#__usergroups" );
            $query->where( "parent_id = 6 OR parent_id>12" );
            $db->setQuery( $query );
            $db->query();
            $result = ($db->getAffectedRows() > 0) ? $db->loadObject() : array();
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.usergroup.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }


}