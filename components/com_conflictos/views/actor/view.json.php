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
class ConflictosViewActor extends JView
{

    /**
     * display method of Hello view
     * @return void
     */
    public function display( $tpl = null )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $action = JRequest::getVar( 'action' );
        $modelo = $this->getModel();
        
        switch( true ) {// Gestion del ACTOR
            case( $action == 'saveActor' ):
                $data = json_decode( JRequest::getVar( 'data' ) );
                $result = (int) $modelo->saveActor( $data );
                break;
            case( $action == 'eliminarActor' ):
                $id = json_decode( JRequest::getVar( 'id' ) );
                $result = $modelo->deleteActor( $id );
                break;
            case( $action == 'guardarIncidencia' ):
                $dataInc = JRequest::getVar( 'incidencia' );
                $result = $modelo->saveIncidencia( $dataInc );
                break;
            case( $action == 'eliminarIncidencia' ):
                $idRegI = JRequest::getVar( 'id' );
                $result = $modelo->deleteIncidencia( $idRegI );
                break;
            case( $action == 'guardarLegitimidad' ):
                $dataLegL = JRequest::getVar( 'legitimidad' );
                $result = $modelo->saveLegitimidad( $dataLegL );
                break;
            case( $action == 'eliminarLegitimidad' ):
                $idReg = JRequest::getVar( 'id' );
                $result = $modelo->deleteLegitimidad( $idReg );
                break;
            case( $action == 'guardarFuncion' ):
                $dataFnc = JRequest::getVar( 'funcion' );
                $result = $modelo->saveFuncion( $dataFnc );
                break;
            case( $action == 'eliminarFuncion' ):
                $idRegF = JRequest::getVar( 'id' );
                $result = $modelo->deleteFuncion( $idRegF );
                break;
            
        }
        
        echo json_encode( $result );
    }

}