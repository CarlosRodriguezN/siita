<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de POA ( #__poa_plan_institucion )
 * 
 */
class jTableActividad extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__pln_actividad', 'intIdActividad_act', $db);
    }

    /**
     *   Regista la actividad.
     * @param type $actividad
     * @param type $idObjetivo
     * @return type
     */
    function saveActividad($actividad, $idObjetivo) {

        $idActividad = 0;

        $data["intIdActividad_act"] = (int) $actividad->idActividad;
        $data["intIdTipoGestion_tpg"] = (int) $actividad->tipoGestion;
        $data["intId_pln_objetivo"] = (int) $idObjetivo;
        $data["intIdResponsable"] = (int) $actividad->idResponsable;
        $data["strDescripcion_act"] = $actividad->descripcion;
        $data["strObservacion_tpg"] = $actividad->observacion;
        $data["fchRegistro_act"] = date("Y-m-d H:i:s");
        $data["fchActividad_tpg"] = $actividad->fchActividad;
        $data["published"] = (int) $actividad->published;
        if ($this->save($data)) {
            $idActividad = $this->intIdActividad_act;
        }
        return $idActividad;
    }

    /**
     *  Obtiene la lista de las actividades de un objetivo 
     * @param type $idPlanObjetivo
     * @return type
     */
    function getLstActividades($idPlanObjetivo) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);

            $query->select('pla.intIdActividad_act                          AS idActividad,
                            pla.intIdTipoGestion_tpg                        AS tipoGestion,
                            tg.strNombre_tpg                                AS desTpoGestion,
                            pla.intId_pln_objetivo                          AS idObjetivo,
                            pla.intIdResponsable                            AS idResponsable,
                            concat(f.strApellido_fnc," ",f.strNombre_fnc)   AS funNombres,
                            ugf.intCodigo_ug                                AS undGestion,
                            pla.strDescripcion_act                          AS descripcion,
                            pla.strObservacion_tpg                          AS observacion,
                            DATE(pla.fchRegistro_act)                       AS fchRegisto,
                            DATE(pla.fchActividad_tpg)	                    AS fchActividad,
                            pla.published                                   AS published');
            $query->from("#__pln_actividad AS pla");
            $query->join("inner", "#__gen_ug_funcionario AS ugf ON ugf.intId_ugf = pla.intIdResponsable");
            $query->join("inner", "#__gen_funcionario AS f ON f.intCodigo_fnc = ugf.intCodigo_fnc");
            $query->join("inner", "#__gen_tipo_gestion AS tg ON tg.intIdTipoGestion_tpg = pla.intIdTipoGestion_tpg");
            $query->where('pla.intId_pln_objetivo =' . $idPlanObjetivo);
            $query->where('pla.published=1');
            $query->group('pla.intIdActividad_act');
            $query->order('DATE(pla.fchActividad_tpg) ASC');

            $db->setQuery($query);
            $db->query();

            $retval = ( $db->loadObjectList() ) ? $db->loadObjectList() 
                                                : array();
            
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_poa.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     *  Retorna una kista de actividades de un determinado funcionario desde una fecha indicada
     * @param type $idFncResponsable        Id del funcionario responsable (ide de la relacion entre el Fnc y UG)
     * @param type $fechaPlan               Fecha para la seleccion
     * @return type
     */
    function getLstActividadesByFnc( $idFncResponsable, $fechaPlan ) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select('
                    pla.intIdActividad_act                          AS idActividad,
                    pla.intIdTipoGestion_tpg                        AS tipoGestion,
                    tg.strNombre_tpg                                AS  desTpoGestion,
                    pla.intId_pln_objetivo                          AS idObjetivo,
                    pla.intIdResponsable                            AS idResponsable,
                    concat(f.strApellido_fnc," ",f.strNombre_fnc)   AS funNombres,
                    ugf.intCodigo_ug                                AS undGestion,
                    pla.strDescripcion_act	                    AS descripcion,
                    pla.strObservacion_tpg	                    AS observacion,
                    DATE(pla.fchRegistro_act)                       AS fchRegisto,
                    DATE(pla.fchActividad_tpg)	                    AS fchActividad,
                    pla.published                                   AS published
                            ');
            $query->from("#__pln_actividad AS pla");
            $query->join("inner", "#__gen_ug_funcionario AS ugf ON ugf.intId_ugf = pla.intIdResponsable");
            $query->join("inner", "#__gen_funcionario AS f ON f.intCodigo_fnc = ugf.intCodigo_fnc");
            $query->join("inner", "#__gen_tipo_gestion AS tg ON tg.intIdTipoGestion_tpg = pla.intIdTipoGestion_tpg");
            $query->where("pla.intIdResponsable = {$idFncResponsable}");
            $query->where("pla.fchActividad_tpg >= '{$fechaPlan}'");
            $query->where("pla.published=1");
            $query->group("pla.intIdActividad_act");
            $query->order("DATE(pla.fchActividad_tpg) ASC");
            $db->setQuery($query);
            $db->query();
            $retval = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : array();
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_poa.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
}
