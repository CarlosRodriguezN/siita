<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

//  load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');

 
/**
 * Vista Fase
 */
class MantenimientoViewIndicador extends JView
{
    protected $_idIndicador;
    protected $_tpoIndicador;
    protected $_idRegIndicador;
    protected $_idRegObjetivo;
    protected $_tpo;
    protected $_idPlan;


    /**
    * display method of Hello view
    * @return void
    */
    public function display($tpl = null) 
    {
        // get the Data
        $form   = $this->get( 'Form' );
        $item   = $this->get( 'Item' );
        $script = $this->get( 'Script' );

        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        
        $this->script = $script;

        // Check for errors.
        if ( count( $errors = $this->get( 'Errors' ) ) ){
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }
    
    /**
    * 
    *   ToolBar - Asignacion de Indicadores a un Objetivo
    * 
    */
    protected function getToolbar() 
    {
        $bar    = new JToolBar( 'toolbar' );

        $bar->appendButton( 'Standard', 'save', 'Agregar', 'indicador.add', false );
        $bar->appendButton( 'Standard', 'cancel', 'Cancelar', 'indicador.cancel', false );

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
            '<link href="administrator/templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />'."\n\n".
            '<!--[if IE 7]>'."\n".
            '<link href="administrator/templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />'."\n".
            '<![endif]-->'."\n".
            '<!--[if gte IE 8]>'."\n\n".
            '<link href="administrator/templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />'."\n".
            '<![endif]-->'."\n".
            '<link rel="stylesheet" href="administrator/templates/bluestork/css/rounded.css" type="text/css" />'."\n"
        );
        
        //  hoja de estilos para los formularios
        $document->addStyleSheet(JURI::root() . 'media/system/css/siita/common.css');
        
        //  Hoja de estilos para pestañas - tabs
        $document->addStyleSheet(JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css');

        //  Hoja de estilos para tablas
        $document->addStyleSheet(JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css');

        //  Hoja de estilos para alertas
        $document->addStyleSheet(JURI::root() . 'media/system/css/alerts/jquery.alerts.css');
        
        //  Hoja de estilos para la semaforizacion
        $document->addStyleSheet(JURI::root() . 'media/system/images/sprites-Objetivos/sprits.css');
        
        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root(). 'media/system/js/jquery-1.7.1.min.js' );
        
        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root(). 'media/system/js/jquery-noconflict.js' );

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript(JURI::root() . 'media/system/js/alerts/jquery.alerts.js');
        
        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root(). 'media/system/js/jquery-ui-1.8.13.custom.min.js' );

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root(). 'media/system/js/FormatoNumeros.js' );
        
        //  Adjunto libreria que la gestion orden de informacion de tablas
        $document->addScript( JURI::root(). 'media/system/js/jquery.tablesorter.min.js' );
        
        //  Adjunto libreria que la gestion Presentacion de informacion de un Indicadores asociados a un indicador
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/indicador/assets/Common.js' );
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/indicador/assets/Vinculantes.js' );
        
        //  Adjunto libreria que la gestion de Reglas de validacion de un formulario
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/indicador/assets/Reglas.js' );
        
        //  Adjunto archivo de gestion de indicadores de tipo plantilla
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/indicador/assets/indicadorPtlla.js' );
        
        //  Adjunto libreria clase Variable 
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/indicador/assets/VariablePtllaIndicador.js' );
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/indicador/assets/GestionVariablesPtllaIndicadores.js' );

        //  Adjunto libreria de gestion de validacion
        $document->addScript( JURI::root() . 'media/system/js/jquery-validate/common_validate.js' );
        
        JText::script('COM_PROYECTOS_COBERTURA_ERROR_UNACCEPTABLE');
    }
}