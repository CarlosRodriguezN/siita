<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * 
 *  Clase que Retorna en formato JSON, Provincias, Cantones y Parroquias 
 *  pertenecientes a una determinada Unidad Territorial
 */
class MantenimientoViewVariable extends JView
{
    function display( $tpl = null )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        
        $action = JRequest::getVar( 'action' );
        switch( true ){
            //  Retorna informacion de una determinada Politica Nacional
            case( $action == 'lstTiposVariables' ):
                $retval = $this->get( 'lstTiposVariables' );
                break;
        }
        
        echo json_encode( $retval );
    }
}