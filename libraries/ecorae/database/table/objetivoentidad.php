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
class jTableObjetivoEntidad extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gen_objetivo_entidad', 'intId_obj_ent', $db );
    }

    /**
     * 
     * Guarda el objetivo en la Tabal "__gen_objetivo_entidad"
     * 
     * @param int $idObjetivo      Identificador del Objetivo
     * @param int $idEntidadOwn    Identificador del tipo de la entidadad a 
     *                                la que pertenece el objetivo
     * @param int $published       Published gestion eliminado logico.
     * @return int
     */
    public function saveObjEnt( $idObjetivo, $idEntidadOwn, $published )
    {
        $data["intId_obj_ent"]      = 0;
        $data["intId_objetivo"]     = $idObjetivo;
        $data["intId_tpoEntidad"]   = $idEntidadOwn;
        $data["published"]          = $published;

        if( !$this->save( $data ) ){
            echo $this->getError();
        }
        return $this->intId_obj_ent;
    }

    /**
     * 
     * @param type $dtaObj
     * @return type
     */
    public function registroObjEnt( $dtaObj )
    {
        if( !$this->save( $dtaObj ) ){
            echo $this->getError();
            exit;
        }
        return $this->intId_obj_ent;
    }

    /**
     * 
     * @param type $idObjetivo
     * @param type $idEntidadOwn
     * @param type $published
     */
    public function updObjEnt( $idObjetivo, $idEntidadOwn, $published )
    {

        $db = & JFactory::getDBO();
        $query = $db->getQuery( TRUE );

        $query->update( '#__gen_objetivo_entidad' );
        $query->set( 'published =' . $published );
        $query->where( 'intId_objetivo = ' . $idObjetivo );
        $query->where( 'intId_tpoEntidad = ' . $idEntidadOwn );

        $db->setQuery( $query );
        $db->query();
    }

    /**
     * RETORNA el id de TIPO ENTIDAD  de un PLAN (PEI, POA, PPPP, PAPP)
     * @param type $idPln
     * @return type
     */
    public function getTpoEntidad( $idPln )
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( "intId_tpoPlan" );
            $query->from( "#__pln_plan_institucion" );
            $query->where( "intId_pi=" . $idPln );
            $db->setQuery( $query );
            $db->query();
            $result = ($db->getAffectedRows() > 0 ) ? $db->loadResult() : false;

            return $result;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * RETORNA los OBJETIVOS de una UNIDAD de GESTION
     * @param int $idEntidad   identificador de la entidad de la unidad de gestion
     * @return array
     */
    public function getObjetivosUnidadGestion( $idEntidad )
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( ''
                    . ' oe.intId_obj_ent     AS idObjEnt,'
                    . ' oe.intId_objetivo    AS idObjetivo,'
                    . ' oe.intId_tpoEntidad  AS idTpoEntidad,'
                    . ' oi.strDescripcion_ob AS descripcion,'
                    . ' oe.published         AS published'
            );
            $query->from( "#__gen_objetivo_entidad AS oe" );

            $query->join( 'inner', '#__pln_objetivo_institucion AS oi'
                    . ' ON oi.intId_ob = oe.intId_objetivo' );

            $query->join( 'inner', '#__pln_plan_objetivo AS po'
                    . ' ON po.intId_ob = oi.intId_ob' );

            $query->join( 'inner', '#__pln_plan_institucion AS pi'
                    . ' ON pi.intId_pi = po.intId_pi' );

            $query->where( 'pi.intIdentidad_ent = ' . $idEntidad );
            $query->where( 'oe.intId_tpoEntidad = 13' );

            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();

            return $result;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * RETORNA los OBJETIVOS de una UNIDAD de GESTION
     * @param int $idEntidad   identificador de la entidad de la unidad de gestion
     * @return array
     */
    public function getObjetivosPlanVigente( $idPlan, $tpoPlan )
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( ''
                    . ' oe.intId_obj_ent     AS idObjEnt,'
                    . ' oe.intId_objetivo    AS idObjetivo,'
                    . ' oe.intId_tpoEntidad  AS idTpoEntidad,'
                    . ' oi.strDescripcion_ob AS descripcion,'
                    . ' oe.published         AS published'
            );
            $query->from( "#__gen_objetivo_entidad AS oe" );

            $query->join( 'inner', '#__pln_objetivo_institucion AS oi'
                    . ' ON oi.intId_ob = oe.intId_objetivo' );

            $query->join( 'inner', '#__pln_plan_objetivo AS po'
                    . ' ON po.intId_ob = oi.intId_ob' );

            $query->join( 'inner', '#__pln_plan_institucion AS pi'
                    . ' ON pi.intId_pi = po.intId_pi' );

            $query->where( 'po.intId_pi = ' . $idPlan );
            $query->where( 'pi.intId_tpoPlan = ' . $tpoPlan );
            $query->where( 'oe.published = 1' );
            $query->where( 'oe.intId_tpoEntidad = 12' );
            
            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0 ) ? $db->loadObjectList() 
                                                    : array();

            return $result;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * RETORNA los OBJETIVOS de una UNIDAD de GESTION
     * @param int $idEntidad   identificador de la entidad de la unidad de gestion
     * @return array
     */
    public function getObjetivosConvenios( $idEntidad )
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( ''
                    . ' oe.intId_obj_ent        AS idObjEnt,'
                    . ' oe.intId_objetivo       AS idObjetivo,'
                    . ' oe.intId_tpoEntidad     AS idTpoEntidad,'
                    . ' oo.strDescripcion_ObjOp AS descripcion,'
                    . ' oe.published            AS published '
            );

            $query->from( " #__gen_objetivo_entidad AS oe" );

            $query->join( 'LEFT', '#__gen_objetivos_operativos AS oo'
                    . ' ON oo.intIdObjetivo_operativo = oe.intId_objetivo' );

            $query->where( ' oo.intIdEntidad_owner = ' . $idEntidad );
            $query->where( ' oe.intId_tpoEntidad = 3 ' );
            $query->where( ' oe.published = 1' );

            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();

            return $result;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * RETORNA los OBJETIVOS de una UNIDAD de GESTION
     * @param int $idEntidad   identificador de la entidad de la unidad de gestion
     * @return array
     */
    public function getObjetivosPrograma( $idEntidad )
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( ''
                    . ' oe.intId_obj_ent        AS idObjEnt,'
                    . ' oe.intId_objetivo       AS idObjetivo,'
                    . ' oe.intId_tpoEntidad     AS idTpoEntidad,'
                    . ' oo.strDescripcion_ObjOp AS descripcion,'
                    . ' oe.published            AS published '
            );

            $query->from( " #__gen_objetivo_entidad AS oe" );

            $query->join( 'LEFT', '#__gen_objetivos_operativos AS oo'
                    . ' ON oo.intIdObjetivo_operativo = oe.intId_objetivo' );

            $query->where( ' oo.intIdEntidad_owner  = ' . $idEntidad );
            $query->where( ' oe.intId_tpoEntidad = 1' );
            $query->where( ' oe.published = 1' );

            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();

            return $result;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * RETORNA los OBJETIVOS de una Proyecto
     * @param int $idEntidad   identificador de la entidad de la unidad de gestion
     * @return array
     */
    public function getObjetivosProyecto( $idEntidad )
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( ''
                    . ' oe.intId_obj_ent        AS idObjEnt,'
                    . ' oe.intId_objetivo       AS idObjetivo,'
                    . ' oe.intId_tpoEntidad     AS idTpoEntidad,'
                    . ' oo.strDescripcion_ObjOp AS descripcion,'
                    . ' oe.published            AS published '
            );

            $query->from( " #__gen_objetivo_entidad AS oe" );

            $query->join( 'LEFT', '#__gen_objetivos_operativos AS oo'
                    . ' ON oo.intIdObjetivo_operativo = oe.intId_objetivo' );

            $query->where( ' oo.intIdEntidad_owner  = ' . $idEntidad );
            $query->where( ' oe.intId_tpoEntidad = 2 ' );
            $query->where( ' oe.published = 1' );

            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();
            return $result;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}