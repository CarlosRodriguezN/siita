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
class adminmapasViewEcoraeMapa extends JView
{

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

        // Check for errors.
        if( count($errors = $this->get('Errors')) ){
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        $this->script = $script;
        $this->layers = $this->_getLstLayers($item->intCodigo_wms);
        
        //  Lista de Shapes
        $this->shape        = $this->_getFilesMapas( $item->intCodigo_wms, 1 );
        $this->imagenes     = $this->_getFilesMapas( $item->intCodigo_wms, 2 );
        $this->geoServicios = $this->_getFilesMapas( $item->intCodigo_wms, 3 );

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }

    private function _getLstLayers($intCodigo_wms)
    {
        $mdMapa = &JModel::getInstance('Mapa', 'adminmapasModel');
        return $mdMapa->getlayerseWMS($intCodigo_wms);
    }
    
    
    
    private function _getFilesMapas( $intCodigo_wms, $idTpo )
    {
        $mdAM = $this->getModel();
        return $mdAM->getFilesMapas( $intCodigo_wms, $idTpo );
    }
    
    /**
     * Setting the toolbar
     */
    protected function getToolbar()
    {
        $bar = new JToolBar('toolbar');

        //and make whatever calls you require
        $bar->appendButton('Standard', 'save', 'Guardar', 'ecoraemapa.save', false);
        $bar->appendButton('Separator');
        $bar->appendButton('Standard', 'delete', 'Eliminar', 'ecoraemapa.delete', true);
        $bar->appendButton('Separator');
        $bar->appendButton('Standard', 'cancel', 'Cancelar', 'ecoraemapa.cancel', false);

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
        // Hoja de estilos para la carga de imagenes
        $document->addStyleSheet(JURI::root() . 'media/system/css/uploadfive/uploadifive.css');
        
        //  Hoja de estilos para tablas
        $document->addStyleSheet(JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css');

        //  PlugIn jQuery uploadify
        $document->addScript(JURI::root() . 'media/system/js/uploadify/swfobject.js');

        //  Adjunto script JQuery al sitio
        $document->addScript(JURI::root() . 'media/system/js/jquery-1.7.1.min.js');

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript(JURI::root() . 'media/system/js/jquery-noconflict.js');

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        //  $document->addScript(JURI::root() . 'components/com_adminmapas/views/ecoraemapa/assets/mapa.js');

        //  Adjunto libreria que permite el trabajo carga de imagenes
        $document->addScript(JURI::root() . 'media/system/js/uploadfive/jquery.uploadifive.min.js');
        
        //  PlugIn jQuery uploadify
        $document->addScript(JURI::root() . 'components/com_adminmapas/views/ecoraemapa/assets/uploadFiles.js');

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript(JURI::root() . 'components/com_adminmapas/views/mapa/assets/mapa.js');
        
        //  Adjunto libreria que permite el bloqueo de la pagina en llamadas ajax
        $document->addScript( JURI::root() . 'media/system/js/blockUI/jquery.blockUI.js' );

        JText::script('COM_ADMINMAPAS_MAPAS_ERROR_UNACCEPTABLE');
    }

}
