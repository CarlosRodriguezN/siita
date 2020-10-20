<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class JTableCoordenadas extends JTable
{

    public function __construct( &$db )
    {
        parent::__construct( '#__pfr_coordenadas_grafico', 'intIdCoordenada_pfr', $db );

        $this->access = (int)JFactory::getConfig()->get( 'access' );
    }

    /**
     * 
     * @name getCoordenadas
     * @param int $idTgPry Identificador de el gráfico de un proyecto.
     * @return array
     * 
     */
    function getFigCoordenadas( $idTgPry )
    {
        try{
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            //se arma el select
            $query->select( "    co.intIdGrafico_pfr AS id,   
                                co.fltLatitud_cord AS lat,
                                co.fltLongitud_cord AS longi" );
            $query->from( '#__pfr_coordenadas_grafico AS co ' );
            $query->where( "co.intIdGrafico_pfr='{$idTgPry}'" );
            $query->where( "co.fltLatitud_cord IS NOT NULL" );
            $query->where( "co.fltLongitud_cord IS NOT NULL" );
            $query->where( "co.fltLatitud_cord >= -90" );
            $query->where( "co.fltLatitud_cord <= 90" );
            $query->where( "co.fltLongitud_cord >= -180" );
            $query->where( "co.fltLongitud_cord <= 180" );
            $query->where( "co.published=1" );

            $db->setQuery( $query );
            $coors = $db->loadObjectList();
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.coordenafas.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $coors;
    }

}
