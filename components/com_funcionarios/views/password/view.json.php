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
class FuncionariosViewPassword extends JView {

    function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');

        $action = JRequest::getVar('action');
        $mdPass = $this->getModel();
        
        switch (true) {
            
            //  Retorna el id del pai que se guardo
            case( $action == 'confirmPass' ):
                $retval = $mdPass->confirmPass();
                break;
            //  Retorna el id del pai que se guardo
            case( $action == 'updPass' ):
                $retval = $mdPass->updPass();
                break;
            
        }
        
        echo json_encode($retval);
    }

}

