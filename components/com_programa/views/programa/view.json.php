<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

//load the JToolBar library and create a toolbar
jimport( 'joomla.html.toolbar' );

/**
 * Vista de Ingreso /Edicion de un Programa
 */
class ProgramaViewPrograma extends JView
{

    /**
     * display method of Hello view
     * @return void
     */
    public function display( $tpl = null )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $modelo = $this->getModel();
        $action = JRequest::getVar( 'action' );

        switch( true ) {
            case( $action == 'saveData' ):
                $data = JRequest::getVar( 'dataFrm' );
                $dtaIndicadores = JRequest::getVar( 'dtaIndicadores' );

                //  Registro el programa
                $retval = $modelo->saveFromJSON( $data, $dtaIndicadores );
                //  Retorna una Variables de acuerdo a un determinado Tipo de Unidad de Medida
                break;
            case( $action == 'getVariablesUnidadMedida'):
                $retval = $modelo->getVarUndMedida( JRequest::getVar( 'idUM' ) );
                break;
            
            //  Retorna una lista de entidades asociadas a un determinado tipo de entidad
            case( $action == 'getResponsablesUG' ):
                $retval = $modelo->getResponsablesUG( JRequest::getVar( 'idUndGestion' ) );
            break;
            case( $action == 'deletePrg' ):
                $id = JRequest::getVar( 'id' );
                $retval = $modelo->delPrograma( $id );
            break;
        }

        echo json_encode( $retval );
    }

}