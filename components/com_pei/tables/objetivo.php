<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class PeiTableObjetivo extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__pln_objetivo_institucion', 'intId_ob', $db );
    }

    /**
     * 
     * @param type $dtaObj
     * @return type
     */
    public function registroObjPei( $dtaObj )
    {
        if( !$this->save( $dtaObj ) ) {
            echo 'error al guardar un objetivo del PEI';
            exit;
        }
        return $this->intId_ob;
    }

    /**
     * 
     * @return type
     */
    public function reistroEntidad()
    {

        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );
            $query->insert( "#__gen_entidad" );
            $query->values( "0, 7" );   // 7 es el id de la entidad de PEI
            $db->setQuery( $query );
            $db->query();

            $query->select( " MAX(intIdentidad_ent) AS id" );
            $query->from( "#__gen_entidad" );
            $db->setQuery( $query );

            $result = ($db->query()) ? $db->loadObject()->id : false;

            return $result;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    public function deleteObjPei( $idObjPei )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );
            $query->update( "#__pln_objetivo_institucion" );
            $query->set( "published = 0" );
            $query->where( "intId_ob = " . $idObjPei );
            $db->setQuery( $query );
            $db->query();
            $result = ($db->getAffectedRows() > 0) ? true : false;

            return $result;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    public function getLstObjetivos( $idPei )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   oi.intId_ob                 AS idObjetivo,                   
                                oi.intIdPrioridad_pr        AS idPrioridadObj, 
                                oi.intId_tpoObj             AS idTpoObj, 
                                oi.intIdPadre_ob            AS idPadreObj, 
                                oi.strDescripcion_ob        AS descObjetivo, 
                                tpob.strDescripcion_tpoObj  AS descTpoObj, 
                                pr.strNombre_pr             AS nmbPrioridadObj, 
                                oi.dteFecharegistro_ob      AS fchRegistroObj,
                                oi.published                AS published' );
            $query->from( '#__pln_objetivo_institucion oi' );
            $query->leftJoin( '#__gen_prioridad as pr ON pr.intIdPrioridad_pr = oi.intIdPrioridad_pr' );
            $query->leftJoin( '#__pln_tipo_objetivo as tpob ON tpob.intId_tpoObj = oi.intId_tpoObj' );
            $query->where( 'oi.intId_pi = ' . $idPei );
            $query->where( 'oi.published = 1' );

            $db->setQuery( (string) $query );
            $db->query();

            $rstObjetivosPln = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : false;

            return $rstObjetivosPln;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}