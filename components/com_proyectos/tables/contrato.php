<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Entidad ( #__gen_entidad )
 * 
 */
class ProyectosTableContrato extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_contrato', 'intIdContrato_ctr', $db);
    }

    function getContratosProyecto($idProyecto) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(' *  ');
            $query->from('#__ctr_contrato As ctr');
            $query->where('ctr.intCodigo_pry = ' . $idProyecto);
            $db->setQuery($query);
            $db->query();

            $lstIndicadores = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : FALSE;

            return $lstIndicadores;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}