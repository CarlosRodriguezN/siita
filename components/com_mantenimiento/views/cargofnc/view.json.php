<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

/**
 * 
 *  Clase que Retorna en formato JSON,  
 *  pertenecientes a una determinada Unidad Territorial
 */
class MantenimientoViewCargoFnc extends JView
{

    function display( $tpl = null )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );

        $action = JRequest::getVar( 'action' );
        $mdCargo = $this->getModel();

        switch( true ) {
            //  Retorna el id del registro que se guardo
            case( $action == 'guardarCargos' ):
                $retval = $mdCargo->guardarCargos();
                break;
            //  Elimina un registro 
            case( $action == 'validarDelCargo' ):
                $retval = $mdCargo->validarDelCargo();
                break;
            //  Elimina un registro 
            case( $action == 'eliminarCargo' ):
                $retval = $mdCargo->eliminarCargo();
                break;
        }

        echo json_encode( $retval );
    }

}

