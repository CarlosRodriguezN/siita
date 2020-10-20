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
class contratosControllerFiscalizador extends JControllerForm {

    protected $view_list = 'fiscalizadores';

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
     * elimina uno FISCALIZADOR
     */
    public function delete() {
        $idsFiscalizadores = JRequest::getVar('cid');
        $modelFiscalizador = $this->getModel();
        foreach ($idsFiscalizadores AS $idFiscalizador) {
            $modelFiscalizador->deleteFiscalizador($idFiscalizador);
        }
        $this->setRedirect(
                JRoute::_(
                        'index.php?option=' . $this->option . '&view=' . $this->view_list
                        . $this->getRedirectToListAppend(), false
                )
        );
    }

}