<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

//load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');
 
/**
 * Vista Fase
 */
class ProgramaViewUnidadTerritorial extends JView
{
    /**
    * display method of Hello view
    * @return void
    */
    public function display($tpl = null) 
    {
        // get the Data
        $form = $this->get('Form');        
        
        // Check for errors.
        if ( count( $errors = $this->get( 'Errors' ) ) ){
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }
        
        // Assign the Data
        $this->form = $form;
        
        //  Asigno valores
        $this->_idProyecto = JRequest::getVar( 'intCodigo_pry' );
        $this->_tpoIndicador = JRequest::getVar( 'tpoIndicador' );
        $this->_idRegIndicador = JRequest::getVar( 'idRegIndicador' );
        $this->_tpo = JRequest::getVar( 'tpo' );
        
        // Display the template
        parent::display($tpl);

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
        $bar->appendButton( 'Standard', 'save', 'Guardar', 'unidadterritorial.asignar', false );
        $bar->appendButton( 'Standard', 'cancel', 'Cancelar', 'unidadterritorial.cancel', false );
        
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
        
        //  Hoja de estilos para pestañas - tabs
        $document->addStyleSheet(JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css');

        //  Hoja de estilos para tablas
        $document->addStyleSheet(JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css');

        //  Hoja de estilos para alertas
        $document->addStyleSheet(JURI::root() . 'media/system/css/alerts/jquery.alerts.css');
        
        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root(). 'media/system/js/jquery-1.7.1.min.js' );
        
        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root(). 'media/system/js/jquery-noconflict.js' );

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript(JURI::root() . 'media/system/js/alerts/jquery.alerts.js');
        
        //  Adjunto libreria que la gestion de Combos Vinculantes
        $document->addScript( JURI::root(). 'components/com_programa/views/unidadterritorial/assets/Vinculantes.js' );
        
        //  Adjunto libreria que la clase de Unidad Territorial
        $document->addScript( JURI::root(). 'components/com_programa/views/unidadterritorial/assets/GestionUndTerritorial.js' );
        
        //  Adjunto libreria que la gestion de Unidad Territorial
        $document->addScript( JURI::root(). 'components/com_programa/views/unidadterritorial/assets/UnidadTerritorial.js' );
        
        //  Adjunto libreria que la gestion de Unidad Territorial        
        $document->addScript( JURI::root(). 'components/com_programa/views/unidadterritorial/assets/Reglas.js' );
        
        JText::script('COM_PROYECTOS_COBERTURA_ERROR_UNACCEPTABLE');
    }
    
}