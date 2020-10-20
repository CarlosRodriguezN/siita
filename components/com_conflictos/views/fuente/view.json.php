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
class ConflictosViewFuente extends JView
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
        $model = $this->getModel();
        
        switch( true ) {
            case( $action == 'saveFuente' ):
                $dataFuente = JRequest::getVar( 'saveFuente' );
                $data = json_decode( $dataFuente );
                $result = $model->saveFuente( $data );
                break;
            case( $action == 'eliminarFuente' ):
                $idDel = JRequest::getVar( 'id' );
                $result = $model->deleteFuente( $idDel );
                break;
            case( $action == 'getFuentes' ):
                $idTipoFuente = JRequest::getVar( 'idTipoFuente' );
                $result = $model->getFuentesByTipo( $idTipoFuente );
                break;
            case( $action == 'guardarTipoFuente' ):
                $tpoFuente = JRequest::getVar( 'tpoFuente' );
                $result = $model->saveTipoFuente( $tpoFuente );
                break;
            case( $action == 'eliminarTipoFuente' ):
                $idReg = JRequest::getVar( 'id' );
                $result = $model->deleteTipoFuente( $idReg );
                break;
            case( $action == 'guardarIncidencia' ):
                $dataInc = JRequest::getVar( 'incidencia' );
                $result = $model->saveIncidencia( $dataInc );
                break;
            case( $action == 'eliminarIncidencia' ):
                $idReg = JRequest::getVar( 'id' );
                $result = $model->deleteIncidencia( $idReg );
                break;
            case( $action == 'guardarLegitimidad' ):
                $dataLeg = JRequest::getVar( 'legitimidad' );
                $result = $model->saveLegitimidad( $dataLeg );
                break;
            case( $action == 'eliminarLegitimidad' ):
                $idReg = JRequest::getVar( 'id' );
                $result = $model->deleteLegitimidad( $idReg );
                break;
        }
        
        echo json_encode( $result );
    }

}