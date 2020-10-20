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
class contratosTableFiscalizadorContrato extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_fiscalizador_contrato', 'intIdFisCrto_fctr', $db);
    }

    public function saveFiscalizador($idContrato, $fiscalizador) {
        $idFiscalizadorContrato = 0;
        if (gettype($fiscalizador->idFiscaContrato) == "string") {
            $fiscalizador->idFiscaContrato = 0;
            $fiscalizador->fschRegisto = date();
        }

        $data["intIdFisCrto_fctr"] = $fiscalizador->idFiscaContrato;
        $data["intIdContrato_ctr"] = $idContrato;
        $data["intIdFiscalizador_fc"] = $fiscalizador->idFiscalizador;
        $data["dteFechaInicio_fctr"] = $fiscalizador->fchIncio;
        $data["dteFechaFin_fctr"] = $fiscalizador->fchFin;
        $data["dteFechaRegistro_fc"] = $fiscalizador->fschRegisto;
        $data["published"] = $fiscalizador->published;
        if ($this->bind($data)) {
            if ($this->save($data)) {
                $idFiscalizadorContrato = $this->intIdFiscalizador_fc;
            }
        }
        return $idFiscalizadorContrato;
    }

}