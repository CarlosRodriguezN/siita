<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

//  Import Joomla JUser Library
jimport( 'joomla.user.user' );

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class ProgramaTableProyecto extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__pfr_proyecto_frm', 'intCodigo_pry', $db );
    }

    /**
     * Recupera la lista de proyectos de un programa
     * @param int $idPrograma       Identificador del programa.
     * @return array                Lista de proyectos.
     */
    public function getProyectosByPrograma( $idPrograma )
    {
        try {
            $db = &JFactory::getDBO();
            $db->getQuery( true );
            $query = $db->getQuery( true );
            $query->select( '*,unm.strSimbolo_unimed' );
            $query->from( '#__pfr_proyecto_frm AS pry' );
            $query->join( "inner", "#__gen_unidad_medida AS unm ON unm.intCodigo_unimed=pry.intCodigo_unimed" );
            $query->where( "intCodigo_prg =" . $idPrograma );

            //  Ejecución
            $db->setQuery( $query );
            $db->query();
            $retval = ($db->loadObject()) ? $db->loadObjectList() : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_adminmapa.table.programa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * Retorna todos los proyectos de un Sub Programa
     * @param int $idSubPrograma        Identificador del sub programa
     * @return Array                    Lista de proyectos.
     */
    public function getProyectosBySubPrograma( $idSubPrograma )
    {
        try {
            $db = &JFactory::getDBO();
            $db->getQuery( true );
            $query = $db->getQuery( true );
            $query->select( '*,unm.strSimbolo_unimed' );
            $query->from( '#__pfr_proyecto_frm AS pry' );
            $query->join( "inner", "#__gen_unidad_medida AS unm ON unm.intCodigo_unimed=pry.intCodigo_unimed" );
            $query->where( "intCodigo_sprg =" . $idSubPrograma );

            //  Ejecución
            $db->setQuery( $query );
            $db->query();
            $retval = ($db->loadObject()) ? $db->loadObjectList() : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_adminmapa.table.programa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * Retorna las ubicaciones geograficas de un proyecto.
     * @param Int $idProyecto       Identificador del proyecto
     * @return array                Lista de ubicaciones geograficas donde esta el proyecto.
     */
    public function getUbicacionesGeograficaProyecto( $idProyecto )
    {
        try {
            $db = &JFactory::getDBO();
            $db->getQuery( true );
            $query = $db->getQuery( true );
            // Armo la sentencia SQL para INSERTAR los valores
            $query->select( '
                            intCodigo_pry,	
                            intID_ut 
                            ' );
            $query->from( '#__ubicgeo_pry' );
            $query->where( "intCodigo_pry =" . $idProyecto );
            //  Ejecución
            $db->setQuery( $query );
            $db->query();
            $retval = ($db->loadObject()) ? $db->loadObjectList() : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_adminmapa.table.programa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * Retorna la lista de imagenes que tiene un proyecto 
     * @param int $idProyecto       Identificador del proyecto.
     * @return array                Lista de imagenes.
     */
    public function getImagenesProyecto( $idProyecto )
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