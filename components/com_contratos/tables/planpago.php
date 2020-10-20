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
class contratosTablePlanPago extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_plan_pagos', 'intIdPlanPago_pgo', $db);
    }

    function getPlanesPagoContrato($idContrato) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("    plg.intIdPlanPago_pgo   AS  idPlanPago,        
                                plg.intCodPago_pgo      AS  codPlanPago,      
                                plg.strProducto_pgo     AS  producto,         
                                plg.dcmMonto_pgo        AS  monto,        
                                plg.inpPorCiento_pgo    AS  porcentaje,
                		plg.dteFechaPago_pgo    AS  fecha, 
                		plg.published           AS  published
                            ");
            $query->from("#__ctr_plan_pagos AS plg");
            $query->where("plg.intIdContrato_ctr=" . $idContrato);
            $query->where("plg.published=1");

            $db->setQuery($query);
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.atributo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * alamacen la relación contratista contrato
     * @param int $idContrato identificador del contrato
     * @param int $planPago Identificador del contratista
     * @return int identificador de la linea contrato contratista.
     */
    public function savePlanPago($idContrato, $planPago) {
        $idPlanPago=false;
        $data["intIdPlanPago_pgo"] = (int) $planPago->idPlanPago;
        $data["intIdContrato_ctr"] = $idContrato;
        $data["intCodPago_pgo"] = (int) $planPago->codPlanPago;
        $data["strProducto_pgo"] = $planPago->producto;
        $data["dcmMonto_pgo"] = $planPago->monto;
        $data["inpPorCiento_pgo"] = (int) $planPago->porcentaje;
        $data["dteFechaPago_pgo"] = $planPago->fecha;
        $data["published"] = (int) $planPago->published;
        if ($this->bind($data)) {
            if ($this->save($data)) {
                $idPlanPago = $this->intIdPlanPago_pgo;
            }
        }
        return $idPlanPago;
    }

}