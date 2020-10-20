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
class MantenimientoViewCargoUG extends JView {

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
        //  Id unidad de gestion
        $this->idUG = (int)  JRequest::getVar('id');
        $this->idGrpCargo = (int)  JRequest::getVar('grupoCrg');
        $this->idRegCargo = (int)  JRequest::getVar('cargoReg');
        
        //  Obtengo el modelo de cargofnc (MantenimientoModelCargoFnc)
        $mdCargoUG = $this->getModel();
        
        $this->componentes = $mdCargoUG->getComponentes( "Espoch", $this->idGrpCargo );
        $this->infoUG = $mdCargoUG->getinfoUG( $this->idUG );
        
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
        $bar->appendButton('Standard', 'save', 'Guardar', 'cargoug.registrar', false);

        $bar->appendButton('Separator');
        
        $bar->appendButton('Standard', 'cancel', 'Cancelar', 'cargoug.cancel', false);

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
        
        $document->addScript( JURI::root() . 'components/com_mantenimiento/views/cargoug/assets/Save.js' );

        //  Adjunto libreria del lenguaje java-script de la vista de un POA
        $document->addScript( JURI::root() . 'components/com_mantenimiento/views/cargoug/assets/ES-CARGO.js' );

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'components/com_mantenimiento/views/cargoug/assets/Reglas.js' );
        
        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'components/com_mantenimiento/views/cargoug/assets/Load.js' );
        
        //  Adjunto libreria para el control de los botonee de guarad y cancelar del livebox
        
        //  Adjunto el script para la carga de los items de la agenda
        $document->addScriptDeclaration( $this->_getComponentes(), $type = 'text/javascript' );
        
        JText::script('COM_MANTENIMIENTO_POA_ERROR_UNACCEPTABLE');
    }
    
    /**
     * 
     * @return string
     */
    private function _getComponentes()
    {
        $retval = '';
        $retval .= 'lstComponentes = new Array();'; 
        if( $this->componentes ) {
            foreach( $this->componentes as $componente ) {
                $retval .= 'var objComponente = new Object();';
                $retval .= 'objComponente = ' . json_encode( $componente ) . ';';
                $retval .= 'lstComponentes.push( objComponente );';
            }
        }

        return $retval;
    }
    
}