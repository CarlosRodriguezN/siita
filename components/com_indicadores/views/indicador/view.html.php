<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

//  load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');

//  Cargo libreria de Lenguajes
jimport( 'joomla.language.language' );
 
/**
 * Vista Fase
 */
class IndicadoresViewIndicador extends JView
{
    protected $_idIndicador;
    protected $_tpoIndicador;
    protected $_idRegIndicador;
    protected $_idRegObjetivo;
    protected $_tpo;
    protected $_idPlan;
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
        $this->canDo = IndicadoresHelper::getActions( $this->item->id );

        // Check for errors.
        if ( count( $errors = $this->get( 'Errors' ) ) ){
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }

        //  Asigno valores
        $this->_idIndicador     = JRequest::getVar( 'idIndicador' );
        $this->_tpoIndicador    = JRequest::getVar( 'tpoIndicador' );
        $this->_idRegIndicador  = JRequest::getVar( 'idRegIndicador' );
        $this->_idRegObjetivo   = JRequest::getVar( 'idRegObjetivo' );
        $this->_tpo             = JRequest::getVar( 'tpo' );
        $this->_idPlan          = JRequest::getVar( 'idPlan' );
        $this->_ent             = JRequest::getVar( 'ent' );
        
        $this->form = $form;

        //  En caso que el indicador a gestionar sea de tipo Meta
        //  Actualizo cambio etiquetas del formulario de gestion de indicadores
        //  por gestion de Indicador Meta
        switch( true ){
            case( $this->_tpo == 'meta' ): 
                $languaje = JFactory::getLanguage();
                $languaje->load( 'com_indMeta', JPATH_SITE );
                //  Assign the Data
                $this->title = JText::_( 'COM_INDICADORES_FRM_INDICADORES' );
            break;

            case( $this->_tpo == 'ime' ): 
                $languaje = JFactory::getLanguage();
                $languaje->load( 'com_indMeta', JPATH_SITE );
                $this->title = JText::_( 'COM_INDICADORES_FRM_INDICADORES_IME' );
            break;

            default:
                //  Assign the Data
                $this->title = JText::_( 'COM_INDICADORES_FRM_INDICADORES' );
            break;
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

        $input  = JFactory::getApplication()->input;
        $input->set( 'hidemainmenu', true );
        $user   = JFactory::getUser();

        if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ){
            $bar->appendButton( 'Standard', 'save', 'Agregar', 'indicador.asignar', false );
        }

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
        $document->addScript( JURI::root(). 'components/com_indicadores/views/indicador/assets/Common.js' );
        
        //  Adjunto libreria que la gestion de Reglas de validacion de un formulario
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Reglas.js' );
        
        //  Adjunto libreria que la gestion de Combos Vinculantes
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Vinculantes.js' );
        
        
//        //  Hoja de estilos para la validacion de campos
//        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-validate/cmxform.css' );
//        
//        //  Adjunto libreria que gestion campos obligatorios
//        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.validate.js' );
//        
//        //  Mascaras, p.e.: telefono, etc
//        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.maskedinput.js' );
//        
//        //  Reglas del Sistema, p.e.: ingresos de coordenadas, fechas, etc
//        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/methods.validate.siita.js' );
        
        if( $this->_tpo == 'meta' ){
            //  Adjunto libreria que la gestion de Indicadores asociados a un indicador
            $document->addScript( JURI::root(). 'components/com_indicadores/views/indicador/assets/GestionIndicadorMeta.js' );
        }else if( $this->_tpo == 'ime' ){
            //  Adjunto libreria que la gestion de Indicadores asociados a un indicador
            $document->addScript( JURI::root(). 'components/com_indicadores/views/indicador/assets/GestionIndicadorMeta.js' );
            $document->addScript( JURI::root(). 'components/com_indicadores/views/indicador/assets/ReglasIME.js' );
        }else{
            //  Adjunto libreria que la gestion Presentacion de informacion de un Indicadores asociados a un indicador
            $document->addScript( JURI::root(). 'components/com_indicadores/views/indicador/assets/GestionIndicador.js' );
        }
        
        //  Adjunto libreria que la gestion Presentacion de informacion de un Indicadores asociados a un indicador
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/CommonIndicadores.js' );
        
        //  Adjunto clase indicador
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Indicador.js' );
        
        //  Adjunto libreria que la gestion de Unidad Territorial
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/GestionUndTerritorial.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/UnidadTerritorial.js' );
        
        //  Adjunto libreria que la gestion de Linea Base
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/LineaBase.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/GestionLineaBase.js' );
        
        //  Adjunto libreria que la gestion de Rangos
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Rango.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/GestionRangos.js' );

        //  Adjunto libreria clase Variable 
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Variable.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/GestionVariables.js' );

        //  Adjunto libreria Clase Enfoque
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Dimension.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/GestionDimension.js' );
        
        //  Adjunto libreria clase Planificacion
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Planificacion.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/GestionPlanificacion.js' );
        
        //  Adjunto libreria clase Seguimiento
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/GestionSeguimiento.js' );
        $document->addScript( JURI::root(). 'components/com_indicadores/views/assets/Seguimiento.js' );
        
        //  Adjunto libreria de gestion de validacion
        $document->addScript( JURI::root() . 'media/system/js/jquery-validate/common_validate.js' );
        
        JText::script('COM_PROYECTOS_COBERTURA_ERROR_UNACCEPTABLE');
    }
}