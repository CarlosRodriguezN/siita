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
class contratosTableEntidad extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__gen_entidad', 'intIdentidad_ent', $db);
    }

    public function saveEntidad($idEntidad, $tipo) {
        
        $data["intIdentidad_ent"] = $idEntidad;
        $data["intIdtipoentidad_te"] = $tipo;
        if ($this->save($data)) {
            $idEntidad = $this->intIdentidad_ent;
        }
        
        return $idEntidad;
    }

}

