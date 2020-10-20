<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Agenda ( #__agd_agenda )
 * 
 */
class JTableExtensions extends JTable 
{
    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) 
    {
        parent::__construct('#__extensions', 'extension_id', $db);
    }

    /**
     *  Ejecuta la sentencia sql en la base para realizar un nuevo registro
     * @param type $data    data para el registro
     * @return type
     */
    public function registrarAgenda($data) 
    {
        if (!$this->save($data)) {
            $result = array();
        } else {
            $result = $this->intIdAgenda_ag;
        }
        return $result;
    }

    public function getComponentesByAutor($autor)
    {
        try {
            $dtaAutor = '%' . $autor . '%';
//            $dtaAutor = '%"author":"' . $autor . '%';
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("ass.id      As id, "
                    . "ass.name         As nombre, "
                    . "ass.rules        As permisos, "
                    . "manifest_cache   As dtageneral");
            $query->from("#__extensions ex");
            $query->innerJoin("#__assets ass ON ass.name = ex.element");
            $query->where("type = 'component'");
            $query->where("enabled = 1");
            $query->where("manifest_cache LIKE '{$dtaAutor}'");
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.extension.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
}