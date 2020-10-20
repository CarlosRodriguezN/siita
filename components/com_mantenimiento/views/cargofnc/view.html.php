<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

//load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');

/**
 * Vista de Ingreso /Edicion de un SubProgramaPrograma
 */
class MantenimientoViewCargoFnc extends JView {

    /**
     * display method of Hello view
     * @return void
     */
    public function display($tpl = null) {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');
        $script = $this->get('Script');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        $this->script = $script;
        $this->lstCargos = $this->get( 'LstCargos' );
        
        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }

    /**
     * Setting the toolbar
     */
    protected function getToolbar() {
        $bar = new JToolBar('toolbar');

        //and make whatever calls you require
        $bar->appendButton('Standard', 'save',JText::_('COM_MANTENIMIENTO_GUARDAR'), 'cargofnc.save', false);
        $bar->appendButton('Standard', 'save',JText::_('COM_MANTENIMIENTO_GUARDAR_SALIR'), 'cargofnc.saveExit', false);
        
        if ($this->item->inpCodigo_cargo != 0){
            $bar->appendButton('Standard', 'delete',JText::_('COM_MANTENIMIENTO_ELIMINAR'), 'cargofnc.delete', false);
        }
        
        $bar->appendButton('Separator');
        $bar->appendButton('Standard', 'cancel', JText::_('COM_MANTENIMIENTO_CANCELAR'), 'cargofnc.cancel', false);

        //generate the html and return
        return $bar->render();
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument() {
        $document = JFactory::getDocument();

        //  Accdemos a la hoja de estilos del administrador
        $document->addStyleSheet('administrator/templates/system/css/system.css');

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

        //  hoja de estilos para los formularios
        $document->addStyleSheet(JURI::root() . 'media/system/css/siita/common.css');

        //  Accdemos a la hoja de estilos de uploadify
        $document->addStyleSheet(JURI::root() . 'media/system/css/uploadify/uploadify.css');

        //  Hoja de estilos para pestañas - tabs
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css' );

        //  Hoja de estilos para alertas
        $document->addStyleSheet( JURI::root() . 'media/system/css/alerts/jquery.alerts.css' );

        //  Hoja de estilos para tablas
        $document->addStyleSheet( JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css' );

        //  Hoja de estilos para la vista de arboles
        $document->addStyleSheet( JURI::root() . 'media/system/css/treeview/jquery.treeview.css' );
        $document->addStyleSheet( JURI::root() . 'media/system/css/treeview/screen.css' );
        
        //  Hoja de estilos para la validacion de campos
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-validate/cmxform.css' );

        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );
        
        //  Adjunto libreria para alertas
        $document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );
        
        //  Adjunto libreria para armar los la vista de arbol
        $document->addScript( JURI::root() . 'media/system/js/treeview/jquery.treeview.js' );
        
        //  Adjunto libreria que permite el bloqueo de la pagina en llamadas ajax
        $document->addScript( JURI::root() . 'media/system/js/blockUI/jquery.blockUI.js' );
        
        //  Adjunto librerias para la validacion de formularios
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.validate.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/jquery.maskedinput.js' );
        $document->addScript( JURI::root() . '/media/system/js/jquery-validate/additional-methods.js' );
        
        //  Adjunto libreria que controla la gestion de los cargos
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/cargofnc/assets/Cargos.js' );
        
        //  Adjunto libreria que controla la gestion de los cargos
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/cargofnc/assets/Save.js' );
        
        //  Adjunto libreria para el lenguaje
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/cargofnc/assets/ES_CARGO_FNC.js' );
        
        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root(). 'components/com_mantenimiento/views/cargofnc/assets/Reglas.js' );
        
        //  Adjunto el script para la carga de los items de la agenda
        $document->addScriptDeclaration( $this->_getLstCargos(), $type = 'text/javascript' );
        
        JText::script('COM_MANTENIMIENTO_CARGO_FNC_ERROR_UNACCEPTABLE');
    }
    
    /**
     * 
     * @return string
     */
    private function _getLstCargos()
    {
        $retval = '';
        $retval .= 'lstCargos = new Array();'; 
        if( $this->lstCargos ) {
            foreach( $this->lstCargos as $componente ) {
                $retval .= 'var objCargo = new Object();';
                $retval .= 'objCargo = ' . json_encode( $componente ) . ';';
                $retval .= 'lstCargos.push( objCargo );';
            }
        }

        return $retval;
    }
    
}