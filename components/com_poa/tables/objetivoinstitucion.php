<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de POA ( #__poa_plan_institucion )
 * 
 */
class PoaTableObjetivoInstitucion extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__pln_objetivo_institucion', 'intId_ob', $db);
    }

    /**
     *  Gestiona el registo de un objetivo.
     * @param type $data        La informacion que va a ser almacenada.
     * @param type $idPei       Identifica el tipo de de POA
     * @param type $idEntidad   Identificador de la entidad.
     * @return int
     */
    public function saveObjetivoPoa($data, $idPei, $idEntidad) {

        $idObjetivo = 0;
        $toSave = array();
        $toSave["intId_ob"] = $data->idObjetivo;
        $toSave["intId_pi"] = $idPei;
        $toSave["intIdentidad_ent"] = $idEntidad;
        $toSave["intIdPadre_ob"] = $data->idObejtivoPadre;
        $toSave["intId_tpoObj"] = $data->idTpoObjetivo;
        $toSave["intIdPrioridad_pr"] = $data->idPrioridad;
        $toSave["strDescripcion_ob"] = $data->descripcion;
        $toSave["dteFecharegistro_ob"] = $data->fchRegistro;
        $toSave["dteFechamodificacion_ob"] = $data->fchModificacion;
        $toSave["published"] = $data->published;
        if ($this->save($toSave)) {
            $idObjetivo = $this->intId_ob;
        }
        return $idObjetivo;
    }

    /**
     * 
     * @param type $idPei
     * @return type
     */
    public function getPoaObjetivos($idPei) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select('pln.intId_ob                    AS idObjetivo,
                            pln.intIdentidad_ent            AS idEntidad,
                            pln.intIdPadre_ob               AS idObejtivoPadre,
                            pln.intId_tpoObj                AS idTpoObjetivo,
                            pln.intIdPrioridad_pr           AS idPrioridad,
                            pri.strNombre_pr                AS nmbPrioridad,
                            pln.strDescripcion_ob           AS descripcion,
                            pln.dteFecharegistro_ob         AS fchRegistro,
                            pln.dteFechamodificacion_ob     AS fchModificacion,
                            pln.published                   AS published
                            ');
            $query->from("#__pln_objetivo_institucion AS pln");
            $query->join("inner", "#__gen_prioridad AS pri ON pri.intIdPrioridad_pr =pln.intIdPrioridad_pr");
            $query->where('intId_pi=' . $idPei);
            $db->setQuery($query);
            $db->query();
            $retval = ($db->loadObject()) ? $db->loadObjectList() : false;
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_poa.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}