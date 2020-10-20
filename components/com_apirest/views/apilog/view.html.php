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
class ApiRestViewApiLog extends JView {

    /**
     * display method of Hello view
     * @return void
     */
    
    protected $_dtaLog;
    
    public function display($tpl = null) {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');

        //  Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        
        //  Assign the Data
        $this->form = $form;
        $this->item = $item;

        //  Accedo al contenido del Log
        $this->_dtaLog = file( JPATH_BASE.'/logs/com_apirest.log.php' );
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
        $bar->appendButton('Standard', 'cancel', 'Cancelar', 'apilog.cancel', false);

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

        //  Hoja de estilos para tablas
        $document->addStyleSheet(JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css');
        
        //  Adjunto lista de Arreglos para gestion de arrays
        JText::script('COM_ACTIVIDAD_PLAN_ERROR_UNACCEPTABLE');
    }

}
