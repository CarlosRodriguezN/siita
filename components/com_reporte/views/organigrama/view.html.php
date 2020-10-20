<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

//load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

/**
 * Clase que muestra un conjunto de Unidades de medida
 */
class ReporteViewOrganigrama extends JView
{

    /**
     * HelloWorlds view display method
     * @return void
     */
    protected $items;
    protected $pagination;
    protected $state;

    function display( $tpl = null )
    {

        $items = $this->get( 'Items' );

       

        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ) {
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }

        // Assign data to the view
        $this->items = $items;
      

        //  Ejecuta el metodo "populateState" de la clase 
        $this->state = $this->get( 'State' );

        // Display the template
        parent::display( $tpl );

        // Set the document
        $this->setDocument();
    }

    /**
     * Setting the toolbar
     */
    protected function getToolbar()
    {
        $bar = new JToolBar( 'toolbar' );

        //  And make whatever calls you require
        $bar->appendButton( 'Standard', 'new', 'Nuevo', 'programa.add', false );

        //  Generate the html and return
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
                '<![endif]-->' . "\n" .
                '<link rel="stylesheet" href="administrator/templates/bluestork/css/rounded.css" type="text/css" />' . "\n"
        );
        //  Hoja de estilos para tablas
        $document->addStyleSheet( JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css' );

        //  Hoja de estilos para pestañas
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css' );

        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );
        
        // Adjunto libreria de la api de google.
        $document->addScript( "https://www.google.com/jsapi" );

        // Adjunto el script de información general.
        $document->addScript( JURI::root() . 'components/com_reporte/views/organigrama/assets/general.js' );

        JText::script( 'COM_PROGRAMA_COBERTURA_ERROR_UNACCEPTABLE' );

        JText::script( 'COM_PROGRAMA_PROGRAMAS_ERROR_UNACCEPTABLE' );
    }

  
}