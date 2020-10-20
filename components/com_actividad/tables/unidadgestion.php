<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class ActividadTableUnidadGestion extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db)
    {
        parent::__construct('#__gen_unidad_gestion', 'intCodigo_ug', $db);
    }

    public function registroPei($dtaPlan)
    {
        
        $propuesta["intId_pi"] = $dtaPlan->intId_pi;
        $propuesta["intId_tpoPlan"] = $dtaPlan->intId_tpoPlan;
        $propuesta["intCodigo_ins"] = $dtaPlan->intCodigo_ins;
        $propuesta["strDescripcion_pi"] = $dtaPlan->strDescripcion_pi;
        $propuesta["dteFechainicio_pi"] = $dtaPlan->dteFechainicio_pi;
        $propuesta["dteFechafin_pi"] = $dtaPlan->dteFechafin_pi;
        $propuesta["strAlias_pi"] = $dtaPlan->strAlias_pi;
        $propuesta["blnVigente_pi"] = $dtaPlan->blnVigente_pi;
        $propuesta["published"] = $dtaPlan->published;
        
        if (!$this->save($propuesta)) {
            echo $this->getError();
            exit;
        }

        return $this->intId_pi;
    }

    public function getLstUnidadesGestion($idInstitucion) {

        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intCodigo_ug, 
                            intIdentidad_ent, 
                            tb_intCodigo_ug, 
                            intCodigo_ins, 
                            strNombre_ug, 
                            strAlias_ug, 
                            published");
            $query->from( "#__gen_unidad_gestion" );
            $query->where( "intCodigo_ins = " . $idInstitucion );
            $query->where( "published = 1" );
            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_pei.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    public function getObjUG( $idUG, $idPadre ) {
        try {
        
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            
            $query = '(SELECT po.intId_ob AS intId_ob, oi.intIdentidad_ent AS idEntidad, po.intId_pln_objetivo AS idPlnObjetivo
                        FROM #__pln_plan_objetivo po
                        INNER JOIN #__pln_objetivo_institucion oi ON oi.intId_ob = po.intId_ob
                        INNER JOIN #__pln_plan_accion pa ON pa.intId_pln_objetivo = po.intId_pln_objetivo
                        INNER JOIN #__pln_ug_responsable ugr ON ugr.intId_plnAccion = pa.intId_plnAccion
                        WHERE ugr.intCodigo_ug = ' . $idUG . ' AND pa.published = 1 AND po.intId_pi = ' . $idPadre . ')
                        UNION DISTINCT
                        (SELECT oi.intId_ob AS intId_ob, oi.intIdentidad_ent AS idEntidad, po.intId_pln_objetivo AS idPlnObjetivo
                        FROM #__pln_objetivo_institucion oi
                        INNER JOIN #__pln_plan_objetivo po ON po.intId_ob = oi.intId_ob
                        INNER JOIN #__ind_indicador_entidad en ON en.intIdentidad_ent = oi.intIdentidad_ent
                        INNER JOIN #__ind_ug_responsable ugr ON ugr.intIdIndEntidad = en.intIdIndEntidad
                        WHERE ugr.intCodigo_ug = ' . $idUG . ' AND po.intId_pi = ' . $idPadre . ')';
            
            $db->setQuery( $query );
            $db->query();
            
            $result = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_pei.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    public function getAccionesUG( $idObj, $idUG, $idPadre ) {
         try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select('DISTINCT pa.intId_plnAccion');
            $query->from( "#__pln_plan_accion pa" );
            $query->innerJoin('#__pln_ug_responsable ugr ON ugr.intId_plnAccion = pa.intId_plnAccion');
            $query->innerJoin('#__pln_plan_objetivo po ON po.intId_pln_objetivo = pa.intId_pln_objetivo');
            $query->where( "po.intId_ob = " . $idObj );
            $query->where( "po.intId_pi = " . $idPadre );
            $query->where( "ugr.intCodigo_ug = " . $idUG );
            
            $db->setQuery( $query );
            $db->query();
            
            $result = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_pei.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    public function getAccionObjPoa($idAccionObjPoa) {
         try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 'pa.intId_plnAccion             AS idAccion, 
                            pa.intId_pln_objetivo           AS idPlnObjetivo, 
                            pa.intTpoActividad_plnAccion    AS idTipoAccion, 
                            tg.strNombre_tpg                AS descTipoActividad, 
                            pa.strDescripcion_plnAccion     AS descripcionAccion, 
                            pa.strObservacion_plnAccion     AS obserbacionAccion, 
                            pa.mnPresupuesto_plnAccion      AS presupuestoAccion, 
                            pa.dteFechaEjecucion_planAccion AS fechaEjecucionAccion, 
                            fr.intId_plnFR                  AS idAccionFR, 
                            fr.intId_ugf                    AS idFunResp, 
                            ugr.intId_plnUGR                AS idAccionUGR,
                            ugr.intCodigo_ug                AS idUniGes,
                            ugfu.intCodigo_ug               AS unidadGestionFun,
                            pa.published                    AS published'
            );
            $query->from( '#__pln_plan_accion as pa' );
            $query->innerJoin( '#__gen_tipo_gestion as tg ON pa.intTpoActividad_plnAccion = tg.intIdTipoGestion_tpg' );
            $query->innerJoin( '#__pln_funcionario_responsable as fr ON pa.intId_plnAccion = fr.intId_plnAccion' );
            $query->innerJoin( '#__pln_ug_responsable as ugr ON pa.intId_plnAccion = ugr.intId_plnAccion' );
            $query->innerJoin( '#__gen_ug_funcionario as ugfu ON ugfu.intId_ugf = fr.intId_ugf' );
            $query->where( 'pa.intId_plnAccion = ' . $idAccionObjPoa );
            $query->where( 'ugr.intVigencia_plnUGR = 1' );
            $query->where( 'fr.intVigencia_plnFR = 1' );
            $query->where( 'pa.published = 1' );
            $query->order( 'idAccion' );
            $db->setQuery( (string) $query );
            $db->query();

            $rstObjetivosPln = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : false;

            return $rstObjetivosPln;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
}