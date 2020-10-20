<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.tablenested' );

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class jTablePlnAccion extends JTable
{

    private $_lstAcciones;
    
    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__pln_plan_accion', 'intId_plnAccion', $db );
    }

    /**
     *  Resgistra en la base de datos una accion de un plan de accion de un objetivo
     * @param type $accion      Objeto accions con toda la data correspondiente
     * @return type
     */
    public function registroAccion( $accion )
    {
        $accionObj["intId_plnAccion"]           = ( (int)$accion->idAccion == 0 )
                                                        ? 0 
                                                        : $accion->idAccion;

        $accionObj["intTpoActividad_plnAccion"] = $accion->idTipoAccion;
        $accionObj["strDescripcion_plnAccion"]  = $accion->descripcionAccion;
        $accionObj["strObservacion_plnAccion"]  = $accion->observacionAccion;
        $accionObj["mnPresupuesto_plnAccion"]   = $accion->presupuestoAccion;
        $accionObj["dteFechaInicio_planAccion"] = $accion->fechaInicioAccion;
        $accionObj["dteFechaFin_planAccion"]    = $accion->fechaFinAccion;
        $accionObj["intIdPadre_plnAccion"]      = $accion->idPadreAccion;
        $accionObj["published"]                 = $accion->published;

        if( (int)$accion->idAccion == 0 ){
            $accionObj["dteFechaRegistro_plnAccion"] = date( "Y-m-d H:i:s" );
        }

        if( !$this->save( $accionObj ) ){
            echo 'error al guardar una acción de un objetivo';
            exit;
        }

        return $this->intId_plnAccion;
    }

    /**
     *  Devuelve una lista de acciones con sus respectivos responsables, de un determinado objetivo
     * @param type $idPlnObj       Id del objetivo (id del de la relacion entre el plan y el objetivo)
     * @return type
     */
    public function getPlanAccion( $idPlnObj )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 'DISTINCT 
                                pa.intId_plnAccion              AS idAccion, 
                                poa.intId_pln_objetivo          AS idPlnObjetivo, 
                                poa.intId_plnobj_accion         AS idPlnObjAccion, 
                                po.intIdentidad_ent             AS idEntObjetivo,
                                pa.intTpoActividad_plnAccion    AS idTipoAccion, 
                                tg.strNombre_tpg                AS descTipoActividad, 
                                pa.strDescripcion_plnAccion     AS descripcionAccion, 
                                pa.strObservacion_plnAccion     AS observacionAccion, 
                                pa.mnPresupuesto_plnAccion      AS presupuestoAccion, 
                                pa.dteFechaInicio_planAccion    AS fechaInicioAccion, 
                                pa.dteFechaFin_planAccion       AS fechaFinAccion, 
                                fr.dteFechaInicio_plnFR         AS fechaInicioFR, 
                                ugr.dteFechaInicio_plnUGR       AS fechaInicioUGR, 
                                ugfu.intCodigo_ug               AS unidadGestionFun,
                                fr.intId_plnFR                  AS idAccionFR, 
                                fr.intId_ugf                    AS idFunResp, 
                                ugr.intId_plnUGR                AS idAccionUGR,
                                ugr.intCodigo_ug                AS idUniGes,
                                pa.published                    AS published'
            );
            $query->from( '#__pln_plan_accion as pa' );
            $query->innerJoin( '#__pln_plnobj_accion as poa ON poa.intId_plnAccion = pa.intId_plnAccion' );
            $query->innerJoin( '#__pln_plan_objetivo AS po ON poa.intId_pln_objetivo = po.intId_pln_objetivo' );
            $query->innerJoin( '#__pln_plan_institucion AS pi ON po.intId_pi = pi.intId_pi' );
            $query->innerJoin( '#__gen_tipo_gestion as tg ON pa.intTpoActividad_plnAccion = tg.intIdTipoGestion_tpg' );
            $query->innerJoin( '#__pln_funcionario_responsable as fr ON pa.intId_plnAccion = fr.intId_plnAccion AND fr.intVigencia_plnFR = 1' );
            $query->innerJoin( '#__pln_ug_responsable as ugr ON pa.intId_plnAccion = ugr.intId_plnAccion AND ugr.intVigencia_plnUGR = 1' );
            $query->innerJoin( '#__gen_ug_funcionario as ugfu ON ugfu.intId_ugf = fr.intId_ugf' );
            
            $query->where( 'poa.intId_pln_objetivo = ' . $idPlnObj );            
            $query->where( 'pa.published = 1' );
            $query->group( 'idAccion' );

            $db->setQuery( ( string ) $query );
            $db->query();

            $rstObjetivosPln = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $rstObjetivosPln;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    
    
    /**
     *  Devuelve una lista de acciones con sus respectivos responsables, de un determinado objetivo
     * 
     *  @param type $idPlnObj       Id del objetivo (id del de la relacion entre el plan y el objetivo)
     *  @return type
     */
    public function getPlanAccionUG( $idPlnObj, $idEntUG )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   DISTINCT pa.intId_plnAccion    AS idAccion, 
                                poa.intId_pln_objetivo          AS idPlnObjetivo, 
                                poa.intId_plnobj_accion         AS idPlnObjAccion, 
                                pa.intTpoActividad_plnAccion    AS idTipoAccion, 
                                tg.strNombre_tpg                AS descTipoActividad, 
                                pa.strDescripcion_plnAccion     AS descripcionAccion, 
                                pa.strObservacion_plnAccion     AS observacionAccion, 
                                pa.mnPresupuesto_plnAccion      AS presupuestoAccion, 
                                pa.dteFechaInicio_planAccion    AS fechaInicioAccion, 
                                pa.dteFechaFin_planAccion       AS fechaFinAccion, 
                                fr.dteFechaInicio_plnFR         AS fechaInicioFR, 
                                ugr.dteFechaInicio_plnUGR       AS fechaInicioUGR, 
                                ugfu.intCodigo_ug               AS unidadGestionFun,
                                fr.intId_plnFR                  AS idAccionFR, 
                                fr.intId_ugf                    AS idFunResp, 
                                ugr.intId_plnUGR                AS idAccionUGR,
                                ugr.intCodigo_ug                AS idUniGes,
                                pa.published                    AS published' );
            $query->from( '#__pln_plan_accion as pa' );
            $query->innerJoin( '#__pln_plnobj_accion as poa ON poa.intId_plnAccion = pa.intId_plnAccion' );
            $query->innerJoin( '#__pln_plan_objetivo AS po ON poa.intId_pln_objetivo = po.intId_pln_objetivo' );
            $query->innerJoin( '#__pln_plan_institucion AS pi ON po.intId_pi = pi.intId_pi AND pi.blnVigente_pi = 1' );
            $query->innerJoin( '#__gen_tipo_gestion as tg ON pa.intTpoActividad_plnAccion = tg.intIdTipoGestion_tpg' );
            $query->innerJoin( '#__pln_funcionario_responsable as fr ON pa.intId_plnAccion = fr.intId_plnAccion' );
            $query->innerJoin( '#__pln_ug_responsable as ugr ON pa.intId_plnAccion = ugr.intId_plnAccion AND ugr.intVigencia_plnUGR = 1' );
            $query->innerJoin( '#__gen_ug_funcionario as ugfu ON ugfu.intId_ugf = fr.intId_ugf' );
            $query->innerJoin( '#__gen_unidad_gestion t11 ON ugr.intCodigo_ug = t11.intCodigo_ug' );
            $query->where( 'poa.intId_pln_objetivo = ' . $idPlnObj );
            $query->where( 'ugr.intVigencia_plnUGR = 1' );
            $query->where( 'fr.intVigencia_plnFR = 1' );
            $query->where( 'pa.published = 1' );
            $query->where( 't11.intIdentidad_ent = '. $idEntUG );
            $query->group( 'idAccion' );
            
            $db->setQuery( ( string ) $query );
            $db->query();

            $rstObjetivosPln = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $rstObjetivosPln;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *  Devuelve una lista de acciones con sus respectivos responsables, de un determinado objetivo
     * @param type $idObj       Id del objetivo (id del objetivo operativo "progrmas, proyectos, contratos")
     * @return type
     */
    public function getPlanAccionOperativo( $idObj )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   DISTINCT pa.intId_plnAccion    AS idAccion, 
                                paoo.intId_plnAcc_objOpr        AS idPlnObjAccion, 
                                pa.intTpoActividad_plnAccion    AS idTipoAccion, 
                                tg.strNombre_tpg                AS descTipoActividad, 
                                pa.strDescripcion_plnAccion     AS descripcionAccion, 
                                pa.strObservacion_plnAccion     AS obserbacionAccion, 
                                pa.mnPresupuesto_plnAccion      AS presupuestoAccion, 
                                pa.dteFechaInicio_planAccion    AS fechaInicioAccion, 
                                pa.dteFechaFin_planAccion       AS fechaFinAccion, 
                                fr.dteFechaInicio_plnFR         AS fechaInicioFR, 
                                ugr.dteFechaInicio_plnUGR       AS fechaInicioUGR, 
                                fr.intId_plnFR                  AS idAccionFR, 
                                fr.intId_ugf                    AS idFunResp, 
                                ugr.intId_plnUGR                AS idAccionUGR,
                                ugr.intCodigo_ug                AS idUniGes,
                                ugfu.intCodigo_ug               AS unidadGestionFun,
                                pa.published                    AS published' );
            $query->from( '#__pln_plan_accion as pa' );
            $query->innerJoin( '#__gen_pln_accion_obj_operativo as paoo ON paoo.intId_plnAccion = pa.intId_plnAccion' );
            $query->innerJoin( '#__gen_tipo_gestion as tg ON pa.intTpoActividad_plnAccion = tg.intIdTipoGestion_tpg' );
            $query->innerJoin( '#__pln_funcionario_responsable as fr ON pa.intId_plnAccion = fr.intId_plnAccion' );
            $query->innerJoin( '#__pln_ug_responsable as ugr ON pa.intId_plnAccion = ugr.intId_plnAccion' );
            $query->innerJoin( '#__gen_ug_funcionario as ugfu ON ugfu.intId_ugf = fr.intId_ugf' );
            $query->where( 'paoo.intIdObjetivo_operativo = ' . $idObj );
            $query->where( 'ugr.intVigencia_plnUGR = 1' );
            $query->where( 'fr.intVigencia_plnFR = 1' );
            $query->where( 'pa.published = 1' );
            $query->group( 'idAccion' );

            $db->setQuery( ( string ) $query );
            $db->query();

            $rstObjetivosPln = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $rstObjetivosPln;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * Elimina de forma logica un accion del plan de accion de un objetivo
     * 
     * @param type $idAcc
     * @return type
     */
    public function deleteAccion( $idAcc )
    {
        $db = & JFactory::getDBO();
        try{

            $db->transactionStart();

            $query = $db->getQuery( TRUE );
            $query->update( "#__pln_plan_accion" );
            $query->set( 'published = 0' );
            $query->where( "intId_plnAccion=" . $idAcc );
            $db->setQuery( $query );

            $result = ($db->query()) ? true : false;

            $db->transactionCommit();

            return $result;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );

            JErrorPage::render( $e );
        }
    }

    public function delAccionesPoa( $idPoa, $idAcc )
    {
        $db = & JFactory::getDBO();
        try{
            $db->transactionStart();
            $query = $db->getQuery( TRUE );

            $sql = "DELETE poa
                    FROM #__pln_plnobj_accion poa
                    INNER JOIN #__pln_plan_objetivo po ON po.intId_pln_objetivo = poa.intId_pln_objetivo
                    WHERE poa.intId_plnAccion = {$idAcc} AND po.intId_pi = {$idPoa}";

            $db->setQuery( $sql );
            $result = ($db->query()) ? true : false;

            $db->transactionCommit();

            return $result;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );

            JErrorPage::render( $e );
        }
    }

    public function updDateAccionesPoa( $idAcc, $fecha, $idUG )
    {
        $db = & JFactory::getDBO();

        try{
            $db->transactionStart();
            $query = $db->getQuery( TRUE );

            $query->update( "#__pln_ug_responsable" );
            $query->set( array("intVigencia_plnUGR = 0", "dteFechaFin_plnUGR = DATE_SUB('{$fecha}', INTERVAL 1 DAY)") );
            $query->where( "intId_plnAccion = {$idAcc}" );
            $query->where( "dteFechaInicio_plnUGR <= '{$fecha}'" );
            $query->where( "intVigencia_plnUGR = 1" );
            $query->where( "intCodigo_ug = {$idUG}" );

            $db->setQuery( $query );

            $result = ($db->query()) ? true : false;

            $db->transactionCommit();

            return $result;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );

            JErrorPage::render( $e );
        }
    }

    public function delAccionPoas( $idAccion )
    {
        $db = & JFactory::getDBO();
        try{
            $db->transactionStart();
            $query = $db->getQuery( TRUE );

            $query->delete( '#__pln_plan_accion' );
            $query->where( 'intId_plnAccion = '. $idAccion );

            $db->setQuery( $query );
            $result = ( $db->query() )  ? true 
                                        : false;

            $db->transactionCommit();

            return $result;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );

            JErrorPage::render( $e );
        }
    }

    public function deleteUnidadGestion( $idAccionUGR )
    {
        $db = & JFactory::getDBO();
        try{
            $db->transactionStart();
            $query = $db->getQuery( TRUE );

            $query->update( "#__pln_ug_responsable" );
            $query->set( "published = 0, intVigencia_plnUGR = 0" );
            $query->where( "intId_plnUGR = {$idAccionUGR}" );

            $db->setQuery( $query );
            $result = ( $db->query() )  ? true 
                                        : false;

            $db->transactionCommit();

            return $result;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );

            JErrorPage::render( $e );
        }
    }

    /**
     * 
     * Retorna informacion de una determinada accion
     * 
     * @param Int $idAccion     Identificador de la Accion
     * 
     * @return Object           Datos de la accion, en caso de no existir 
     *                          informacion retorna FALSE
     * 
     */
    private function _getDtaAccion( $idAccion )
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.mnPresupuesto_plnAccion      AS presupuesto, 
                                t1.dteFechaInicio_planAccion    AS fchInicio, 
                                t1.dteFechaFin_planAccion       AS fchFin,
                                t2.intCodigo_ug                 AS idUGResponsable,
                                t3.intId_ugf                    AS idFuncionario ' );
            $query->from( '#__pln_plan_accion t1 ' );
            $query->join( 'INNER', '#__pln_ug_responsable t2 ON t1.intId_plnAccion = t2.intId_plnAccion ' );
            $query->join( 'INNER', '#__pln_funcionario_responsable t3 ON t1.intId_plnAccion = t3.intId_plnAccion ' );
            $query->where( 't1.intId_plnAccion = ' . $idAccion );

            $db->setQuery( ( string ) $query );
            $db->query();

            $rst = ( $db->getNumRows() > 0 )? $db->loadObject() 
                                            : false;

            return $rst;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Verifico si a existido un cambio en las fechas de registradas en las 
     * acciones
     * 
     * @param int $idAccion         Identificador de la accion
     * @param Object $dtaAccion     Datos de la accion a registrar
     * 
     * @return int  Bandera de cambio
     *              0: Sin Cambio
     *              1: Cambio en la fecha de Fin de la Accion
     *                  1.1: Fecha Registrada es MENOR a la Nueva Fecha
     *                  1.2: Fecha Registrada es MAYOR a la Nueva Fecha
     * 
     *              2: Cambio en el Presupuesto de la accion
     *                  2.1: Presupuesto Registrado es MENOR al Nuevo Presupuesto
     *                  2.2: Presupuesto Registrado es MAYOR al Nuevo Presupuesto
     * 
     *              3: Cambio de Unidad de Gestion Responsable
     * 
     *              4: Cambio de Funcionario Responsable
     * 
     *              5: Cambio en Fecha como en Presupuesto
     * 
     */
    public function revisionCambios( $idAccion, $dtaAccion )
    {
        $banCambio = 0;

        $dtaAR = $this->_getDtaAccion( $idAccion );

        if( $dtaAR ){

            $fchRegistro = new DateTime( $dtaAR->fchFin );
            $fchNueva = new DateTime( $dtaAccion->fechaFinAccion );

            //  Cambio en la fecha de Fin de la Accion
            if( $fchRegistro != $fchNueva ){
                $banCambio = 1;
            }

            //  Cambio en el Presupuesto de la accion
            if( $dtaAR->presupuesto != $dtaAccion->presupuestoAccion ){
                $banCambio = 2;
            }

            //  Cambio de UG Responsable
            if( $dtaAR->idUGResponsable != $dtaAccion->idAccionUGR ){
                $banCambio = 3;
            }

            //  Cambio de Funcionario Responsable
            if( $dtaAR->idFuncionario != $dtaAccion->idFunResp ){
                $banCambio = 4;
            }

            //  Cambio en Fecha como en Presupuesto
            if( $fchRegistro != $fchNueva && $dtaAR->presupuesto != $dtaAccion->presupuestoAccion ){
                $banCambio = 5;
            }
        }

        return $banCambio;
    }


    public function eliminarAccionPlan( $idPlnObjetivo )
    {
        $this->_lstAcciones = $this->_getLstPlnAccion( $idPlnObjetivo );

        $this->_deleteAccionPlanObjetivo( $idPlnObjetivo );

        $this->_deleteFuncionarioResponsable();

        $this->_deleteUGResponsable();

        $this->_deleteAccion();

        return;
    }
    
    
    private function _getLstPlnAccion( $idPlnObjetivo )
    {
        $db = JFactory::getDBO();

        try{
            $query = $db->getQuery( true );

            $query->select( 't1.intId_plnAccion AS idAccion' );
            $query->from( '#__pln_plnobj_accion t1' );
            $query->where( 't1.intId_pln_objetivo = '. $idPlnObjetivo );

            $db->setQuery( ( string ) $query );
            $db->query();

            $rst = ( $db->getNumRows() >= 0 )   ? $db->loadObjectList()
                                                : array();

            return $rst;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );

            JErrorPage::render($e);
        }
    }
    
    
    private function _deleteAccionPlanObjetivo( $idPlnObjetivo )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();
            $query = $db->getQuery( true );

            $query->delete( '#__pln_plnobj_accion' );
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
    
    private function _deleteFuncionarioResponsable()
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();
            $query = $db->getQuery( true );
            
            foreach( $this->_lstAcciones as $accion ){
                $query->delete( '#__pln_funcionario_responsable' );
                $query->where( 'intId_plnAccion = '. $accion->idAccion );

                $db->setQuery( ( string ) $query );
                $db->query();
            }

            $db->transactionCommit();

            return;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );

            JErrorPage::render($e);
        }
    }
    
    private function _deleteUGResponsable()
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();
            $query = $db->getQuery( true );
            
            foreach( $this->_lstAcciones as $accion ){
                $query->delete( '#__pln_ug_responsable' );
                $query->where( 'intId_plnAccion = '. $accion->idAccion );

                $db->setQuery( ( string ) $query );
                $db->query();
            }

            $db->transactionCommit();

            return;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );

            JErrorPage::render($e);
        }
    }
    
    private function _deleteAccion()
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();
            $query = $db->getQuery( true );
            
            foreach( $this->_lstAcciones as $accion ){
                $query->delete( '#__pln_plan_accion' );
                $query->where( 'intId_plnAccion = '. $accion->idAccion );

                $db->setQuery( ( string ) $query );
                $db->query();
            }

            $db->transactionCommit();

            return;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );

            JErrorPage::render($e);
        }
    }
    
    
    
    public function getAccionesHijas( $idEntObjetivo, $idAccion )
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intId_pln_objetivo   AS idPlanObjetivo, 
                                t1.intIdentidad_ent     AS idEntObjetivo,
                                t2.intId_plnAccion      AS idAccion, 
                                t3.intIdPadre_plnAccion AS idPadreAccion' );
            $query->from( '#__pln_plan_objetivo t1' );
            $query->join( 'INNER', '#__pln_plnobj_accion t2 ON t1.intId_pln_objetivo = t2.intId_pln_objetivo' );
            $query->join( 'INNER', '#__pln_plan_accion t3 ON t2.intId_plnAccion = t3.intId_plnAccion' );
            $query->where( 't1.intIdPadre = '. $idEntObjetivo );
            $query->where( 't3.intIdPadre_plnAccion = '. $idAccion );
            
            $db->setQuery( ( string ) $query );
            $db->query();

            $rst = ( $db->getNumRows() > 0 )? $db->loadObjectList() 
                                            : false;

            return $rst;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    
    
    public function getATO( $idEntObjetivo, $dtaAccion )
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intId_pln_objetivo   AS idPlnObj,
                                t1.intId_pi             AS idPlan,
                                t1.intId_ob             AS idObj,
                                t1.intIdentidad_ent     AS idEntObj,
                                t2.intId_tpoPlan        AS idTpoPlan,
                                t2.dteFechainicio_pi    AS fchInicio,
                                t2.dteFechafin_pi       AS fchFin' );
            $query->from( '#__pln_plan_objetivo t1' );
            $query->join( 'INNER', '#__pln_plan_institucion t2 ON t1.intId_pi = t2.intId_pi AND t2.blnVigente_pi = 1' );
            $query->where( 't1.intIdPadre = '. $idEntObjetivo );
            $query->where( 't2.dteFechainicio_pi >= "'. $dtaAccion->fechaInicioAccion .'"' );

            $db->setQuery( ( string ) $query );
            $db->query();

            $rst = ( $db->getNumRows() > 0 )? $db->loadObjectList() 
                                            : false;

            return $rst;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    
    public function __destruct()
    {
        return;
    }
    
}
