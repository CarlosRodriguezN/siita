<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

//load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');
 
/**
 * Clase que muestra un conjunto de coordenadas de los graficos cp
 */
class CanastaproyViewCoordenadasgrafs extends JView
{
    /**
    * Metodo que muestra la lista las coordenadas de los graficos cp
    * @return void
    */
    
    protected $items;
    protected $pagination;
    protected $state;
    
    
    function display($tpl = null) 
    {
        //  Ejecutamos la funcion "get" de la clase JView, 
        //  la cual internamente accede al "CanastaproyModelCoordenadasgrafs" y ejecuta el metodo "getItems"
        $items = $this->get('Items');

        //  Ejecuta el metodo getPagination propio de la clase JModel
        $pagination = $this->get('Pagination');

        // Verifica errores.
        if ( count( $errors = $this->get( 'Errors' ) ) ){
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }
        
        // Asigna la data a la vista
        $this->items = $items;
        $this->pagination = $pagination;

        //  Ejecuta el metodo "populateState" de la clase 
        //  "CanastaproyModelCoordenadasgrafs" del modelo
        $this->state = $this->get( 'State' );

        // Muestra el template
        parent::display( $tpl );

        // Asigna el document
        $this->setDocument();
    }
    
    /**
    * Configuración de la barra de herramientas
    */
    protected function getToolbar() 
    {
        $bar = new JToolBar( 'toolbar' );

        //  y hacer las llamadas que requieren
        $bar->appendButton( 'Standard', 'new', 'Nuevo', 'coordenadagraf.add', false );
        $bar->appendButton( 'Standard', 'edit', 'Editar', 'coordenadagraf.edit', true );
        
        $bar->appendButton( 'Separator' );
        
        $bar->appendButton( 'Standard', 'publish', 'Publicar', 'coordenadasgrafs.publish', true );
        $bar->appendButton( 'Standard', 'unpublish', 'Despublicar', 'coordenadasgrafs.unpublish', true );
        
        //genera el html y retorna
        return $bar->render();
    }
    
    
    /**
    * Método para configurar las propiedades del document
    *
    * @return void
    */
    protected function setDocument() 
    {
        $document = JFactory::getDocument();
        
        //  Acedemos a la hoja de estilos del administrador
        $document->addStyleSheet( 'administrator/templates/system/css/system.css' );
        
        //  Agregamos hojas de estilo complementarias 
        $document->addCustomTag(
            '<link href="administrator/templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />'."\n\n".
            '<!--[if IE 7]>'."\n".
            '<link href="administrator/templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />'."\n".
            '<![endif]-->'."\n".
            '<!--[if gte IE 8]>'."\n\n".
            '<link href="administrator/templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />'."\n".
            '<![endif]-->'."\n"
        );
        
        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root(). 'media/system/js/jquery-1.7.1.min.js' );
        
        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root(). 'media/system/js/jquery-noconflict.js' );
        
        JText::script('COM_CANASTAPROY_COORDENADA_GRAF_ERROR_UNACCEPTABLE');
    }
}