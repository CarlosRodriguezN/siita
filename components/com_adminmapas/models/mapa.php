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
class adminmapasModelMapa extends JModelAdmin
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
    public function getTable($type = 'Mapa', $prefix = 'adminmapasTable', $config = array())
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
        $form = $this->loadForm('com_adminmapas.mapa', 'mapa', array( 'control' => 'jform', 'load_data' => $loadData ));

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
        $data = JFactory::getApplication()->getUserState('com_adminmapas.edit.mapa.data', array());

        if( empty($data) ){
            $data = $this->getItem();
        }

        return $data;
    }

    public function getInfoLayer()
    {
        $layersArray = array();
        $url = JRequest::getVar('url');

        if( strlen( $url ) ){
            $urlWms = $this->_procesarUrl( $url );
            $dtaWms = $this->_getDtaLayerWms( $urlWms );

            if( $dtaWms->length ){
                foreach( $dtaWms as $k => $wms ){
                    if( $wms->getAttribute('queryable') ){
                        $layersArray[] = $this->_getAttrLayer( $k, $wms );
                    }
                }
            }
        }

        //  Retorna un array con el array de la información
        return $layersArray;
    }

    /**
     * 
     * Proceso Url
     * 
     * @param type $url
     * @return type
     */
    private function _procesarUrl( $url )
    {
        // si la cadena es diferente de vacio
        $request = "request=GetCapabilities";

        $urlProcesada = ( strpos( $url, '?' ) ) ? $url. $request 
                                                : $url. '?' .$request;

        return $urlProcesada;
    }

    
    
    private function _getDtaLayerWms( $url )
    {
        //  Recuperamos el XML de la URL WMS
        $xml = file_get_contents( $url );
        $dtaWms = new DOMDocument;
        $dtaWms->loadXML($xml);
        
        return $dtaWms->getElementsByTagName('Layer');
    }

    
    private function _getAttrLayer( $k, $wms )
    {
        $name   = $wms->getElementsByTagName('Name');
        $title  = $wms->getElementsByTagName('Title');

        //  Creamos el array que contendra la información necesaria
        $dataLayer = array();

        if( $name && $title ){
            $dataLayer["idLayer"]   = $k;
            $dataLayer["name"]      = (string)$name->item(0)->nodeValue;
            $dataLayer["title"]     = (string)$title->item(0)->nodeValue;
        }
        
        return $dataLayer;
    }



    /**
     * Funcion que recupera todas las LAYERS de un WMS ($wms)
     */
    public function getlayerseWMS($idWMS)
    {
        //Instancio el objeto de la clase Layers "/mod_mapa/tables/mapa.php"
        $layerWMS = $this->getTable('Mapa', 'adminmapasTable');

        return $layerWMS->getLayers($idWMS);
    }

    /**
     * @abstract Función para guardar la información del el form
     * @param type $dataJSON
     */
    public function saveWMSFromJSON( $dataJSON )
    {
        try{
            //  Registro informacion general del Mapa
            $idWMS = $this->_registroDtaMapa( $dataJSON );
            $layers = json_decode( $dataJSON['dataLayer'] );

            if( count( (array)$layers->capas ) ){
                foreach( $layers->capas as $capa ){
                    //  Registro Informacion de capas
                   $this->_registroCapaMapa( $capa, $idWMS );
                }
            }

            return true;
        } catch( Exception $e ){
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_adminmapa.model.mapa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    
    private function _registroDtaMapa( $dataJSON )
    {
        //  Registro informacion general del Mapa
        $tableWMS = $this->getTable();
        return $tableWMS->saveWmsData($dataJSON);
    }
    
    
    private function _registroCapaMapa( $capa, $idWMS )
    {
        $layerTable = $this->getTable('layer', 'adminmapasTable');
        return $layerTable->saveUpdateLayer($capa, $idWMS);
    }

    
    public function deleteWMS($intCodigo_wms)
    {
        $tableWMS = $this->getTable();
        if( $tableWMS->deleteLayerWMS($intCodigo_wms) ){ //elimino las capas 
            return $tableWMS->deleteWMS($intCodigo_wms); // elimino el servidoer
        }
        return false;
    }
}
