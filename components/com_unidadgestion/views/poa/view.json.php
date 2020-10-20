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
class PoaViewPoa extends JView
{

    function display( $tpl = null )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );

        $action = JRequest::getVar( 'action' );
        $mdPlanEI = $this->getModel();

        switch( true ) {

            //  Retorna el id de la propuesta que se guardo
            case( $action == 'guardarPoa' ):
                $retval = $mdPlanEI->guardarPoa();
                break;

            //  Retorna el id de la propuesta que se guardo
            case( $action == 'updVigenciaPoa' ):
                $retval = (int) $mdPlanEI->updVigenciaPoa();
                break;
            
            //  Borra un Archivo
            case( $action == 'delArchivo' ):
                $retval = (int) $mdPlanEI->delArchivo();
                break;
        }

        echo json_encode( $retval );
    }

}

