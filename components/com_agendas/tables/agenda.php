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
class AgendasTableAgenda extends JTable 
{
    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) 
    {
        parent::__construct('#__agd_agenda', 'intIdAgenda_ag', $db);
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

    /**
     *  Realiza la ejecucion de la sentencia sql en la base de datos para la 
     * eliminacion logica de un registro
     * @param type $idAgenda    Id del registro a eliminar
     * @return type
     */
    public function deleteLogicalAgenda( $idAgenda )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update("#__agd_agenda agd");
            $query->set("agd.published = 0");
            $query->where("agd.intIdAgenda_ag = {$idAgenda}");
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? true : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_agendas.tables.agenda.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna TRUE si se puede UPD la estructura de una agenda si no retorna FALSE
     * @param type $idAgenda
     * @return type
     */
    public function avalibleUpdEstAgd( $idAgenda )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intIdItem_it");
            $query->from("#__agd_item");
            $query->where("intIdAgenda_ag = {$idAgenda}");
            
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? false : true;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_agendas.tables.agenda.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna TRUE si se puede eliminar una agenda si no retorna FALSE
     * @param type $idAgenda
     * @return type
     */
    public function canDeleteAgd( $idAgenda )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("i.intIdItem_it");
            $query->from("#__agd_item i");
            $query->innerJoin( "#__agd_oei oei ON oei.intIdItem_it = i.intIdItem_it" );
            $query->where("intIdAgenda_ag = {$idAgenda}");
            $db->setQuery($query);
            $db->query();
            $result = ( $db->getAffectedRows() > 0 ) ? false : true;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_agendas.tables.agenda.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Elimina un registro de agendas
     * @param type $id
     * @return type
     */
    public function deleteAgenda( $id ){
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->delete( " #__agd_agenda " );
            $query->where("intIdAgenda_ag = {$id}");
            $db->setQuery($query);
            return $db->query();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_agendas.tables.agenda.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Elimina estructura de una agenda
     * @param type $id
     * @return type
     */
    public function deleteEstructuraAgd( $id ){
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->delete( " #__agd_estructura " );
            $query->where("intIdAgenda_ag = {$id}");
            $db->setQuery($query);
            return $db->query();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_agendas.tables.agenda.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Elimina el detalle de agenda
     * @param type $id
     * @return type
     */
    public function deleteDetalleAgd( $id ){
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->delete( " #__agd_detalle_agenda " );
            $query->where("intIdAgenda_ag = {$id}");
            $db->setQuery($query);
            return $db->query();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_agendas.tables.agenda.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Elimina los items de agenda
     * @param type $id
     * @return type
     */
    public function deleteItemsAgd( $id ){
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->delete( " #__agd_item " );
            $query->where("intIdAgenda_ag = {$id}");
            $db->setQuery($query);
            return $db->query();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_agendas.tables.agenda.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}