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
class contratosControllerEstadoGarantia extends JControllerForm {

    protected $view_list = 'estadosgarantia';

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
        $idsEstadosGarantia = JRequest::getVar('cid');
        $mdEstadoGarantia = $this->getModel();
        foreach ($idsEstadosGarantia AS $idEstadoGarantia) {
            $mdEstadoGarantia->deleteEstadoGarantia($idEstadoGarantia);
        }
        $this->setRedirect(
                JRoute::_(
                        'index.php?option=' . $this->option . '&view=' . $this->view_list
                        . $this->getRedirectToListAppend(), false
                )
        );
    }

}