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
class mantenimientoTableGrupo extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__gen_grupo', 'intCodigo_grp', $db);
    }

    public function getLstGrupos($idCategoria) {
        try {
            $db = JFactory::getDBO();

            $query = $db->getQuery(true);
            $query->select('intCodigo_grp AS id, 
                            strDescripcion_grp AS nombre');
            $query->from('#__gen_grupo');
            $query->where('strCodigoCategoria = ' . $idCategoria);
            $db->setQuery($query);
            $db->query();
            $extension = $db->loadObjectList();
            
            return $extension;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}