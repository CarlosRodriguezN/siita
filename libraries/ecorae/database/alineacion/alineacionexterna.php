<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
//jimport( 'joomla.database.table' );
jimport( 'joomla.database.tablenested' );

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class jTableAlineacioExterna extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__agd_oei', 'intid_agd_oei', $db );
    }

    /**
     * 
     * @param type $idAgenda
     * @param type $idPadre
     * @return type
     */
    public function saveAlineacionExterna( $alineacion, $idObjetivo, $idItem )
    {
        try {
            $dta["intid_agd_oei"]   = $alineacion->idAlineacion;
            $dta["intId_ob"]        = $idObjetivo;
            $dta["intIdItem_it"]    = $idItem;
            $dta["published"]       = $alineacion->published;

            if(!$this->save( $dta )) {
                echo $this->getError();
                exit;
            }

            return $this->intid_agd_oei;
        } catch (Exception $ex) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * @param type $idObjetivo
     */
    public function getAlineacionesByObjetivo( $idObjetivo )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   ago.intid_agd_oei        AS idAlineacion,
                                ago.intIdItem_it         AS idItem,
                                itm.intIdAgenda_ag       AS idAgenda,
                                agd.strDescripcion_ag    AS nombre,
                                ago.published            AS published' );
            $query->from( '#__agd_oei ago' );
            $query->join( 'INNER', '#__agd_item AS itm ON ago.intIdItem_it=itm.intIdItem_it ' );
            $query->join( 'INNER', '#__agd_agenda AS agd ON agd.intIdAgenda_ag=itm.intIdAgenda_ag ' );
            $query->where( "ago.published = 1" );
            $query->where( "ago.intId_ob = $idObjetivo" );

            $db->setQuery( (string) $query );
            $db->query();

            $alineacion = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() 
                                                    : array();

            return $alineacion;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    
    public function getAlineacionIndicador( $idIndicador )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   DISTINCT
                                    t1.descripcionPlan,
                                    t1.fechaInicioPlan,
                                    t1.fechaFinPlan,
                                    t1.descripcionObjetivo,
                                    t2.agenda,
                                    CONCAT( t2.F1, " - ", t2.F2 ) AS gerarquia_1,
                                    CONCAT( t2.F3, " - ", t2.F4 ) AS gerarquia_2,
                                    CONCAT( t2.F5, " - ", t2.F6 ) AS gerarquia_3' );
            $query->from( '#__plan_objetivo_indicador t1' );
            $query->join( 'inner', '#__vw_oei_agenda t2 ON t1.idObjetivo = t2.idObjetivo' );
            $query->where( "t1.idIndicador = ". $idIndicador );
            
            $db->setQuery( (string) $query );
            $db->query();

            $alineacion = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() 
                                                    : array();

            return $alineacion;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }


    
    public function __destruct()
    {
        return;
    }
    

}
