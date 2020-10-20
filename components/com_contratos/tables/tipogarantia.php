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
class contratosTableTipoGarantia extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_tipo_garantia', 'intIdTpoGarantia_tg', $db);
    }

    public function deleteTipoGarantia($idAtributo) {
        try {
                $db = & JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->delete($db->nameQuote('#__ctr_tipo_garantia'));
                $query->where($db->nameQuote('intIdTpoGarantia_tg') . '=' . $db->quote($idAtributo));

                $db->setQuery($query);
                $db->query();
                return true;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.tipogarantia.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
}