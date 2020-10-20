<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Atribtuo ( #__ctr_atributo )
 * 
 */
class contratosTableProyecto extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__pfr_proyecto_frm', 'intCodigo_pry', $db);
    }

    function getProyectos($idPrograma) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('t1.intCodigo_pry    AS id,
                            t1.strNombre_pry    AS name
                                    ');
            $query->from('#__pfr_proyecto_frm AS t1');
            $query->where('intCodigo_prg=' . $idPrograma);
            $db->setQuery($query);
            $db->query();
            return $db->loadObjectList();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.tiposcontratista.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}