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
class MantenimientoViewItemAgd extends JView {

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
        //  Id de entidad de la unidad de gestion
        $this->idItem = (int)  JRequest::getVar('idItem');
        $this->regEstructura = (int)  JRequest::getVar('regEstructura');
        $this->owners = json_decode(JRequest::getVar('owners'));
        
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
        $bar->appendButton('Standard', 'save', 'Guardar', 'itenagd.registrar', false);

        $bar->appendButton('Separator');
        
        $bar->appendButton('Standard', 'cancel', 'Cancelar', 'itenagd.cancel', false);

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
        $document->addStyleSheet( 'administrator/templates/system/css/system.css' );

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

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );
        
        //  Adjunto el script para la carga de los items de la agenda
        $document->addScriptDeclaration( $this->_getOwners(), $type = 'text/javascript' );
        
        //  Adjunto libreria del lenguaje java-script de la vista de un POA
        $document->addScript( JURI::root() . 'components/com_mantenimiento/views/itemagd/assets/ES-ITEM.js' );

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'components/com_mantenimiento/views/itemagd/assets/Reglas.js' );
        
        //  Adjunto libreria para el control de los botonee de guarad y cancelar del livebox
        $document->addScript( JURI::root() . 'components/com_mantenimiento/views/itemagd/assets/GestionItem.js' );
        
        JText::script('COM_MANTENIMIENTO_AGD_ITEM_ERROR_UNACCEPTABLE');
        
    }

    private function _getOwners()
    {
        $retval = '';
        $retval .= 'owners = new Array();';
        if( $this->owners ) {
            foreach( $this->owners as $owner ) {
                $retval .= 'owners.push(' . $owner . ');';
            }
        }
        
        return $retval;
    }
}

    