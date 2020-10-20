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
class PeiTableUgEntidad extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__gen_entidad', 'intIdentidad_ent', $db);
    }

    
    function registraEntidadUg( $idTpoEnt ) {

        $data["intIdentidad_ent"] = 0;
        $data["intIdtipoentidad_te"] = $idTpoEnt;

        if (!$this->save($data)) {
            echo $this->getError();
            exit;
        }

        return $this->intIdentidad_ent;
    }

}