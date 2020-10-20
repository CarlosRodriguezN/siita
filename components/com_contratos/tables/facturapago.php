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
class contratosTableFacturaPago extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_factura_pago', 'intIdFacturaPago', $db);
    }

    public function saveFacturaPago($idFactura, $idPago, $pago) {
        $data["intIdFacturaPago"] = (int) $pago->idFacturaPago;
        $data["intIdPago_pgo"] = (int) $idPago;
        $data["intIdFactura_fac"] = (int) $idFactura;
        if ($this->save($data)) {
            return $this->intIdFacturaPago;
        }
    }

}
