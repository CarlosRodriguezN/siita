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
class UnidadGestionViewFuncionarios extends JView {

    /**
     * display method of Hello view
     * @return void
     */
    public function display($tpl = null) {
        // get the Data
        $form = $this->get('Form');
//        $item = $this->get('Item');
        $script = $this->get('Script');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Assign the Data
        $this->form = $form;
//        $this->item = $item;
        $this->script = $script;
        
        //  Id de entidad de la unidad de gestion
        $this->idFnc = (int)  JRequest::getVar('idFnc');
        $this->anioVigente = (int)  JRequest::getVar('plnVigente');
        
        //  Instacia el modelo
        $mdFuncionario = $this->getModel();
        $this->item = $mdFuncionario->getFuncionario( $this->idFnc );
        
        // obtengo la informacion de funcionario
        $this->lstObjetivos = $mdFuncionario->getObjetivosPln( $this->idFnc, $this->anioVigente );
        
        
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
        
//        $bar->appendButton('Standard', 'cancel', 'Cancelar', 'funcionarios.cancel', false);

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
        
        // Adjunto libreria de la api de google.
        $document->addScript( "https://www.google.com/jsapi" );

        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );
        
        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'components/com_unidadgestion/views/funcionarios/assets/Reglas.js' );
        
        //  Adjunto el script para la carga de la lista de actividades del funcionario
        $document->addScriptDeclaration( $this->_getLstActsFnc(), $type = 'text/javascript' );
        
        //  Adjunto el script para la carga de la data general del funcionario
        $document->addScriptDeclaration( $this->_getDtaFnc(), $type = 'text/javascript' );
        
        JText::script('COM_UNIDAD_GESTION_POA_ERROR_UNACCEPTABLE');
    }

    private function _getLstActsFnc()
    {
        $retval = '';
        //  Lista de actividades de un funcionario
        $retval .= 'lstActividadesFnc = new Array();';
        if ( $this->lstObjetivos ){
            foreach ( $this->lstObjetivos AS $obj ){
                $lstActsFnc = $obj->lstActividades;
                if( $lstActsFnc && !empty($lstActsFnc) ){
                    foreach( $lstActsFnc AS $actividad ){
                        $retval .= 'lstActividadesFnc.push( ' . json_encode( $actividad ) . ' );';
                    }
                }
            }
        }
        
        return $retval;
    }
    
    private function _getDtaFnc()
    {
        $retval = '';
        $retval .= 'dtaFnc = new Array();';
        if ( $this->item ){
            foreach ( $this->item AS $key=>$value ){
                $retval .= 'dtaFnc["'. $key . '"] = "' . $value . '";';
            }
        }
        return $retval;
    }
    
}   