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
class contratosTablePago extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_pago', 'intIdPago_pgo', $db);
    }

    public function getPago($idPago) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("    pgo.intIdPago_pgo       AS  idPago,
                                pgo.intIdTipoPago_tp    AS  idTipoPago,
                                pgo.intCodPago_pgo      AS  codPago,
                                pgo.strCUR_pgo          AS  cur,
                                pgo.dcmMonto_pgo        AS  monto,
                                pgo.inpPorCiento_pgo    AS  porcentajePago
                            ");
            $query->from("#__ctr_pagos AS pgo");
            $query->join("inner", "#__ctr_tipo_pago AS pgo.intIdTipoPago_tp=pgo.intIdTipoPago_tp");
            $query->where('intIdPago_pgo =' . $db->quote($idPago));

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

    public function getPagosContrato($idContrato) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("    pgo.intIdPago_pgo       AS  idPago,
                                pgo.intIdTipoPago_tp    AS  idTipoPago,
                                tpgo.strNombre_tp       AS  nombreTipoPago,
                                pgo.intCodPago_pgo      AS  codPago,
                                pgo.strCUR_pgo          AS  cur,
                                pgo.dcmMonto_pgo        AS  monto,
                                pgo.strDocumento_pgo    AS  documento
                            ");
            $query->from("#__ctr_pago AS pgo");
            $query->join("inner", "#__ctr_tipo_pago AS tpgo ON tpgo.intIdTipoPago_tp=pgo.intIdTipoPago_tp");
            $query->where("pgo.intIdContrato_ctr=" . $idContrato);
            $query->group("pgo.intIdPago_pgo");
            $db->setQuery($query);
            $db->query();
            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.pago.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function savePagoFactura($idContrato, $pago) {

        $data["intIdPago_pgo"] = $pago->idPago;
        $data["intIdTipoPago_tp"] = $pago->idTipoPago;
        $data["intCodPago_pgo"] = $pago->codPago;
        $data["intIdContrato_ctr"] = $idContrato;
        $data["strCUR_pgo"] = $pago->cur;
        $data["dcmMonto_pgo"] = $pago->monto;
        $data["strDocumento_pgo"] = $pago->documento;
        if ($this->bind($data)) {
            if ($this->save($data)) {
                return $this->intIdPago_pgo;
            }
        }
    }

    public function saveAnticipoContrato($idContrato, $pago) {
        $data["intIdPago_pgo"] = $pago->idPago;
        $data["intIdTipoPago_tp"] = 1;
        $data["intCodPago_pgo"] = $pago->codPago;
        $data["intIdContrato_ctr"] = $idContrato;
        $data["strCUR_pgo"] = $pago->cur;
        $data["dcmMonto_pgo"] = $pago->monto;
        $data["strDocumento_pgo"] = $pago->documento;
        if ($this->bind($data)) {
            if ($this->save($data)) {
                return $this->intIdPago_pgo;
            }
        }
        return $this->intIdPago_pgo;
    }

    /**
     * recupera el objeto Anticipo de un contrato. al que se le asociara la factura luego.
     * @param type $idContrato 
     */
    public function getAnticipoContrato($idContrato) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("    pgo.intIdPago_pgo       AS  idPago,
                                pgo.intIdTipoPago_tp    AS  idTipoPago,
                                pgo.intCodPago_pgo      AS  codPago,
                                pgo.strCUR_pgo          AS  cur,
                                pgo.dcmMonto_pgo        AS  monto,
                                pgo.strDocumento_pgo    AS  documento
                            ");
            $query->from("#__ctr_pago AS pgo");
            $query->where("pgo.intIdContrato_ctr=" . $idContrato);
            $query->where("pgo.intIdTipoPago_tp = 1");
            $db->setQuery($query);

            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObject() : array();

            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.pago.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * @param type $idFacturaPago
     * @return type
     */
    public function getAnticipoFacturaContrato($idFacturaPago) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(" 
                            fac.intIdFactura_fac    AS  idFactura,
                            fac.intCodFactura_fac   AS  codFactura,
                            fac.dteFechaFactura_fac AS  fchFactura,
                            fac.strNumero_fac       AS  numFactura,
                            fac.dcmMonto_fac        AS  monto,
                            fac.published           AS  published
                            ");
            $query->from("#__ctr_factura AS fac");
            $query->join("inner", "#__ctr_factura_pago AS fp ON fp.intIdFactura_fac=fac.intIdFactura_fac");
            $query->where("fp.intIdFacturaPago=" . (int) $idFacturaPago);
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

    public function getFacturaPagoAnticipo($idPago) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(" 
                fp.intIdFacturaPago    AS  idFacturaPago,
                fp.intIdFactura_fac    AS  idFactura
                            ");
            $query->from("#__ctr_factura_pago AS fp");
            $query->where("fp.intIdPago_pgo=" . (int) $idPago);
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

}
