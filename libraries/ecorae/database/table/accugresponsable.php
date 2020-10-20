<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class jTableAccUGResponsable extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__pln_ug_responsable', 'intId_plnUGR', $db );
    }

    /**
     *  Rejistra la unidad de gestión responsable de una acción
     * @param type $idAccion
     * @param type $accion
     * @return type
     */
    public function registroUniGesRes( $accion )
    {
        $ugr["intId_plnUGR"]          = 0;
        $ugr["intId_plnAccion"]       = $accion->idAccion;
        $ugr["intCodigo_ug"]          = $accion->idUniGes;
        $ugr["dteFechaInicio_plnUGR"] = $accion->fechaInicioUGR;
        $ugr["intVigencia_plnUGR"]    = 1;
        $ugr["published"]             = 1;

        if( !$this->save( $ugr ) ) {
            echo $this->getError();
            exit;
        }

        return $this->intId_plnUGR;
    }

    /**
     *  Actualiza la fecha deinicio de unidad de gestión responsable, titular/vigente de una acción
     * @param type      $idAccionFR     Id de la relación entre acción y unidad de gestión responsable
     * @return type
     */
    public function updDateUGR( $idAccion )
    {
        $db = & JFactory::getDBO();
        
        try {
            $db->transactionStart();
            $query = $db->getQuery( TRUE );
            
            $query->update( "#__pln_ug_responsable" );
            $query->set( "dteFechaFin_plnUGR = '". DATE( 'Y-m-d' ) ."'" );
            $query->set( "intVigencia_plnUGR = 0" );
            $query->where( "intId_plnAccion =" . $idAccion );

            $db->setQuery( $query );

            $result = ( $db->query() )  ? true 
                                        : false;

            $db->transactionCommit();

            return $result;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render($e);
        }
    }

    /**
     *  Devuelve el Id actual de la unidad de gestión responsable de una acción
     * @param type $idAccUGR    Id de la ralacion entre la unidad de gestión y acción
     * @return type
     */
    public function getActualUGR( $idAccUGR )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );
            $query->select( "ugr.intCodigo_ug            AS idUG,
                            ug.intIdentidad_ent         AS entidadUG,
                            ugr.dteFechaInicio_plnUGR   AS fechaInicio" );
            $query->from( "#__pln_ug_responsable ugr" );
            $query->innerJoin( "#__gen_unidad_gestion ug ON ug.intCodigo_ug = ugr.intCodigo_ug" );
            $query->where( "ugr.intId_plnUGR = " . $idAccUGR );
            $query->where( "ugr.intVigencia_plnUGR = 1" );
            $query->order(  "ugr.intId_plnUGR DESC LIMIT 1");
            $db->setQuery( $query );

            $result = ($db->query()) ? $db->loadObject() : array();
            
            return $result;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Actualiza la informavion de la UGR anterior 
     * @param type $idAccionUGR     Id de la relacion entre la accion y la UGR
     * @param type $fechaFin        Fecha de fin de resposabilidad de la UG
     * @return type
     */
    public function updDateFinUnidadGestion( $idAccionUGR, $fechaFin )
    {
        $db = & JFactory::getDBO();
        
        try {
            $db->transactionStart();
            
            $query = $db->getQuery( TRUE );
            
            $query->update( "#__pln_ug_responsable" );
            $query->set( "intVigencia_plnUGR = 0, dteFechaFin_plnUGR = {$fechaFin}" );
            $query->where( "intId_plnUGR = {$idAccionUGR}" );
            
            $db->setQuery( $query );
            $result = ($db->query()) ? true : false;
            
            $db->transactionCommit();
            
            return $result;
        } catch( Exception $e ) {
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            
            JErrorPage::render($e);
            
        }
    }
    
}