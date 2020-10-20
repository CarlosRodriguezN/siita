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
class ProgramaTableVariableIndicador extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ind_variables_indicador', 'intIdVariableIndicador_var', $db);
    }

    /**
     * 
     * Retorno las variables pertenecientes a un determinado indicador
     * 
     * @param type $idIndicadorEntidad
     * @return type
     * 
     */
    public function getSeguimientoVariableIndicador($idIndicadorEntidad) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('seg.intId_seg                   AS idSeguimiento, 
                            vni.intIdVariableIndicador_var  AS idIndicadorVariable,
                            seg.dteFecha_seg                AS fchSeguimiento,
                            seg.dcmValor_seg                AS valor');
            $query->from('#__ind_variables_indicador AS vni');
            $query->join('inner', '#__ind_seguimiento AS seg ON seg.intIdVariableIndicador_var=vni.intIdVariableIndicador_var');
            $query->where('vni.intIdVariableIndicador_var=' . $idIndicadorEntidad);
            $db->setQuery((string) $query);
            $db->query();

            $rst = ( $db->getNumRows() > 0 ) ? $db->loadObject() : FALSE;

            return $rst;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function getLstVariablesIndicador($indicadorEntidad) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('DISTINCT vni.intIdVariableIndicador_var     AS idVariableIndicador, 
                                     vni.intIdIndEntidad                AS idIndicadorEntidad,
                                     vni.intIdVariable_var              AS idVariable,
                                     vrl.strDescripcion_var             AS descripcionVariable,
                                     vrl.dteFechaRegistro_var           AS fchRegVariable,
                                     vrl.dteFechaModificacion_var       AS fchModVariable');
            $query->from('#__ind_variables_indicador AS vni');
            $query->join('inner', '#__gen_variables      AS vrl ON vrl.intIdVariable_var=vni.intIdVariable_var');
            $query->where('vni.intIdIndEntidad=' . $indicadorEntidad);
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