<?php

//  Importa la tabla necesaria para hacer la gestion

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'programas' . DS . 'tables' . DS . 'programa.php';

/**
 * Getiona el objetivo
 */
class Programas {

    public function __construct() {
        return;
    }

    /**
     * 
     */
    public function getProgramas() {
        $db = JFactory::getDBO();
        $tbProgramas = new jTablePrograma($db);
        $lstProgramas = $tbProgramas->getProgramas();
        return $lstProgramas;
    }

}
