<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * 
 *  Clase que permite la gestion de Peticiones Ajax
 * 
 */
class AlineacionViewEstrategica extends JView {

    function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');
        $action = JRequest::getVar('action');
        $mdAlineacion = $this->getModel();
        switch (true) {
            case $action == "getEstructura":
                $retval = $mdAlineacion->getEstructura();
                break;
            case $action == "getItems":
                $retval = $mdAlineacion->getItems();
                break;
        }
        
        echo json_encode($retval);exit();
    }

}
