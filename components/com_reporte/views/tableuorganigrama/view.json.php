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
jimport('ecorae.entidad.entidad');

class ReporteViewTableuOrganigrama extends JView {

    function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');

        $action = JRequest::getVar('action');
        $mdAlineacion = $this->getModel();

        switch (true) {
            //  Retorna informacion de una determinada Politica Nacional
            case $action == 'updUrl':
                $urlTableU = JRequest::getVar('url');
                $idEntidad = JRequest::getVar('idEntidad');
                $oEntidad = new Entidad();
                $oEntidad->updUrlEntidad($idEntidad, $urlTableU);
                break;
        }
        echo json_encode($retval);
    }

}
