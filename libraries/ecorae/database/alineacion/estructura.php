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
class jTableEstructura extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__agd_estructura', 'intIdEstructura_es', $db);
    }

    /**
     * RECUPERA la ESTRUCTURA de la agenda.
     * @param type $idAgenda
     * @return type     
     */
    public function getEstructura($idAgenda) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(''
                    . 'itm.intIdEstructura_es       AS id,'
                    . 'itm.intIdEstuctura_padre_es  AS idPadre,'
                    . 'itm.strDescripcion_es        AS nombre');
            $query->from('#__agd_estructura AS itm');
            $query->where('itm.intIdAgenda_ag =' . $idAgenda);
            $query->where('itm.published = 1');
            $query->order('itm.intNivel');
            $db->setQuery((string) $query);
            $db->query();

            $estructura = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : false;

            return $estructura;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}
