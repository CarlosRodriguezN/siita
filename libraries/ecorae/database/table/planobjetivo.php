<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
//jimport( 'joomla.database.table' );
jimport( 'joomla.database.tablenested' );

/**
 * 
 * Gestiona la asociacion entre un plan ( PEI / POA ) y sus objetivos informcion 
 * que se registra en la tabla ( #__pln_plan_objetivo ).
 * 
 */
class jTablePlanObjetivo extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__pln_plan_objetivo', 'intId_pln_objetivo', $db );
    }

    /**
     * 
     * Gestiono Informacion de la relacion entre un PLAN y un Objetivo
     * 
     * @param array $dtaObj      Datos de la relacion Plan - Objetivo
     * 
     * @return int  Identificador del Registro de la relacion Plan - Objetivo
     * 
     */
    public function registroPlnObj( $dtaPlnObj )
    {
        if( !$this->save( $dtaPlnObj ) ){
            echo $this->getError();
            exit;
        }
        
        return $this->intId_pln_objetivo;
    }

    /**
     * 
     * Retorno identificador del Plan - Objetivo y el Identificador del Entidad-Objetivo
     * 
     * @param type $idPoa   Identificador del Plan
     * @param type $idObj   Identificador del Objetivo
     * 
     * @return type
     */
    public function getPlanObjetivo( $idPoa, $idObj )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intId_pln_objetivo AS idPlnObj,
                                t1.intIdentidad_ent    AS idEntidadObj' );
            $query->from( '#__pln_plan_objetivo t1 ' );
            $query->where( "t1.intId_pi = {$idPoa}" );
            $query->where( "t1.intId_ob = {$idObj}" );

            $db->setQuery( (string) $query );
            $db->query();

            $rstObjetivosPln = ( $db->getNumRows() > 0 )? $db->loadResult() 
                                                        : false;

            return $rstObjetivosPln;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    public function getIdPeiObjetivo( $idPlnObj )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 'intId_pi AS idPei' );
            $query->from( '#__pln_plan_objetivo' );
            $query->where( "intId_pln_objetivo = {$idPlnObj}" );

            $db->setQuery( (string) $query );
            $db->query();

            $rstPln = ( $db->getNumRows() > 0 ) ? $db->loadObject() : false;

            return $rstPln;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    public function getFchIniPei( $idPlnObjetivo )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 'pi.dteFechafin_pi AS fechaFin' );
            $query->from( '#__pln_plan_institucion pi ' );
            $query->innerJoin( "#__pln_plan_objetivo po ON pi.intId_pi = po.intId_pi AND pi.blnVigente_pi = 1" );
            $query->where( "po.intId_pln_objetivo = {$idPlnObjetivo}" );

            $db->setQuery( (string) $query );
            $db->query();

            $rstPln = ( $db->getNumRows() > 0 ) ? $db->loadResult() : false;

            return $rstPln;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorna una lista de Objetivos Planes Hijo
     * 
     * @param type $idEntidad      Indicador Entidad
     * @return type
     * 
     */
    public function getPlnObjetivosIndicador( $idEntidad, $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t2.dteFechainicio_pi    AS fchInicio, 
                                t2.dteFechafin_pi       AS fchFin,
                                t1.intIdentidad_ent     AS idEntidad,  
                                t1.intIdPadre           AS idPadre,
                                t2.intId_tpoPlan        AS idTpoPln, 
                                t3.intIdIndEntidad      AS idIndEntidad, 
                                0                       AS bandera' );
            $query->from( ' #__pln_plan_objetivo t1 ' );
            $query->join( ' INNER', '#__pln_plan_institucion t2 ON t1.intId_pi = t2.intId_pi AND t2.blnVigente_pi = 1' );
            $query->join( ' INNER', '#__ind_indicador_entidad t3 ON t1.intIdentidad_ent = t3.intIdentidad_ent ' );
            $query->where( ' t1.intIdPadre = ' . $idEntidad );
            $query->where( ' t3.intIdIEPadre_indEnt = ' . $idIndEntidad );

            $db->setQuery( (string) $query );
            $db->query();

            $rstPlnObj = ( $db->getNumRows() > 0 )  ? $db->loadObjectList() 
                                                    : array();

            return $rstPlnObj;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorna Informacion de Planes, Objetivos con sus respectivas fechas de Inicio y Fin
     * 
     * @param int $idEntidad   Identificador tipo de Entidad del Objetivo
     * 
     * @return array
     * 
     */
    public function getPlnObjetivos( $idEntidad, $dtaObj = null )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   DISTINCT t1.intId_ob    AS idObjetivo,
                                t1.intIdentidad_ent     AS idEntidad, 
                                t1.intIdPadre           AS idPadre, 
                                t2.intId_pi             AS idPlan,
                                t2.intIdentidad_ent     AS idEntidadPlan,
                                t2.dteFechainicio_pi    AS fchInicio, 
                                t2.dteFechafin_pi       AS fchFin,         
                                t2.intId_tpoPlan        AS idTpoPln' );
            $query->from( ' #__pln_plan_objetivo t1 ' );
            $query->join( ' INNER', '#__pln_plan_institucion t2 ON t1.intId_pi = t2.intId_pi AND t2.blnVigente_pi = 1' );
            $query->join( ' LEFT', '#__ind_indicador_entidad t3 ON t1.intIdentidad_ent = t3.intIdentidad_ent AND t3.intVigencia_indEnt = 1' );
            $query->where( ' t1.intIdPadre = '. $idEntidad );
            
            if( !is_null( $dtaObj ) ){
                $query->where( 't1.intId_ob = '. $dtaObj->idObj );
            }

            $db->setQuery( (string) $query );
            $db->query();

            $rstPlnObj = ( $db->getNumRows() > 0 )  ? $db->loadObjectList() 
                                                    : array();

            return $rstPlnObj;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorna informacion de Planes, Objetivos, Indicadores a los que esta 
     * asociado un determinado objetivo
     * 
     * @param int $idIndEntidad       Identificador de la entidad objetivo
     * 
     * @return array
     * 
     */
    public function getPlnObjIndicadores( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   DISTINCT 
                                    t1.intId_ob             AS idObjetivo,
                                    t1.intIdentidad_ent     AS idEntidad, 
                                    t1.intIdPadre           AS idPadre, 
                                    t2.intId_pi             AS idPlan,
                                    t2.intIdentidad_ent     AS idEntidadPlan,
                                    t2.dteFechainicio_pi    AS fchInicio, 
                                    t2.dteFechafin_pi       AS fchFin,         
                                    t2.intId_tpoPlan        AS idTpoPln,
                                    t2.intIdPadre_pi        AS idPlanPadre,
                                    t3.intIdIndEntidad      AS idIndEntidad,
                                    t3.intCodigo_ind        AS idIndicador,
                                    t3.dcmValor_ind         AS umbral,
                                    IF( YEAR( NOW() ) <= YEAR( t2.dteFechainicio_pi ), 1, 0 ) AS vigencia' );
            $query->from( '#__pln_plan_objetivo t1 ' );
            $query->join( 'INNER', '#__pln_plan_institucion t2 ON t1.intId_pi = t2.intId_pi AND t2.blnVigente_pi = 1' );
            $query->join( 'LEFT', '#__ind_indicador_entidad t3 ON t1.intIdentidad_ent = t3.intIdentidad_ent' );
            $query->where( 't3.intIdIEPadre_indEnt = '. $idIndEntidad );
            
            $db->setQuery( (string) $query );
            $db->query();

            $rstPlnObj = ( $db->getNumRows() > 0 )  ? $db->loadObjectList() 
                                                    : array();

            return $rstPlnObj;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    
    /**
     * 
     * Retorna informacion de Planes, Objetivos, Indicadores a los que esta 
     * asociado un determinado objetivo
     * 
     * @param int $idIndEntidad       Identificador de la entidad objetivo
     * 
     * @return array
     * 
     */
    public function getPlnObjIndicadoresUpd( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 't1.intIdIndEntidad' );
            $query->from( '#__ind_indicador_entidad t1' );
            $query->where( 't1.intIdIEPadre_indEnt IN (	SELECT t2.intIdIndEntidad 
                                                        FROM #__ind_indicador_entidad t2
                                                        WHERE t2.intIdIEPadre_indEnt = '. $idIndEntidad .' )' );
            
            $db->setQuery( (string) $query );
            $db->query();

            $rstPlnObj = ( $db->getNumRows() > 0 )  ? $db->loadObjectList() 
                                                    : array();

            return $rstPlnObj;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    
    
    
    
    public function getPlnObjetivosAcciones( $idEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   DISTINCT 
                                    t1.intId_ob             AS idObjetivo,
                                    t1.intIdentidad_ent     AS idEntidad, 
                                    t1.intIdPadre           AS idPadre,
                                    t1.intId_pln_objetivo   AS idPlnObjetivo,
                                    t2.intId_pi             AS idPlan,
                                    t2.intIdentidad_ent     AS idEntidadPlan,
                                    t2.dteFechainicio_pi    AS fchInicio, 
                                    t2.dteFechafin_pi       AS fchFin,         
                                    t2.intId_tpoPlan        AS idTpoPln' );
            $query->from( ' #__pln_plan_objetivo t1 ' );
            $query->join( ' INNER', '#__pln_plan_institucion t2 ON t1.intId_pi = t2.intId_pi AND t2.blnVigente_pi = 1' );
            $query->where( ' t1.intIdPadre = '. $idEntidad );
            
            $db->setQuery( (string) $query );
            $db->query();

            $rstPlnObj = ( $db->getNumRows() > 0 )  ? $db->loadObjectList() 
                                                    : array();

            return $rstPlnObj;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    
    public function getDtaObjetivo( $idPlan )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intId_ob             AS idObjetivo,
                                t1.intIdentidad_ent     AS idEntidadObjetivo, 
                                t1.intId_pln_objetivo   AS idPlnObjetivo,
                                t2.dteFechainicio_pi    AS fchInicio, 
                                t2.dteFechafin_pi       AS fchFin,
                                t3.strDescripcion_ob	AS descObjetivo' );
            $query->from( '#__pln_plan_objetivo t1' );
            $query->join( 'INNER', '#__pln_plan_institucion t2 ON t1.intId_pi = t2.intId_pi AND t2.blnVigente_pi = 1' );
            $query->join( 'INNER', '#__pln_objetivo_institucion t3 ON t1.intId_ob = t3.intId_ob' );
            $query->where( 't2.intId_pi = '. $idPlan );

            $db->setQuery( (string) $query );
            $db->query();

            $rstPlnObj = ( $db->getNumRows() > 0 )  ? $db->loadObject() 
                                                    : new stdClass();

            return $rstPlnObj;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    
    public function deletePlanObjetivo( $idPlnObjetivo )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();
            $query = $db->getQuery( true );

            $query->delete( '#__pln_plan_objetivo' );
            $query->where( 'intId_pln_objetivo = '. $idPlnObjetivo );

            $db->setQuery( ( string ) $query );
            $db->query();

            $ban = ( $db->getAffectedRows() >= 0 )  ? TRUE   
                                                    : FALSE;

            $db->transactionCommit();

            return $ban;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );

            JErrorPage::render($e);
        }
    }

    
    /**
     * 
     * Obtengo informacion si un determinado plan objetivo esta asociado al plan 
     * de un funcionario
     * 
     */
    public function getPlanUndGestion( $idResponsable, $plnObjetivo )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 'DISTINCT t1.intId_pi AS idPlan' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->where( 't1.blnVigente_pi = 1' );
            $query->where( 't1.dteFechainicio_pi = "'. $plnObjetivo->fchInicio .'"' );
            $query->where( 't1.dteFechafin_pi = "'. $plnObjetivo->fchFin .'"' );
            $query->where( 't1.intId_tpoPlan = '. $plnObjetivo->idTpoPln );
            $query->where( 't1.intIdentidad_ent = ( SELECT t1.intIdentidad_ent AS idEntidadUG
                                                    FROM #__gen_unidad_gestion t1
                                                    WHERE t1.intCodigo_ug = '. $idResponsable .' )' );
            
            $db->setQuery( (string) $query );
            $db->query();

            $idPlan = ( $db->getNumRows() > 0 ) ? $db->loadObject()->idPlan
                                                : FALSE;

            return $idPlan;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    /**
     * 
     * Obtengo informacion si un determinado plan objetivo esta asociado al plan 
     * de un funcionario
     * 
     */
    public function getPlanFuncionario( $idResponsable, $plnObjetivo )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 'DISTINCT t1.intId_pi AS idPlan' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->where( 't1.blnVigente_pi = 1' );
            $query->where( 't1.dteFechainicio_pi = "'. $plnObjetivo->fchInicio .'"' );
            $query->where( 't1.dteFechafin_pi = "'. $plnObjetivo->fchFin .'"' );
            $query->where( 't1.intId_tpoPlan = '. $plnObjetivo->idTpoPln );
            $query->where( 't1.intIdentidad_ent = ( SELECT tb.intIdentidad_ent AS idEntidadFuncionario 
                                                    FROM #__gen_ug_funcionario ta
                                                    JOIN #__gen_funcionario tb ON ta.intCodigo_fnc = tb.intCodigo_fnc
                                                    WHERE ta.intId_ugf = '. $idResponsable .' )' );
            
            $db->setQuery( (string) $query );
            $db->query();

            $idPlan = ( $db->getNumRows() > 0 ) ? $db->loadObject()->idPlan
                                                : FALSE;

            return $idPlan;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    public function getAPOF( $idPlan, $plnObjetivo )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 'intId_pln_objetivo AS idPlnObj' );
            $query->from( '#__pln_plan_objetivo t1' );
            $query->where( 't1.intId_pi = '. $idPlan );
            $query->where( 't1.intId_ob = '. $plnObjetivo->idObjetivo );

            $db->setQuery( (string) $query );
            $db->query();

            $idPlnObj = ( $db->getNumRows() > 0 )   ? $db->loadObject()->idPlnObj
                                                    : FALSE;

            return $idPlnObj;
        } catch ( Exception $e ) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_canastaproy.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
 
    
    public function updPlanObjetivo( $idPlanUpd, $ato )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->update( '#__pln_plan_objetivo' );
            $query->set( 'intId_pi = '. $idPlanUpd );
            $query->where( 'intId_pln_objetivo = '. $ato->idPlnObj );

            $db->setQuery( (string) $query );
            $db->query();

            return $db->getAffectedRows();
        } catch ( Exception $e ) {
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