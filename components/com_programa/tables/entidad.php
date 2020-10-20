<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

//  Import Joomla JUser Library
jimport('joomla.user.user');

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class ProgramaTableEntidad extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__gen_entidad', 'intIdentidad_ent', $db);
    }

    /**
     * Registra o actualiza el campo de la entidad.
     * @param int $idEntidad campo de la entidad.
     * @param int $tipo indica el tipo de la entidad
     * @return type
     */
    function saveEntidad($idEntidad, $tipo) {
        $data["intIdentidad_ent"] = $idEntidad;
        $data["intIdtipoentidad_te"] = $tipo;
        if ($this->save($data)) {
            $idEntidad = $this->intIdentidad_ent;
        }
        return $idEntidad;
    }

    function getEntidadIndicador($idIndicador) {
        
    }

}