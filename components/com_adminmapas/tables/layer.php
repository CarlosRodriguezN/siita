<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla #_map_wms_shp_ecorae ( #__categoria )
 * 
 */
class adminmapasTableLayer extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db)
    {
        parent::__construct('#__map_layers', 'intCodigoMapLayers', $db);
    }

    function saveUpdateLayer( $dataLayerForm, $intCodigo_wms )
    {
        $dataLayer['intCodigoMapLayers']= $dataLayerForm->intCodigoMapLayers;
        $dataLayer['intCodigo_wms']     = $intCodigo_wms;
        $dataLayer['strNombreLayer']    = $dataLayerForm->name;
        $dataLayer['strTitleLayer']     = $dataLayerForm->title;
        $dataLayer['strFormatLayer']    = 'image/png';
        $dataLayer['published']         = $dataLayerForm->checked;

        if( !$this->save($dataLayer) ){
            echo $this->getError(); 
            exit;
        }
        
        return $this->intCodigoMapLayers;
    }

}
