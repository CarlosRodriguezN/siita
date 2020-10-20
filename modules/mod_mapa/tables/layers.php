<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class JTableLayers extends JTable
{

    public function __construct( &$db )
    {
        parent::__construct( '#__map_layers', 'intCodigoMapLayers', $db );

        $this->access = ( int ) JFactory::getConfig()->get( 'access' );
    }

    function getLayers( $idWMS )
    {
        try{
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );

            $query->select( "   ly.intCodigoMapLayers AS id,
                                ly.strNombreLayer AS layer,
                                ly.strTitleLayer AS title,
                                ly.strFormatLayer AS format" );
            $query->from( '#__map_layers AS ly ' );
            $query->where( "ly.intCodigo_wms= " . $idWMS );
            $query->where( "ly.published = '1'" );
            $query->order( "ly.strNombreLayer ASC" );

            $db->setQuery( $query );
            $layers = $db->loadObjectList();
            $result = $this->_programasObjectConvert( $layers );
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.programas.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return array_values( $result );
    }

    function _programasObjectConvert( $layers )
    {
        $layersJSON = array();
        try{
            if( $layers ){
                foreach( $layers as $layer ){
                    $layerJSON                          = ( object ) null;

                    $layerJSON->property                = ( object ) null;
                    $layerJSON->property->name          = $layer->title;
                    $layerJSON->property->hasCheckbox   = true;
                    $layerJSON->property->id            = $layer->id;
                    $layerJSON->type                    = "layer";
                    $layerJSON->data                    = ( object ) null;
                    $layerJSON->data->layer             = $layer->layer;
                    $layerJSON->data->title             = $layer->title;
                    $layerJSON->data->format            = $layer->format;

                    $layersJSON[$layer->id]             = $layerJSON;
                }
            }
        } catch( Exception $e ){
            echo $e->getMessage();
        }
        return ( array ) ($layersJSON);
    }

}
