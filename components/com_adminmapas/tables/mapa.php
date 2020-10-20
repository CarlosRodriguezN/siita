<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla categoria ( #__categoria )
 * 
 */
class adminmapasTableMapa extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db)
    {
        parent::__construct('#__map_wms', 'intCodigo_wms', $db);
    }

    public function saveWmsData($dataForm)
    {
        $data['intCodigo_wms']  = $dataForm['intCodigo_wms'];
        $data['strNombre']      = $dataForm['strNombre'];
        $data['strCopyright']   = $dataForm['strCopyright'];
        $data['strDescripcion'] = $dataForm['strDescripcion'];
        $data['strURLService']  = trim( $dataForm['strURLService'], '?' );
        $data['published']      = $dataForm['published'];

        if( !$this->save($data) ){
            echo $this->getError(); 
            exit;
        }

        return $this->intCodigo_wms;
    }

    public function getLayers($idWMS)
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            // SQL de las capas de un WMS
            $query->select('ml.intCodigoMapLayers,
                            ml.intCodigo_wms,
                            ml.strNombreLayer, 
                            ml.strTitleLayer,
                            ml.published');
            $query->from('#__map_layers ml');
            $query->where("ml.intCodigo_wms = '{$idWMS}'");

            $db->setQuery($query);
            $db->query();
            return $db->loadObjectList();
        } catch( Exception $e ){
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_adminmapa.table.mapa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function deleteLayerWMS($idWMS)
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            // SQL de las capas de un WMS
            $query->delete($db->nameQuote('#__map_layers'));
            $query->where($db->nameQuote('intCodigo_wms') . '=' . $db->quote($idWMS));

            $db->setQuery($query);
            $db->query();
            return true;
        } catch( Exception $e ){
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_adminmapa.table.mapa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function deleteWMS($idWMS)
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            // SQL de las capas de un WMS
            $query->delete($db->nameQuote('#__map_wms'));
            $query->where($db->nameQuote('intCodigo_wms') . '=' . $db->quote($idWMS));

            $db->setQuery($query);
            $db->query();
            return true;
        } catch( Exception $e ){
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_adminmapa.table.mapa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}
