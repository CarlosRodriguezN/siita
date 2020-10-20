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
class ProyectosViewCanastaProyectos extends JView
{
    function display( $tpl = null )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelo = $this->getModel();
        
        $action = JRequest::getVar( 'action' );
        
        switch( $action ){
            //  Retorna informacion de una lista de proyectos pertenecientes a un determinado programa
            case 'getProyectos':
                $retval = $modelo->getLstProyectos( JRequest::getVar( 'idPrograma' ) );
            break;
        }

        echo json_encode( $retval );
    }
}