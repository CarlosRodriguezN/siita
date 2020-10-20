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
class ConflictosViewActores extends JView
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
        //  Ejecutamos la funcion "get" de la clase JView, 
        //  la cual internamente accede al "docenteModelCategorias" y ejecuta el metodo "getItems"
        $items = $this->get( 'Items' );

        //  Ejecuta el metodo getPagination propio de la clase JModel
        $pagination = $this->get( 'Pagination' );

        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ) {
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }

        // ¿Qué permisos de acceso tiene este usuario? ¿Qué se puede (s) hacer?
        $this->canDo = ConflictosHelper::getActions();
        
        // Assign data to the view
        $this->items = $items;
        $this->pagination = $pagination;

        //  Ejecuta el metodo "populateState" de la clase 
        //  "IndicadoresModelUnidadMedidas" del modelo
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
        if( $this->canDo->get( 'core.create' ) ){
            $bar->appendButton( 'Standard', 'new', JText::_( 'BTN_NUEVO' ), 'actor.add', false );
        }
        
        $bar->appendButton( 'Separator' );
        
        $bar->appendButton( 'Standard', 'cancel', JText::_( 'BTN_CANCELAR' ), 'actor.cancel', false );
        
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
        
        // adjunto la clase java script de un reglas 
        $document->addScript( JURI::root() . 'components/com_conflictos/views/actores/assets/rules.js' );
        
        $document->setTitle( JText::_( 'COM_CONFLICTOS_GESTION_ACTORES' ) );
    }

}