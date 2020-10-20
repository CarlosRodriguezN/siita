<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla API ( #__api_urls )
 * 
 */

class ApiRestTableUrl extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    public function __construct( &$db ) 
    {
        parent::__construct( '#__api_urls', 'intIdApiUrl', $db );
    }
    
    
    public function registrarUrl( $dtaUrl )
    {
        if( !$this->save( $dtaUrl ) ){
            echo $this->getError();
            exit;
        }

        return $this->intIdApiUrl;
    }
    
    public function dtaUrlPorToken( $token )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intIdApiUrl          AS idUrl,
                                t1.intCodigo_ins        AS idInstitucion,
                                t1.strIPInstitucion_api AS ips,
                                t1.intIdDocumento       AS idDocumento,
                                t1.dteFechaInicio_api   AS fchInicio,
                                t1.dteFechaFin_api      AS fchFin,
                                t1.published' );
            $query->from( '#__api_urls t1' );
            $query->where( 't1.strToken_api = "'. $token .'"' );
            
            $db->setQuery( (string)$query );
            $db->query();

            $dtaUrl = ( $db->getNumRows() > 0 ) ? $db->loadObject() 
                                                : array();

             return $dtaUrl;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    
    public function updVigencia( $idUrl )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->update( '#__api_urls ' );
            $query->set( 'intVigente_Api = IF( intVigente_Api = 1, 0, 1 )' );
            $query->where( 'intIdApiUrl = '. $idUrl );

            $db->setQuery( (string)$query );
            $db->query();

            return $db->getAffectedRows();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
}