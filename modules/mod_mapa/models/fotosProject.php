<?php

defined( '_JEXEC' ) or die();
require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'tables' . DS . 'fotosProject.php';

class fotosProjectModel extends JModel
{

    /**
     * 
     * @param type $idProyecto
     * @return int
     */
    function getFotosProject( $idProyecto )
    {
        $path = JPATH_SITE . DS . 'components' . DS . 'com_proyectos' . DS . 'images' . DS . $idProyecto . DS . 'images';

        if( file_exists( $path ) ) {
            $count = 0;
            $directorio = opendir( $path );

            while( $imagen = readdir( $directorio ) ) {
                if( $imagen != "." && $imagen != ".." ) {
                    $docu["strNombre_img"] = $imagen;
                    $docu["intCodigo_pry"] = $idProyecto;
                    $docu["intId_img"] = $count;
                    $lstArchivos[] = $docu;
                    $count++;
                }
            }
            closedir( $directorio );
        }
        $retval = ( count( $lstArchivos ) > 0 ) ? $lstArchivos : false;
        return $retval;
    }

}
