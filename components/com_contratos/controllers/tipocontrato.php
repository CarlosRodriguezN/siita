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
class contratosControllerTipoContrato extends JControllerForm {

    protected $view_list = 'tiposcontrato';

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
        $idsTiposContrato = JRequest::getVar('cid');
        $mdTipoContrato = $this->getModel();
        foreach ($idsTiposContrato AS $idTipoContrato) {
            $mdTipoContrato->deleteTipoContrato($idTipoContrato);
        }
        $this->setRedirect(
                JRoute::_(
                        'index.php?option=' . $this->option . '&view=' . $this->view_list
                        . $this->getRedirectToListAppend(), false
                )
        );
    }

}