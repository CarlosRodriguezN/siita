<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Unidad Territorial  ( #__ut_undTerritorial )
 * 
 */
class contratosTableVwdpa extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__vw_dpa', 'inpID_dpa', $db);
    }

    /**
     *
     * Retorna lista de cantones pertenecientes a una determanda Provincia
     * 
     * @return type 
     */
    public function lstCantones($idProvincia) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('    DISTINCT dpa.intIDCanton_dpa AS id, 
                                dpa.strNombreCanton_dpa AS nombre');

            $query->from('#__vw_dpa dpa');
            $query->where('dpa.intIDProvincia_dpa = ' . $idProvincia);
            $query->order('dpa.strNombreCanton_dpa');

            $db->setQuery((string) $query);

            $db->query();

            $rstCantones = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : false;

            return $rstCantones;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     *
     * Retorna lista de cantones pertenecientes a una determanda Provincia
     * 
     * @return type 
     */
    public function lstParroquias($idCanton) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('   DISTINCT dpa.intIDParroquia_dpa AS id, 
                                dpa.strNombreParroquia_dpa AS nombre');
            $query->from('#__vw_dpa dpa');
            $query->where('dpa.intIDCanton_dpa = ' . $idCanton);
            $query->order('dpa.strNombreParroquia_dpa');

            $db->setQuery((string) $query);

            $db->query();

            $rstParroquias = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : false;

            return $rstParroquias;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * @param type $idPropuesta
     * @return type
     */
    public function deleteUndTerritorial($idPropuesta) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->delete('#__cp_ubicgeo');
            $query->where('intIdPropuesta_cp = ' . $idPropuesta);

            $db->setQuery((string) $query);
            $result = ($db->query()) ? true : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * Lista de Unidad Territorial
     * 
     * @param type $idContrato Identificador de unidad territorial
     * 
     * @return type
     */
    public function getLstUndTerritoriales($idContrato) {
        try {
            $db = JFactory::getDBO();

            $sql = "SELECT  t1.intID_ut             AS idUndTerritorial, 
                            t2.intID_tut            AS tpoUndTerritorial
                    FROM " . $db->nameQuote("#__ctr_ubicgeo_ctr") . " t1
                    JOIN " . $db->nameQuote("#__ut_undTerritorial") . " t2 ON
                    t1.intID_ut = t2.intID_ut
                    WHERE t1.intIdContrato_ctr = '" . $idContrato . "'";
            $db->setQuery((string) $sql);
            $db->query();

            if ($db->getNumRows() > 0) {
                $lstUT = $db->loadObjectList();

                foreach ($lstUT as $ut) {
                    switch ($ut->tpoUndTerritorial) {
                        case 3:
                            $infoUT = $this->_getDtaUT($ut->idUndTerritorial, $ut->tpoUndTerritorial);
                            break;

                        case 4:
                            $infoUT = $this->_getDtaUT($ut->idUndTerritorial, $ut->tpoUndTerritorial);
                            break;

                        case 5:
                            $infoUT = $this->_getDtaUT($ut->idUndTerritorial, $ut->tpoUndTerritorial);
                            break;
                    }
                    $dtaUT[] = $infoUT;
                }
            }
            return ($dtaUT) ? $dtaUT : array();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * Retorna informaion de una determinadad Unidad territorial de acuerdo 
     * a un determinado Tipo de unidad Territorial
     * 
     * @param type $idUt    Identificador de Unidad territorial
     * @param type $tpoUT   Identificador de Tipo de Unidad Territorial
     * 
     * @return type
     */
    private function _getDtaUT($idUt, $tpoUT) {
        try {
            $db = JFactory::getDBO();

            switch ($tpoUT) {
                case 3:
                    $sql = "SELECT  DISTINCT t1.intIDProvincia_dpa AS idProvincia,
                                    t1.strNombreProvincia_dpa AS provincia, 
                                    0 AS idCanton, 
                                    0 AS idParroquia
                            FROM tb_vw_dpa t1
                            WHERE intIDProvincia_dpa = " . $idUt;
                    break;

                case 4:
                    $sql = "SELECT  DISTINCT t1.intIDProvincia_dpa AS idProvincia,
                                    t1.strNombreProvincia_dpa AS provincia,
                                    t1.intIDCanton_dpa AS idCanton, 
                                    t1.strNombreCanton_dpa AS canton, 
                                    0 AS idParroquia
                            FROM tb_vw_dpa t1
                            WHERE intIDCanton_dpa = " . $idUt;
                    break;

                case 5:
                    $sql = "SELECT  DISTINCT t1.intIDProvincia_dpa AS idProvincia,
                                    t1.strNombreProvincia_dpa AS provincia,
                                    t1.intIDCanton_dpa AS idCanton, 
                                    t1.strNombreCanton_dpa AS canton,
                                    t1.intIDParroquia_dpa AS idParroquia,
                                    t1.strNombreParroquia_dpa AS parroquia
                            FROM tb_vw_dpa t1
                            WHERE intIDParroquia_dpa = " . $idUt;
                    break;
            }

            $db->setQuery((string) $sql);
            $db->query();

            $lstUndTerritorial = ( $db->getNumRows() > 0 ) ? $db->loadObject() : FALSE;

            return $lstUndTerritorial;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
                
}