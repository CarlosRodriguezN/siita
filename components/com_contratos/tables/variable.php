<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class contratosTableVariable extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__gen_variables', 'intCodigo_var', $db);
    }

    /**
     * 
     * Retorno las variables pertenecientes a un determinado indicador
     * 
     * @param type $idIndicador
     * @return type
     * 
     */
    public function getLstVariablesIndicador($idIndicador) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('DISTINCT t1.intCodigo_var        AS idVariable, 
                                     t2.strDescripcion_var   AS nombre');
            $query->from('#__ind_planificacion t1');
            $query->join('INNER', '#__gen_variables t2 ON t1.intcodigo_var = t2.intcodigo_var');
            $query->join('INNER', '#__ind_indicador t3 ON t1.intCodigo_ind = t3.intCodigo_ind 
                                                      AND t3.intCodigo_ind = ' . $idIndicador);
            $query->order('t2.strdescripcion_var');
            $db->setQuery((string) $query);
            $db->query();

            $rst = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : FALSE;

            return $rst;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Retorna una lista de variables 
     */
    public function getLstVariables() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('   t1.intcodigo_var, 
                                t2.strDescripcion_tipovar,
                                t1.strdescripcion_var');

            $query->from('#__gen_variables t1');
            $query->join('INNER', '#__gen_tipo_variable t2 ON t1.inpcodigo_tipovar = t2.inpCodigo_tipovar');
            $query->order('t1.strDescripcion_tipovar');

            $db->setQuery((string) $query);
            $db->query();

            $rst = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : FALSE;

            return $rst;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
}