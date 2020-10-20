<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
jimport('ecorae.uploadfile.upload');

JTable::addIncludePath(JPATH_BASE . DS . 'components' . DS . 'com_adminmapas' . DS . 'tables');

/**
 * Modelo tipo obra
 */
class adminmapasModelEcoraemapa extends JModelAdmin
{

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Ecoraemapa', $prefix = 'adminmapasTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param	array	$data		Data for the form.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	mixed	A JForm object on success, false on failure
     * @since	1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_adminmapas.ecoraemapa', 'ecoraemapa', array( 'control' => 'jform', 'load_data' => $loadData ));

        if( empty($form) ){
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_adminmapas.edit.ecoraemapa.data', array());

        if( empty($data) ){
            $data = $this->getItem();
        }
        return $data;
    }

    /**
     * 
     * Gestiona la carga de Imagenes
     * 
     * @param type $file
     */
    public function saveUploadFiles($idMapEcorae, $tipo)
    {
        switch( $tipo ){
            //   Path para shapes
            case "shapes":
                $path = JPATH_SITE . '/images/stories/mapShapes';
            break;
            
            //   Path para imagenes
            case "images":
                $path = JPATH_SITE . '/images/stories/mapImages';
            break;
            
            //   Path para GeoServicio
            case "geoservicio":
                $path = JPATH_SITE . '/images/stories/mapGeoServicios';
            break;
        }

        $up_file = new upload( "Filedata", NULL, $path, $idMapEcorae );

        return $up_file->save();
    }

    /** desde el AJAX */
    public function saveEcoraeMapaData($dataFormulario)
    {
        echo '1 <hr>'; exit;
        
        $ecoraeMapaTable = $this->getTable();
        $data = json_decode($dataFormulario);

        return $ecoraeMapaTable->saveEcoraeMapaData($data);
    }

    /*
     * desde el form
     */

    public function saveEcoraeMapaDataForm( $dataFormulario )
    {
        $ecoraeMapaTable = $this->getTable();
        return $ecoraeMapaTable->saveEcoraeMapaData( json_decode( $dataFormulario ) );
    }

    
    
    public function saveLayersMapa( $idEcoraeMapa, $dtaLayers )
    {
        try{
            $layers = json_decode( $dtaLayers );
            
            if( count( (array)$layers ) ){
                foreach( $layers as $capa ){
                    //  Registro Informacion de capas
                   $this->_registroCapaMapa( $capa, $idEcoraeMapa );
                }
            }

            return true;
        } catch( Exception $e ){
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_adminmapa.model.mapa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    private function _registroCapaMapa( $capa, $idWMS )
    {
        $layerTable = $this->getTable('layer', 'adminmapasTable');
        return $layerTable->saveUpdateLayer($capa, $idWMS);
    }
    
    
    /**
     * 
     * @param type $intCodigo_shp_ecorae Identidicador delShape de Ecorae que se desea eliminar.
     */
    function deleteMapaEcorae($intCodigo_shp_ecorae)
    {
        $ecoraeTable = $this->getTable();
        return $ecoraeTable->deleteMapaEcorae($intCodigo_shp_ecorae);
    }

    /**
     * 
     * @param type $intCodigo_shp_ecorae identificador del mapa de ecorae eliminado
     * @return boolean
     */
    function deleteImageShapeEcorae($intCodigo_shp_ecorae)
    {
        // eliminando la imagen 
        $paths['Imagen'] = JPATH_SITE . '/images/stories/mapImages/' . $intCodigo_shp_ecorae . '.png';
        $paths['Shape'] = JPATH_SITE . '/images/stories/mapShapes/' . $intCodigo_shp_ecorae . '.zip';
        foreach( $paths AS $path ){
            unlink($path);
        }
        return true;
        //eliminando el shape.
    }

    
    /**
     * Retorna la lista de imagenes de un proyecto
     * 
     * @return int
     */
    public function getFilesMapas( $intCodigo_wms, $idTpo )
    {
        switch( $idTpo ){
            case '1': 
                $path = JPATH_SITE . DS . 'images' . DS . 'stories' . DS . 'mapShapes';
            break;
        
            //   Path para imagenes
            case '2':
                $path = JPATH_SITE . '/images/stories/mapImages';
            break;
            
            //   Path para GeoServicio
            case '3':
                $path = JPATH_SITE . '/images/stories/mapGeoServicios';
            break;
        }

        if( file_exists( $path ) ){
            $count = 0;
            $directorio = opendir( $path );

            while( $archivo = readdir( $directorio ) ){
                if( strstr( $archivo, $intCodigo_wms ) && $archivo != "." && $archivo != ".." ){
                    $docu["nameArchivo"]= $archivo;
                    $docu["published"]  = 1;
                    $docu["regArchivo"] = $count;

                    $lstArchivos[] = $docu;
                    $count++;
                }
            }

            closedir( $directorio );
        }

        return ( $lstArchivos != null ) ? $lstArchivos 
                                        : array();
    }
    
    
}
