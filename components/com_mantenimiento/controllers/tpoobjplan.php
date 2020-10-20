<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * 
 *  Controlador linea base
 * 
 */
class MantenimientoControllerTpoObjPlan extends JControllerForm {

    protected $view_list = 'TposObjsPlan';

    protected function allowAdd($data = array()) {
        return true;
    }

    protected function allowEdit($data = array(), $key = 'id') {
        return true;
    }

    function add() {
        parent::add();
    }

    /**
     *  Retorna el Id del registro de linea base
     */
    function save() {

        $mdLB = $this->getModel();
        
        if ($mdLB->registrarTpoObjPlan()) {

            $this->setRedirect(
                    JRoute::_(
                            'index.php?option=' . $this->option . '&view=' . $this->view_list
                            . $this->getRedirectToListAppend(), false
                    )
            );
            return true;
        }
    }

}
