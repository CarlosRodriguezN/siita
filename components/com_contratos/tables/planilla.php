<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla cargo ( #__ctr_cargo )
 * 
 */
class contratosTablePlanilla extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_planilla', 'intIdPlanilla_ptlla', $db);
    }

    /**
     * Recupera la planillas de un contrato.
     * @param type $idContrato
     */
    function getPlanillaContrato($idFactura) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(" 
                            pln.intIdPlanilla_ptlla	AS  idPlanilla,
                            pln.intCodPlantilla_ptlla	AS  codPlanilla,
                            pln.intIdFactura_fac	AS  idFactura,
                            pln.dcmMonto_ptlla          AS  monto,
                            pln.inpMes_ptlla            AS  mes,
                            pln.dteFechaEntrega_ptlla	AS  fchEntrega
                            ");
            $query->from("#__ctr_planilla AS pln");
            $query->join("inner", "#__ctr_factura AS fac ON fac.intIdFactura_fac=pln.intIdFactura_fac");
            $db->setQuery($query);
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : false;

            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.pago.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * @param type $idFactura
     * @param type $planilla
     */
    function saveFacturaPlanilla($idFactura, $planilla) {

        $data["intCodPlantilla_ptlla"] = $planilla->codPlanilla;
        $data["dteFechaEntrega_ptlla"] = $planilla->fchEntrega;
        $data["intIdPlanilla_ptlla"] = $planilla->idPlanilla;
        $data["intIdFactura_fac"] = $idFactura;
        $data["inpMes_ptlla"] = $planilla->mes;
        $data["dcmMonto_ptlla"] = $planilla->monto;

        if ($this->bind($data)) {
            if ($this->save($data)) {
                return $this->intIdPlanilla_ptlla;
            }
        }
        
        return false;
    }

}