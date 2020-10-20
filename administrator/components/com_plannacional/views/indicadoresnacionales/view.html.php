<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Vista de Categorias
 */
class PlanNacionalViewIndicadoresNacionales extends JView
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
        //  la cual internamente accede al "PlanNacionalModelCategorias" y ejecuta el metodo "getItems"
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

        //  Ejecuta el metodo "populateState" de la clase 
        //  "PlanNacionalModelCategorias" del modelo
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
        JToolBarHelper::title(JText::_( 'COM_PLANNACIONAL_MANAGER_INDICADORNACIONAL' ), 'indicadornacional' );
        
        JToolBarHelper::addNewX( 'indicadornacional.add' );
        JToolBarHelper::editListX( 'indicadornacional.edit' );

        //  Muestra un separador de tarea de la barra de herramientas
        JToolBarHelper::divider();
        
        //  Muestra la opcion para publicar una determinada( seleccionada ) actividad
        JToolBarHelper::publish( 'indicadoresnacionales.publish' );
                
        //  Muestra la opcion para des publicar una determinada( seleccionada ) actividad
        JToolBarHelper::unpublish( 'indicadoresnacionales.unpublish' );
        
        //  Muestra un separador de tarea de la barra de herramientas
        JToolBarHelper::divider();
        
        JToolBarHelper::deleteListX( '', 'indicadoresnacionales.delete' );
    }
    
    /**
    * Method to set up the document properties
    *
    * @return void
    */
    protected function setDocument() 
    {
        $document = JFactory::getDocument();
        $document->setTitle( JText::_( 'COM_PLANNACIONAL_ADMINISTRATION' ) );
    }
}