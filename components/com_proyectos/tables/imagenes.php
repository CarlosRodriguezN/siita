<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Imagenes
 * 
 */
class ProyectosTableImagenes extends JTable
{

    /**
     *   Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db)
    {
        parent::__construct('#__pfr_images_proyecto', 'intId_img', $db);
    }

    /**
     * 
     * Retorno el ultimo identificador registrado en un determinado proyecto
     * 
     * @param type $idProyecto
     * @return type
     */
    public function getLastId($idProyecto)
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('MAX( img.intId_img ) AS idImagen');
            $query->from('#__pfr_images_proyecto img');

            $db->setQuery((string) $query);
            $db->query();

            return $db->loadObject()->idImagen;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * Retorno una lista de Imagenes pertenecientes a un determinado proyecto
     * 
     * @param type $idProyecto  Identificador del proyecto
     * 
     * @return type
     */
    public function getImagenes($idProyecto)
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('   img.intId_img, 
                                img.strNombre_img, 
                                img.published');
            $query->from('#__pfr_images_proyecto img');
            $query->where('img.intCodigo_pry = ' . $idProyecto);

            $db->setQuery((string) $query);
            $db->query();

            $rst = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : FALSE;

            return $rst;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     */
    public function getCountImgProy($idProyecto)
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('   COUNT(img.intId_img) AS total');
            $query->from('      #__pfr_images_proyecto AS img');
            $query->where('     img.intCodigo_pry = ' . $idProyecto);
            $db->setQuery((string) $query);
            $db->query();

            $total = $db->loadObject()->total;
            return $total;
            ;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     */
    public function getIdIconProy($idProyecto)
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('   img.intId_img AS id');
            $query->from('#__pfr_images_proyecto AS img');
            $query->where('img.intCodigo_pry = ' . $idProyecto);
            $query->where('img.strTipo_imagen like "icon"');
            $db->setQuery((string) $query);
            $db->query();
            $idReturn = ($db->loadObject()->id) ? $db->loadObject()->id : 0;
            return $idReturn;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    /**
     * 
     * Gestiono el registro de una imagen en un proyecto
     * 
     * @param array $dtaImagen   Datos informativos de una imagen
     * 
     * @return boolean
     * 
     * @throws Exception
     */
    public function registrarImagen( $dtaImagen )
    {
        if( !$this->save( $dtaImagen ) ){
            throw new Exception( 'Error al Registrar Imagen de un Proyecto' );
        }

        return true;
    }
    
    /**
     * 
     * Elimina el registro de una determinada imagen
     * 
     * @param type $idImg   Identificador de la imagen
     * @return boolean
     * @throws Exception
     */
    public function deleteImagen( $idImg )
    {
        if( !$this->delete( $idImg ) ){
            throw new Exception( 'Error al Eliminar Imagen de un Proyecto' );
        }

        return true;
    }
}