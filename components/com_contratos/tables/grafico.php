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
class contratosTableGrafico extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_grafico', 'intIdGrafico_crtg', $db);
    }

    /**
     * Recupera la lista de Graficos de un contrato. 
     * @param type $idContrato
     * @return type
     */
    function getGraficosContrato($idContrato) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("    
                            grf.intIdGrafico_crtg           AS  idGrafico, 	
                            grf.intId_tg                    AS  idTipoGrafico,
                            tpg.strDescripcion_tg           AS  nmbTipoGrafico,
                            grf.strDescripcionGrafico_crtg  AS  descripcion,
                            grf.published                   AS  published	
                            ");
            $query->from("#__ctr_grafico AS grf");
            $query->join('inner','tb_gen_tipo_grafico AS tpg ON tpg.intId_tg = grf.intId_tg');
            $query->where("intIdContrato_ctr=" . $idContrato);
            $db->setQuery($query);
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.cargo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Almacena, acttualiza los campos de un grafico de un CONTRATO
     * @param type $idContrato
     * @param type $graficos
     */
    public function saveGraficoContrato($idContrato, $grafico) {
        $retval = false;
        $data["strDescripcionGrafico_crtg"] = $grafico->descripcion;
        $data["intIdGrafico_crtg"] = $grafico->idGrafico;
        $data["intId_tg"] = $grafico->idTipoGrafico;
        $data["intIdContrato_ctr"] = $idContrato;
        $data["published"] = $grafico->published;
        if ($this->bind($data)) {
            if ($this->save($data)) {
                $retval = $this->intIdGrafico_crtg;
            }
        }
        return $retval;
    }

}
