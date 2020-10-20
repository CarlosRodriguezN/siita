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
class contratosTableCargo extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_cargo', 'intIdCargo_cgo', $db);
    }

    public function deleteCargo($idCargo) {
        try {
            if (!$this->relacionadaTo($idCargo)) {
                $db = & JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->delete($db->nameQuote('#__ctr_cargo'));
                $query->where($db->nameQuote('intIdCargo_cgo') . '=' . $db->quote($idCargo));

                $db->setQuery($query);
                $db->query();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.tiposcontratista.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * verifica si tiene relacion con otras tablas
     */
    public function relacionadaTo($idCargo) {
        if ($this->relacionada($idCargo, 'ctr_contacto', 'intIdCargo_cgo')) {
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
            $log = &JLog::getInstance('com_contratos.table.cargo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}