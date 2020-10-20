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
class contratosTableMulta extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_multa', 'intIdMulta_mta', $db);
    }

    public function deleteMulta($idAtributo) {
        try {
            if (!$this->relacionadaTo($idAtributo)) {
                $db = & JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->delete($db->nameQuote('#__ctr_multa'));
                $query->where($db->nameQuote('intIdMulta_mta') . '=' . $db->quote($idAtributo));

                $db->setQuery($query);
                $db->query();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.multa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * verifica si tiene relacion con otras tablas
     */
    public function relacionadaTo($idCargo) {
        if ($this->relacionada($idCargo, 'ctr_contrato', 'intIdMulta_mta')) {
            return true;
        }
        return false;
    }

    /**
     * verifica que no este relacionada el registro con otro en otra tabla.
     * @param type $idTipo
     * @param string $tabla nombre de la tabla que sera comparada, sin en prefijo
     * @param string $clmName nombre de la columna que sera comparada.
     */
    function relacionada($idTipo, $tabla, $clmName) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("COUNT(" . $clmName . ") AS cantidad");
            $query->from("#__" . $tabla);
            $query->where($clmName . '=' . $db->quote($idTipo));

            $db->setQuery($query);
            $db->query();
            $num = $db->loadObject()->cantidad;
            return ($num > 0) ? true : false;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.atributo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     */
    public function getMultasContrato($idContrato) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('mlt.intIdMulta_mta          AS idMulta,
                            mlt.intCodMulta_mta         AS codMulta,
                            mlt.dcmMonto_mta            AS monto,
                            mlt.strObservacioin_mta     AS observacion,
                            mlt.published               AS published
                            ');
            $query->from('#__ctr_multa AS mlt');

            $query->where('mlt.intIdContrato_ctr=' . $idContrato);

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
     * @param type $multa
     */
    public function saveMultas($idContrato, $multa) {
        if ($multa->published != 0) {
            $data = false;
            if (gettype($multa->idMulta) == "string") {
                $multa->idMulta = 0;
            }

            $data["intIdMulta_mta"] = $multa->idMulta;
            $data["intIdContrato_ctr"] = $idContrato;
            $data["intCodMulta_mta"] = $multa->codMulta;
            $data["dcmMonto_mta"] = $multa->monto;
            $data["strObservacioin_mta"] = $multa->observacion;
            $data["published"] = $multa->published;

            if ($this->bind($data)) {

                if ($this->save($data)) {
                    return true;
                }
            }
        } else {
            $this->delete($multa->idMulta);
        }
    }

}