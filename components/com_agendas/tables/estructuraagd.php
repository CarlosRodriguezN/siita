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
class AgendasTableEstructuraAgd extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__agd_estructura', 'intIdEstructura_es', $db );
    }
    
    /**
     *  Ejecuta la sentencia sql en la base para realizar un nuevo registro
     * @param type $data    data para el registro
     * @return type
     */
    public function registrarEstructura($data) 
    {
        if (!$this->save($data)) {
            $result = array();
        } else {
            $result = $this->intIdEstructura_es;
        }
        return $result;
    }
    
    /**
     *  Realiza la ejecucion de la sentencia sql en la base de datos para la 
     * eliminacion logica de un registro
     * @param type $idEstructura    Id del registro a eliminar
     * @return type
     */
    public function deleteLogicalEstructura( $idEstructura )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update("#__agd_estructura e");
            $query->set("e.published = 0");
            $query->where("e.intIdEstructura_es = {$idEstructura}");
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? true : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_agendas.tables.estructuraagd.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     *  Retorna de la base de datos la estructura de una agenda
     * @param type $idAgenda    Id de la agenda
     */
    public function getEstructuraAgd( $idAgenda )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("ea.intIdEstructura_es       AS idEstructura,
                            ea.intIdAgenda_ag           AS idAgenda,
                            ea.intIdEstuctura_padre_es  AS idPadreEtr,
                            b.strDescripcion_es         AS descPadreEtr,
                            ea.strDescripcion_es        AS descripcionEtr,
                            ea.intNivel                 AS nivelEtr,
                            ea.published");
            $query->from("#__agd_estructura ea");
            $query->leftJoin("#__agd_estructura b ON ea.intIdEstuctura_padre_es = b.intIdEstructura_es");
            $query->where("ea.intIdAgenda_ag = {$idAgenda}");
            $query->where("ea.published = 1");
            $query->order("ea.intNivel ASC");
            
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_agendas.tables.estructuraagd.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna TRUE si se pued eliminar un elemento de un aestructura si no retorna FALSE
     * @param type $idEstructura
     * @return type
     */
    public function avalibleDel( $idEstructura )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intIdItem_it");
            $query->from("#__agd_item");
            $query->where("intIdEstructura_es = {$idEstructura}");
            
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? false : true;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_agendas.tables.estructuraagd.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}