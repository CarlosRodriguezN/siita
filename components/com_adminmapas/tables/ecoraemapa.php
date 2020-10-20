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
class adminmapasTableEcoraeMapa extends JTable
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

    /**
     * desde el ajax
     * @param type $dataFormulario
     * @return type
     */
    public function saveEcoraeMapaData( $dataFormulario )
    {
        $data["intCodigo_wms"]  = $dataFormulario->intCodigo_wms;
        $data["strNombre"]      = $dataFormulario->strNombre;
        $data["strDescripcion"] = $dataFormulario->strDescripcion;
        $data["strURLService"]  = $dataFormulario->strURLService;
        $data["strCopyright"]   = $dataFormulario->strCopyright;
        $data["intTpoMapa"]     = (int)$dataFormulario->intTpoMapa;
        $data["published"]      = $dataFormulario->published;
        
        if( !$this->save($dataFormulario) ){
            echo $this->getError();
            exit;
        }

        return $this->intCodigo_wms;
    }

    /**
     * desde el FORM
     * @param type $dataFormulario
     * @return type
     */
    public function saveEcoraeMapaDataForm($dataFormulario)
    {
        echo '2 <hr>';
        var_dump( $dataFormulario ); exit;
        
        $data["intCodigo_wms"]  = $dataFormulario["intCodigo_shp_ecorae"];
        $data["strNombre"]      = $dataFormulario["strNombre"];
        $data["strCopyright"]   = $dataFormulario["strCopyright"];
        $data["strDescripcion"] = $dataFormulario["strDescripcion"];
        $data["published"]      = $dataFormulario["published"];
        
        if( $this->bind($data) ){
            if( $this->save($dataFormulario) ){
                return $this->intCodigo_shp_ecorae;
            }
        }
    }

    /**
     * 
     * @param type $intCodigo_shp_ecorae identificador del mapa que sera eliminado.
     */
    public function deleteMapaEcorae($intCodigo_shp_ecorae)
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            // SQL de las capas de un WMS
            $query->delete($db->nameQuote('#__map_wms_shp_ecorae'));
            $query->where($db->nameQuote('intCodigo_shp_ecorae') . '=' . $db->quote($intCodigo_shp_ecorae));

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
