<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Atribtuo ( #__ctr_atributo )
 * 
 */
class contratosTableUnidadTerritorial extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_ubicgeo_ctr', 'intIdUbicGeo_ctr', $db);
    }

    /**
     * 
     * @param type $idContrato
     * @param type $unidadTerritorial
     */
    public function saveUnidadTerritorial($idContrato, $unidadTerritorial) {
        if ($unidadTerritorial->published == 1) {
            $ubicacionGeografica = new stdClass();

            $ubicacionGeografica->intIdContrato_ctr = $idContrato;

            if ($unidadTerritorial->idProvincia != 0) {
                $ubicacionGeografica->intID_ut = $unidadTerritorial->idProvincia;
                if ($unidadTerritorial->idCanton != 0) {
                    $ubicacionGeografica->intID_ut = $unidadTerritorial->idCanton;
                    if ($unidadTerritorial->idParroquia != 0) {
                        $ubicacionGeografica->intID_ut = $unidadTerritorial->idParroquia;
                    }
                }
            }
            $success = $this->_db->insertObject("#__ctr_ubicgeo_ctr", $ubicacionGeografica);
            return $success;
        }
    }

    /**
     * 
     * @param type $idContrato
     */
    public function deleteUnidadesTerritoriales($idContrato) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->delete($db->nameQuote('#__ctr_ubicgeo_ctr'));
            $query->where($db->nameQuote('intIdContrato_ctr') . '=' . $db->quote($idContrato));

            $db->setQuery($query);
            $db->query();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.tiposcontratista.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Recupera las coordenadas de una DPA
     * @param type      $idUbcTerr      Unidad territorial.
     * @return Arrat                    Lista de coordenadas
     */
    public function dtaDPA($idUbcTerr) {
        try {
            $db = JFactory::getDBO();

            $sql = "SELECT  intID_ut, 
                            intID_tut, 
                            strCodDPA_ut
                    FROM " . $db->nameQuote("#__ut_undTerritorial") . " t1
                    WHERE intID_ut = " . $idUbcTerr;

            $db->setQuery((string) $sql);
            $db->query();


            if ($db->getNumRows() > 0) {
                $dtaUT = $db->loadObject();

                $dtaUT->coordenada = $this->_getDataDPA($dtaUT);
            }

            return $dtaUT;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Recupera las coordenadas de una DPA
     * @param object   $dtaDPA  Objeto DPA
     * @return object           Objeto coodenadas 
     */
    private function _getDataDPA($dtaDPA) {
        try {
            $db = JFactory::getDBO();

            switch ($dtaDPA->intID_tut) {
                case '3':
                    $sql = "SELECT  DISTINCT    lat                         AS lat, 
                                                longi                       AS lng,
                                                strNombreProvincia_dpa      AS provincia,
                                                strNombreCanton_dpa         AS canton,
                                                strNombreParroquia_dpa      AS parroquia
                            FROM tb_vw_dpa 
                            WHERE intIDProvincia_dpa = " . $dtaDPA->intID_ut;
                    break;

                case '4':
                    $sql = "SELECT  DISTINCT    lat                         AS lat, 
                                                longi                       AS lng,
                                                strNombreProvincia_dpa      AS provincia,
                                                strNombreCanton_dpa         AS canton,
                                                strNombreParroquia_dpa      AS parroquia
                            FROM tb_vw_dpa 
                            WHERE intIDCanton_dpa = " . $dtaDPA->intID_ut;
                    break;

                case '5':
                    $sql = "SELECT  DISTINCT    lat                         AS lat, 
                                                longi                       AS lng,
                                                strNombreProvincia_dpa      AS provincia,
                                                strNombreCanton_dpa         AS canton,
                                                strNombreParroquia_dpa      AS parroquia
                            FROM tb_vw_dpa 
                            WHERE intIDParroquia_dpa = " . $dtaDPA->intID_ut;
                    break;
            }

            $db->setQuery((string) $sql);
            $db->query();

            $undTerritorial = ( $db->getNumRows() > 0 ) ? $db->loadObject() : FALSE;

            return $undTerritorial;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}