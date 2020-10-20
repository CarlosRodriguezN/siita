<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * 
 *  Controlador Contrato
 * 
 */
class contratosControllerMulta extends JControllerForm {

    protected $view_list = 'multas';

    protected function allowAdd($data = array()) {
        return true;
    }

    protected function allowEdit($data = array(), $key = 'id') {
        return true;
    }

    public function add() {
        parent::add();
    }

    /**
     * elimina uno contratista
     */
    public function delete() {
        $idsMultas = JRequest::getVar('cid');
        $mdMulta = $this->getModel();
        foreach ($idsMultas AS $idMulta) {
            $mdMulta->deleteMulta   ($idMulta);
        }
        $this->setRedirect(
                JRoute::_(
                        'index.php?option=' . $this->option . '&view=' . $this->view_list
                        . $this->getRedirectToListAppend(), false
                )
        );
    }

}