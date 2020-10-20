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
class ProyectosViewLineasBases extends JView
{
    /**
    * HelloWorlds view display method
    * @return void
    */
    
    protected $items;
    protected $pagination;
    protected $state;    
    protected $_lstInstituciones;
    protected $_idRegIndicador;
            
    function display($tpl = null) 
    {
        //  Ejecutamos la funcion "get" de la clase JView, 
        //  la cual internamente accede al "ProyectosModelLineasBases" y ejecuta el metodo "getItems"
        $items = $this->get('Items');
        
        //  Ejecuta el metodo getPagination propio de la clase JModel
        $pagination = $this->get('Pagination');

        // Check for errors.
        if ( count( $errors = $this->get( 'Errors' ) ) ){
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }
        
        // Assign data to the view
        $this->items = $items;
        $this->pagination = $pagination;
        
        $this->title = JText::_('COM_PROYECTOS_ADD_LINEABASE_TITLE');
        
        //  Asigno valores
        $this->_idProyecto = JRequest::getVar( 'intCodigo_pry' );
        $this->_tpoIndicador = JRequest::getVar( 'tpoIndicador' );
        $this->_idRegIndicador = JRequest::getVar( 'idRegIndicador' );
        $this->_tpo = JRequest::getVar( 'tpo' );

        $this->getLstInstituciones();
        
        //  Ejecuta el metodo "populateState" de la clase 
        //  "ProyectosModelLineasBases" del modelo
        $this->state = $this->get( 'State' );

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

        //  Asigno una linea Base al Indicador
        $bar->appendButton( 'Standard', 'new', 'Asignar', 'lineabase.asignar', false );
        
        //  Cancelo
        $bar->appendButton( 'Standard', 'cancel', 'Cancelar', 'lineabase.cancelar', false );

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
        
        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root(). 'media/system/js/jquery-1.7.1.min.js' );
        
        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root(). 'media/system/js/jquery-noconflict.js' );
        
        //  Adjunto libreria que la gestion de Lineas con un determinado indicador
        $document->addScript( JURI::root(). 'components/com_proyectos/views/lineasbases/assets/lineabase.js' );
        
        //  Adjunto libreria de gestion de Reglas
        $document->addScript( JURI::root(). 'components/com_proyectos/views/lineasbases/assets/Reglas.js' );
 
        JText::script('COM_PRESUPUESTOS_COBERTURA_ERROR_UNACCEPTABLE');
    }
    
    
    /**
     * Retorna una lista de instituciones en estado vigente
     */
    private function getLstInstituciones()
    {
        $options = array();
        $modelo = $this->getModel();
        $instituciones = $modelo->getLstInstituciones();
        
        foreach( $instituciones as $institucion ){
            $options[] = JHtml::_( 'select.option', $institucion->idInstitucion, $institucion->nombre );
        }

        $this->_lstInstituciones = $options;
    }
}