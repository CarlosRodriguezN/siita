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
class IndicadoresViewContexto extends JView
{
    
    protected $canDo;

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
        $this->_idRegContexto = JRequest::getVar( 'idRegContexto' );
        $this->title = JText::_( 'COM_INDICADORES_CONTEXTO_INDICADORES' );
        
        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }
    
    
    
    private function _getTicketTableu( $nombreDashBoard )
    {
        //  Retorna informacion URL con ticket de confianza de los server de ECORAE
        $mdTableau = $this->getModel( 'Contexto', 'IndicadoresView' );
        return $mdTableau->getTicketTableuPorNombre( $nombreDashBoard );
    }
    
    
    
    /**
    * ToolBar - Asignacion de Indicadores a un Objetivo
    */
    protected function getToolbar() 
    {
        $bar = new JToolBar( 'toolbar' );
        
        if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ){
            $bar->appendButton( 'Standard', 'save', 'Agregar', 'contexto.asignar', false );
        }

        $bar->appendButton( 'Standard', 'cancel', 'Cancelar', 'contexto.cancel', false );
        
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
        
        //  Adjunto libreria que la gestion orden de informacion de tablas
        $document->addScript( JURI::root(). 'media/system/js/jquery.tablesorter.min.js' );
        
        //  Adjunto libreria que la gestion Presentacion de informacion de un Indicadores asociados a un indicador
        $document->addScript( JURI::root(). 'components/com_indicadores/views/contexto/assets/Common.js' );
        
        //  Adjunto clase indicador
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Indicador.js' );
        

        //  Adjunto libreria que la gestion de Reglas de validacion de un formulario
        $document->addScript( JURI::root(). 'components/com_indicadores/views/contexto/assets/reglas.js' );
        
        //  Adjunto libreria que la gestion de Combos Vinculantes
        $document->addScript( JURI::root(). 'components/com_indicadores/views/contexto/assets/Vinculantes.js' );

        //  Adjunto libreria que la gestion Presentacion de informacion de un Indicadores asociados a un indicador
        $document->addScript( JURI::root(). 'components/com_indicadores/views/contexto/assets/GestionIndicadorContexto.js' );
        
         //  Adjunto libreria que la gestion de Rangos
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/GestionRangos.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Rango.js' );
        
        //  Adjunto libreria clase Variable 
        $document->addScript( JURI::root(). 'components/com_indicadores/views/contexto/assets/Variable.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/contexto/assets/GestionVariables.js' );
        
        //  Adjunto libreria de gestion de validacion
        $document->addScript( JURI::root() . 'media/system/js/jquery-validate/common_validate.js' );
        
        JText::script('COM_PROYECTOS_COBERTURA_ERROR_UNACCEPTABLE');
    }
}