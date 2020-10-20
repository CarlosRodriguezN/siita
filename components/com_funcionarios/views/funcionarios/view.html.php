<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 //load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');
/**
 * Clase que muestra un conjunto de Unidades de medida
 */
class FuncionariosViewFuncionarios extends JView
{
    /**
    * HelloWorlds view display method
    * @return void
    */
    
    protected $items;
    protected $pagination;
    protected $state;
    protected $canDo;
            
    function display($tpl = null) 
    {
        //  Ejecutamos la funcion "get" de la clase JView, 
        //  la cual internamente accede al "docenteModelCategorias" y ejecuta el metodo "getItems"
        $items = $this->get('Items');
        
        //  Ejecuta el metodo getPagination propio de la clase JModel
        $pagination = $this->get('Pagination');

        // ¿Qué permisos de acceso tiene este usuario? ¿Qué se puede (s) hacer?
        $this->canDo = FuncionariosHelper::getActions();
        
        // Check for errors.
        if ( count( $errors = $this->get( 'Errors' ) ) ){
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }
        
        // Assign data to the view
        $this->items = $items;
        $this->pagination = $pagination;

        //  Ejecuta el metodo "populateState" de la clase 
        //  "IndicadoresModelUnidadMedidas" del modelo
        $this->state = $this->get( 'State' );
        $this->lstUG = $this->get( 'LstUG' );
        $this->lstFncUg = $this->_getLstFncUg( $items, $this->lstUG );
        $this->idUg = null;
        
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

        //  And make whatever calls you require
        if( $this->canDo->get( 'core.create' ) ){
            $bar->appendButton( 'Standard', 'new', JText::_( 'COM_FUNCIONARIO_EVENTO_NUEVO' ), 'funcionario.add', false );
        }

        $bar->appendButton( 'Standard', 'cancel', JText::_( 'COM_FUNCIONARIO_EVENTO_CANCELAR' ), 'funcionario.cancel', false );
        
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
        // Hoja de estilos para la carga de imagenes
        $document->addStyleSheet( JURI::root() . 'media/system/css/uploadfive/uploadifive.css' ); 
        
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

        //  Adjunto libreria java script upladifi
        $document->addScript( JURI::root() . '/media/system/js/uploadfive/jquery.uploadifive.min.js' );

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );

        //  Adjunto libreria para alertas
        $document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );
        
        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'components/com_funcionarios/views/funcionarios/assets/Reglas.js' );
        
        JText::script('COM_FUNCIONARIOS_FNC_ERROR_UNACCEPTABLE');
    }
    
    /**
     *  Retorna el arbol de unidades de gestion con sus funcionarios
     * @return type
     */
    private function _getLstFncUg( $lstFnc, $lstUG ) 
    {
        if ( $lstUG ) {
            $lstFuncionariosUG = array();
            foreach ( $lstUG AS $ug ) {
                $id = $ug->idUG;
                $lstFncUg = array();
                foreach ( $lstFnc AS $funcionario ) {
                    if ($funcionario->idUg == $id) {
                        $lstFncUg[] = $funcionario;
                    }
                }
                $ug->lstaFncUG = $lstFncUg;
                $lstFuncionariosUG[$id] = $ug;
            }
        }
        return $lstFuncionariosUG;
    }
}