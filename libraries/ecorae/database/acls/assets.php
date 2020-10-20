<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla assets ( #__assets )
 * 
 */
class JTableAssets extends JTable 
{
    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) 
    {
        parent::__construct('#__assets', 'id', $db);
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public function getRulesComponent( $id )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select( "rules" );
            $query->from( "#__assets" );
            $query->where("id = {$id}");
            $db->setQuery($query);
            $db->query();
            $result = ( $db->getAffectedRows() > 0 ) ? $db->loadObject() : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.extension.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * 
     * @param type $id
     * @param type $rules
     * @return type
     */
    public function registrarRules( $id, $rules )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update( "#__assets" );
            $query->set( "rules = '{$rules}'" );
            $query->where("id = {$id}");
            $db->setQuery($query);
            $db->query();
            $result = ( $db->getAffectedRows() > 0 ) ? true : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.extension.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna la data relgras de un componente dado el name del componente
     * @param type $com         Nomnre del componente
     * @return type
     */
    public function getDataComByName( $com )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select( "id, name, rules" );
            $query->from( "#__assets" );
            $query->where("name = '{$com}'");
            $db->setQuery($query);
            $db->query();
            $result = ( $db->getAffectedRows() > 0 ) ? $db->loadObject() : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.extension.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
}