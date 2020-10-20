<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class TableUser extends JTable
{

    function __construct( &$db )
    {
        parent::__construct( 'rds_users', 'id', $db );
    }

    function getCoorUnTerritoriaActual( $idUT )
    {
        try{
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );

            $query->select( "T1.intID_ut AS id,T1.strNombre_ut AS nombre, T1.lat AS lat,T1.longi AS longi" );
            $query->from( '#__ut_undTerritorial AS T1  ' );
            $query->where( "T1.intID_ut in(
					SELECT T2.intID_ut FROM dev_siita.tb_ut_relacionDPA AS T2
					where T2.intIDPadre_ut = '{$idPadre}'   )" );
            $query->order( "T1.strNombre_ut asc" );

            $db->setQuery( $query );
            $result = $db->loadObjectList();
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return (array)$result;
    }

}