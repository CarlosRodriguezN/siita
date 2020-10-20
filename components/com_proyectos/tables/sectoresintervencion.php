<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */

class ProyectosTableSectoresIntervencion extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__gen_sectores_intervencion', 'intId_si', $db );
    }
    
    /**
     * 
     * @param type $id
     * @return boolean
     */
    public function getEstructuraSecIntrv( $id )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select( 'intId_esi,'
                    . 'intId_si,'
                    . 'strDescripcion_esi,'
                    . 'intOwner_esi' );
            $query->from( '#__gen_estructura_intervencion' );
            $query->where( 'intId_si = '. $id );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 )   ? $db->loadObjectList() : array();
            return $retval;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
            return false;
        }
    }
    
    /**
    *   Retorna el ise del sertor la estructura de sectores de intervencion vigente
    * @return boolean
    */
    public function getSctIntrvVgn(){
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select( 'intId_si as id');
            $query->from( '#__gen_sectores_intervencion' );
            $query->where( 'vigencia_si = 1 LIMIT 1' );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 )   ? $db->loadObject() : array();
            return $retval;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
            return false;
        }
    }
    
    /**
     *  Retorna el id del padre se un subsector
     * @param type $idSS
     * @return boolean
     */
    public function ownerSubSec( $idSS ){
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select( 'inpCodigo_sec as id');
            $query->from( '#__gen_subsector' );
            $query->where( 'inpCodigo_subsec = ' . $idSS );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 )   ? $db->loadObject() : array();
            return $retval;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
            return false;
        }
    }

    /**
     *  Retorna el id del padre se un subsector
     * @param type $idSec
     * @return boolean
     */
    public function ownerSector( $idSec ){
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select( 'intId_macrosector as id');
            $query->from( '#__gen_sector' );
            $query->where( 'inpCodigo_sec = ' . $idSec );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 )   ? $db->loadObject() : array();
            return $retval;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
            return false;
        }
    }

    
}