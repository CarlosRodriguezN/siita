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
class ProyectosViewCanastaProyectos extends JView
{
    /**
    * HelloWorlds view display method
    * @return void
    */
    protected $items;
    protected $pagination;
    protected $state;
    protected $_programas;
    
    function display($tpl = null) 
    {
        //  Ejecutamos la funcion "get" de la clase JView, 
        //  la cual internamente accede al "ProyectosModelFases" y ejecuta el metodo "getItems"
        $items = $this->get('Items');
        
        //  Ejecuta el metodo getPagination propio de la clase JModel
        $pagination = $this->get('Pagination');

        // Check for errors.
        if ( count( $errors = $this->get( 'Errors' ) ) ){
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }
        
        $this->title = ( $item->intCodigo_pry == 0 )? JText::_('COM_PROYECTOS_ADD_PROYECTO_TITLE') 
                                                    : JText::_('COM_PROYECTOS_EDIT_PROYECTO_TITLE');
        
        // Assign data to the view
        $this->items = $items;
        $this->pagination = $pagination;

        //  Ejecuta el metodo "populateState" de la clase 
        //  "ProyectosModelFases" del modelo
        $this->state = $this->get( 'State' );

        //  var_dump( $this->state->get( 'filter.published' ) ); exit;
        
        //  Retorno una lista de programas registrados en el sistema SIITA
        $this->_programas = $this->_getLstProgramas();
        
        
        
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

        //  Agrego el boton asignar
        $bar->appendButton( 'Standard', 'new', JText::_( 'COM_PROYECTO_CANASTA_ASIGNAR' ), 'proyecto.addCanastaProyecto', true );

        //  Agrego el boton Creo
        $bar->appendButton( 'Standard', 'new', JText::_( 'COM_PROYECTO_CANASTA_CREAR_PROYECTO' ), 'proyecto.crearProyecto', true );

        //  Agrego el boton cancelar
        $bar->appendButton( 'Standard', 'cancel', JText::_( 'COM_PROYECTO_CANASTA_CANCELAR_PROYECTO' ), 'proyecto.cancel', false );
        
        //  Generate the html and return
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
        
        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript( JURI::root(). 'components/com_proyectos/views/canastaproyectos/assets/Vinculantes.js' );

        JText::script('COM_PRESUPUESTOS_COBERTURA_ERROR_UNACCEPTABLE');
    }

    
    /**
     * 
     * Retorna una lista de programas pertenecientes
     * 
     * @return type
     */
    private function _getLstProgramas()
    {
        $mdCanasta = $this->getModel();
        return $mdCanasta->lstProgramas();
    }
    
}