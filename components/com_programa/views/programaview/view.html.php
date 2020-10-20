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
class ProgramaViewProgramaView extends JView
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

        //  Recupera la información General.
        $general = $this->get( "DataPrograma" );

        //  Recupera la lista de proyectos
        $proyectos = $this->get( "ProyectosPrograma" );

        //  Recupera la lista de indicadores de un programa
        $indicadores = $this->get( "IndicadoresPrograma" );

        $enfoques = $this->getListIdEnfoques( $indicadores );

        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ) {
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }

        // Assign data to the view
        $this->items = $items;
        $this->pagination = $pagination;
        $this->general = $general;
        $this->proyectos = $proyectos;
        $this->indicadores = $indicadores;
        $this->efoques = $enfoques;

        //  Ejecuta el metodo "populateState" de la clase 
        $this->state = $this->get( 'State' );

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

        //  And make whatever calls you require
        $bar->appendButton( 'Standard', 'new', 'Nuevo', 'programa.add', false );

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

        // Hoja de estilo para el carrusel
        $document->addStyleSheet( 'modules/mod_mapa/tmpl/css/lightbox.css' );

        // Hojas de estilo para el carrusel de imagenes
        $document->addStyleSheet( 'components/com_programa/views/programaview/css/ie7/skin.css' );

        // Hojas de estilo para el infoindows
        $document->addStyleSheet( 'media/system/css/siita/infowindows.css' );

        // Adjunto libreria de la api de google.
        $document->addScript( "https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places,drawing,geometry" );

        // Adjunto libreria de la api de google.
        $document->addScript( "https://www.google.com/jsapi" );

        //  Hoja de estilos para pestañas
        $document->addStyleSheet( JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css' );

        //  Adjunto script JQuery al sitio
        $document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

        // para le tabajo con pestañas.
        $document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );

        //  Adjunto libreria que permite el trabajo de Mootools y Jquery
        $document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );

        // Adjunto el script de información general.
        $document->addScript( JURI::root() . 'components/com_programa/views/programaview/assets/general.js' );

        // Adjunto el script de información general.
        $document->addScript( JURI::root() . 'components/com_programa/views/programaview/assets/mapa.js' );
        // Adjunto el script de información general.
        $document->addScript( JURI::root() . 'components/com_programa/views/programaview/assets/charts.js' );

        // Adjunto las scrips para hacer el carrusel de imagenes
        $document->addScript( JURI::root() . 'components/com_programa/views/programaview/assets/jquery.jcarousel.min.js' );

        //  Adjunto lista de proyectos de un programa programas
        $document->addScript( JURI::root() . 'modules/mod_mapa/tmpl/js/lightbox.js' );

        //  Adjunto el arra de programas.
        $document->addScriptDeclaration( $this->getArraysProgramaScript(), $type = 'text/javascript' );

        //  Adjunto la lista de charts.
        $document->addScriptDeclaration( $this->arrayToCharts(), $type = 'text/javascript' );

        JText::script( 'COM_PROGRAMA_COBERTURA_ERROR_UNACCEPTABLE' );

        JText::script( 'COM_PROGRAMA_PROGRAMAS_ERROR_UNACCEPTABLE' );
    }

    /**
     *  Recupera la lista de programas para JavaScript 
     * @return string
     */
    public function getArraysProgramaScript()
    {
        $proyectos = $this->proyectos;
        $arrayProyectos = 'lstProyectos = new Array();';
        if ($proyectos) {
            foreach ($proyectos AS $proyecto) {
                $arrayProyectos .= 'var oProyecto = ' . json_encode($proyecto) . ';';
                $arrayProyectos .= 'lstProyectos.push( oProyecto );';
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
        if( $this->indicadores ) {
            foreach( $this->indicadores AS $indicador ) {
                if( $indicador->idEnfoque == $idEnfoque ) {
                    $name = $indicador->enfoque;
                }
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
        if( $this->indicadores ) {
            foreach( $this->indicadores AS $indicador ) {
                if( $indicador->idEnfoque == $idEnfoque ) {
                    $suma = $suma + $indicador->valor;
                }
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
        if( $this->indicadores ) {
            foreach( $this->indicadores AS $indicador ) {
                if( $indicador->idEnfoque == $idEnfoque ) {
                    $arrayDimenciones[] = $indicador->idDimension;
                }
            }
            $arrayDimenciones = array_unique( $arrayDimenciones );
        }
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
        if( $this->indicadores ) {
            foreach( $this->indicadores AS $indicador ) {
                if( $indicador->idDimension == $dimension ) {
                    $nameDimension = $indicador->dimension;
                }
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
        if( $this->indicadores ) {
            foreach( $this->indicadores AS $indicador ) {
                if( $indicador->idDimension == $dimencion ) {
                    $arrayDimenciones[] = $indicador;
                }
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
        if( $this->efoques ) {
            foreach( $this->efoques AS $enfoque ) {
                $scriptCharts .= 'var lstIndicadores = new Array();';
                if( $this->indicadores ) {
                    foreach( $this->indicadores AS $indicador ) {
                        if( $indicador->idEnfoque == $enfoque ) {
                            $scriptCharts .= 'var oIndicador =' . json_encode( $indicador ) . ' ;';
                            $scriptCharts .= 'lstIndicadores.push( oIndicador );';
                        }
                    }
                    $scriptCharts .= 'lstEnfoques.push(lstIndicadores);';
                }
            }
        }
        return $scriptCharts;
    }

}