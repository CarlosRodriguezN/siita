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
class contratosTableGarantia extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_garantia', 'intIdGarantia_gta', $db);
    }

    public function deleteGarantia($idGarantia) {
        try {
            if (!$this->relacionadaTo($idGarantia)) {
                $db = & JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->delete($db->nameQuote('#__ctr_garantia'));
                $query->where($db->nameQuote('intIdGarantia_gta') . '=' . $db->quote($idGarantia));

                $db->setQuery($query);
                $db->query();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.atributo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * verifica si tiene relacion con otras tablas
     */
    public function relacionadaTo($idCargo) {
        if ($this->relacionada($idCargo, 'ctr_contrato', 'intIdGarantia_gta')) {
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
     * @param type $idContrato
     * @param type $garantia
     */
    public function saveGarantias($idContrato, $garantia) {
        if ($garantia->published != 0) {
            if (gettype($garantia->idGarantia) == "string") {
                $garantia->idGarantia = 0;
            }

            $data["intIdGarantia_gta"] = $garantia->idGarantia;
            $data["intIdTpoGarantia_tg"] = $garantia->idTipoGarantia;
            $data["intIdFrmGarantia_fg"] = $garantia->idFormaGarantia;
            $data["intIdContrato_ctr"] = $idContrato;
            $data["intCodGarantia_gta"] = $garantia->codGarantia;
            $data["dcmMonto_gta"] = $garantia->monto;
            $data["dteFechaDesde_gta"] = $garantia->fchDesde;
            $data["dteFechaHasta_gta"] = $garantia->fchHasta;
            $data["published"] = $garantia->published;


            if ($this->bind($data)) {

                if ($this->save($data)) {
                    return $this->intIdGarantia_gta;
                }
            }
        } else {
            $this->delete($garantia->idGarantia);
        }
    }

    /**
     * borra cuando es eliminado desde el formunlari
     * @param type $idGarantia
     * @return boolean
     */
    public function delete($idGarantia) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->delete($db->nameQuote('#__ctr_garantia'));
            $query->where($db->nameQuote('intIdGarantia_gta') . '=' . $db->quote($idGarantia));

            $db->setQuery($query);
            $db->query();
            return true;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.atributo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}
