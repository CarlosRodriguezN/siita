<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class ProyectosTableGrafico extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__pfr_grafico', 'intIdGrafico_pfr', $db);
    }

    /**
     *  Regista un grafico.
     * @param type $idProyecto
     * @param type $grafico
     * @return type
     */
    public function saveGrafico($idProyecto, $grafico) {
        $idGrafico = 0;
        $data = array();
        $data["intIdGrafico_pfr"] = (int) $grafico->idGrafico;
        $data["intId_tg"] = (int) $grafico->tpoGrafico;
        $data["intCodigo_pry"] = (int) $idProyecto;
        $data["strDescripcionGrafico_pfr"] = $grafico->descGrafico;

        if ($this->save($data)) {
            $idGrafico = $this->intIdGrafico_pfr;
        }
        
        return $idGrafico;
    }

    /**
     *  Recupera la data de un grafico
     * @param type $idProyecto
     * @return type
     */
    public function getGraficosProyecto($idProyecto) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('grf.intIdGrafico_pfr            AS idGrafico,
                            grf.intId_tg                    AS tpoGrafico,           
                            grf.strDescripcionGrafico_pfr   AS descGrafico,
                            tgr.strDescripcion_tg           AS infoTpoGrafico,
                            grf.published                   AS published
                            ');
            $query->from('#__pfr_grafico AS grf');
            $query->join('inner','#__gen_tipo_grafico AS tgr ON grf.intId_tg=tgr.intId_tg');
            $query->where('grf.intCodigo_pry = ' . $idProyecto);
            $query->where('grf.published = 1');

            $db->setQuery($query);
            $db->query();

            $lstGraficos = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : FALSE;

            return $lstGraficos;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    
    public function delGrafico($idGrafico){
        try {
            if ((int) $idGrafico != 0) {
                $db = & JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->delete('#__pfr_grafico');
                $query->where('intIdGrafico_pfr=' . $idGrafico);
                $db->setQuery($query);
                $db->query();
            }
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        } 
    }
}