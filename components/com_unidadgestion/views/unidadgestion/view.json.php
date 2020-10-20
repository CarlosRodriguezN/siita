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
class UnidadGestionViewUnidadGestion extends JView {

    function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');

        $action = JRequest::getVar('action');
        $mdUGPoas = $this->getModel();

        switch (true) {
            
            //  Retorna el id del pai que se guardo
            case( $action == 'guardarUG' ):
                $retval = (int)$mdUGPoas->guardarUndGes();
                break;
            
            //  Retorna True si el funcionario se puede eliminar
            case( $action == 'validarDeleteFuncionario' ):
                $retval = (int)$mdUGPoas->validarDeleteFnci();
                break;
            
            //  Retorna True si el funcionario se puede eliminar
            case( $action == 'updVigenciaPoa' ):
                $retval = (int)$mdUGPoas->updVigenciaPoa();
                break;
            
            //  Retorna True si el funcionario se puede eliminar
            case( $action == 'eliminarUnidadGestion' ):
                $retval = (int)$mdUGPoas->eliminarUnidadGestion();
                break;
            
            //  Retorna la lista de opciones adicionales y le lista actualizada de funcionarios
            case( $action == 'guardarOpAdd' ):
                $retval = $mdUGPoas->guardarOpAdd();
                break;
            
        }

        echo json_encode($retval);
    }
}

