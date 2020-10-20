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
class PoaViewPoa extends JView {

    /**
     * display method of Hello view
     * @return void
     */
    public function display($tpl = null) {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');
        $script = $this->get('Script');
        // identificador
        //$padre = JRequest::getVar("idPadre");
        // recupera la informacion del PEI
        $pei = $this->get("Pei");
        $idPoa = JRequest::getVar("intId_pi");

        // Lista de objetivos de un POA
        $mdPoa=$this->getModel();
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Assign the Data

        $this->form = $form;
        $this->item = $item;
        $this->script = $script;
        //$this->objetivosPoa = $objetivosPoa;
        // objeto PEI al que pertenece el POA
        $this->pei = $pei;
        //  Asigna la data relacionada con la propuesta
        if( $item->intId_pi != 0 ) {
            //  Identificador de Entidad PEI
            $idTpoEntidad = 1;
            
            $mdPlnEstIns = $this->getModel();
            $this->lstObjetivos = $mdPlnEstIns->lstObjetivos( $item->intId_pi, $idTpoEntidad );
            
        }
        
        
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
        $bar->appendButton('Standard', 'save', 'Guardar', 'poa.registroPoa', false);

        $del = $this->get('AvalibleDel');
//        var_dump($this->item->intId_pi); exit;
//    $del = $this->avalibleDell($this->item->intId_pi);
        if ($del) {
            $bar->appendButton('Standard', 'delete', 'Eliminar', 'poa.deletePoa', false);
        }


        $bar->appendButton('Separator');
//        $bar->appendButton('Standard', 'pdf', 'PDF', 'poa.pdf', false);
//        $bar->appendButton('Standard', 'excel', 'Excel', 'poa.excel', false);
        $bar->appendButton('Standard', 'cancel', 'Cancelar', 'poa.cancel', false);


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
        
        //  Hoja de estilos para los formularios
        $document->addStyleSheet( JURI::root() . 'media/system/css/siita/common.css' );
        
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

        //  Adjunto libreria java script upladifi
        $document->addScript(JURI::root() . '/media/system/js/uploadfive/jquery.uploadifive.min.js');

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript(JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js');

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript(JURI::root() . 'media/system/js/alerts/jquery.alerts.js');
       
        //  Adjunto libreria para los datos generales del POA
        $document->addScript(JURI::root() . 'components/com_poa/views/poa/assets/ES-POA.js');
        
        //  Adjunto archivos para la gestion de objetivos.
        $document->addScript( JURI::root() . 'components/com_poa/views/poa/assets/Objetivo.js' );
        $document->addScript( JURI::root() . 'components/com_poa/views/poa/assets/GestionObjetivos.js' );
        $document->addScript( JURI::root() . 'components/com_poa/views/poa/assets/GestionObjetivo.js' );
        
        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript(JURI::root() . 'components/com_poa/views/poa/assets/Reglas.js');

        //  Adjunto libreria para la carga de archivos
        $document->addScript(JURI::root() . 'components/com_poa/views/poa/assets/uploadFather.js');

        //  Adjunto libreria para los combos vinculantes 
        //$document->addScript(JURI::root() . 'components/com_poa/views/poa/assets/objetivos.js');

        //  Adjunto libreria para los datos generales del POA
        $document->addScript(JURI::root() . 'components/com_poa/views/poa/assets/Poa.js');

          //  Adjunto lista de Arreglos para gestion de tablas dinamicas
        $document->addScriptDeclaration( $this->_getLstData(), $type = 'text/javascript' );
        
        //  Adjunto lista de Arreglos para gestion de tablas dinamicas
        $document->addScriptDeclaration( $this->_getObjetivos(), $type = 'text/javascript' );

        JText::script('COM_POA_PLAN_ERROR_UNACCEPTABLE');
    }


    /**
     * Arma la estructura array en java script para manejar los objetivos de un poa 
     * @return string
     */
    private function _getLstData()
    {

        $retval = '';
        
        //  Objeto poa
        $retval .= 'var oPEI = new Object();';

        //  Lista de Objetivos de un Pei
        $retval .= 'oPEI.lstPoasPei = new Array();';

        //  lista de Poas relacionados con un Pei
        if( $this->lstPoasPei ) {
            $objJSON = json_encode( $this->lstPoasPei );
            $retval .= 'oPEI.lstPoasPei = ' . $objJSON . ';';
        }

        return $retval;
    }
    /**
     * 
     * 
     * 
     * @return string
     * 
     */
    private function _getObjetivos()
    {
        $retval = '';
        
        //  Lista de Objetivos de un Pei
        $retval .= 'objLstObjetivo = new GestionObjetivos();';
        
        //  lista de objetivos del poa
        if( $this->lstObjetivos ) {
            foreach( $this->lstObjetivos as $objetivo ){
                $retval .= 'var objObjetivo = new Objetivo();';
                $retval .= 'objObjetivo.setDtaObjetivo( '. json_encode( $objetivo ) .' );';
                $retval .= 'objLstObjetivo.addObjetivo( objObjetivo );';
            }
        }

        return $retval;
    }

}