<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Atribtuo ( #__ctr_atributo )
 * 
 */
class contratosTableGarantiaEstado extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_garantia_estado', 'intIdGarantiaEstado_ge', $db);
    }

    /**
     * 
     * @param type $idGarantia
     * @param type $idEstado
     */
    function saveGarantiaEstado($idGarantia, $estado) {
//        if ($estado->published != 0) {
            
            $data["intIdGarantiaEstado_ge"] = $estado->idGarantiaEstado;
            $data["dteFechaRegistro_ge"] = $estado->fchRegistro;
            $data["intIdGarantia_gta"] = $idGarantia;
            $data["intIdEstadoGarantia_eg"] = $estado->idEstadoGarantia;
            $data["strObservasion_ge"] = $estado->observacion;
            $data["intEstadoActGarantia_ge"] = $estado->estadoAct;
            $data["published"] = $estado->published;
             
            if ($this->bind($data)) {
                if ($this->save($data)) {
                    return $this->intIdGarantiaEstado_ge;
                }
            }
//        } else {
//          //  $this->delete($estado->intIdGarantiaEstado);
//        }
    }

}