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
class contratosTableProrroga extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_proroga', 'intIdProrroga_prrga', $db);
    }

    /**
     * 
     */
    public function getProrrogasContrato($idContrato) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("prrg.intIdProrroga_prrga     AS  idProrroga,
                            prrg.intCodProroga_prrga     AS  idCodigoProrroga,
                            prrg.dcmMora_prrga           AS  mora,
                            prrg.intPlazo_prrga          AS  plazo,
                            prrg.strDocumento_prrga      AS  documento,
                            prrg.strObservacion_prrga    AS  observacion,
                            prrg.published
                            ");
            $query->from("#__ctr_proroga AS prrg");
            $query->where('prrg.intIdContrato_ctr=' . $idContrato);
            $query->where('prrg.published = 1');

            $db->setQuery($query);
            $db->query();

            return ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.atributo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * @param type $idContrato
     * @param type $prorroga
     */
    public function saveProrroga($idContrato, $prorroga) {
        
        $data["intIdProrroga_prrga"] = $prorroga->idProrroga;
        $data["intIdContrato_ctr"] = $idContrato;
        $data["intCodProroga_prrga"] = $prorroga->idCodigoProrroga;
        $data["dcmMora_prrga"] = $prorroga->mora;
        $data["intPlazo_prrga"] = $prorroga->plazo;
        $data["strDocumento_prrga"] = $prorroga->documento;
        $data["strObservacion_prrga"] = $prorroga->observacion;
        $data["published"] = $prorroga->published;

        if ($this->bind($data)) {

            if ($this->save($data)) {
                return true;
            }
        }
    }

}