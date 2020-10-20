<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

//  Import Joomla JUser Library
jimport('joomla.user.user');

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class ContratosTableIndicador extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#tb_ind_indicador', 'intCodigo_ind', $db);
    }

    function getIndicadorById($idIndicador) {
        try {
            $db = &JFactory::getDBO();
            $db->getQuery(true);
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__ind_indicador');
            $query->where("intCodigo_ind =" . $idIndicador);
            $db->setQuery($query);
            $db->query();
            $retval = ($db->loadObject()) ? $db->loadObject() : false;
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_adminmapa.table.entidad.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}