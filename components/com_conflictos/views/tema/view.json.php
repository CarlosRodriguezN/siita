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
class ConflictosViewTema extends JView
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
            case( $action == 'saveTema' ):
                $dataTema = JRequest::getVar( 'saveTema' );
                $data = json_decode( $dataTema );
                $result = $model->saveTema( $data );
                break;
            case( $action == 'getFuentes' ):
                $idTipoFuente = JRequest::getVar( 'idTipoFuente' );
                $result = $model->getFuentesByTipo( $idTipoFuente );
                break;
            case( $action == 'delArchivoTema' ):
                $owner = JRequest::getVar( 'owner' );
                $name = JRequest::getVar( 'name' );
                $result = $model->delArchivo( $owner, $name );
                break;
            case( $action == 'delArchivoActor' ):
                $owner = JRequest::getVar( 'owner' );
                $actor = JRequest::getVar( 'actor' );
                $name = JRequest::getVar( 'name' );
                $result = $model->delArchivoActor( $owner, $actor, $name );
                break;
            case( $action == 'guardarTipoTema' ):
                $dataTT =  JRequest::getVar( 'tpoTema' );
                $result = $model->saveTipoTema( $dataTT );
                break;
            case( $action == 'eliminarTipoTema' ):
                $idReg = JRequest::getVar( 'id' );
                $result = $model->deleteTipoTema( $idReg );
                break;
            case( $action == 'guardarEstado' ):
                $dataEst =  JRequest::getVar( 'estado' );
                $result = $model->saveEstado( $dataEst );
                break;
            case( $action == 'eliminarEstado' ):
                $idReg = JRequest::getVar( 'id' );
                $result = $model->deleteEstado( $idReg );
                break;
            case( $action == 'deleteTema' ):
                $id = JRequest::getVar( 'id' );
                $result = $model->eliminarTema( $id );
                break;
        }
        
        echo json_encode( $result );
        
    }

}