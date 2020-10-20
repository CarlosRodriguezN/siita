<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * 
 *  Controlador Fases
 * 
 */
class ApiRestControllerApilog extends JControllerForm
{
    protected $view_list = 'apilog';

    protected function allowAdd($data = array())
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

    function cancel()
    {
        // Redirect to the edit screen.
        $this->setRedirect( JRoute::_( 'index.php?option=' . $this->option , false ) );
    }

}
