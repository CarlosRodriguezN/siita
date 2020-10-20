<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * 
 *  Controlador mapa
 * 
 */
class adminmapasControllerEcoraeMapaFrontEnd extends JControllerForm {

    protected $view_list = 'EcoraeMapasFrontEnd';

    protected function allowAdd($data = array()) {
        return true;
    }

    protected function allowEdit($data = array(), $key = 'id') {
        return true;
    }

    function add() {
        parent::add();
    }

//    function save() {
//        //  Accedo al modelo del componente com_programa
//        $modelo = $this->getModel();
//        return $modelo->saveDataShapes();
//                
//        
//    }

    public function saveFiles() {
        $modelo = $this->getModel();

        $modelo->saveDataShapes();
        $session = JFactory::getSession();
        if ($session->get('banImgs') == null) {
            // Redirect to the list screen.
            $this->setRedirect(
                    JRoute::_(
                            'index.php?option=' . $this->option . '&view=' . $this->view_list
                            . $this->getRedirectToListAppend(), false
                    )
            );
        }
        return true;
    }

}