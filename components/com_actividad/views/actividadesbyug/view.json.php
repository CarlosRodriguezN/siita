<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * 
 *  Clase que Retorna en formato JSON,  
 *  pertenecientes a una determinada Unidad Territorial
 */
class ActividadViewActividad extends JView {

    function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');

        $action = JRequest::getVar('action');
        $mdActivida = $this->getModel();

        switch (true) {

            //  Retorna retorna una lista de responsables
            case( $action == 'getResonsables' ):
                $unidadGestion = JRequest::getVar("idUnidadGestion");
                $retval = $mdActivida->getResponsables($unidadGestion);
                break;
        }

        echo json_encode($retval);
    }

}

