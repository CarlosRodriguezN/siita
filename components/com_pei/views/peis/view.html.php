<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

//load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

/**
 * Clase que muestra la lista de planes institucionales
 */
class PeiViewPeis extends JView
{

    /**
     * Peis view display method
     * @return void
     */
    protected $items;
    protected $pagination;
    protected $state;
    protected $_lstPlanes;
    
    function display( $tpl = null )
    {
        //  Ejecutamos la funcion "get" de la clase JView, 
        //  la cual internamente accede al "PeiModelPei" y ejecuta el metodo "getItems"
        $items = $this->get( 'Items' );
        
        foreach( $items as $item ){
            $dtaPlan["idPlan"]     = $item->intId_pi;
            $dtaPlan["vigencia"]   = $item->blnVigente_pi;

            $this->_lstPlanes[]   = $dtaPlan;
        }

        //  Ejecuta el metodo getPagination propio de la clase JModel
        $pagination = $this->get( 'Pagination' );

        // What Access Permissions does this user have? What can (s)he do?
        $this->canDo = PeiHelper::getActions();

        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ){
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }

        // Assign data to the view
        $this->items = $items;
        $this->pagination = $pagination;

        //  Ejecuta el metodo "populateState" de la clase 
        //  "PeiModelPeis" del modelo
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

        if( $this->canDo->get( 'core.create' ) ){
            $bar->appendButton( 'Standard', 'new', JText::_( 'COM_PEI_EVENTO_NUEVO' ), 'pei.add', false );
        }

        $bar->appendButton( 'Standard', 'cancel', JText::_( 'COM_PEI_EVENTO_CANCELAR' ), 'pei.cancel', false );

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

        //  Hoja de estilos para pestañas - tabs
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css' );

        //  Hoja de estilos para alertas
        $document->addStyleSheet( JURI::root() . 'media/system/css/alerts/jquery.alerts.css' );

        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );

        //  Adjunto libreria que permite el bloqueo de la pagina en llamadas ajax
        $document->addScript( JURI::root() . 'media/system/js/blockUI/jquery.blockUI.js' );

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'components/com_pei/views/pei/assets/VigenciaPlan.js' );

        //  Adjunto libreria que controla los botones del Joomla
        $document->addScript( JURI::root() . 'components/com_pei/views/peis/assets/Reglas.js' );

        JText::script( 'COM_PEI_PLAN_ERROR_UNACCEPTABLE' );
    }

}
