<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

//load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

/**
 * 
 * Clase que muestra un panel de acceso para las Secretarias Tecnicas Provinciales
 * 
 */
class PanelViewConvenios extends JView
{

    /**
     * Panel view display method
     * @return void
     */
    protected $items;
    protected $pagination;
    protected $state;
    protected $canDo;
    
    function display( $tpl = null )
    {
        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ) {
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }
        
        $this->canDo = PanelHelper::getActions();
        $this->_ticketTableu = $this->_getTicketTableu( 'AnlisisdeConvenios/ScorecarddeConvenios' );
        
        // Display the template
        parent::display( $tpl );

        // Set the document
        $this->setDocument();
    }

    
    private function _getTicketTableu( $nombreDashBoard )
    {
        //  Retorna informacion URL con ticket de confianza de los server de ECORAE
        $mdTableau = $this->getModel();
        return $mdTableau->getTicketTableuPorNombre( $nombreDashBoard );
    }
    
    /**
     * Setting the toolbar
     */
    protected function getToolbar()
    {
        $bar = new JToolBar( 'toolbar' );

        $bar->appendButton( 'Standard', 'list', JText::_( 'COM_PANEL_LISTA_CONVENIOS' ), 'convenios.lista', false );

        //generate the html and return
        return $bar->render();
    }
    
    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle( JText::_( 'COM_PANEL_CONTROL' ) );

        //  Accdemos a la hoja de estilos del administrador
        $document->addStyleSheet( 'administrator/templates/system/css/system.css' );

        //  Agregamos hojas de estilo complementarias 
        $document->addCustomTag(
                '<link href="administrator/templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />' . "\n\n" .
                '<!--[if IE 7]>' . "\n" .
                '<link href="administrator/templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />' . "\n" .
                '<![endif]-->' . "\n" .
                '<!--[if gte IE 8]>' . "\n\n" .
                '<link href="administrator/templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />' . "\n" .
                '<![endif]-->' . "\n"
        );
        
        //  Hoja de estilos para pestañas - tabs
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css' );

        //  Hoja de estilos para tablas
        $document->addStyleSheet( JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css' );

        //  Hoja de estilos para alertas
        $document->addStyleSheet( JURI::root() . 'media/system/css/alerts/jquery.alerts.css' );

        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'components/com_panel/views/convenios/assets/reglas.js' );
        
    }

}