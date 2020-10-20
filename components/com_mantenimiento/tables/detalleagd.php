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
class MantenimientoTableDetalleAgd extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__agd_detalle_agenda', 'intIdDetalle_dt', $db );
    }

    /**
     *  Ejecuta la sentencia sql en la base para realizar un nuevo registro
     * @param type $data    data para el registro
     * @return type
     */
    public function registrarDetalle($data) 
    {
        if (!$this->save($data)) {
            $result = array();
        } else {
            $result = $this->intIdDetalle_dt;
        }
        return $result;
    }
    
    /**
     *  Retorna de la base de datos una lista de detalles de una agenda
     * @param type $idAgenda    Id de la agenda
     */
    public function getLstDetallesAgd( $idAgenda )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("da.intIdDetalle_dt  AS idDetalle,
                            da.intIdAgenda_ag   AS idAgenda,
                            da.strCampo_dt      AS strCampoDtll,
                            da.strValorCampo_dt AS strValorDtll,
                            da.published        AS published");
            $query->from("#__agd_detalle_agenda da");
            $query->where("da.intIdAgenda_ag = {$idAgenda}");
            $query->where("da.published = 1");
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.detalleagd.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Realiza la ejecucion de la sentencia sql en la base de datos para la 
     * eliminacion logica de un registro
     * @param type $idDetalle           Id del registro a eliminar
     * @return type
     */
    public function deleteLogicalDetalle( $idDetalle )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update("#__agd_detalle_agenda d");
            $query->set("d.published = 0");
            $query->where("d.intIdDetalle_dt = {$idDetalle}");
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? true : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.detalleagd.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
}