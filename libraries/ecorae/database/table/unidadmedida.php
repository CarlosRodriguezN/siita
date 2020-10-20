<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport('joomla.database.tablenested');


/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de POA ( #__poa_plan_institucion )
 * 
 */
class jTableUnidadMedida extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gen_unidad_medida', 'intCodigo_unimed', $db );
    }
    
    /**
     *  Obtiene la lista de las actividades de un objetivo 
     * @param type $idObjetivo
     * @return type
     */
    function getDtaUnidadMedida( $idTpoUM )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   ta.intCodigo_unimed AS id, 
                                CONCAT( strSimbolo_unimed, " - ", UPPER( ta.strDescripcion_unimed ) ) AS nombre' );
            $query->from( "#__gen_unidad_medida AS ta" );
            $query->where( 'ta.intId_tum = ' . $idTpoUM );
            $query->where( 'ta.published = 1' );
            $query->order( 'ta.strDescripcion_unimed' );

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