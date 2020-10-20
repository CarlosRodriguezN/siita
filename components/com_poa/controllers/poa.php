<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla controllerform library
jimport( 'joomla.application.component.controllerform' );

// Inserto libreria de gestion de carga de archivos
jimport( 'ecorae.uploadfile.upload' );

/**
 * 
 *  Controlador proyecto
 * 
 */
class PoaControllerPoa extends JControllerForm
{

    protected $view_list = 'poas';

    protected function allowAdd( $data = array( ) )
    {
        return true;
    }

    protected function allowEdit( $data = array( ), $key = 'id' )
    {
        return true;
    }

    function add()
    {
        parent::add();
    }

     /**
     * Gestiona la carga de archivos.
     */
    public function saveFiles()
    {
        $idObjetivo = JRequest::getVar( "idObjetivo" );
        $idActividad = JRequest::getVar( "idActividad" );
        $idPoa = JRequest::getVar( "idPoa" );
        $tipo = JRequest::getVar( "tipo" );
        $flag2 = JRequest::getVar( "flag2" );
        $redirecTo = JRequest::getVar( "redirecTo" );

        switch( $tipo ) {
            case 1:
                $dirName = 'peis';
                break;
            case 2:
                $dirName = 'poas';
                break;
            case 3:
                $dirName = 'programas';
                break;
        }

        $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "docs" . DS . $dirName . DS . $idPoa . DS . 'objetivos' . DS . $idObjetivo . DS . 'actividades' . DS . $idActividad;
        $numArchivo = count( glob( $path . '/{*.*}', GLOB_BRACE ) ) + 1;
        $fileName = $idActividad . '_' . $numArchivo;

        $up_file = new upload( 'Filedata', NULL, $path, $fileName );
        $up_file->save();
        $data["flag2"]=$flag2;
        $data["redirecTo"]=$redirecTo;
        
        echo json_encode( $data );exit();
    }
}
