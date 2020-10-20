<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

//load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');

/**
 * Vista de Ingreso/Edicion de un plan estrategico institucional
 */
class PoaViewActividad extends JView {

    /**
     * display method of Hello view
     * @return void
     */
    public function display($tpl = null) {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');
        $script = $this->get('Script');

        $registroObj = JRequest::getVar("registroObj");
        $idObjetivo = JRequest::getVar("intId_ob");
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Assign the Data

        $this->form = $form;
        $this->item = $item;
        $this->script = $script;
        $this->registroObj = $registroObj;
        $this->idObjetivo = $idObjetivo;
        //$this->objetivosPei = $objetivosPei;
        // objeto PEI al que pertenece el Pei
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
        $bar->appendButton('Standard', 'save', 'Registrar', 'actividad.registar', false);

        $bar->appendButton('Standard', 'cancel', 'Cancelar', 'actividad.cancelar', false);




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

        // Hoja de estilos para la carga de imagenes
        $document->addStyleSheet(JURI::root() . 'media/system/css/uploadfive/uploadifive.css');
        
        //  Hoja de estilos para tablas
        $document->addStyleSheet(JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css');

        //  Hoja de estilos para pestañas - tabs
        $document->addStyleSheet(JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css');

        //  Hoja de estilos para alertas
        $document->addStyleSheet(JURI::root() . 'media/system/css/alerts/jquery.alerts.css');

        //  Adjunto script JQuery al sitio
        $document->addScript(JURI::root() . 'media/system/js/jquery-1.7.1.min.js');

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript(JURI::root() . 'media/system/js/jquery-noconflict.js');

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript(JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js');

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript(JURI::root() . 'media/system/js/alerts/jquery.alerts.js');
        
        //  Adjunto libreria para los datos generales del Pei
        $document->addScript(JURI::root() . 'components/com_pei/views/actividad/assets/ES_ACT.js');
        
         //  Adjunto libreria java script upladifi
        $document->addScript(JURI::root() . '/media/system/js/uploadfive/jquery.uploadifive.min.js');
         
        //  Adjunto libreria para los datos generales del Pei
        $document->addScript(JURI::root() . 'components/com_pei/views/actividad/assets/ReglasAct.js');
         
        //  Adjunto libreria para la carga de Documentos
        $document->addScript(JURI::root() . 'components/com_pei/views/actividad/assets/uploadSon.js');

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript(JURI::root() . 'components/com_pei/views/actividad/assets/Actividad.js');

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript(JURI::root() . 'components/com_pei/views/actividad/assets/actividades.js');

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript(JURI::root() . 'components/com_pei/views/actividad/assets/save.js');

       
        //  Adjunto lista de Arreglos para gestion de arrays

        JText::script('COM_Pei_PLAN_ERROR_UNACCEPTABLE');
    }

}