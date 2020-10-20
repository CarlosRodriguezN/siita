<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

//load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

JHTML::_( 'behavior.modal' );

/**
 * Clase que muestra los contratos
 */
class contratosViewContratos extends JView
{

    /**
     * HelloWorlds view display method
     * @return void
     */
    protected $items;
    protected $pagination;
    protected $state;


    function display($tpl = null)
    {
        //  Ejecutamos la funcion "get" de la clase JView, 
        //  la cual internamente accede al "ProyectosModelFases" y ejecuta el metodo "getItems"
        $items = $this->get( 'Items' );

        //  Ejecuta el metodo getPagination propio de la clase JModel
        $pagination = $this->get( 'Pagination' );

        // Check for errors.
        if( count($errors = $this->get('Errors')) ){
            JError::raiseError(500, implode('<br />', $errors));

            return false;
        }

        // Assign data to the view
        $this->items = $items;
        $this->pagination = $pagination;

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

        if( JRequest::getVar( 'tpoContrato' ) != 2 ){
            //  And make whatever calls you require
            $bar->appendButton( 'Standard', 'new', JText::_( 'COM_CONTRATO_NUEVO' ), 'contrato.add', false );
            $bar->appendButton( 'Standard', 'cancel', JText::_( 'COM_CONTRATO_CANCELAR' ), 'contrato.cancel', false );
        }else{
            //  And make whatever calls you require
            $bar->appendButton( 'Standard', 'new', JText::_( 'COM_CONTRATO_NUEVO' ), 'convenio.add', false );
            $bar->appendButton( 'Standard', 'cancel', JText::_( 'COM_CONTRATO_CANCELAR' ), 'convenio.cancel', false );
        }

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
                '<![endif]-->' . "\n"
                // '<link rel="stylesheet" href="administrator/templates/bluestork/css/rounded.css" type="text/css" />'."\n"
        );

        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery

        $document->addScript( JURI::root() . 'components/com_contratos/views/contrato/assets/contratos.js' );

        //  Adjunto libreria que permite el control de lo botones del Joomla

        $document->addScript( JURI::root() . 'components/com_contratos/views/contratos/assets/Reglas.js' );

        JText::script( 'COM_CONTRATOS_CONTRATO_ERROR_UNACCEPTABLE' );

        JText::script('COM_CONTRATOS_CONTRATO_ERROR_UNACCEPTABLE');
    }

}