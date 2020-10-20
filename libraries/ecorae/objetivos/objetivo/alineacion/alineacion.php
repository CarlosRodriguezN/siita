<?php

//  
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'alineacion' . DS . 'alineacionexterna.php';

class Alineacion {

    /**
     * 
     * @param type $alineacion
     * @param type $idObjetivo
     * @return type
     */
    public function saveAlineacion($alineacion, $idObjetivo) {
        $db = JFactory::getDBO();

        $tbAlineacion = new jTableAlineacioExterna($db);
        $idItem = $this->getItemValue($alineacion);
        $idAlineacion = $tbAlineacion->saveAlineacionExterna($alineacion, $idObjetivo, $idItem);
        return $idAlineacion;
    }

    /**
     * Recupera el id del Item que ha sido seleccionado
     * @param type $alineacion
     * @return type
     */
    private function getItemValue($alineacion) {
        $idItem = 0;
        if (count($alineacion->niveles)) {
            foreach ($alineacion->niveles AS $nivel) {
                if ($nivel->item->idItem != 0) {
                    $idItem = $nivel->item->idItem;
                }
            }
        }
        return $idItem;
    }

    /**
     * 
     * @param type $idObjetivo
     * @return type
     */
    public function getAlineacionObjetivo($idObjetivo) {
        $db = JFactory::getDBO();

        $tbAlineacion = new jTableAlineacioExterna($db);
        $lstAlineaciones = $tbAlineacion->getAlineacionesByObjetivo($idObjetivo);
        return $lstAlineaciones;
    }

}
