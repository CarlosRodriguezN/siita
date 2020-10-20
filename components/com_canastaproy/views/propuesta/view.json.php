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
class CanastaproyViewPropuesta extends JView {

    function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');

        $action = JRequest::getVar('action');
        $mdPropuesta = $this->getModel();

        switch (true) {
            //  Retorna la lista de cantones de una determinada provincia 
            case( $action == 'getCantones' ):
                $retval = $mdPropuesta->getCantones(JRequest::getInt('idProvincia'));
                break;
            //  Retorna la lista de parroquias de un determinado canton
            case( $action == 'getParroquias' ):
                $retval = $mdPropuesta->getParroquias(JRequest::getInt('idCanton'));
                break;
            //  Retorna la lista de politicas de una determinado objetivo nacional
            case( $action == 'getPoliticaNacional' ):
                $retval = $mdPropuesta->getPoliticaNacional(JRequest::getInt('idObjNac'));
                break;
            //  Retorna la lista de metas de una determinada politica nacional
            case( $action == 'getMetaNacional' ):
                $retval = $mdPropuesta->getMetaNacional(JRequest::getInt('idObjNac'), JRequest::getInt('idPolNac'));
                break;
            //  Retorna el id de la propuesta que se guardo
            case( $action == 'guardarPropuesta' ):
                $retval = $mdPropuesta->guardarPropuesta();
                break;
            //  Retorna el id de la propuesta que se guardo
            case( $action == 'eliminarPropuesta' ):
                $retval = $mdPropuesta->eliminarPropuesta();
                break;
        }

        echo json_encode($retval);
    }

}

