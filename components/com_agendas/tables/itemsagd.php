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
class AgendasTableItemsAgd extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__agd_item', 'intIdItem_it', $db );
    }

    /**
     *  Ejecuta la sentencia sql en la base para realizar un nuevo registro
     * @param type $data    data para el registro
     * @return type
     */
    public function registrarItem( $data )
    {
        if (!$this->save($data)) {
            $result = array();
        } else {
            $result = $this->intIdItem_it;
        }
        return $result;
    }
    
    /**
     *  Realiza la ejecucion de la sentencia sql en la base de datos para la 
     * eliminacion logica de un registro
     * @param type $idEstructura    Id del registro a eliminar
     * @return type
     */
    public function deleteLogicalItem( $idEstructura )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update("#__agd_item i");
            $query->set("i.published = 0");
            $query->where("i.intIdItem_it = {$idEstructura}");
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? true : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_agendas.tables.itemsagd.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    
    /**
     *  Ejecuta un sentencia a la base de datos para obtener la lista de items 
     * de acuerdo a la estructura y a su owner
     * @param type $idEstructura            Id de la estructura
     * @param type $idOwner                 Id del item owner
     * @return type
     */
    public function getItemsByEtr( $idEstructura, $idOwner = 0 )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("i.intIdItem_it          AS idItem,
                            i.intIdAgenda_ag        AS idAgenda,
                            i.intIdEstructura_es    AS idEstructura,
                            i.intIdItem_padre_it    AS idOwner,
                            i.strDescripcion_it     AS descripcionItem,
                            i.strNivel_it           AS nivelItem,
                            i.published");
            $query->from("#__agd_item i");
            $query->where("i.intIdEstructura_es = {$idEstructura}");
            $query->where("i.intIdItem_padre_it = {$idOwner}");
            $query->where("i.published = 1");
            
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_agendas.tables.itemsagd.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}