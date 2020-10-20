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
class jTableItem extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__agd_item', 'intIdItem_it', $db);
    }

    /**
     * 
     * @param type $idAgenda
     * @param type $idPadre
     * @return type
     */
    public function getItems($idAgenda, $idPadre) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(''
                    . 'intIdItem_it          AS idItem,'
                    . 'intIdAgenda_ag        AS idAgenda,'
                    . 'intIdEstructura_es    AS idEstructura,'
                    . 'intIdItem_padre_it    AS idPadre,'
                    . 'strDescripcion_it     AS descripcion,'
                    . 'strNivel_it           AS nivel'
            );
            $query->from('#__agd_item');
            $query->where("intIdAgenda_ag = {$idAgenda}");
            $query->where("intIdItem_padre_it = {$idPadre}");
            $query->where("published = 1");

            $db->setQuery((string) $query);
            $db->query();

            $rstObjetivosPln = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $rstObjetivosPln;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * 
     * @param type $idItem
     */
    public function getItemById($idItem) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(''
                    . 'intIdItem_it          AS idItem,'
                    . 'intIdAgenda_ag        AS idAgenda,'
                    . 'intIdEstructura_es    AS idEstructura,'
                    . 'intIdItem_padre_it    AS idPadre,'
                    . 'strDescripcion_it     AS descripcion,'
                    . 'strNivel_it           AS nivel'
            );
            $query->from('#__agd_item');
            $query->where("intIdItem_it = {$idItem}");
            $db->setQuery((string) $query);
            $db->query();

            $item = ( $db->getNumRows() > 0 ) ? $db->loadObject() : false;
            return $item;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function getSinItems($idAgenda, $idPadre) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('intIdEstructura_es AS idEstructura');
            $query->from('#__agd_estructura');
            $query->where("intIdAgenda_ag = {$idAgenda}");
            $query->where("intIdEstuctura_padre_es = {$idPadre}");
            $query->where("published = 1");
            $db->setQuery((string) $query);
            $db->query();
            $rstObjetivosPln = ( $db->getNumRows() > 0 ) ? $db->loadObject() : array();
            return $rstObjetivosPln;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    
}
