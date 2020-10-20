<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

//  load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');

//  Cargo libreria de Lenguajes
jimport('joomla.language.language');

/**
 * Vista Fase
 */
class AlineacionViewOperativa extends JView {

    /**
     * display method of Hello view
     * @return void
     */
    public function display($tpl = null) {
        // get the Data
        $form = $this->get('Form');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        //  Asigno valores
        $this->registroObj  = JRequest::getVar('registroObj');
        $this->tpoEntidad   = JRequest::getVar('tpoEntidad');
        $this->registroPoa  = JRequest::getVar('registroPoa');

        // Assign the Data
        $this->form = $form;

        $this->title = JText::_('COM_ALINEACION_OPERATIVA');

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }

    /**
     * ToolBar - Asignacion de Indicadores a un Objetivo
     */
    protected function getToolbar() {
        $bar = new JToolBar('toolbar');

        //and make whatever calls you require
        $bar->appendButton('Standard', 'save', 'Agregar', 'operativa.asignar', false);
        $bar->appendButton('Standard', 'cancel', 'Cancelar', 'operativa.cancel', false);

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

        //  Hoja de estilos para pestañas - tabs
        $document->addStyleSheet(JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css');

        //  Hoja de estilos para tablas
        $document->addStyleSheet(JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css');

        //  Hoja de estilos para alertas
        $document->addStyleSheet(JURI::root() . 'media/system/css/alerts/jquery.alerts.css');

        //  Adjunto script JQuery al sitio
        $document->addScript(JURI::root() . 'media/system/js/jquery-1.7.1.min.js');

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript(JURI::root() . 'media/system/js/jquery-noconflict.js');

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript(JURI::root() . 'media/system/js/alerts/jquery.alerts.js');

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript(JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js');

        //  Adjunto libreria que la gestion orden de informacion de tablas
        $document->addScript(JURI::root() . 'media/system/js/jquery.tablesorter.min.js');

        //  Adjunto libreria que la gestion Presentacion de informacion de una Alineacion
        $document->addScript(JURI::root() . 'components/com_alineacion/views/operativa/assets/AlineacionEcorae.js');
        $document->addScript(JURI::root() . 'components/com_alineacion/views/operativa/assets/Common.js');
        $document->addScript(JURI::root() . 'components/com_alineacion/views/operativa/assets/GestionAlineacion.js');
        $document->addScript(JURI::root() . 'components/com_alineacion/views/operativa/assets/ajaxCall.js');
        $document->addScript(JURI::root() . 'components/com_alineacion/views/operativa/assets/eventsAlineacion.js');
        $document->addScript(JURI::root() . 'components/com_alineacion/views/operativa/assets/load.js');
        $document->addScript(JURI::root() . 'components/com_alineacion/views/operativa/assets/save.js');


        JText::script('COM_PROYECTOS_COBERTURA_ERROR_UNACCEPTABLE');
    }

}
