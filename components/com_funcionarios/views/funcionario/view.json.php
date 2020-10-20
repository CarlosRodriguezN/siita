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
class FuncionariosViewFuncionario extends JView
{

    function display( $tpl = null )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );

        $action = JRequest::getVar( 'action' );
        $modelFnc = $this->getModel();

        switch ( true ) {

            //  Retorna el id del pai que se guardo
            case( $action == 'guardarFuncionario' ):
                $retval = $modelFnc->guardarFuncionario();
                break;

            //  Retorna True si el funcionario se puede eliminar
            case( $action == 'eliminarFuncionario' ):
                $retval = (int) $modelFnc->eliminarFuncionario();
                break;

            //  Borra un Archivo
            case( $action == 'delArchivo' ):
                $retval = (int) $modelFnc->delArchivo();
                break;
            
            //  Borra un Archivo
            case( $action == 'getOpAdd' ):
                $retval = $modelFnc->getOpAdd();
                break;
            
            //  Obtiene los cargos de una unidad de gestion
            case( $action == 'getCargosUG' ):
                $retval = $modelFnc->getCargosUG();
                break;
            
        }

        echo json_encode( $retval );
    }
}
