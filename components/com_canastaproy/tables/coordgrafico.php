<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla coordenadas grafico de cp ( #__cp_coordenadas_grafico )
 * 
 */
class CanastaproyTableCoordgrafico extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__cp_coordenadas_grafico', 'intId_cgcp', $db);
    }

    public function addCoordenadaGraficoCP($dtaCoordenada) {
        if (!$this->save($dtaCoordenada)) {
            echo 'error al guardar la coordenada';
            exit;
        }

        return $this->intId_cgcp;
    }

    public function delCoordenadaGraficoCP($idCoordenada) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->delete("#__cp_coordenadas_grafico");
            $query->where("intId_cgcp='{$idCoordenada}'");
            $db->setQuery($query);
            $db->query();
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function delCoordenadasGrafico($idGrafico) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->delete("#__cp_coordenadas_grafico");
            $query->where("intId_gcp='{$idGrafico}'");
            $db->setQuery($query);
            $result = ($db->query()) ? true : false;
            
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function getLstCoordenadasGrafico($idGrafico) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('cgp.intId_cgcp, cgp.fltLatitud_cord, cgp.fltLongitud_cord');
            $query->from('#__cp_coordenadas_grafico as cgp');
            $query->where('cgp.intId_gcp = ' . $idGrafico);

            $db->setQuery((string) $query);
            $db->query();

            $rstCoordenadas = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : false;

            return $rstCoordenadas;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}