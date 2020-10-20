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
class AlineacionViewOperativa extends JView {

    function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');

        $action = JRequest::getVar('action');
        $mdAlineacion = $this->getModel();

        switch (true) {
            //  Retorna informacion de una determinada Politica Nacional
            case $action == 'getObjetivos':
                $tpoEntidad = (int) JRequest::getVar('tpoEntidad');
                $retval = $mdAlineacion->getObjetivosTipoEntidad($tpoEntidad);
                break;
        }
        echo json_encode($retval);
    }

}
