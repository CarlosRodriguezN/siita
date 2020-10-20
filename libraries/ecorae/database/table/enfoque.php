<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport('joomla.database.tablenested');


/**
 * 
 * Clase que gestiona informacion de la tabla Enfoque
 * 
 */
class jTableEnfoque extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gen_enfoque', 'intId_enfoque', $db );
    }
    
    /**
     *  Obtiene la lista de las actividades de un objetivo 
     * @param type $idObjetivo
     * @return type
     */
    public function getLstDimension( $idEnfoque )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intId_dim AS id, 
                                UPPER( t1.strDescripcion_dim ) AS nombre' );
            $query->from( "#__gen_dimension t1 " );
            $query->where( 't1.intId_enfoque =' . $idEnfoque );
            $query->order( 't1.strDescripcion_dim' );

            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->loadObjectList() ) ? $db->loadObjectList() 
                                                : array();

            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    public function getLstEnfoques()
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   DISTINCT 
                                    t1.idEnfoque,
                                    t1.enfoque,
                                    t1.idDimension,
                                    t1.dimension' );
            $query->from( "#__dim_dimension_enfoque t1 " );

            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->loadObjectList() ) ? $db->loadObjectList() 
                                                : array();

            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}