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
class ProyectosTableUbiGeoCtr extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_ubicgeo_ctr', 'intIdContrato_ctr', $db);
    }

    /**
     * recupera la lista de ubicaicones de un contrato.
     * @param int       $idContrato     Identificador del contrato
     * @return array
     */
    function getUbicacionContrato($idContrato) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('intID_ut');
            $query->from('#__ctr_ubicgeo_ctr');
            $query->where('intIdContrato_ctr=' . $idContrato);
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