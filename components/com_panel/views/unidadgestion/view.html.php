<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

//load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

/**
 * 
 * Clase que muestra un panel de acceso para una determinadad unidad de gestion
 * 
 */
class PanelViewUnidadGestion extends JView
{

    /**
     * 
     * Panel view display method
     * @return void
     * 
     */
    protected $items;
    protected $idEntUG;
    protected $pagination;
    protected $state;

    function display( $tpl = null )
    {
        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ) {
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }

        $this->idEntUG = JRequest::getVar( 'idEntUndGestion' );
        $mdUndGes = $this->getModel();

        $dtaIndUG = array( );
        $this->lstPoasUG = $mdUndGes->lstPoasUG( $this->idEntUG );
        $this->lstObjetivosUG = $this->_getLstObjsUg( $this->lstPoasUG );

        foreach( $this->lstObjetivosUG as $objetivo ) {
            $dtaIndUG["Objetivo"] = $objetivo->descObjetivo;
            $dtaIndUG["lstIndicadores"] = $objetivo->lstIndicadores;
        }

        $lstIndicadores[] = $dtaIndUG;
        $this->items = $lstIndicadores;

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
        $bar->appendButton( 'Standard', 'new', JText::_( 'COM_PANEL_UG_NUEVO' ), 'unidadgestion.nuevo', false );

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
    }

    /**
     * 
     * @return type
     */
    private function _getLstObjsUg()
    {
        if( $this->lstPoasUG ) {
            $lstObjetivosUG = array( );
            foreach( $this->lstPoasUG AS $Poa ) {
                $lstObjs = $Poa->lstObjetivos;

                if( !empty( $lstObjs ) ) {
                    foreach( $lstObjs AS $objetivo ) {
                        if( count( $lstObjetivosUG ) > 0 ) {
                            foreach( $lstObjetivosUG AS $objUg ) {
                                if( ( $objetivo->idObjetivo != $objUg->idObjetivo ) && $objetivo->idTpoObj == 1 ) {
                                    $lstObjetivosUG[] = $objetivo;
                                }
                            }
                        } elseif( $objetivo->idTpoObj == 1 ) {
                            $lstObjetivosUG[] = $objetivo;
                        }
                    }
                }
            }
        }

        return $lstObjetivosUG;
    }

}