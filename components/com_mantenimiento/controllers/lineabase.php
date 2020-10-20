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
class MantenimientoControllerLineaBase extends JControllerForm {

    protected $view_list = 'LineasBase';

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
        $form = JRequest::getVar('jform');

        if ($form["intCodigo_lbind"] == 0) {
            $form["dteFechaRegistro_lbind"] = date("Y-m-d H:i:s");
        }

        $form["dteFechaModificacion_lbind"] = date("Y-m-d H:i:s");

        JRequest::setVar('jform', $form);
        
        $mdLB = $this->getModel();
        
        if ($mdLB->registrarLineaBase()) {

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
