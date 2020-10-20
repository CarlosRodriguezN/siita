<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 *  Clase que Retorna en formato JSON
 */

class MantenimientoViewLineaBase extends JView {

    function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');

        $action = JRequest::getVar('action');
        $mdLineaBase = $this->getModel();

        switch (true) {
            //  Gestiona el mantenimiento de Linea Base 
            case( $action == 'mntLineaBase' ):
                $retval = $mdLineaBase->mantenimientoLinBas();
            break;
            
        }

        echo json_encode( $retval );
    }
}

