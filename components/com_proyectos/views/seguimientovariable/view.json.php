<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

//load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');
 
/**
 * Vista Planificacion
 */
class ProyectosViewSeguimientoVariable extends JView
{
    /**
    *   display method of Hello view
    *   @return void
    */
   public function display( $tpl = null )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        
        $action = JRequest::getVar( 'action' );
        $mdPlanificacion = $this->getModel();
        
        switch( true ){
            case( $action == 'getVariables' ):
                $retval = $mdPlanificacion->getVariablesInd( JRequest::getVar( 'idIndicador' ) );
            break;
        }
        
        echo json_encode( $retval );
    }
}