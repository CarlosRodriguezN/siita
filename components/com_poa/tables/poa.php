<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de POA ( #__poa_plan_institucion )
 * 
 */
class PoaTablePoa extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__pln_plan_institucion', 'intId_pi', $db);
    }

    public function registroPoa($dtaPlan) {

        $idPoa = 0;

        $propuesta["intId_pi"] = $dtaPlan->intId_pi;
        $propuesta["intIdPadre_pi"] = $dtaPlan->idPadrePei;
        $propuesta["intId_tpoPlan"] = $dtaPlan->intId_tpoPlan;
        $propuesta["intCodigo_ins"] = $dtaPlan->intCodigo_ins;
        $propuesta["strDescripcion_pi"] = $dtaPlan->strDescripcion_pi;
        $propuesta["dteFechainicio_pi"] = $dtaPlan->dteFechainicio_pi;
        $propuesta["dteFechafin_pi"] = $dtaPlan->dteFechafin_pi;
        $propuesta["strAlias_pi"] = $dtaPlan->strAlias_pi;
        $propuesta["blnVigente_pi"] = $dtaPlan->blnVigente_pi;
        $propuesta["published"] = $dtaPlan->published;

        if ($this->save($propuesta)) {
            $idPoa = $this->intId_pi;
        }

        return $idPoa;
    }

    public function updVigencia($idPoa, $opVgen) {
        try {

            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update("#__pln_plan_institucion");
            $query->set("blnVigente_pi=" . $opVgen);
            $query->where("intId_pi= " . $idPoa);
            $db->setQuery($query);

            $result = ($db->query()) ? $idPoa : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_poa.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Recupera el Pei al que pertenese el poa
     * @param type $idPei
     * @return type
     */
    public function getPei($idPei) {
        try {

            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select('
                            intId_pi,
                            intId_tpoPlan,
                            intCodigo_ins,     
                            intIdPadre_pi,     
                            strDescripcion_pi,
                            dteFechainicio_pi,
                            dteFechafin_pi,     
                            blnVigente_pi ,     
                            strAlias_pi,        
                            published      
                            ');
            $query->from("#__pln_plan_institucion");
            $query->where('intId_pi=' . $idPei);
            $db->setQuery($query);
            $db->query();
            $retval = ($db->loadObject()) ? $db->loadObject() : false;
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_poa.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}