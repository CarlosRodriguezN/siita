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
class FuncionariosViewPlnAccion extends JView {

    function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');

        $action = JRequest::getVar('action');
        $mdPlanEI = $this->getModel();
        
        switch (true) {
            
            //  Retorna el id del pai que se guardo
            case( $action == 'getResponsables' ):
                $idUG = JRequest::getVar("idUnidadGestion");
                $retval = $mdPlanEI->getResponsables((int)$idUG);
                break;
            
        }
        
        echo json_encode($retval);
    }

}