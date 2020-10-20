<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

//load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

/**
 * Clase que muestra un conjunto de Unidades de medida
 */
class ProyectosViewProyectoView extends JView
{

    /**
     * HelloWorlds view display method
     * @return void
     */
    protected $items;
    protected $pagination;
    protected $state;

    function display( $tpl = null )
    {

        $items = $this->get( 'Items' );

        //  recupero la informacion del proyecto.
        $proyecto = $this->get( "DataProyecto" );

        //  lista de contratos  de un proyecto
        $contratos = $this->get( "ContratosProyecto" );

        //  Indicadores de un contrato.
        $indicadores = $this->get( "IndicadoresProyecto" );

        // recupera la lista de enfoques
        $enfoques = $this->getListIdEnfoques( $indicadores );

        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ) {
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }

        $this->items = $items;

        //  data del proyecto
        $this->proyecto = $proyecto[0];

        //  Lista de contratos
        $this->contratos = $contratos;

        //  Lista de indicadores
        $this->indicadores = $indicadores;

        //  Lista de enfoques
        $this->efoques = $enfoques;

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

        //  And make whatever calls you require
//        $bar->appendButton('Standard', 'new', 'Nuevo', 'programa.add', false);
        //  Generate the html and return
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
                '<![endif]-->' . "\n" .
                '<link rel="stylesheet" href="administrator/templates/bluestork/css/rounded.css" type="text/css" />' . "\n"
        );
        //  Hoja de estilos para tablas
        $document->addStyleSheet( JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css' );

        $document->addStyleSheet( 'modules/mod_mapa/tmpl/css/lightbox.css' );

        // Hojas de estilo para el carrusel de imagenes
        $document->addStyleSheet( 'components/com_proyecto/views/proyectoview/css/ie7/skin.css' );

        // Hojas de estilo para el infoindows
        $document->addStyleSheet( 'media/system/css/siita/infowindows.css' );
        
        // Adjunto libreria de la api de google.
        $document->addScript( "https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places,drawing,geometry" );

        //  Hoja de estilos para pestañas
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css' );

        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

        // para le tabajo con pestañas.
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );

        // Adjunto el script de información general.
        $document->addScript( JURI::root() . 'components/com_proyectos/views/proyectoview/assets/general.js' );

        // Adjunto el script de información general.
        $document->addScript( JURI::root() . 'components/com_proyectos/views/proyectoview/assets/mapa.js' );

        // Adjunto las scrips para hacer el carrusel de imagenes
        $document->addScript( JURI::root() . 'components/com_proyectos/views/proyectoview/assets/jquery.jcarousel.min.js' );

        //  Adjunto lista de proyectos de un programa programas
        $document->addScript( JURI::root() . 'modules/mod_mapa/tmpl/js/lightbox.js' );

        //  Adjunto el arra de programas.
        $document->addScriptDeclaration( $this->getArraysContratosScript(), $type = 'text/javascript' );
        JText::script( 'COM_PROGRAMA_COBERTURA_ERROR_UNACCEPTABLE' );

        JText::script( 'COM_PROGRAMA_PROGRAMAS_ERROR_UNACCEPTABLE' );
    }

    /**
     * Creea el script de la lista de contratos.
     * @return string
     */
    public function getArraysContratosScript()
    {
        $contratos = $this->contratos;

        $arrayProyectos = 'lstContratos = new Array();';
        if( $contratos ) {
            foreach( $contratos AS $contrato ) {
                $arrayProyectos .= 'var oContrato = ' . json_encode( $contrato ) . ';';
                $arrayProyectos .= 'lstContratos.push( oContrato );';
            }
        }
        return $arrayProyectos;
    }

    /**
     *  Recupera la lista de enfoques
     * @param type $indicadores
     * @return type
     */
    public function getListIdEnfoques( $indicadores )
    {
        $lstIdsEnfoques = false;
        if( $indicadores ) {
            foreach( $indicadores AS $indicador ) {
                $lstIdsEnfoques[] = $indicador->idEnfoque;
            }
            $lstIdsEnfoques = array_unique( $lstIdsEnfoques );
        }
        return $lstIdsEnfoques;
    }

    /**
     * Recupera el nombre de un enfoque
     * @param int $idEnfoque
     * @return String
     */
    public function getNameEnfoque( $idEnfoque )
    {
        $name = "";
        foreach( $this->indicadores AS $indicador ) {
            if( $indicador->idEnfoque == $idEnfoque ) {
                $name = $indicador->enfoque;
            }
        }
        return $name;
    }

    /**
     * Recupera el la suma de los valores de un enfoque;
     * @param int $idEnfoque    Identificador del enfoque
     * @return int  
     */
    public function totalValueIndicador( $idEnfoque )
    {
        $suma = 0;
        foreach( $this->indicadores AS $indicador ) {
            if( $indicador->idEnfoque == $idEnfoque ) {
                $suma = $suma + $indicador->valor;
            }
        }
        return $suma;
    }

    /**
     * 
     * @param type $idEnfoque
     */
    public function getDimencionesEnfoque( $idEnfoque )
    {
        $arrayDimenciones = array( );
        foreach( $this->indicadores AS $indicador ) {
            if( $indicador->idEnfoque == $idEnfoque ) {
                $arrayDimenciones[] = $indicador->idDimension;
            }
        }
        $arrayDimenciones = array_unique( $arrayDimenciones );
        return $arrayDimenciones;
    }

    /**
     * 
     * @param type $dimension
     * @return type
     */
    public function getNombreDimension( $dimension )
    {
        $nameDimension = "";
        foreach( $this->indicadores AS $indicador ) {
            if( $indicador->idDimension == $dimension ) {
                $nameDimension = $indicador->dimension;
            }
        }
        return $nameDimension;
    }

    /**
     * 
     * @param type $dimencion
     * @return type
     */
    public function getIndicadoresDimencion( $dimencion )
    {
        $arrayDimenciones = array( );
        foreach( $this->indicadores AS $indicador ) {
            if( $indicador->idDimension == $dimencion ) {
                $arrayDimenciones[] = $indicador;
            }
        }
        return $arrayDimenciones;
    }

    /**
     * 
     * @return string
     */
    public function arrayToCharts()
    {
        $scriptCharts = '';
        $scriptCharts .= 'var lstEnfoques = new Array();';
        foreach( $this->efoques AS $enfoque ) {
            $scriptCharts .= 'var lstIndicadores = new Array();';
            foreach( $this->indicadores AS $indicador ) {
                if( $indicador->idEnfoque == $enfoque ) {
                    $scriptCharts .= 'var oIndicador =' . json_encode( $indicador ) . ' ;';
                    $scriptCharts .= 'lstIndicadores.push( oIndicador );';
                }
            }
            $scriptCharts .= 'lstEnfoques.push(lstIndicadores);';
        }
        return $scriptCharts;
    }

}