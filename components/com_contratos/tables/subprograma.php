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
class contratosTableSubPrograma extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__pfr_sub_programa', 'intId_SubPrograma', $db);
    }

    function getSubProgramas($idPrograma) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('t1.intId_SubPrograma        AS idSubPrograma,
                            t1.strDescripcion_sprg      AS descripcion
                                    ');
            $query->from('#__pfr_sub_programa AS t1');
            $query->where('intCodigo_prg ='.$idPrograma);
            $db->setQuery($query);
            $db->query();
            return $db->loadObjectList();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.tiposcontratista.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}