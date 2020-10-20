<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

//load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');
JHTML::_('behavior.modal'); 
/**
 * Vista de Ingreso /Edicion de un Programa
 */
class adminmapasViewMapa extends JView {

    /**
     * display method of Hello view
     * @return void
     */
    public function display($tpl = null)
    {
        //Estos archivos van en busca de el modelo en /com_amdin mapas/models
        $item = $this->get('Item');
        $script = $this->get('Script');
        //Se carga de /com_amdinmapas/models/mapa.php
        $form = $this->get('Form');
        
        $ltsWMSLayers = ( $item <> '0' )? $this->_getlayerseWMS( $item->intCodigo_wms ) 
                                        : '';
        
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        $this->script = $script;
        $this->layers = $ltsWMSLayers;
        
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
        $bar = new JToolBar('toolbar');

        //and make whatever calls you require
        $bar->appendButton('Standard', 'save', 'Guardar', 'mapa.save', false);
        $bar->appendButton('Separator');
        $bar->appendButton( 'Standard', 'delete', 'Eliminar', 'mapa.delete', true );
        $bar->appendButton('Separator');
        $bar->appendButton('Standard', 'cancel', 'Cancelar', 'mapa.cancel', false);

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

        //  Accdemos a la hoja de estilos de uploadify
        $document->addStyleSheet(JURI::root() . 'media/system/css/uploadify/uploadify.css');

        //  PlugIn jQuery uploadify
        $document->addScript(JURI::root() . 'media/system/js/uploadify/swfobject.js');

        //  Adjunto script JQuery al sitio
        $document->addScript(JURI::root() . 'media/system/js/jquery-1.7.1.min.js');

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript(JURI::root() . 'media/system/js/jquery-noconflict.js');

        //  PlugIn jQuery uploadify
        $document->addScript( JURI::root(). 'media/system/js/uploadify/jquery.uploadify.js' );
        
        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript(JURI::root() . 'components/com_adminmapas/views/mapa/assets/mapa.js');
        
        JText::script('COM_ADMINMAPAS_MAPAS_ERROR_UNACCEPTABLE');
    }


    
    private function _getlayerseWMS( $intCodigo_wms )
    {
        $modelo = $this->getModel();
        $info = $modelo->getlayerseWMS( $intCodigo_wms );
        return $info;
    }
}