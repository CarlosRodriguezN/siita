<?php

//  Adjunto Tablas asociadas 
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'entidad' . DS . 'entidad.php';

class Entidad {

    public function __construct() {
        
    }

    public function getEntidad($idEntidad) {
        $db = JFactory::getDBO();
        $tbEntidad = new jTableEntidad($db);
        $result = $tbEntidad->getEntidad($idEntidad);
        return $result;
    }

    /**
     *  Registro de una entidad
     * @param type $idEntidad       Identificador de la Entidad.
     * @param type $idTpoEntidad    Identificador del tipo de Entidad
     * @param type $urlTableU       Dirección URL de TableU.
     * @return type
     */
    public function saveEntidad($idEntidad, $idTpoEntidad, $urlTableU) {
        $db = JFactory::getDBO();
        $tbEntidad = new jTableEntidad($db);
        $result = $tbEntidad->saveEntidad($idEntidad, $idTpoEntidad, $urlTableU);
        return $result;
    }

    /**
     *
     * Actualiza la url de la entidad
     *  
     * @param type $idEntidad
     * @param type $urlTableU
     */
    public function updUrlEntidad($idEntidad, $urlTableU) {
        $db = JFactory::getDBO();
        $tbEntidad = new jTableEntidad($db);
        $result = $tbEntidad->updUrlEntidad($idEntidad, $urlTableU);
        return $result;
    }

}
