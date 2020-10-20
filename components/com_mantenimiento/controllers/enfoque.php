<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla controllerform library
jimport( 'joomla.application.component.controllerform' );

/**
 * 
 *  Controlador linea base
 * 
 */
class MantenimientoControllerEnfoque extends JControllerForm
{

    protected $view_list = 'enfoques';

    protected function allowAdd( $data = array () )
    {
        return true;
    }

    protected function allowEdit( $data = array (), $key = 'id' )
    {
        return true;
    }

    function add()
    {
        parent::add();
    }

}
