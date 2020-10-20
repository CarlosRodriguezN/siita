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
class contratosTableFactura extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_factura', 'intIdFactura_fac', $db);
    }

    /**
     * Recupera las facturas de un contrato.
     * @param type $idContrato
     */
    public function getFacturasContrato($idContrato) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("    intIdFactura_fac    AS  idFactura,
                                intCodFactura_fac   AS  codFactura,
                                dteFechaFactura_fac AS  fchFactura,
                                strNumero_fac       AS  numFactura,
                                dcmMonto_fac        AS  monto,
                                published           AS  published
                            ");
            $query->from("#__ctr_factura");
            $query->where('intIdContrato_ctr=' . $db->quote($idContrato));
            $query->order('dteFechaFactura_fac');
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
     * Recupera el pago de una factura.
     * @param type $idFactura
     * @return type
     */
    public function getPagosFacturaContrato($idFactura) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(" 
                            pf.intIdFacturaPago     AS     idFacturaPago,
                            pf.intIdPago_pgo        AS     idPago,
                            pf.intIdFactura_fac     AS     idFactura,
                            pgo.intIdTipoPago_tp    AS     idTipoPago,
                            pgo.intCodPago_pgo      AS     codPago,
                            pgo.strCUR_pgo          AS     cur, 
                            pgo.dcmMonto_pgo        AS     monto, 
                            pgo.strDocumento_pgo    AS     documento                      
                            ");
            $query->from("#__ctr_factura_pago AS pf");
            $query->join("inner", "#__ctr_pago AS pgo ON pgo.intIdPago_pgo = pf.intIdPago_pgo ");
            $query->where('pf.intIdFactura_fac=' . $db->quote($idFactura));
            $db->setQuery($query);
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObject() : false;

            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.atributo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Recupera la planilla de una factura.
     * @param type $idFactura
     * @return type
     */
    public function getPlanillaFacturaContrato($idFactura) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(" 
                            pln.intIdPlanilla_ptlla	AS  idPlanilla,
                            pln.intIdFactura_fac	AS  idFactura,
                            pln.intCodPlantilla_ptlla	AS  codPlanilla,
                            pln.dcmMonto_ptlla          AS  monto,
                            pln.inpMes_ptlla            AS  mes,
                            pln.dteFechaEntrega_ptlla	AS  fchEntrega
                            ");
            $query->from("#__ctr_planilla AS pln");
            $query->join("inner", "#__ctr_factura AS fac ON fac.intIdFactura_fac=pln.intIdFactura_fac");
            $query->where("pln.intIdFactura_fac=" . $idFactura);
            $db->setQuery($query);
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObject() : false;

            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.pago.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Guarda los datos generales de una factura,(los pagos y los planillas se 
     * guaradan en cada tabla )
     * @param type $idContrato
     * @param type $factura
     * @return boolean
     */
    public function saveFactura($idContrato, $factura) {
        $data["intIdFactura_fac"] = $factura->idFactura;
        $data["intIdContrato_ctr"] = $idContrato;
        $data["intCodFactura_fac"] = $factura->codFactura;
        $data["dteFechaFactura_fac"] = $factura->fchFactura;
        $data["strNumero_fac"] = $factura->numFactura;
        $data["dcmMonto_fac"] = $factura->monto;
        $data["published"] = $factura->published;
        if ($this->bind($data)) {
            if ($this->save($data)) {
                return $this->intIdFactura_fac;
            }
        }
        return false;
    }

}