<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class jTableDescripcionOrg extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__gen_institucion', 'intCodigo_ins', $db);
    }

    /**
     * Recupera el plan
     * @param type $idEntidad
     * @return type
     */
    public function getPLan($idEntidad) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(''
                    . 'pl.intId_pi,'
                    . 'pl.intId_tpoPlan,'
                    . 'pl.intCodigo_ins,'
                    . 'pl.intIdPadre_pi,'
                    . 'pl.intIdentidad_ent  AS idEntidad,'
                    . 'pl.strDescripcion_pi,'
                    . 'pl.dteFechainicio_pi,'
                    . 'pl.dteFechafin_pi,'
                    . 'pl.blnVigente_pi,'
                    . 'pl.strAlias_pi,'
                    . 'en.strUrlTableU_ent'
                    . '');

            $query->from('#__pln_plan_institucion as pl');

            $query->join(' inner', '#__gen_entidad AS en ON  pl.intIdentidad_ent = en.intIdentidad_ent');

            $query->where('pl.intIdentidad_ent= ' . $idEntidad);
            $query->where('pl.published = 1');

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
     * RECUPERA la INSTITUCION dado el identificador de la ENTIDAD
     * 
     * @param type $idEntidad
     */
    public function getInstitucion($idInstitucion) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(''
                    . 'ins.strNombre_ins    AS nombre,'
                    . 'ins.intCodigo_ins    AS idInstitucion,'
                    . 'ins.indIdentidad_ent AS idEntidad'
            );

            $query->from('#__gen_institucion AS ins');

            $query->where('ins.intCodigo_ins = ' . $idInstitucion);
            $query->where('ins.published = 1');

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
     * Recupera la unidades de gestion de una institucion
     * 
     * @param type $idInstitucion   Identificador de la institucion
     * @return type
     * 
     */
    public function getUnidadesGestion($idInstitucion) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(''
                    . 'ug.intCodigo_ug,'
                    . 'ug.intIdentidad_ent AS idEntidad,'
                    . 'ug.tb_intCodigo_ug,'
                    . 'ug.intCodigo_ins,'
                    . 'ug.strNombre_ug,'
                    . 'ug.strAlias_ug,'
                    . 'ug.intTpoUG_ug,'
                    . 'ug.published,'
                    . 'ug.checked_out,'
                    . 'ug.checked_out_time,'
                    . 'en.strUrlTableU_ent'
                    . '');

            $query->from('#__gen_unidad_gestion AS ug ');

            $query->join(' inner', '#__gen_entidad AS en ON  ug.intIdentidad_ent = en.intIdentidad_ent');

            $query->where(' ug.intCodigo_ins = ' . $idInstitucion);
            $query->where(' ug.published = 1');
            $query->where(' ug.intCodigo_ug <> 0');
            $query->where(' ug.tb_intCodigo_ug = 0');

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

    /**
     * 
     * Recupera el contrafo daod la entidad del contrato.
     * 
     * @param type $idEntidad   Identificador de la entidad del contrato.
     * @return type
     */
    public function getContratoByEntidad($idEntidad) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('*');

            $query->from('#__ctr_contrato AS ug ');

            $query->where(' ug.intIdentidad_ent = ' . $idEntidad);
            $query->where(' ug.published = 1');

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
