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
class MantenimientoViewCargoUG extends JView
{

    function display( $tpl = null )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );

        $action = JRequest::getVar( 'action' );
        $mdCargoUG = $this->getModel();

        switch( true ) {
            //  Retorna el id del registro que se guardo
            case( $action == 'asignarCargoUG' ):
                $retval = $mdCargoUG->asignarCargoUG();
                break;
            //  Elimina un registro 
            case( $action == 'eliminarCargoUG' ):
                $retval = $mdCargoUG->eliminarCargoUG();
                break;
        }

        echo json_encode( $retval );
    }

}

