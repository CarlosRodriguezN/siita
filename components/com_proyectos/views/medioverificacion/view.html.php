<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

//load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');
 
/**
 * Vista de gestion de planificacion de un variable
 */
class ProyectosViewMedioVerificacion extends JView
{
    /**
    * display method of Hello view
    * @return void
    */
    protected $_idProyecto;
    protected $_idTipoML;
    protected $_idML;
    protected $_idRegML;
    
    protected $_idCmp;
    protected $_idAct;
    protected $canDo;
    

    public function display($tpl = null) 
    {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');
        $script = $this->get('Script');
        
        $this->_idProyecto = JRequest::getVar( 'intCodigo_pry' );
        $this->_idTipoML = JRequest::getVar( 'idTipoML' );
        $this->canDo = ProyectosHelper::getActions();

        if( $this->_idTipoML != 4 ){
            $this->_idRegML = JRequest::getVar( 'idRegML' );
            $this->_idML = JRequest::getVar( 'idML' );
        }else{
            $this->_idCmp = JRequest::getVar( 'idRegCmp' );
            $this->_idAct = JRequest::getVar( 'idRegAct' );
        }
        
        // Check for errors.
        if ( count( $errors = $this->get( 'Errors' ) ) ){
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }
        
        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        $this->script = $script;
        
        //  Titulo de la plantilla
        $this->title = JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_LSTMEDVERIFICACION_TITLE' );
        
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
        if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ){
            $bar->appendButton( 'Standard', 'save', 'Guardar', 'medioVerificacion.asignar', false );
            $bar->appendButton( 'Separator' );
        }

        $bar->appendButton( 'Standard', 'cancel', 'Cancelar', 'medioVerificacion.cancel', false );
        
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
        
        //  hoja de estilos para los formularios
        $document->addStyleSheet(JURI::root() . 'media/system/css/siita/common.css');
        
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
        
        //  Hoja de estilos para pestañas
        $document->addStyleSheet( JURI::root(). 'media/system/css/jquery-ui-1.8.13.custom.css' );
        
        //  Hoja de estilos para tablas
        $document->addStyleSheet( JURI::root(). 'media/system/css/tablesorter/jquery-tablesorter-style.css' );
        
        //  Hoja de estilos para alertas
        $document->addStyleSheet( JURI::root(). 'media/system/css/alerts/jquery.alerts.css' );
        
         //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root(). 'media/system/js/jquery-1.7.1.min.js' );
        
        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root(). 'media/system/js/jquery-noconflict.js' );
        
        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript( JURI::root(). 'media/system/js/alerts/jquery.alerts.js' );
        
        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root(). 'media/system/js/jquery-ui-1.8.13.custom.min.js' );
        
        //  Adjunto libreria que la gestion orden de informacion de tablas
        $document->addScript( JURI::root(). 'media/system/js/jquery.tablesorter.min.js' );
        
        //  Adjunto libreria que la gestion de Medios de Verificacion y Supuestos
        $document->addScript( JURI::root(). 'components/com_proyectos/views/medioverificacion/assets/reglas.js' );
                
        //  Adjunto libreria con objetos de medios de verificacion y Supuestos
        $document->addScript( JURI::root(). 'components/com_proyectos/views/medioverificacion/assets/GestionMedioVerificacion.js' );
        
        //  Adjunto libreria con objetos de medios de verificacion y Supuestos
        $document->addScript( JURI::root(). 'components/com_proyectos/views/medioverificacion/assets/MedioVerificacion.js' );
        
        //  Adjunto libreria con objetos de medios de verificacion y Supuestos
        $document->addScript( JURI::root(). 'components/com_proyectos/views/medioverificacion/assets/Supuesto.js' );
        
        JText::script('COM_PROYECTOS_COBERTURA_ERROR_UNACCEPTABLE');
    }
}