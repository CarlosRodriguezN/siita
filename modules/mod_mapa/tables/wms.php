<?php

/**
 * @package     Joomla.Platform
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'models' . DS . 'layers.php';
//require_once  'modules' . DS . 'mod_mapa' . DS . 'models' . DS . 'layer.php';

/**
 * Category table 
 *
 * @package     Joomla.Platform
 * @subpackage  Table
 * @since       11.1
 */
class JTableWMS extends JTable
{

    /**
     * Constructor
     *
     * @param   JDatabase  &$db  A database connector object
     *
     * @since   11.1
     */
    public function __construct( &$db )
    {
        parent::__construct( '#__map_wms', 'intCodigo_wms', $db );

        $this->access = (int)JFactory::getConfig()->get( 'access' );
    }

    function getWMS()
    {
        try{
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            dev_siita . tb_map_wms;

            $query->select( "    wms.intCodigo_wms AS WMS,
                                wms.strNombre AS nombre, 
                                wms.strDescripcion AS Descripcion,
                                wms.strCopyright AS copyright,
                                wms.strURLService AS URLService" );
            $query->from( '#__map_wms AS wms ' );
            $query->where( 'wms.published=1' );
            $query->order( "wms.strNombre asc" );

            $db->setQuery( $query );

            $ltsWMS = ($db->loadObjectList())
                    ? $db->loadObjectList()
                    : false;

            $result = $this->_programasObjectConvert( $ltsWMS );

            //  El recorrido de el vector permite eliminar el nodo que no tenga hijos.
            for ( $i = 0; $i < count( $result ); $i++ ){
                if ( count( $result[$i]->children ) == 0 ){
                    unset( $result[$i] );
                    $result = array_values( $result );
                    $i--;
                }
            }
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.programas.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }

        return $result;
    }

    function _programasObjectConvert( $ltsWMS )
    {
        $ltsWMSJSON = array ();
        try{
            if ( $ltsWMS ){
                foreach ( $ltsWMS as $index => $ltsWMS ){
                    $programaJSON = (object)null;
                    $programaJSON->property = (object)null;
                    $programaJSON->property->name = $ltsWMS->nombre;
                    $programaJSON->property->hasCheckbox = true;
                    $programaJSON->property->id = $ltsWMS->WMS;
                    $programaJSON->type = "institucion";
                    $programaJSON->data = (object)null;
                    $programaJSON->data->copyright = $ltsWMS->copyright;
                    $programaJSON->data->Descripcion = $ltsWMS->Descripcion;
                    $programaJSON->data->URLService = $ltsWMS->URLService;
                    $programaJSON->children = $this->wmsSetLayers( $ltsWMS->WMS );

                    $ltsWMSJSON[$index] = $programaJSON;
                }
            }
        } catch ( Exception $e ){
            echo $e->getMessage();
        }

        return $ltsWMSJSON;
    }

//    /*
//     *Función que llama al modelo de la capa para recuperar las capas que posee un servidor de mapas
//     */
    function wmsSetLayers( $idWMS )
    {
        try{
            $layer = new layersModel(); // creamos el modelo de layers
            $result = array_values( $layer->getLayers( $idWMS ) );
        } catch ( Exception $e ){
            echo $e->getMessage();
        }
        return (array)$result;
    }

}
