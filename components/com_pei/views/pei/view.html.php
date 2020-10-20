<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

//load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

/**
 * Vista de Ingreso/Edicion de un plan estrategico institucional
 */
class PeiViewPei extends JView
{
    protected $_lstContextos;
    protected $_fchInicio;
    protected $_fchFin;
    protected $_anioVigencia;
    protected $canDo;

    /**
     * display method of Hello view
     * @return void
     */
    public function display( $tpl = null )
    {
        // get the Data
        $form = $this->get( 'Form' );
        $item = $this->get( 'Item' );
        $script = $this->get( 'Script' );
        $user = JFactory::getUser();
        
        // What Access Permissions does this user have? What can (s)he do?
        $this->canDo = PeiHelper::getActions( $user->id );
        
        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ) {
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }

        // Assign the Data
        $this->form     = $form;
        $this->item     = $item;
        
        $this->script   = $script;
        $this->_idPei   = $item->intId_pi;
        
        $this->_fchInicio = ( !is_null( $this->item->dteFechainicio_pi ) )  
                                ? $this->item->dteFechainicio_pi 
                                : '';
        
        $this->_fchFin = ( !is_null( $this->item->dteFechafin_pi ) )
                            ? $this->item->dteFechafin_pi
                            : '';
        
        //  Asigna la data relacionada con la propuesta
        if( $item->intId_pi != 0 ) {
            //  Identificador de Entidad PEI
            $idTpoEntidad = 1;

            $mdPlnEstIns = $this->getModel();
            $this->lstObjetivos = $mdPlnEstIns->lstObjetivos( $item->intId_pi, $idTpoEntidad );
            
            //  Obtengo la lista de OEI
            $this->items = $mdPlnEstIns->lstOEI( $item->intId_pi );

            //  Obtengo una lista de Indicadores de tipo contexto asociados a una deteminada Entidad de tipo PEI
            $this->_lstContextos = json_encode( $mdPlnEstIns->lstContextos( $item->intIdentidad_ent ) );

            //  Obtengo la lista de PPPP's
            $tpoPlnPPPP = 3;    //  Id de tipo de plan PAPP
            $this->lstPPPPs = $mdPlnEstIns->getLstPlanes( $item->intId_pi, $tpoPlnPPPP );
        }

        // Display the template
        parent::display( $tpl );

