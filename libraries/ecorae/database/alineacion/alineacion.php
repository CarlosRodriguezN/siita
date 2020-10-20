<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
//jimport( 'joomla.database.table' );
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class jTableAlineci extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__pln_objetivo_institucion', 'intId_ob', $db);
    }

    function getEstructura($idEstructura, $tipo) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('intId_pln_objetivo AS idPlnObj,
                            intIdentidad_ent    AS idEntidadObj');
            $query->from('#__pln_plan_objetivo');
            $query->where("intId_pi = {$idPoa}");
            $query->where("intId_ob = {$idObj}");

            $db->setQuery((string) $query);
            $db->query();

            $rstObjetivosPln = ( $db->getNumRows() > 0 ) ? $db->loadResult() : false;

            return $rstObjetivosPln;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    

}
