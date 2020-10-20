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
class contratosControllerTipoContratista extends JControllerForm {

    protected $view_list = 'tiposContratista';

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
     * elimina uno o varios , tipo de contratista
     */
    public function delete() {
        $idsTipos = JRequest::getVar('cid');
        $modelTiposContratista = $this->getModel();
        foreach ($idsTipos AS $idTipoCtr) {
            $modelTiposContratista->deleteTipos($idTipoCtr);
        }
        $this->setRedirect(
                JRoute::_(
                        'index.php?option=' . $this->option . '&view=' . $this->view_list
                        . $this->getRedirectToListAppend(), false
                )
        );
    }

}