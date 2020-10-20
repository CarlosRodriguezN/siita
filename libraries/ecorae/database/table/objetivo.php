<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
//jimport( 'joomla.database.table' );
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class jTableObjetivo extends JTable
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
            echo $this->getError();
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
            $log = &JLog::getInstance( 'librares.tables.objetivo.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  
     *  Elimina un registro de la tabla de Objetivos
     * 
     *  @param int $idObjetivo       Id del Objetivo
     * 
     *  @return type
     * 
     */
    public function eliminadoLogicoObjetivo( $idObjetivo )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->update( "#__pln_objetivo_institucion" );
            $query->set( "published = 0" );
            $query->where( "intId_ob = " . $idObjetivo );

            $db->setQuery( $query );
            $db->query();

            $result = ( $db->getAffectedRows() > 0 )? true 
                                                    : false;

            return $result;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'librares.tables.objetivo.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Elimina el registro de un objetivo en la tabla para la 
     * alineacion interna de la institucion
     * 
     * @param type $idObj        Id del Obejtivo
     * @param type $tpoEnt       Id del tipo de entidad
     * @return type
     */
    public function deleteObjEnt( $idObj, $tpoEnt )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );
            $query->update( "#__gen_objetivo_entidad" );
            $query->set( "published = 0" );
            $query->where( "intId_objetivo = " . $idObj );
            $query->where( "intId_tpoEntidad = " . $tpoEnt );
            $db->setQuery( $query );
            $db->query();
            $result = ($db->getAffectedRows() > 0) ? true : false;

            return $result;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'librares.tables.objetivo.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Ejecuta la eliminacion de un registro de un objetivo
     * @param type $idObj
     * @return type
     */
    public function eliminarRegistroObj( $idObj )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );
            $query->delete( "#__pln_objetivo_institucion" );
            $query->where( "intId_ob = " . $idObj );
            $db->setQuery( $query );
            $db->query();
            $result = ($db->getAffectedRows() > 0) ? true : false;
            return $result;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'librares.tables.objetivo.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Elimina fisicamente el registro de un objetivo en la tabla para la 
     * alineacion interna de la institucion
     * @param type $idObj        Id del Obejtivo
     * @param type $tpoEnt       Id del tipo de entidad
     * @return type
     */
    public function eliminarRegistroObjEnt( $idObj, $tpoEnt )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );
            $query->delete( "#__gen_objetivo_entidad" );
            $query->where( "intId_objetivo = " . $idObj );
            $query->where( "intId_tpoEntidad = " . $tpoEnt );
            $db->setQuery( $query );
            $db->query();
            $result = ($db->getAffectedRows() > 0) ? true : false;
            return $result;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'librares.tables.objetivo.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * Lista los Objetivos de un determinado Plan
     * @param type $idPlan   Identificador del Plan
     * @return type
     */
    public function getLstObjetivos( $idPlan, $idEntFnc, $idTpoPln, $fchInicioPln = null, $fchFinPln = null )
    {
        try {
            $rstObjetivosPln = array();
            if( $idPlan ){
                $db = JFactory::getDBO();
                $query = $db->getQuery( true );

                $query->select( '   pi.intId_pi					AS idPlan, 
                                    pi.dteFechainicio_pi        AS fchInicioPlan, 
                                    pi.dteFechafin_pi           AS fchFinPlan,
                                    oi.intId_ob                 AS idObjetivo,
                                    po.intIdentidad_ent         AS idEntidad,
                                    po.intId_pln_objetivo       AS idPlnObjetivo,
                                    oi.intIdPrioridad_pr        AS idPrioridadObj, 
                                    oi.intId_tpoObj             AS idTpoObj, 
                                    oi.intIdPadre_ob            AS idPadreObj, 
                                    oi.strDescripcion_ob        AS descObjetivo, 
                                    tpob.strDescripcion_tpoObj  AS descTpoObj, 
                                    pr.strNombre_pr             AS nmbPrioridadObj, 
                                    oi.dteFecharegistro_ob      AS fchRegistroObj,
                                    oi.published                AS published' );
                $query->from( '#__pln_objetivo_institucion oi' );
                $query->join( 'INNER', '#__pln_plan_objetivo as po ON po.intId_ob = oi.intId_ob' );
                $query->join( 'INNER', '#__gen_prioridad as pr ON pr.intIdPrioridad_pr = oi.intIdPrioridad_pr' );
                $query->join( 'INNER', '#__pln_tipo_objetivo as tpob ON tpob.intId_tpoObj = oi.intId_tpoObj' );
                $query->join( 'INNER', '#__pln_plan_institucion pi ON pi.intId_pi = po.intId_pi' );
                $query->where( 'pi.intId_pi = ' . $idPlan );
                $query->where( 'oi.published = 1' );

                $db->setQuery( (string) $query );
                $db->query();

                $rstObjetivosPln = ( $db->getNumRows() > 0 )? $db->loadObjectList() 
                                                            : array();
            }
            return $rstObjetivosPln;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'librares.tables.objetivo.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Retorna True si el plan tiene alguna relacion existente sino retorna False
     * @param type $idPlan
     * @return type
     */
    public function getRegsRelacionPln( $idPlan )
    {
        try {
            $rstObjetivosPln = array();
            if( $idPlan ){
                $db = JFactory::getDBO();
                $query = $db->getQuery( true );

                $query->select( 'oi.intId_ob AS idObjetivo' );
                $query->from( '#__pln_objetivo_institucion oi' );
                $query->innerJoin( '#__pln_plan_objetivo po ON po.intId_ob = oi.intId_ob' );
                $query->where( 'po.intId_pi = ' . $idPlan );
                
                $db->setQuery( (string) $query );
                $db->query();

                $rstObjetivosPln = ( $db->getNumRows() > 0 ) ? true : false;
            }
            return $rstObjetivosPln;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'librares.tables.objetivo.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Obtengo Informacion de Objetivos con sus respectivos Indicadores y 
     * Acciones de una determinada Unidad de Gestion
     * 
     * @param type $idUndGestion    Identificador de Unidad de Gestion
     * @param type $idPei           Identificador del Plan
     * @param type $idTpoPln        Identificador del Tipo de Plan
     * 
     * @return type
     * 
     */
    public function getOEIxUGxAcc( $idUndGestion, $idPln, $idTpoPln = 3 )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            
            $sql = 'SELECT  DISTINCT t4.intId_pi    AS idPlan,
                            t4.intIdPadre_pi        AS idPadre,
                            t1.intId_ob             AS idObjetivo, 
                            t1.intId_pln_objetivo   AS idAccion,
                            t1.intIdentidad_ent     AS idEntidad,
                            t4.dteFechainicio_pi    AS fchInicio, 
                            t4.dteFechafin_pi       AS fchFin
                    FROM #__pln_plan_objetivo t1
                    JOIN #__ind_indicador_entidad t2 ON t1.intIdentidad_ent = t2.intIdentidad_ent
                    JOIN #__ind_ug_responsable t3 ON t2.intIdIndEntidad = t3.intIdIndEntidad
                    JOIN #__pln_plan_institucion t4 ON t4.intId_pi = t1.intId_pi
                    WHERE t3.intCodigo_ug = '. $idUndGestion .' 
                    AND t4.intIdPadre_pi = '. $idPln .'
                    AND t4.intId_tpoPlan = '. $idTpoPln .'

                    UNION

                    SELECT  DISTINCT t2.intId_pi    AS idPlan,
                            t2.intIdPadre_pi        AS idPadre,
                            t1.intId_ob             AS idObjetivo, 
                            t1.intId_pln_objetivo   AS idAccion,
                            t1.intIdentidad_ent     AS idEntidad,
                            t2.dteFechainicio_pi    AS fchInicio, 
                            t2.dteFechafin_pi       AS fchFin
                    FROM #__pln_plan_objetivo t1
                    JOIN #__pln_plan_institucion t2 ON t1.intId_pi = t2.intId_pi
                    JOIN #__pln_plnobj_accion t3 ON t3.intId_pln_objetivo = t1.intId_pln_objetivo
                    JOIN #__pln_plan_accion t4 ON t4.intId_plnAccion = t3.intId_plnAccion
                    JOIN #__pln_ug_responsable t5 ON t4.intId_plnAccion = t5.intId_plnAccion
                    WHERE t5.intCodigo_ug = '. $idUndGestion .' 
                    AND t2.intIdPadre_pi = '. $idPln .'
                    AND t2.intId_tpoPlan = '. $idTpoPln;
            
            $db->setQuery( $sql );
            $db->query();

            $result = ($db->getAffectedRows() > 0)  ? $db->loadObjectList() 
                                                    : array();

            return $result;

        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('librares.tables.objetivo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * Obtengo Informacion de Objetivos con sus respectivos Indicadores y 
     * Acciones de una determinada Unidad de Gestion
     * 
     * @param type $idFuncionario   Identificador de Unidad de Gestion
     * @param type $idPlan          Identificador del Plan
     * @param type $idTpoPln        Identificador del Tipo de Plan
     * 
     * @return type
     * 
     */
    public function getOEIxFuncionarioxAcc( $idFuncionario, $idPlan, $idTpoPln = 3 )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            
            $sql = 'SELECT  DISTINCT t4.intId_pi    AS idPlan,
                            t4.intIdPadre_pi        AS idPadre,
                            t1.intId_ob             AS idObjetivo, 
                            t1.intId_pln_objetivo   AS idAccion,
                            t1.intIdentidad_ent     AS idEntidad,
                            t4.dteFechainicio_pi    AS fchInicio, 
                            t4.dteFechafin_pi       AS fchFin
                    FROM #__pln_plan_objetivo t1
                    JOIN #__ind_indicador_entidad t2 ON t1.intIdentidad_ent = t2.intIdentidad_ent
                    JOIN #__ind_funcionario_responsable t3 ON t2.intIdIndEntidad = t3.intIdIndEntidad AND t3.intVigencia_fgr = 1
                    JOIN #__pln_plan_institucion t4 ON t4.intId_pi = t1.intId_pi
                    WHERE t3.intId_ugf = '. $idFuncionario .' 
                    AND t4.intIdPadre_pi = '. $idPlan .'
                    AND t4.intId_tpoPlan = '. $idTpoPln .'
                    
                    UNION

                    SELECT  DISTINCT t2.intId_pi    AS idPlan,
                            t2.intIdPadre_pi        AS idPadre,
                            t1.intId_ob             AS idObjetivo, 
                            t1.intId_pln_objetivo   AS idAccion,
                            t1.intIdentidad_ent     AS idEntidad,
                            t2.dteFechainicio_pi    AS fchInicio, 
                            t2.dteFechafin_pi       AS fchFin
                    FROM #__pln_plan_objetivo t1
                    JOIN #__pln_plan_institucion t2 ON t1.intId_pi = t2.intId_pi
                    JOIN #__pln_plnobj_accion t3 ON t3.intId_pln_objetivo = t1.intId_pln_objetivo
                    JOIN #__pln_plan_accion t4 ON t4.intId_plnAccion = t3.intId_plnAccion
                    JOIN #__pln_funcionario_responsable t5 ON t4.intId_plnAccion = t5.intId_plnAccion
                    WHERE t5.intId_ugf = '. $idFuncionario .' 
                    AND t2.intIdPadre_pi = '. $idPlan .'
                    AND t2.intId_tpoPlan = '. $idTpoPln;

            $db->setQuery( $sql );
            $db->query();

            $result = ($db->getAffectedRows() > 0)  ? $db->loadObjectList() 
                                                    : array();

            return $result;

        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('librares.tables.objetivo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * 
     * Retorna informacion general de un determinado Objetivo
     * 
     * @param type $idObjetivo
     */
    public function getDtaObjetivo( $idObjetivo )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intId_ob                 AS idObjetivo, 
                                t1.strDescripcion_ob        AS descObjetivo' );
            $query->from( ' #__pln_objetivo_institucion t1' );
            $query->where( 't1.intId_ob = '. $idObjetivo );

            $db->setQuery( (string) $query );
            $db->query();
            
            $rstObjetivosPln = ( $db->getNumRows() > 0 )? $db->loadObject() 
                                                        : array();

            return $rstObjetivosPln;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'librares.tables.objetivo.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Registro un nuevo objetivo
     * 
     * @param Object $objetivo  
     * 
     * @return type
     */
    public function registroObjetivo( $objetivo )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intId_ob                 AS idObjetivo, 
                                t1.strDescripcion_ob        AS descObjetivo' );
            $query->from( ' #__pln_objetivo_institucion t1' );
            $query->where( 't1.intId_ob = '. $idObjetivo );
            
            $db->setQuery( (string) $query );
            $db->query();
            
            $rstObjetivosPln = ( $db->getNumRows() > 0 )? $db->loadObject() 
                                                        : array();

            return $rstObjetivosPln;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'librares.tables.objetivo.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Existe objetivo
     * 
     * @param type $descObjetivo    Descripcion del objetivo
     * 
     * @return type
     * 
     */
    public function existeObjetivo( $descObjetivo )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intId_ob AS idObjetivo' );
            $query->from( ' #__pln_objetivo_institucion t1' );
            $query->where( 't1.strDescripcion_ob = "'. trim( $descObjetivo ) .'"' );
            
            $db->setQuery( (string) $query );
            $db->query();
            
            $objetivo = ( $db->getNumRows() > 0 )   ? $db->loadObject() 
                                                    : false;

            $rst = ( $objetivo )? $objetivo->idObjetivo
                                : 0;
            
            return $rst;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'librares.tables.objetivo.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Retorna True si el Objetivo tiene alguna relacion existente con 
     *  indicadores si no retorna False
     * 
     *  @param type $idObj  Identificador del Objetivo
     *  @return type
     * 
     */
    public function getRegsObjInds( $idObj )
    {
        try {
            $rstObjetivosPln = array();
            if( $idObj ){
                $db = JFactory::getDBO();
                $query = $db->getQuery( true );
                
                $query->select( 't1.intIdIndEntidad AS idEntInd' );
                $query->from( '#__ind_indicador_entidad t1' );
                $query->innerJoin( '#__pln_plan_objetivo t2 ON t1.intIdentidad_ent = t2.intIdentidad_ent' );
                $query->where( 't2.intId_ob = ' . $idObj );
                
                $db->setQuery( (string) $query );
                $db->query();
                
                $rstObjetivosPln = ( $db->getNumRows() > 0 )? true 
                                                            : false;
            }

            return $rstObjetivosPln;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'librares.tables.objetivo.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Retorna True si el Objetivo tiene alguna relacion existente con 
     *  Actividades si no retorna False
     * @param type $idObj
     * @return type
     */
    public function getRegsObjActs( $idObj )
    {
        try {
            $rstObjetivosPln = array();
            if( $idObj ){
                $db = JFactory::getDBO();
                $query = $db->getQuery( true );
                $query->select( 't1.intIdActividad_act AS idActividad' );
                $query->from( '#__pln_actividad t1' );
                $query->innerJoin( '#__pln_plan_objetivo t2 ON t1.intId_pln_objetivo = t2.intId_pln_objetivo' );
                $query->where( 't2.intId_ob = ' . $idObj );
                $db->setQuery( (string) $query );
                $db->query();
                $rstObjetivosPln = ( $db->getNumRows() > 0 ) ? true : false;
            }
            return $rstObjetivosPln;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'librares.tables.objetivo.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Retorna True si el Objetivo tiene alguna relacion existente con 
     *  Acciones si no retorna False
     * @param type $idObj
     * @return type
     */
    public function getRegsObjAccs( $idObj )
    {
        try {
            $rstObjetivosPln = array();
            if( $idObj ){
                $db = JFactory::getDBO();
                $query = $db->getQuery( true );
                $query->select( 't1.intId_plnAccion AS idAccion' );
                $query->from( '#__pln_plan_accion t1' );
                $query->innerJoin( '#__pln_plnobj_accion t2 ON t1.intId_plnAccion = t2.intId_plnAccion' );
                $query->innerJoin( '#__pln_plan_objetivo t3 ON t2.intId_pln_objetivo = t3.intId_pln_objetivo' );
                $query->where( 't3.intId_ob = ' . $idObj );
                $db->setQuery( (string) $query );
                $db->query();
                $rstObjetivosPln = ( $db->getNumRows() > 0 ) ? true : false;
            }
            return $rstObjetivosPln;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'librares.tables.objetivo.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
}