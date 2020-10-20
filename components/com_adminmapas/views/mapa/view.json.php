<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

//load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');
JHTML::_('behavior.modal');     
/**
 * Vista de Ingreso /Edicion de un Programa
 */
class adminmapasViewMapa extends JView {

    /**
     * display method of Hello view
     * @return void
     */
    public function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');

        switch( JRequest::getVar( 'action' ) ){
            case 'getLayers': 
                // esta funcion es llamada en el modelo mapa.php
                $retval = $this->get('InfoLayer');
            break;
        }  

        echo json_encode( $retval );
    }

}