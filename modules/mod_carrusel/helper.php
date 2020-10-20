<?php

// no direct access
defined('_JEXEC') or die;
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'programas' . DS . 'programas.php';

/**
 * @package		Joomla.Site
 * @subpackage          mod_plan_vigente
 * @since		1.5
 */
class modCarruselHelper {

    public function __construct() {
        return;
    }

    /**
     * Recupera la lista de programas.
     */
    public function getProgramas() {
        $oProgramas = new Programas();
        $lstProgramas = $oProgramas->getProgramas();
        return $lstProgramas;
    }

}
