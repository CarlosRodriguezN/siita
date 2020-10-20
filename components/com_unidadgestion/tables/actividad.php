<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__poa_plan_institucion )
 * 
 */
class UnidadGestionTableActividad extends JTable {

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
        $data["intId_ob"] = (int) $idObjetivo;
        $data["intIdResponsable"] = (int)$actividad->idResponsable;
        $data["strDescripcion_act"] = $actividad->descripcion;
        $data["strObservacion_tpg"] = $actividad->observacion;
        $data["fchRegistro_act"] = $actividad->fchRegisto;
        $data["fchActividad_tpg"] = $actividad->fchActividad;
        $data["published"] = (int) $actividad->published;

        if ($this->save($data)) {

            $idActividad = $this->intIdActividad_act;
        }
        return $idActividad;
    }

    /**
     *  Obtiene la lista de las actividades de un objetivo 
     * @param type $idObjetivo
     * @return type
     */
    function getLstActividades($idObjetivo) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select('
                    pla.intIdActividad_act          AS  idActividad,
                    pla.intIdTipoGestion_tpg        AS  tipoGestion,
                    pla.intId_ob                    AS  idObjetivo,
                    pla.intIdResponsable            AS  idResponsable,
                    concat(f.strApellido_fnc," ",f.strNombre_fnc) AS funNombres,
                    fu.intCodigo_ug                 AS  undGestion,
                    pla.strDescripcion_act	    AS  descripcion,
                    pla.strObservacion_tpg	    AS  observacion,
                    pla.fchRegistro_act             AS  fchRegisto,
                    pla.fchActividad_tpg	    AS  fchActividad,
                    pla.published                   AS  published
                            ');
            $query->from("#__pln_actividad AS pla");
            $query->join("inner", "#__gen_funcionario AS f ON f.intCodigo_fnc =pla.intIdResponsable");
            $query->join("inner", "#__gen_ug_funcionario AS fu ON fu.intCodigo_fnc =f.intCodigo_fnc");
            $query->where('pla.intId_ob =' . $idObjetivo);
            $query->where('pla.published=1');
            $db->setQuery($query);
            $db->query();
            $retval = ($db->loadObjectList()) ? $db->loadObjectList() : array();
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}