<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla grafico de propuesta de proyecto ( #__cp_grafico_propuesta )
 * 
 */
class CanastaproyTableGraficocp extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__cp_grafico_propuesta', 'intId_gcp', $db);
    }

    public function addGraficoPropuesta($graficoPropuesta) {
        if (!$this->save($graficoPropuesta)) {
            echo 'error al guardar el grafico de propuesta';
            exit;
        }
        return $this->intId_gcp;
    }

    public function delGraficoPropuesta($idGrafico) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->delete("#__cp_grafico_propuesta");
            $query->where("intId_gcp= ". $idGrafico );
            $db->setQuery($query);
            $result = ($db->query()) ? true : false;
            
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function getLstGraficoPropuesta($idPropuesta) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('gp.intId_gcp, gp.intId_tg, tg.strDescripcion_tg, gp.strDescripcionGrafico_gcp');
            $query->from('#__cp_grafico_propuesta as gp');
            $query->join('INNER', '#__gen_tipo_grafico as tg ON tg.intId_tg = gp.intId_tg');
            $query->where('gp.intIdPropuesta_cp = ' . $idPropuesta);

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