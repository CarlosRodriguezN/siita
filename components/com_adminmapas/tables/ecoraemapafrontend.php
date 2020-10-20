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
class adminmapasTableEcoraeMapaFrontEnd extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__map_wms_shp_ecorae', 'intCodigo_shp_ecorae', $db);
    }

    public function setDataShape() {
        try {
            //recupero la informcacion de el formulario 
            $infoEcoSHP = JRequest::getVar('jform');

            if ($infoEcoSHP["intCodigo_shp_ecorae"] == "0") {//nuevo dato
                //inserto los datos en la tabla #_map_wms
                $idWMP = $this->sqlInsertEcoraeSHPData($infoEcoSHP);
            } else {//actualización
                $idWMP = $this->_sqlUpdateWMSData($infoEcoSHP);
            }
            return $idWMP;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_adminmapa.table.ecoraemapa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function sqlInsertEcoraeSHPData( $values ) {
        try {
            $db = &JFactory::getDBO();
            $db->getQuery(true);

            $dataShp = json_decode( $values );
            
            // recupero los elemetos que seran alamacenados en la tabla wms
            $strNombre = ( $dataShp->strNombre )? $dataShp->strNombre
                                                : '';
            
            $strCopyright = ( $dataShp->strDescripcion )    ? $dataShp->strDescripcion
                                                            : '';
            
            $strDescripcion = ( $dataShp->autor )   ? $dataShp->autor 
                                                    : '';
            
            // Armo la sentencia SQL para INSERTAR los valores
            $sql = "INSERT INTO #__map_wms_shp_ecorae
                (   strNombre,
                    strDescripcion,
                    strCopyright)
                VALUES(
                       '{$strNombre}',
                       '{$strDescripcion}',
                       '{$strCopyright}'
                      );";

                       
            $db->setQuery(trim($sql, ','));
            $db->query();

            return $db->insertid();
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_adminmapa.table.ecoraemapafrontend.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    
    public function lastIdShape()
    {
        try {
            $db = &JFactory::getDBO();
            $db->getQuery(true);

            // Armo la sentencia SQL para INSERTAR los valores
            $sql = "SELECT MAX( intCodigo_shp_ecorae ) as id
                    FROM #__map_wms_shp_ecorae";
            
            $db->setQuery( $sql );
            $db->query();

            return $db->loadObject()->id;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_adminmapa.table.ecoraemapafrontend.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}