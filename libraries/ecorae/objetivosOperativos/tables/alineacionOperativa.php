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
class jTableAlineacionOperativa extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gen_alineacion_operativa', 'intIdAlineacion', $db );
    }

    /**
     * REGISTRA la alineacion del objetivo
     * @param object    $alineacion  ALINEACION
     * @param int       $idObjOpe    identificador del objetivo al que se alinea
     * @param object    $tpoEntidad  tipos entidad a la que pertenece el objetivo que se esta alineando
     * @return int  
     */
    public function saveAlineacionOperativa( $alineacion, $idObjOpe, $tpoEntidad )
    {
        $idAlineacion = 0;

        $dta[ 'intIdAlineacion' ]           = (int)$alineacion->idAlineacion;
        $dta[ 'intId_obj_ent' ]             = $alineacion->idObjetivo;
        $dta[ 'intIdObjetivo' ]             = $idObjOpe;
        $dta[ 'intIdTpoObjetivo_entidad' ]  = $tpoEntidad;
        $dta[ 'published' ]                 = $alineacion->published;

        if( $this->save( $dta ) ){
            $idAlineacion = $this->intIdAlineacion;
        }
        
        return $idAlineacion;
    }

    /**
     * Recupera las alineaciones de un objetivo
     * @param type $idObjetivo  Identificador del objetivo
     * @param type $tpoEntidad  Entidad a la que pertenece el objetivo
     * @return type
     */
    public function getAlineacionesOperativas( $idObjetivo, $tpoEntidad )
    {
        try{
            $db = JFactory::getDBO();

            $query = $db->getQuery( true );
            $query->select( ''
                    . ' oa.intIdAlineacion          AS idAlineacion, '      // id idAlineacion, '// id
                    . ' oe.intId_obj_ent            AS idObjetivo,'         // al que se alinea
                    . ' oe.intId_tpoEntidad         AS idOwner,'            //tipo entidad del objetivo al que se alinea
                    . ' oa.intIdTpoObjetivo_entidad AS tpoEntidObjetivo,'   //tipo entidad del objetivo que se alinea
                    . ' oa.published                AS published' );

            $query->from( '#__gen_alineacion_operativa AS oa' );

            $query->join( 'inner', '#__gen_objetivo_entidad AS oe'
                    . ' ON oe.intId_obj_ent = oa.intId_obj_ent' );

            $query->where( 'oa.intIdTpoObjetivo_entidad = ' . $tpoEntidad );
            $query->where( 'oa.intIdObjetivo = ' . $idObjetivo );
            $query->where( 'oa.published = 1' );
            $db->setQuery( (string)$query );
            $db->query();

            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $result;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    public function getAlineacionOperativaPPCC( $idObjetivo, $tpoEntidad )
    {
        try{
            $db = JFactory::getDBO();

            $query = $db->getQuery( true );
            $query->select( ''
                    . ' oa.intIdAlineacion          AS idAlineacion, '// id idAlineacion, '// id
                    . ' oa.intId_obj_ent            AS idObjetivo,'// al que se alinea
                    . ' oa.intIdTpoObjetivo_entidad AS tpoEntidObjetivo,'//tipo entidad del objetivo que se alinea
                    . ' oe.intId_tpoEntidad         AS idOwner,'//tipo entidad del objetivo al que se alinea
                    . ' oo.strDescripcion_ObjOp     AS descripcion,'// descripcion del objetivo que se esta alinendo
                    . ' oa.published                AS published' );

            $query->from( '#__gen_alineacion_operativa AS oa' );

            $query->join( 'inner', '#__gen_objetivo_entidad AS oe'
                    . ' ON oe.intId_obj_ent = oa.intId_obj_ent' );

            $query->join( 'inner', '#__gen_objetivos_operativos AS oo'
                    . ' ON oo.intIdObjetivo_operativo = oe.intId_objetivo' );

            $query->where( ' oa.intIdTpoObjetivo_entidad = ' . $tpoEntidad );
            $query->where( ' oa.intIdObjetivo = ' . $idObjetivo );
            $query->where( ' oa.published = 1' );

            $db->setQuery( (string)$query );
            $db->query();

            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $result;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    
    public function getAlineacionPlnOperativo( $idEntidadObjetivo )
    {
        try{
            $db = JFactory::getDBO();

            $query = $db->getQuery( true );
            $query->select( '   t1.intIdAlineacion          AS idAlineacion,  
                                t1.intId_obj_ent            AS idObjetivo, 
                                t1.intIdTpoObjetivo_entidad AS tpoEntidObjetivo, 
                                t3.intId_tpoPlan            AS idOwner, 
                                t4.strDescripcion_ob        AS nombre,
                                t1.published                AS published' );
            $query->from( '#__gen_alineacion_operativa AS t1' );
            $query->join( 'inner', '#__pln_plan_objetivo t2 ON t1.intIdObjetivo = t2.intIdentidad_ent' );
            $query->join( 'inner', '#__pln_plan_institucion t3 ON t2.intId_pi = t3.intId_pi' );
            $query->join( 'inner', '#__pln_objetivo_institucion t4 ON t2.intId_ob = t4.intId_ob' );
            $query->where( 't1.intIdObjetivo = '. $idEntidadObjetivo );
            $query->where( 't1.intIdTpoObjetivo_entidad = 2' );
            $query->where( 't1.published = 1' );

            $db->setQuery( (string)$query );
            $db->query();

            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() 
                                                : array();

            return $result;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}