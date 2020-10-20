<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
//jimport( 'joomla.database.table' );
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class jTablePrograma extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__pfr_programa', 'intCodigo_prg', $db);
    }

    /**
     * 
     * @return type
     */
    public function getProgramas() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(''
                    . 'intCodigo_prg        AS  idPrograma,'
                    . 'intIdEntidad_ent     AS  idEntidad,'
                    . 'strNombre_prg        AS  strNombre,'
                    . 'strAlias_prg         AS  alias,'
                    . 'strDescripcion_prg   AS  descripcion,'
                    . 'idContent            AS  idContenido,'
                    . 'idMenu               AS  idMenu,'
                    . 'published            AS  published');
            $query->from('#__pfr_programa');
            $query->where('published = 1');
            $query->where('intCodigo_prg <> 0');
            $db->setQuery((string) $query);
            $db->query();

            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}
