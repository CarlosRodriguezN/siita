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
class jTableDescripcion extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__gen_alineacion_operativa', 'intIdAlineacion', $db);
    }

    /**
     * 
     * @param type $idObjetivo  Identificado del objetivo entidad "tb_gen_objetivo_entidad"
     * @param type $tpoEntidad  Tipo de la entidad a la que pertenece el objetivo
     * @return type
     */
    public function getDescPlan($idObjetivo, $tpoEntidad) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(''
                    . 'pi.strDescripcion_pi AS grDescripcion,'
                    . 'oi.strDescripcion_ob AS descripcion'
            );

            $query->from('#__gen_objetivo_entidad AS oe');

            $query->join(' inner', '#__pln_objetivo_institucion  AS oi '
                    . ' ON oi.intId_ob = oe.intId_objetivo');

            $query->join(' inner', '#__pln_plan_objetivo AS po '
                    . ' ON oi.intId_ob = po.intId_ob');

            $query->join(' inner', '#__pln_plan_institucion AS pi'
                    . ' ON pi.intId_pi = po.intId_pi');

            $query->where('oe.intId_obj_ent = ' . $idObjetivo);
            $query->where('pi.intId_tpoPlan = 1');

            $db->setQuery((string) $query);
            $db->query();
            $result = ( $db->loadObject()) ? $db->loadObject() : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Descripcion de la Unidad de Gestion
     * @param type $idObjetivo
     * @param type $tpoEntidObjetivo    Al que pertenece
     * @return type
     */
    public function getDescripUnidGestion($idObjetivo, $tpoEntidad) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(''
                    . 'oi.strDescripcion_ob AS descripcion,'
                    . 'ug.strAlias_ug AS grDescripcion'
            );

            $query->from('#__gen_objetivo_entidad AS oe');

            $query->join(' inner', '#__pln_objetivo_institucion  AS oi '
                    . ' ON oi.intId_ob = oe.intId_objetivo');

            $query->join(' inner', '#__pln_plan_objetivo AS po '
                    . ' ON oi.intId_ob = po.intId_ob');

            $query->join(' inner', '#__pln_plan_institucion AS pi'
                    . ' ON pi.intId_pi = po.intId_pi');

            $query->join('inner', '#__gen_unidad_gestion AS ug '
                    . ' ON pi.intIdentidad_ent = ug.intIdentidad_ent');

            $query->where(' oe.intId_obj_ent = ' . $idObjetivo);
            $query->where(' oe.intId_tpoEntidad =' . $tpoEntidad);
            $query->where(' pi.intId_tpoPlan = 2'); // 2: identificado del TIPO de PLAN "POA"

            
            
            $db->setQuery((string) $query);
            $db->query();
            $result = ( $db->loadObject()) ? $db->loadObject() : false;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function getDescripCbtCnv($idObjetivo) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(''
                    . ' ct.strDescripcion_ctr   AS grDescripcion,'
                    . ' oo.strDescripcion_ObjOp AS descripcion'
            );

            $query->from('#__gen_objetivo_entidad AS oe');

            $query->join(' inner', '#__gen_objetivos_operativos  AS oo '
                    . ' ON oo.intIdObjetivo_operativo = oe.intId_objetivo');

            $query->join(' inner', '#__ctr_contrato  AS ct '
                    . ' ON oo.intIdEntidad_owner = ct.intIdentidad_ent');


            $query->where('intId_obj_ent = ' . $idObjetivo);
            $query->where('intId_tpoEntidad = 3');
            $query->where('intIdTipoContrato_tc	 = 2');

            $db->setQuery((string) $query);
            $db->query();
            $result = ( $db->loadObject()) ? $db->loadObject() : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * @param type $idObjetivo
     * @return type
     */
    public function getDescripPrograma($idObjetivo) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(''
                    . ' pr.strDescripcion_prg   AS grDescripcion,'
                    . ' oo.strDescripcion_ObjOp AS descripcion'
            );

            $query->from('#__gen_objetivo_entidad AS oe');

            $query->join(' inner', '#__gen_objetivos_operativos  AS oo '
                    . ' ON oo.intIdObjetivo_operativo = oe.intId_objetivo');

            $query->join(' inner', '#__pfr_programa  AS pr '
                    . ' ON oo.intIdEntidad_owner = pr.intIdEntidad_ent');


            $query->where('intId_obj_ent = ' . $idObjetivo);
            $query->where('intId_tpoEntidad = 1');

            $db->setQuery((string) $query);
            $db->query();
            $result = ( $db->loadObject()) ? $db->loadObject() : false;
            
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    /**
     * 
     * @param type $idObjetivo
     * @return type
     */
    public function getDescripProyecto($idObjetivo) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(''
                    . ' pr.strNombre_pry        AS grDescripcion,'
                    . ' oo.strDescripcion_ObjOp AS descripcion'
            );

            $query->from('#__gen_objetivo_entidad AS oe');

            $query->join(' inner', '#__gen_objetivos_operativos  AS oo '
                    . ' ON oo.intIdObjetivo_operativo = oe.intId_objetivo');

            $query->join(' inner', '#__pfr_proyecto_frm AS pr '
                    . ' ON oo.intIdEntidad_owner = pr.intIdEntidad_ent');


            $query->where('intId_obj_ent = ' . $idObjetivo);
            $query->where('intId_tpoEntidad = 2');
            
            $db->setQuery((string) $query);
            $db->query();
            $result = ( $db->loadObject()) ? $db->loadObject() : false;
            
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}
