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
class contratosControllerFormaGarantia extends JControllerForm {

    protected $view_list = 'formasgarantia';

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
        $idsFormasGarantia = JRequest::getVar('cid');
        $mdFormaGarantia = $this->getModel();
        foreach ($idsFormasGarantia AS $idFormaGarantia) {
            $mdFormaGarantia->deleteFormaGarantia($idFormaGarantia);
        }
        $this->setRedirect(
                JRoute::_(
                        'index.php?option=' . $this->option . '&view=' . $this->view_list
                        . $this->getRedirectToListAppend(), false
                )
        );
    }

}