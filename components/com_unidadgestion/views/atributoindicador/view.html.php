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
class UnidadgestionViewAtributoIndicador extends JView
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
        $this->_idIndicador = JRequest::getVar( 'idIndicador' );
        $this->_tpoIndicador = JRequest::getVar( 'tpoIndicador' );
        $this->_idRegObjetivo = JRequest::getVar( 'registroObj' );
        
        $this->_tpo = JRequest::getVar( 'tpo' );
        
        $this->titleLst = JText::_( 'COM_PEI_LST_INDICADORES' );
        $this->titleFrm = JText::_( 'COM_PEI_FRM_INDICADORES' );
        
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
        
        //and make whatever calls you require
        $bar->appendButton( 'Standard', 'save', 'Agregar', 'atributosindicador.asignar', false );
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
        
        //and make whatever calls you require
        $bar->appendButton( 'Standard', 'save', 'Agregar Indicador', 'indicadorObjetivo.asignar', false );
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
        
        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root(). 'media/system/js/jquery-ui-1.8.13.custom.min.js' );
        
        //  Adjunto libreria que la gestion orden de informacion de tablas
        $document->addScript( JURI::root(). 'media/system/js/jquery.tablesorter.min.js' );
        
        //  Adjunto libreria que la gestion Presentacion de informacion de un Indicadores asociados a un indicador
        $document->addScript( JURI::root(). 'components/com_unidadgestion/views/atributoindicador/assets/Common.js' );
        
        //  Adjunto libreria que la gestion Presentacion de informacion de un Indicadores asociados a un indicador
        $document->addScript( JURI::root(). 'components/com_unidadgestion/views/atributoindicador/assets/GestionObjetivoIndicador.js' );
        
        //  Adjunto libreria que la gestion Presentacion de informacion de un Indicadores asociados a un indicador
        $document->addScript( JURI::root(). 'components/com_unidadgestion/views/atributoindicador/assets/ReglasInicio.js' );
        
        //  Adjunto clase indicador
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Indicador.js' );

        //  Adjunto libreria que la gestion de Reglas de validacion de un formulario
        $document->addScript( JURI::root(). 'components/com_unidadgestion/views/atributoindicador/assets/Reglas.js' );
        
        //  Adjunto libreria que la gestion de Combos Vinculantes
        $document->addScript( JURI::root(). 'components/com_unidadgestion/views/atributoindicador/assets/Vinculantes.js' );
        
        //  Adjunto libreria que la gestion de Unidad Territorial
        $document->addScript( JURI::root(). 'components/com_unidadgestion/views/atributoindicador/assets/GestionUndTerritorial.js' );
        
        //  Adjunto libreria Unidad Territorial
        $document->addScript( JURI::root(). 'components/com_unidadgestion/views/atributoindicador/assets/UnidadTerritorial.js' );
        
        //  Adjunto libreria que la gestion de Linea Base
        $document->addScript( JURI::root(). 'components/com_unidadgestion/views/atributoindicador/assets/GestionLineaBase.js' );
        
        //  Adjunto libreria clase Linea Base
        $document->addScript( JURI::root(). 'components/com_unidadgestion/views/atributoindicador/assets/LineaBase.js' );
        
        //  Adjunto libreria que la gestion de Rangos
        $document->addScript( JURI::root(). 'components/com_unidadgestion/views/atributoindicador/assets/GestionRangos.js' );
        
        //  Adjunto libreria clase Rango
        $document->addScript( JURI::root(). 'components/com_unidadgestion/views/atributoindicador/assets/Rango.js' );
        
        //  Adjunto libreria clase Variable 
        $document->addScript( JURI::root(). 'components/com_unidadgestion/views/atributoindicador/assets/Variable.js' );        
        
        //  Adjunto libreria que la gestion de Variables
        $document->addScript( JURI::root(). 'components/com_unidadgestion/views/atributoindicador/assets/GestionVariables.js' );
        
        //  Adjunto libreria Clase Enfoque
        $document->addScript( JURI::root(). 'components/com_unidadgestion/views/atributoindicador/assets/Dimension.js' );

        //  Adjunto libreria Clase Gestion Enfoque
        $document->addScript( JURI::root(). 'components/com_unidadgestion/views/atributoindicador/assets/GestionDimension.js' );
        
        JText::script('COM_UNIDAD_GESTION_COBERTURA_ERROR_UNACCEPTABLE');
    }
}