<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

//  load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

// TABS
jimport( 'joomla.html.html.tabs' );

/**
 * 
 * Clase que muestra un panel de acceso para las Unidades de Gestion
 * 
 */
class PanelViewUnidadesGestion extends JView
{

    /**
     * Panel view display method
     * @return void
     */
    protected $items;
    protected $pagination;
    protected $state;
    protected $_ticketTableu;
    protected $_ticketTableuAccion;
    protected $canDo;

    protected $_options;
    
    function display( $tpl = null )
    {
        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ) {
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }
        
        $this->canDo = PanelHelper::getActions();

        //  Genera Ticket para Reporte de Indicadores - Unidades de Gestion
        $this->_ticketTableu        = $this->_getTicketTableu( 'UnidadesdeGestion/ScorecarddeUnidadesdeGestin' );
        
        //  Genera Ticket para Reporte de Acciones - Unidades de Gestion
        $this->_ticketTableuAccion  = $this->_getTicketTableu( 'AnlisisdeAcciones/ScorecarddeAcciones' );
        
        $this->_options = array(   'onActive' => 'function(title, description){
                                        description.setStyle("display", "block");
                                        title.addClass("open").removeClass("closed");
                                    }',

                                    'onBackground' => 'function(title, description){
                                        description.setStyle("display", "none");
                                        title.addClass("closed").removeClass("open");
                                    }',

                                    'startOffset' => 0,  // 0 starts on the first tab, 1 starts the second, etc...
                                    'useCookie' => true, // this must not be a string. Don't use quotes.
        );

        // Display the template
        parent::display( $tpl );

        // Set the document
        $this->setDocument();
    }

    private function _getTicketTableu( $nombreDashBoard )
    {
        //  Retorna informacion URL con ticket de confianza de los server de ECORAE
        $mdTableau = JModel::getInstance( 'UnidadesGestion', 'PanelModel' );
        //  $mdTableau = $this->getModel();
        
        return $mdTableau->getTicketTableuPorNombre( $nombreDashBoard );
    }
    
    /**
     * Setting the toolbar
     */
    protected function getToolbar()
    {
        $bar = new JToolBar( 'toolbar' );

        $bar->appendButton( 'Standard', 'list', JText::_( 'COM_PANEL_LISTA_UG' ), 'unidadesgestion.lista', false );
        
        //generate the html and return
        return $bar->render();
    }

    /**
     * 
     * Method to set up the document properties
     *
     * @return void
     * 
     */
    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle( JText::_( 'COM_PANEL_CONTROL' ) );

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

        //  Hoja de estilos para pestañas - tabs
//        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css' );
//
//        //  Hoja de estilos para tablas
//        $document->addStyleSheet( JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css' );
//
//        //  Hoja de estilos para alertas
//        $document->addStyleSheet( JURI::root() . 'media/system/css/alerts/jquery.alerts.css' );
//
//        //  Adjunto script JQuery al sitio
//        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );
//
//        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
//        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );
//
//        //  Adjunto libreria que permite el trabajo con pestañas
//        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );

        //  Adjunto libreria que permite el trabajo con pestañas
//        $document->addScript( JURI::root() . 'components/com_panel/views/unidadesgestion/assets/reglas.js' );
    }

    /**
     * 
     * @return type
     */
    private function _getLstObjsUg()
    {
        $lstObjetivosUG = array();
        
        if( count( $this->lstPoasUG ) > 0 ) {
            foreach( $this->lstPoasUG AS $poa ) {
                $lstObjs = $poa->lstObjetivos;

                if( count( $lstObjs ) ) {
                    foreach( $lstObjs AS $objetivo ) {

                        if( count( $lstObjetivosUG ) > 0 ) {
                            foreach( $lstObjetivosUG AS $key => $objUg ) {

                                if( ( $objetivo->idObjetivo != $objUg->idObjetivo ) && $objetivo->idTpoObj == 1 ) {
                                    $lstObjetivosUG[$key] = $objetivo;
                                }

                            }
                        } elseif( $objetivo->idTpoObj == 1 ) {
                            $lstObjetivosUG[$key] = $objetivo;
                        }

                    }
                }

            }
        }
        
        return $lstObjetivosUG;
    }
} 