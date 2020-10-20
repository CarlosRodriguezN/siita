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
class MantenimientoViewCargosFnc extends JView
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
        //  la cual internamente accede al "MantenimientoModelTiposVariable" y ejecuta el metodo "getItems"
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
        $this->lstUG = $this->get( 'LstUG' );
        $this->lstCargosUg = $this->_getLstCargosUg( $items, $this->lstUG );
        $this->idUgReg = null;

        //  Ejecuta el metodo "populateState" de la clase 
        //  "ProyectosModelFases" del modelo
        $this->state = $this->get( 'State' );

        // Display the template
        parent::display( $tpl );

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
        $bar->appendButton( 'Standard', 'new', JText::_('COM_MANTENIMIENTO_NUEVO'), 'cargofnc.add', false );
        $bar->appendButton( 'Standard', 'cancel', JText::_('COM_MANTENIMIENTO_CANCELAR'), 'cargosfnc.cancel', false );
        
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

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );

        //  Adjunto libreria para alertas
        $document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );
        
        //  Adjunto libreria que controla los botones del Joomla
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/cargosfnc/assets/Reglas.js' );
        
        //  Adjunto libreria que controla los botones del Joomla
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/cargosfnc/assets/ES_CARGO.js' );
        
        //  Adjunto libreria que gestion la lista de cargos por unidad de gestion
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/cargosfnc/assets/GestionCargosUG.js' );
        
        //  Adjunto libreria que gestion la lista de cargos por unidad de gestion
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/cargosfnc/assets/GestionCargo.js' );
        
        //  Adjunto el script para la carga de la lista de Cargos por unidad de gestion
        $document->addScriptDeclaration( $this->_setLstCargosUg(), $type = 'text/javascript' );
        
        JText::script('COM_MANTENIMIENTO_CARGO_FNC_ERROR_UNACCEPTABLE');
    }
    
    /**
     *  Retorna el arbol de unidades de gestion con los cargos que posee
     * @return type
     */
    private function _getLstCargosUg( $lstCargo, $lstUG ) 
    {
        if ( $lstUG ) {
            foreach ( $lstUG AS $key=>$ug ) {
                $id = $ug->idUG;
                $ug->idReg = $key;
                $lstCrgUg = array();
                foreach ( $lstCargo AS $cargo ) {
                    if ($cargo->intCodigo_ug == $id) {
                        $reg = count($lstCrgUg);
                        $cargo->idReg = $reg;
                        $lstCrgUg[] = $cargo;
                    }
                }
                $ug->lstCargosUG = $lstCrgUg;
            }
        }
        return $lstUG;
    }
    
    
    private function _setLstCargosUg()
    {
        $retval = '';
        $retval .= 'objLstCargosUG = new GestionCargosUG();';
        if( $this->lstCargosUg ){
            foreach( $this->lstCargosUg as $objCargosUG ){
                $retval .= 'objLstCargosUG.addCargosUG( ' . json_encode( $objCargosUG ) . ' );';
            }
        }

        return $retval;
    }
}