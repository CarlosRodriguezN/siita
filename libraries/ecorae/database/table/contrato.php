<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport('joomla.database.tablenested');

/**
 * 
 *  Clase que gestiona informacion de la tabla de Contratos ( #_ctr_contrato )
 * 
 */
class JTableContrato extends JTable {

    /**
     * Constructor
     * @param   JDatabase  &$db  A database connector object
     * @since   11.1
     */
    public function __construct(&$db) {
        parent::__construct('#__ctr_contrato', 'intIdContrato_ctr', $db);

    }
    
     /**
     *  Retorna la lista de Contratos de una determinada unidad de gestión responsable
     * 
     * @param int      $idEntidadUG        Id de entidad de unidad de gestión
     * @return type
     */
    function getLstContratosUG( $idEntidadUG )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select("ctr.intIdContrato_ctr AS idContrato,
                            ctr.intIdentidad_ent AS idEntidadCtr,
                            ctr.strDescripcion_ctr AS nombreCtr, 
                            ctr.published");
            $query->from('#__ctr_contrato AS ctr');
            $query->innerJoin( "#__ctr_ug_responsable cug ON cug.intIdContrato_ctr = ctr.intIdContrato_ctr");
            $query->innerJoin( "#__gen_unidad_gestion ug ON ug.intCodigo_ug = cug.intCodigo_ug");
            $query->where( "ug.intIdentidad_ent = {$idEntidadUG}");
            $query->where( "cug.intVigencia_ctrUGR = 1");
            $query->where("ctr.intIdTipoContrato_tc = 1");
            $query->order("ctr.intIdContrato_ctr DESC");
            
            $db->setQuery( (string) $query );
            $db->query();
            
            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList(): array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.contratos.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
     /**
     *  Retorna la lista de Contratos de una determinada unidad de gestión responsable
     * 
     * @param int      $idFnc        Id de entidad de unidad de gestión
     * @return type
     */
    function getLstContratosFnc( $idFnc )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select("ctr.intIdContrato_ctr AS idContrato,
                            ctr.intIdentidad_ent AS idEntidadCtr,
                            ctr.strDescripcion_ctr AS nombreCtr, 
                            ctr.published");
            $query->from('#__ctr_contrato AS ctr');
            $query->innerJoin( "#__ctr_funcionario_responsable cfr ON cfr.intIdContrato_ctr = ctr.intIdContrato_ctr");
            $query->innerJoin( "#__gen_ug_funcionario ugf ON ugf.intId_ugf = cfr.intId_ugf");
            $query->where("ctr.intIdTipoContrato_tc = 1");
            $query->where( "ugf.intCodigo_fnc = {$idFnc}");
            $query->where( "cfr.intVigencia_ctrFR = 1");
            $query->order("ctr.intCodigo_pry DESC");

            $db->setQuery( (string) $query );
            $db->query();
            
            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList(): array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.contratos.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * 
     * Listo todos los contratos asociados a un determinado programa
     * 
     * @param type $idPrograma      Identificado de programa
     * @return type
     * 
     */
    public function getLstContratosPrg( $idPrograma )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select( '   t1.intIdContrato_ctr    AS idContrato, 
                                t1.intIdentidad_ent     AS idEntidadCtr, 
                                t1.strDescripcion_ctr   AS nombreCtr' );
            $query->from( '#__ctr_contrato t1' );
            $query->where( 't1.intIdTipoContrato_tc = 1' );
            $query->where( 't1.intCodigo_pry IN (   SELECT t2.intCodigo_pry
                                                    FROM tb_pfr_proyecto_frm t2
                                                    WHERE t2.intCodigo_prg = '. $idPrograma .' )' );
            $query->order( 't1.strDescripcion_ctr asc' );

            $db->setQuery( (string) $query );
            $db->query();
            
            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList()
                                                : array();
            
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.contratos.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    /**
     * 
     * Listo todos los contratos asociados a un determinado programa
     * 
     * @param type $idPrograma      Identificado de programa
     * @return type
     * 
     */
    public function getLstConveniosPrg( $idPrograma )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select( '   t1.intIdContrato_ctr    AS idContrato, 
                                t1.intIdentidad_ent     AS idEntidadCtr, 
                                t1.strDescripcion_ctr   AS nombreCtr' );
            $query->from( '#__ctr_contrato t1' );
            $query->where( 't1.intIdTipoContrato_tc = 2' );
            $query->where( 't1.intCodigo_pry IN (   SELECT t2.intCodigo_pry
                                                    FROM tb_pfr_proyecto_frm t2
                                                    WHERE t2.intCodigo_prg = '. $idPrograma .' )' );
            $query->order( 't1.strDescripcion_ctr asc' );

            $db->setQuery( (string) $query );
            $db->query();
            
            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList()
                                                : array();
            
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.contratos.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    
    
    /**
     * 
     * Listo todos los contratos asociados a un determinado proyecto
     * 
     * @param type $idProyecto      Identificado de proyecto
     * @return type
     * 
     */
    public function getLstContratosPry( $idProyecto )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select( '   t1.intIdContrato_ctr    AS idContrato, 
                                t1.intIdentidad_ent     AS idEntidadCtr, 
                                t1.strDescripcion_ctr   AS nombreCtr' );
            $query->from( '#__ctr_contrato t1' );
            $query->where( 't1.intIdTipoContrato_tc = 1' );
            $query->where( 't1.intCodigo_pry IN (   SELECT t2.intCodigo_pry
                                                    FROM tb_pfr_proyecto_frm t2
                                                    WHERE t2.intCodigo_pry = '. $idProyecto .' )' );
            $query->order( 't1.strDescripcion_ctr asc' );

            $db->setQuery( (string) $query );
            $db->query();
            
            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList()
                                                : array();
            
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.contratos.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    
    /**
     * 
     * Listo todos los Convenios asociados a un determinado programa
     * 
     * @param type $idProyecto      Identificado de programa
     * @return type
     * 
     */
    public function getLstConveniosPry( $idProyecto )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select( '   t1.intIdContrato_ctr    AS idContrato, 
                                t1.intIdentidad_ent     AS idEntidadCtr, 
                                t1.strDescripcion_ctr   AS nombreCtr' );
            $query->from( '#__ctr_contrato t1' );
            $query->where( 't1.intIdTipoContrato_tc = 2' );
            $query->where( 't1.intCodigo_pry IN (   SELECT t2.intCodigo_pry
                                                    FROM tb_pfr_proyecto_frm t2
                                                    WHERE t2.intCodigo_prg = '. $idProyecto .' )' );
            $query->order( 't1.strDescripcion_ctr asc' );

            $db->setQuery( (string) $query );
            $db->query();
            
            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList()
                                                : array();
            
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.contratos.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}

