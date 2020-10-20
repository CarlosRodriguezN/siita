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
class contratosControllerAtributo extends JControllerForm {

    protected $view_list = 'atributos';

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
        $idsAtributos = JRequest::getVar('cid');
        $modelAtributo = $this->getModel();
        foreach ($idsAtributos AS $idAtributo) {
            $modelAtributo->deleteAtributo($idAtributo);
        }
        $this->setRedirect(
                JRoute::_(
                        'index.php?option=' . $this->option . '&view=' . $this->view_list
                        . $this->getRedirectToListAppend(), false
                )
        );
    }

}