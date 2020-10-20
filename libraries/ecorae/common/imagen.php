<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

class imagen
{
    private $_file;
    private $_path;
    private $_idImagen;
    private $_alto;
    private $_ancho;
    private $_tpoImagen;

    /**
     * 
     * @param type $file
     * @param type $path
     * @param type $idImagen
     * @param type $alto
     * @param type $ancho
     */
    public function __construct( $file, $path, $idImagen, $alto, $ancho )
    {
        $this->_file    = $file;
        $this->_path    = $path;
        $this->_idImagen= $idImagen;
        $this->_alto    = $alto;
        $this->_ancho   = $ancho;
    }

    public function loadImage()
    {
        $ban = true;

        if( $this->_refactorizarImagen( $this->_alto, $this->_ancho ) ){
            $ban = $this->_crearNuevaImagen();
        }

        return $ban;
    }

    /**
     * 
     * Verifico si el tamaño del icono sobrepasa el tamaño de 16px x 16px
     * 
     * @param Array $this->_file       Informacion del Archivo
     * @param String $this->_path      Ubicacion del Archivo
     * @param int $this->_idImagen   Idenficador del Archivo
     * 
     * @return boolean
     * 
     */
    private function _refactorizarImagen( $alto, $ancho )
    {
        $ban = false;

        $dtaImg     = explode( '.', $this->_file["name"] );
        $this->_tpoImagen = $dtaImg[1];

        echo $this->_tpoImagen .'<hr>'; 
        
        $pathFile   = $this->_path . DS . $this->_idImagen . '.' . $dtaImg[1];
        $dtaSizeImg = getimagesize( $pathFile );

        if( $dtaSizeImg[0] > $ancho && $dtaSizeImg[1] > $alto || $dtaSizeImg[0] < $ancho && $dtaSizeImg[1] < $alto ){
            $ban = true;
        }

        return $ban;
    }
    
 
    private function _crearNuevaImagen()
    {
        $nuevaImagen = imagecreatetruecolor( $this->_ancho, $this->alto );

        switch( $this->_tpoImagen ){
            case "jpg":
            case "jpeg":
                $imagen = imagecreatefromjpeg( $this->_path. DS . $this->_idImagen . '.'. $this->_tpoImagen );
            break;

            case "png":
                $imagen = imagecreatefrompng( $this->_path. DS . $this->_idImagen . '.'. $this->_tpoImagen );
            break;
        }

        list( $ancho_orig, $alto_orig ) = getimagesize($this->_file );
        imagecopyresampled( $nuevaImagen, $imagen, 0, 0, 0, 0, $this->_ancho, $this->_alto, $ancho_orig, $alto_orig );

        echo $this->_path. DS . $this->_idImagen . '.'. $this->_tpoImagen .'<hr>';

        return imagejpeg( $nuevaImagen, $this->_path. DS . $this->_idImagen . '.'. $this->_tpoImagen, 100 );
    }
    
}