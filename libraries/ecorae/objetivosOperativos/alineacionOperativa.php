<?php

//  Importa la tabla necesaria para hacer la gestion

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivosOperativos' . DS . 'tables' . DS . 'alineacionOperativa.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivosOperativos' . DS . 'tables' . DS . 'descripciones.php';

/**
 * Getiona el objetivo
 */
class AlineacionOperativa {

    public function __construct() {
        return;
    }

    /**
     * 
     * @param type $alineaciones
     * @param type $idObjOpe
     * @param type $tpoEntidad
     */
    public function saveAlineacionesOperativas($alineaciones, $idObjOpe, $tpoEntidad) {
        if (count($alineaciones) > 0) {
            foreach ($alineaciones AS $alineacion) {
                $this->_saveAlineacionOperativa($alineacion, $idObjOpe, $tpoEntidad);
            }
        }
    }

    /**
     * GESTION| de la alineacion
     * @param array $alineacion     Objeto alineacion
     * @param int   $idObjOpe       Identificador del objetivo operativo que se esta alineando
     * @param object $tpoEntidad     Tipo de la entidad a la que pertenece el objetivo operativo
     * @return int
     */
    private function _saveAlineacionOperativa($alineacion, $idObjOpe, $tpoEntidad) {
        $db = JFactory::getDBO();
        $tbObjEnt = new jTableAlineacionOperativa($db);
        $idAlineacion = $tbObjEnt->saveAlineacionOperativa($alineacion, $idObjOpe, $tpoEntidad);
        return $idAlineacion;
    }

    /**
     * 
     * @param type $objetivo
     * @param type $tpoIdEntidadOwner Tipo de entidad a la que pertence el objetivo.
     * @return type
     */
    public function getAlineacionOperativa($objetivo, $tpoIdEntidadOwner) {
        $db = JFactory::getDBO();
        $tbObjEnt = new jTableAlineacionOperativa($db);
        $lstAlineaciones = $tbObjEnt->getAlineacionesOperativas($objetivo->idObjetivo, $tpoIdEntidadOwner);

        if (count($lstAlineaciones) > 0) {
            foreach ($lstAlineaciones AS $key => $alineacion) {
                $alineacion->regAlineacion = $key;
                $alnDesc = $this->_grDescripcion($alineacion);
                $alineacion->descripcion = $alnDesc->descripcion; //al que
                $alineacion->grDescripcion = $alnDesc->grDescripcion; // al que
            }
        }
        $objetivo->lstAlineaciones = $lstAlineaciones;
    }

    /**
     * Recupera la descripcion de la entidad a la que pertenece el objetivo al que se esta alineando 
     * @param type $alineacion
     */
    private function _grDescripcion($alineacion) {
        $descr = "";
        switch (true) {

            case (int) $alineacion->idOwner == 12:// de un Pei, Poa
                $db = JFactory::getDBO();
                $tbObjEnt = new jTableDescripcion($db);
                $descr = $tbObjEnt->getDescPlan($alineacion->idObjetivo, $alineacion->tpoEntidObjetivo);
                break;

            case (int) $alineacion->idOwner == 13:// se seleccciono por unidad de gestion
                $db = JFactory::getDBO();
                $tbObjEnt = new jTableDescripcion($db);
                $descr = $tbObjEnt->getDescripUnidGestion($alineacion->idObjetivo, $alineacion->idOwner);
                break;

            case (int) $alineacion->idOwner == 1:// programa
                $db = JFactory::getDBO();
                $tbObjEnt = new jTableDescripcion($db);
                $descr = $tbObjEnt->getDescripPrograma($alineacion->idObjetivo, $alineacion->idOwner);
                break;

            case (int) $alineacion->idOwner == 2:// proyecto
                $db = JFactory::getDBO();
                $tbObjEnt = new jTableDescripcion($db);
                $descr = $tbObjEnt->getDescripProyecto($alineacion->idObjetivo, $alineacion->idOwner);
                break;

            case (int) $alineacion->idOwner == 3:// convenio
                $db = JFactory::getDBO();
                $tbObjEnt = new jTableDescripcion($db);
                $descr = $tbObjEnt->getDescripCbtCnv($alineacion->idObjetivo, $alineacion->idOwner);
                break;
        }

        return $descr;
    }

}
