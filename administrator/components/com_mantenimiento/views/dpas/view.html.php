<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Clase que muestra un conjunto de Unidades de medida
 */
class mantenimientoViewDpas extends JView
{
    /**
    * HelloWorlds view display method
    * @return void
    */
    
    protected $items;
    protected $pagination;
    protected $state;
    
    
    function display($tpl = null) 
    {
        //  Ejecutamos la funcion "get" de la clase JView, 
        //  la cual internamente accede al "docenteModelCategorias" y ejecuta el metodo "getItems"
        $items = $this->get('Items');
      //  var_dump($items);exit();
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

        //  Ejecuta el metodo "populateState" de la clase 
        //  "IndicadoresModelUnidadMedidas" del modelo
        $this->state = $this->get( 'State' );
        
        //  Llamada al metodo que publica 
        //  la barra de herramientas en backEnd del sitio
        $this->addToolBar();

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }
 
    /**
    * Setting the toolbar
    */
    protected function addToolBar() 
    {
        JToolBarHelper::title(JText::_( 'COM_MANTENIMIENTO_MANAGER_DPA' ), 'dpa' );
        
        JToolBarHelper::addNewX('dpa.add');
        JToolBarHelper::editListX('dpa.edit');

        //  Muestra un separador de tarea de la barra de herramientas
        JToolBarHelper::divider();
        
        //  Muestra la opcion para publicar una determinada( seleccionada ) actividad
        JToolBarHelper::publish( 'dpas.publish' );
                
        //  Muestra la opcion para des publicar una determinada( seleccionada ) actividad
        JToolBarHelper::unpublish( 'dpas.unpublish' );
        
        //  Muestra un separador de tarea de la barra de herramientas
        JToolBarHelper::divider();
        
        //  Gestiona la migracion de datos a las tablas DPA
        JToolBarHelper::custom( 'dpa.cargaDatos', '', '', 'Carga Datos', false );
        
        //  Muestra un separador de tarea de la barra de herramientas
        JToolBarHelper::divider();
        
        //  Crea las tablas para la auditoria del sistema
        JToolBarHelper::custom( 'dpa.makeTablesTrigger', '', '', 'Tablas-Trigger', false );
        
        //  Crea los Triggers para la auditoria del sistema
        JToolBarHelper::custom( 'dpa.makeTrigger', '', '', 'Trigger', false );
        
    }
    
    /**
    * Method to set up the document properties
    *
    * @return void
    */
    protected function setDocument() 
    {
        $document = JFactory::getDocument();
        $document->setTitle( JText::_( 'COM_MANTENIMIENTO_ADMINISTRATION_DPA' ) );
    }
}