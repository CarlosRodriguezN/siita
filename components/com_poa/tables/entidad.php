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
class PoaTableEntidad extends JTable {

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
     * Almacena o edita la entidad. 
     * @param type $data
     * @return type
     */
    function saveEntidad($idEntidad, $tpoEntidad) {
        $idEntidad = 0;
        $toSave["intIdentidad_ent"] = $idEntidad;
        $toSave["intIdtipoentidad_te"] = $tpoEntidad;
        if ($this->save($toSave)) {
            $idEntidad = $this->intIdentidad_ent;
        }
        return $idEntidad;
    }

}