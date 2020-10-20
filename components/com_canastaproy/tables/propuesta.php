<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla beneficiario ( #__gen_benefificario )
 * 
 */
class CanastaproyTablePropuesta extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__cp_propuesta', 'intIdPropuesta_cp', $db);
    }

    public function registrarPropuesta($dtaPropuesta) {
        if (!$this->save($dtaPropuesta)) {
            echo 'error al guardar la propuesta';
            exit;
        }

        return $this->intIdPropuesta_cp;
    }
    
    public function eliminarPropuesta ($idPropuesta) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->delete("#__cp_propuesta");
            $query->where("intIdPropuesta_cp= ". $idPropuesta );
            $db->setQuery($query);
            $result = ($db->query()) ? true : false;
            
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function availableUndTrrPrp( $id ){
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intIdPropuesta_cp");
            $query->from("#__cp_ubicgeo");
            $query->where("intIdPropuesta_cp= ". $id );
            $db->setQuery($query);
            $db->query();
            $result = ( $db->getAffectedRows() > 0 ) ? true : false;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function availableAlineacionPrp( $id ){
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intId_PropuestaPNBV");
            $query->from("#__cp_propuesta_pnbv");
            $query->where("intIdPropuesta_cp= ". $id );
            $db->setQuery($query);
            $db->query();
            $result = ( $db->getAffectedRows() > 0 ) ? true : false;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function availableUbcGeoPrp( $id ){
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intId_gcp");
            $query->from("#_cp_grafico_propuesta");
            $query->where("intIdPropuesta_cp= ". $id );
            $db->setQuery($query);
            $db->query();
            $result = ( $db->getAffectedRows() > 0 ) ? true : false;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function unpublishedPrp($id){
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update("#__cp_propuesta");
            $query->set("published = 0");
            $query->where("intIdPropuesta_cp= ". $id );
            $db->setQuery($query);
            $result = ( $db->query() ) ? true : false;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}