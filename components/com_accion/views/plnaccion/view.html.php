<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

//load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

/**
 * Vista de Ingreso/Edicion de un plan accion (de un objetivo)
 */
class AccionViewPlnAccion extends JView
{

    protected $canDo;

    /**
     * display method of Hello view
     * @return void
     */
    public function display( $tpl = null )
    {
        // get the Data
        $form = $this->get( 'Form' );
        $item = $this->get( 'Item' );
        $script = $this->get( 'Script' );

        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ) {
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }

        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        $this->script = $script;
        
        // ¿Qué permisos de acceso tiene este usuario? ¿Qué se puede (s) hacer?
        $this->canDo = AccionHelper::getActions();

        //  data enviada por el objetivo
        $this->tpoPln       = JRequest::getVar("tpoPln");
        $this->idRegPlan    = JRequest::getVar("registroPln");
        $this->idRegObjetivo= JRequest::getVar("registroObj");
        $this->idUG         = JRequest::getVar("idUG");
        $this->idFnc        = JRequest::getVar("idFnc");
                
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

        //and make whatever calls you require
        if(  $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' )  ){
            $bar->appendButton( 'Standard', 'save', 'Guardar', 'plnaccion.registrar', false );
        }

        $bar->appendButton( 'Standard', 'cancel', 'Cancelar', 'plnaccion.cancel', false );

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
                '<![endif]-->' . "\n"
        );
        
        //  hoja de estilos para los formularios en backgrawn
        $document->addStyleSheet( JURI::root() . 'media/system/css/siita/common.css' );

        //  Hoja de estilos para tablas
        $document->addStyleSheet( JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css' );

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

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/FormatoNumeros.js' );

        //  Adjunto libreria del lenguaje java-script de la vista de objetivos de un Pei
        $document->addScript( JURI::root() . 'components/com_accion/views/plnaccion/assets/ES_ACCION.js' );
        
        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );

        //  Adjunto libreria para actualizacion de los combos vinculantes
        $document->addScript( JURI::root() . 'components/com_accion/views/plnaccion/assets/Vinculantes.js' );
        
        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'components/com_accion/views/plnaccion/assets/ReglasPA.js' );

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'components/com_accion/views/plnaccion/assets/Accion.js' );

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'components/com_accion/views/plnaccion/assets/GestionAcciones.js' );
        
        //  Adjunto libreria para el contro de los botonee de guarad y cancelar del livebox
        $document->addScript( JURI::root() . 'components/com_accion/views/plnaccion/assets/Save.js' );
        
        JText::script( 'COM_ACCION_PLAN_ERROR_UNACCEPTABLE' );
    }
}