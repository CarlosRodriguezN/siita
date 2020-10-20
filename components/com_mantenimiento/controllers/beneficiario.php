<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
 
/**
 * 
 *  Controlador proyecto
 * 
 */
class MantenimientoControllerBeneficiario extends JControllerForm
{
    protected $view_list = 'beneficiarios';
    
    protected function allowAdd( $data = array() )
    {
        return true;
    }
    
    protected function allowEdit($data = array(), $key = 'id')
    {
        return true;
    }
    
    function add()
    {
        parent::add();
    }
    
//    function save( $key = null, $urlVar = null )
//    {
//        var_dump( JRequest::get() ); exit;
//    }
    
}
