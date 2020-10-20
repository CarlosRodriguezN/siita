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
class MantenimientoViewAgenda extends JView
{

    function display( $tpl = null )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );

        $action = JRequest::getVar( 'action' );
        $mdAgenda = $this->getModel();

        switch( true ) {

            //  Retorna el id de la propuesta que se guardo
            case( $action == 'guardarAgenda' ):
                $retval = $mdAgenda->guardarAgenda();
                break;
            
            case( $action == 'eliminarAgenda' ):
                $id = JRequest::getvar( 'id' );
                $retval = $mdAgenda->eliminarAgenda($id);
                break;
            
        }

        echo json_encode( $retval );
    }

}

