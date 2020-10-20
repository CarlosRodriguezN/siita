<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla CONTRATOS ( #__ctr_contrato )
 * 
 */
class contratosTableTipocontratista extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_tipo_contratista', 'intIdTipoContratista_tpocta', $db);
    }

    /**
     * Elmina el tipo de contratista, verifica si esta o no asociada a un contratista.
     * @param int $idTipo
     * @return boolean
     */
    function deleteTipo($idTipo) {
        if (!$this->relacionada($idTipo, 'ctr_contratista', 'intIdTipoContratista_tpocta')) {
            try {
                $db = & JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->delete($db->nameQuote('#__ctr_tipo_contratista'));
                $query->where($db->nameQuote('intIdTipoContratista_tpocta') . '=' . $db->quote($idTipo));

                $db->setQuery($query);
                $db->query();
                return true;
            } catch (Exception $e) {
                jimport('joomla.error.log');
                $log = &JLog::getInstance('com_contratos.table.tiposcontratista.log.php');
                $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
            }
        }
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
            $log = &JLog::getInstance('com_contratos.table.tiposcontratista.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}