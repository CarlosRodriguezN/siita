<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
jimport('joomla.filesystem.file');
 
/**
 * 
 *  Controlador Programas
 * 
 */
class IndicadoresControllerIndicador extends JControllerForm
{
    protected $view_list = 'indicadores';
    
    protected function allowAdd($data = array()) {
        return true;
    }
    
    protected function allowEdit($data = array(), $key = 'id') {
        return true;
    }
    
    function add() {
        parent::add();
    }
    
    function save( $key = null, $urlVar = null )
    { 
        
    }
}