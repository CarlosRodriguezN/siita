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
class PeiTablePlnAccion extends JTable
{

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

    public function getAccionObj($idAccion) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            
            $query->select('pa.intId_plnAccion              AS idAccion, 
                            pa.intId_pln_objetivo           AS idPlnObjetivo, 
                            pa.intTpoActividad_plnAccion    AS idTipoAccion, 
                            tg.strNombre_tpg                AS descTipoActividad, 
                            pa.strDescripcion_plnAccion     AS descripcionAccion, 
                            pa.strObservacion_plnAccion     AS obserbacionAccion, 
                            pa.mnPresupuesto_plnAccion      AS presupuestoAccion, 
                            pa.dteFechaInicio_planAccion    AS fechaInicioAccion, 
                            pa.dteFechaFin_planAccion       AS fechaFinAccion, 
                            fr.intId_plnFR                  AS idAccionFR, 
                            fr.intId_ugf                    AS idFunResp, 
                            ugr.intId_plnUGR                AS idAccionUGR,
                            ugr.intCodigo_ug                AS idUniGes,
                            ugfu.intCodigo_ug               AS unidadGestionFun,
                            pa.published                    AS published'
            );
            $query->from('#__pln_plan_accion as pa');
            $query->innerJoin('#__gen_tipo_gestion as tg ON pa.intTpoActividad_plnAccion = tg.intIdTipoGestion_tpg');
            $query->innerJoin('#__pln_funcionario_responsable as fr ON pa.intId_plnAccion = fr.intId_plnAccion');
            $query->innerJoin('#__pln_ug_responsable as ugr ON pa.intId_plnAccion = ugr.intId_plnAccion');
            $query->innerJoin('#__gen_ug_funcionario as ugfu ON ugfu.intId_ugf = fr.intId_ugf');
            $query->where('pa.intId_plnAccion = ' . $idAccion);
            $query->where('ugr.intVigencia_plnUGR = 1');
            $query->where('fr.intVigencia_plnFR = 1');
            $query->where('pa.published = 1');
            $query->order('idAccion');
            $db->setQuery((string) $query);
            $db->query();

            $rstObjetivosPln = ( $db->getNumRows() > 0 ) ? $db->loadObject() : false;

            return $rstObjetivosPln;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_pei.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function registroAccionObj( $accion ) {

        $accionObj["intId_plnAccion"] = $accion->idAccion;
        $accionObj["intId_pln_objetivo"] = $accion->idPlnObjetivo;
        $accionObj["intTpoActividad_plnAccion"] = $accion->idTipoAccion;
        $accionObj["strDescripcion_plnAccion"] = $accion->descripcionAccion;
        $accionObj["strObservacion_plnAccion"] = $accion->obserbacionAccion;
        $accionObj["mnPresupuesto_plnAccion"] = $accion->presupuestoAccion;
        $accionObj["dteFechaInicio_planAccion"] = $accion->fechaInicioAccion;
        $accionObj["dteFechaFin_planAccion"] = $accion->fechaFinAccion;
        $accionObj["published"] = $accion->published;
        $accionObj["dteFechaRegistro_plnAccion"] = date("Y-m-d H:i:s");

        if (!$this->save($accionObj)) {
            echo 'error al guardar una acción de un objetivo';
            exit;
        }

        return $this->intId_plnAccion;
    }

    public function getUndGesRep($objAccionObj) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('*');
            $query->from('#__pln_ug_responsable');
            $query->where('intId_plnUGR = ' . $objAccionObj->idAccionUGR );
            $db->setQuery((string) $query);
            $db->query();

            $rstUGR = ( $db->getNumRows() > 0 ) ? $db->loadObject(): false;

            return $rstUGR;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_pei.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    
    public function registroUndGesRep( $poaUGR, $idPlanAccionObj ) {
        try{
            
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            
            $query->insert("#__pln_ug_responsable");
            $query->columns('intId_plnUGR, 
                            intId_plnAccion, 
                            intCodigo_ug, 
                            dteFechaInicio_plnUGR, 
                            dteFechaFin_plnUGR, 
                            intVigencia_plnUGR');
            $query->values("0, " . $idPlanAccionObj . ", " . $poaUGR->intCodigo_ug . ", '" . $poaUGR->dteFechaInicio_plnUGR . "', '" . $poaUGR->dteFechaFin_plnUGR . "', " . $poaUGR->intVigencia_plnUGR);
            $db->setQuery($query);

            $result = ($db->query()) ? true : false;
            
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_pei.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    

    public function getFunRep( $objAccionObj ) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('*');
            $query->from('#__pln_funcionario_responsable');
            $query->where('intId_plnFR = ' . $objAccionObj->idAccionFR );
            $db->setQuery((string) $query);
            $db->query();

            $rstFR = ( $db->getNumRows() > 0 ) ? $db->loadObject(): false;

            return $rstFR;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_pei.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    public function registroFunRep( $poaFR, $idPlanAccionObj ) {
         try{
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            
            $query->insert("#__pln_funcionario_responsable");
            $query->columns('intId_plnFR, 
                            intId_plnAccion, 
                            intId_ugf, 
                            dteFechaInicio_plnFR, 
                            dteFechaFin_plnFR, 
                            intVigencia_plnFR');
            $query->values("0, " . $idPlanAccionObj . ", " . $poaFR->intId_ugf . ", '" . $poaFR->dteFechaInicio_plnFR . "', '" . $poaFR->dteFechaFin_plnFR . "', " . $poaFR->intVigencia_plnFR);
            $db->setQuery($query);

            $result = ($db->query()) ? true : false;
            
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_pei.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}