        // Set the document
        $this->setDocument();
    }

    /**
     * Setting the toolbar
     */
    protected function getToolbar()
    {
        $bar = new JToolBar( 'toolbar' );

        //and make whatever calls you require
        if( $this->canDo->get( 'core.create' ) ){
            $bar->appendButton( 'Standard', 'save', JText::_( 'COM_PEI_EVENTO_GUARDAR' ), 'pei.registroPei', false );
            $bar->appendButton( 'Standard', 'save', JText::_( 'COM_PEI_EVENTO_GUARDAR_SALIR' ), 'pei.guardarSalir', false );
        }

        $bar->appendButton( 'Separator' );
        
        $bar->appendButton( 'Standard', 'control', JText::_( 'COM_PEI_CONTROL_Y_MONITOREO' ), 'pei.panel', false );
        $bar->appendButton( 'Standard', 'organization', JText::_( 'COM_PEI_ORGANIGRAMA' ), 'pei.organigrama', false );
        $bar->appendButton( 'Standard', 'list', JText::_( 'COM_PEI_GESTION_PLANES' ), 'pei.listaPeis', false );
        
        $bar->appendButton( 'Separator' );
        
        $del = $this->get( 'AvalibleDel' );
        
        if( (int)$this->item->intId_pi && $del ) {
            if( $this->canDo->get( 'core.delete' ) ){
                $bar->appendButton( 'Standard', 'delete', JText::_( 'COM_PEI_EVENTO_ELIMINAR' ), 'pei.deletePei', false );
            }
        }
        
        $bar->appendButton( 'Standard', 'cancel', JText::_( 'COM_PEI_EVENTO_CANCELAR' ), 'pei.cancel', false );

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
        
        //  hoja de estilos para los formularios en backgrawn
        $document->addStyleSheet( JURI::root() . 'media/system/css/siita/common.css' );
        
        // Hoja de estilos para la carga de imagenes
        $document->addStyleSheet( JURI::root() . 'media/system/css/uploadfive/uploadifive.css' );

        //  Hoja de estilos para la iconografia de objetivos
        $document->addStyleSheet(JURI::root() . 'media/system/images/sprites-Objetivos/sprits.css');

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

        //  Adjunto libreria java script upladifi
        $document->addScript( JURI::root() . '/media/system/js/uploadfive/jquery.uploadifive.min.js' );

        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );
        
        //  Adjunto libreria que permite el trabajo con pestañas
        $document->addScript( JURI::root() . 'media/system/js/jquery.datatables.min.js' );
        $document->addScript( JURI::root() . 'media/system/js/jquery.datatables.js' );
        $document->addScript( JURI::root() . 'media/system/js/jquery.jeditable.js' );
        
        //  Adjunto libreria que permite el bloqueo de la pagina en llamadas ajax
        $document->addScript( JURI::root() . 'media/system/js/blockUI/jquery.blockUI.js' );
        
        //  Adjunto libreria del lenguaje java-script de la vista de objetivos de un Pei
        $document->addScript( JURI::root() . 'components/com_pei/views/pei/assets/ES_OBJETIVO.js' );

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );

        //  Adjunto libreria que la gestion de rubros de financiamiento registrados
        $document->addScript( JURI::root() . 'media/system/js/jquery-validate/common_validate.js' );

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'components/com_pei/views/pei/assets/Reglas.js' );
        
        //  Adjunto libreria que controla la vigencia de los planes
        $document->addScript( JURI::root() . 'components/com_pei/views/pei/assets/VigenciaPlan.js' );

        //  Adjunto libreria que gestiona indicadores
        $document->addScript( JURI::root() . 'components/com_indicadores/views/assets/Indicador.js' );
        $document->addScript( JURI::root() . 'components/com_indicadores/views/assets/CommonIndicadores.js' );

        //  Adjunto libreria que gestiona programacion
        $document->addScript( JURI::root() . 'libraries/ecorae/objetivos/objetivo/indicadores/assets/Programacion.js' );
        $document->addScript( JURI::root() . 'libraries/ecorae/objetivos/objetivo/indicadores/assets/GestionProgramacion.js' );

        //  Adjunto libreria que controla ingreso de informacion especifica en los campos
        $document->addScript( JURI::root() . 'libraries/ecorae/objetivos/objetivo/indicadores/assets/MetaProgramacion.js' );

        //  Adjunto libreria para los objetivos de un PEI 
        $document->addScript( JURI::root() . 'components/com_pei/views/pei/assets/Objetivo.js' );
        $document->addScript( JURI::root() . 'components/com_pei/views/pei/assets/GestionObjetivos.js' );
        $document->addScript( JURI::root() . 'components/com_pei/views/pei/assets/GestionObjetivo.js' );
        $document->addScript( JURI::root() . 'components/com_pei/views/pei/assets/uploadFather.js' );

        //  Adjunto libreria para los datos generales del PEI
        $document->addScript( JURI::root() . 'components/com_pei/views/pei/assets/Pei.js' );
        $document->addScript( JURI::root() . 'components/com_pei/views/pei/assets/ProgramacionPlan.js' );
        
        //  Adjunto Librerias de gestion de contextos
        $document->addScript( JURI::root() . 'components/com_pei/views/pei/assets/Contexto.js' );
        $document->addScript( JURI::root() . 'components/com_pei/views/pei/assets/GestionContextos.js' );

        //  Adjunto lista de Arreglos para gestion de tablas dinamicas
        $document->addScriptDeclaration( $this->_getLstData(), $type = 'text/javascript' );

        //  Adjunto lista de Arreglos para gestion de tablas dinamicas
        $document->addScriptDeclaration( $this->_getObjetivos(), $type = 'text/javascript' );

        //  Adjunto lista de Arreglos de los planes de tipo PPPP
        $document->addScriptDeclaration( $this->_getLstPPPPs(), $type = 'text/javascript' );

        //  Adjunto lista de Arreglos de los planes de tipo PAPP
        $document->addScriptDeclaration( $this->_getLstPAPPs(), $type = 'text/javascript' );

        //  Adjunto lista de Contextos
        $document->addScriptDeclaration( $this->_getContextos(), $type = 'text/javascript' );

        JText::script( 'COM_PEI_PLAN_ERROR_UNACCEPTABLE' );
    }

    /**
     * 
     * Genero los arreglos ( arrays ), con informacion de Indicadores de 
     * Grupo de Accion Prioritaria, Otros Indicadores, Lineas Base
     * 
     * Los cuales sirven para la gestion de informacion de dichos indicadores
     * 
     * @return string
     */
    private function _getLstData()
    {
        $retval = '';

        //  Objeto pei
        $retval .= 'var oPEI = new Object();';

        //  Lista de Objetivos de un Pei
        $retval .= 'oPEI.lstPoasPei = new Array();';

        //  lista de Poas relacionados con un Pei
        if( isset( $this->lstPoasPei ) ) {
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

        //  lista de objetivos del pei
        if( $this->lstObjetivos ) {
            foreach( $this->lstObjetivos as $objetivo ) {
                $retval .= 'var objObjetivo = new Objetivo();';
                $retval .= 'objObjetivo.setDtaObjetivo( '. json_encode( $objetivo ) .' );';
                $retval .= 'objLstObjetivo.addObjetivo( objObjetivo );';
            }
        }

        return $retval;
    }
    
    
    
    /**
     * 
     * Seteo informacion perteneciente a contextos
     * 
     * @return type
     */
    private function _getContextos()
    {
         $retval .= 'objContexto = new Contexto();';
         
         foreach( $this->lstContextos as $contexto ){
            $retval .= 'objContexto.setContexto( '. json_encode( $contexto ) .' )';
            $retval .= 'jQuery( "#tbLstContextos > tbody:last" ).append( objContexto.addFilaContexto( 0 ) )';
         }
         
         return $retval;
    }
    
    private function _getLstPPPPs()
    {
        $retval = '';
        $retval .= 'var oLstPPPPs = new Object();';
        $retval .= 'oLstPPPPs.lstPppp = new Array();';
        if( $this->lstPPPPs ) {
            foreach ( $this->lstPPPPs as $plan ) {
                $objJSON = json_encode( $plan );
                $retval .= 'oLstPPPPs.lstPppp.push(' . $objJSON . ');';
            }
        }
        return $retval;
    }
    
    private function _getLstPAPPs()
    {
        $retval = '';
        $retval .= 'var oLstPAPPs = new Object();';
        $retval .= 'oLstPAPPs.lstPapp = new Array();';
        if( $this->lstPAPPs ) {
            foreach ( $this->lstPAPPs as $plan ) {
                $objJSON = json_encode( $plan );
                $retval .= 'oLstPAPPs.lstPapp.push(' . $objJSON . ');';
            }
        }
        return $retval;
    }
}