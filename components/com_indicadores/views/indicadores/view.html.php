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
class IndicadoresViewIndicadores extends JView
{
    protected $_idIndicador;
    protected $_idRegIndicador;
    protected $_idRegObjetivo;
    
    protected $_tpoIndicador;
    protected $_tpo;
    protected $_tpoPlan;
    protected $_idPlan;
    protected $_idUsr;
    
    protected $canDo;
    
    /**
    * display method of Hello view
    * @return void
    */
    public function display($tpl = null) 
    {
        // get the Data
        $form = $this->get('Form');        
        
        // What Access Permissions does this user have? What can (s)he do?
        $this->canDo = IndicadoresHelper::getActions();
        
        // Check for errors.
        if ( count( $errors = $this->get( 'Errors' ) ) ){
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }
        
        // Assign the Data
        $this->form = $form;
        
        //  Asigno valores
        $this->_tpoIndicador    = JRequest::getVar( 'tpoIndicador' );
        $this->_tpo             = JRequest::getVar( 'tpo' );
        $this->_tpoPlan         = JRequest::getVar( 'tpoPlan' );
        $this->_idPlan          = JRequest::getVar( 'idPlan' );
        $this->_idPoa           = JRequest::getVar( 'idPoa' );
        $this->_idRegObjetivo   = JRequest::getVar( 'idRegObjetivo' );
        $this->_idIndicador     = JRequest::getVar( 'idIndicador' );
        
        $this->_idUsr           = JRequest::getVar( 'idUsr' );
        
        $this->titleLst = JText::_( 'COM_INDICADORES_LST_INDICADORES' );
        $this->titleFrm = JText::_( 'COM_INDICADORES_FRM_INDICADOR' );

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }
    
    /**
    * ToolBar - Asignacion de Indicadores a un Objetivo
    */
    protected function getToolbarLista() 
    {
        $bar = new JToolBar( 'toolbar' );

        //  And make whatever calls you require
        if( $this->canDo->get( 'core.create' ) ){
            $bar->appendButton( 'Standard', 'save', JText::_( 'BTN_GUARDAR' ), 'atributosindicador.asignar', false );
        }

        $bar->appendButton( 'Standard', 'cancel', 'Cancelar', 'atributosindicador.cancel', false );
        
        //generate the html and return
        return $bar->render();
    }
    
    /**
    * ToolBar - Formulario de creacion de un indicador
    */
    protected function getToolbarFrm() 
    {
        $bar = new JToolBar( 'toolbar' );
        
        if( $this->canDo->get( 'core.create' ) ){
            $bar->appendButton( 'Standard', 'save', 'Agregar Indicador', 'indicadorObjetivo.asignar', false );
        }

        $bar->appendButton( 'Standard', 'cancel', 'Cancelar', 'indicadorObjetivo.cancel', false );
        
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
        
        //  Adjunto libreria que la gestion orden de informacion de tablas
        $document->addScript( JURI::root(). 'media/system/js/jquery.tablesorter.min.js' );
        
        //  Adjunto libreria que la gestion Presentacion de informacion de un Indicadores asociados a un indicador
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/CommonIndicadores.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/indicadores/assets/Common.js' );
        
        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root(). 'media/system/js/FormatoNumeros.js' );

        //  Adjunto libreria que la gestion de Reglas de validacion de un formulario
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Reglas.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/indicadores/assets/ReglasIndicadores.js' );

        //  Adjunto libreria que la gestion de Combos Vinculantes
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Vinculantes.js' );

        //  Adjunto librerias de Gestion de Indicador
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Indicador.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Programacion.js' );

        $document->addScript( JURI::root(). 'components/com_indicadores/views/indicadores/assets/GestionObjetivoIndicador.js' );

        //  Adjunto librerias  gestion de Unidad Territorial
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/UnidadTerritorial.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/GestionUndTerritorial.js' );

        //  Adjunto librerias de Gestion de Lineas Base
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/LineaBase.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/GestionLineaBase.js' );

        //  Adjunto libreria que la gestion de Rangos
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Rango.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/GestionRangos.js' );

        //  Adjunto librerias de Gestion de Enfoques Dimension de un Determinado Indicador
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Dimension.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/GestionDimension.js' );

        //  Adjunto libreria clase Variable 
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/GestionVariables.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Variable.js' );

        //  Adjunto libreria clase Planificacion
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/GestionPlanificacion.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Planificacion.js' );

        //  Adjunto libreria clase Seguimiento
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/GestionSeguimiento.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Seguimiento.js' );

        //  Adjunto libreria que gestion los Mantenimientos
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Mantenimiento.js' );

        //  Adjunto libreria que gestion campos obligatorios
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.validate.js' );
        
        //  Mascaras, p.e.: telefono, etc
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.maskedinput.js' );
        
        //  Reglas del Sistema, p.e.: ingresos de coordenadas, fechas, etc
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/methods.validate.siita.js' );
        
        //  Adjunto libreria de gestion de validacion
        $document->addScript( JURI::root() . 'media/system/js/jquery-validate/common_validate.js' );

        JText::script('COM_PROYECTOS_COBERTURA_ERROR_UNACCEPTABLE');
    }
}