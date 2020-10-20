<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport('joomla.database.tablenested');

/**
 * 
 *  Clase que gestiona informacion de la tabla de Convenio ( #_ctr_contrato )
 * 
 */
class JTableConvenio extends JTable {

    /**
     * Constructor
     * @param   JDatabase  &$db  A database connector object
     * @since   11.1
     */
    public function __construct(&$db) {
        parent::__construct('#__ctr_contrato', 'intIdContrato_ctr', $db);

    }
    
     /**
     *  Retorna la lista de Convenios de una determinada unidad de gestión responsable
     * 
     * @param int      $idEntidadUG        Id de entidad de unidad de gestión
     * @return type
     */
    function getLstConveniosUG( $idEntidadUG )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select("ctr.intIdContrato_ctr AS idConvenio,
                            ctr.intIdentidad_ent AS idEntidadCnv,
                            ctr.strDescripcion_ctr AS nombreCnv,
                            ctr.published");
            $query->from('#__ctr_contrato AS ctr');
            $query->innerJoin( "#__ctr_ug_responsable cug ON cug.intIdContrato_ctr = ctr.intIdContrato_ctr");
            $query->innerJoin( "#__gen_unidad_gestion ug ON ug.intCodigo_ug = cug.intCodigo_ug");
            $query->where( "ug.intIdentidad_ent = {$idEntidadUG}");
            $query->where( "cug.intVigencia_ctrUGR = 1");
            $query->where("ctr.intIdTipoContrato_tc = 2");
            $query->order("ctr.intIdContrato_ctr DESC");

            $db->setQuery( (string) $query );
            $db->query();
            
            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList(): array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.convenios.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
     /**
     *  Retorna la lista de Convenios de un determinado funcionario responsable
     * 
     * @param int      $idFnc        Id del funcionario
     * @return type
     */
    function getLstConveniosFnc( $idFnc )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select("ctr.intIdContrato_ctr AS idConvenio,
                            ctr.intIdentidad_ent AS idEntidadCnv,
                            ctr.strDescripcion_ctr AS nombreCnv,
                            ctr.published");
            $query->from('#__ctr_contrato AS ctr');
            $query->innerJoin( "#__ctr_funcionario_responsable cfr ON cfr.intIdContrato_ctr = ctr.intIdContrato_ctr");
            $query->innerJoin( "#__gen_ug_funcionario ugf ON ugf.intId_ugf = cfr.intId_ugf");
            $query->where("ctr.intIdTipoContrato_tc = 2");
            $query->where( "ugf.intCodigo_fnc = {$idFnc}");
            $query->where( "cfr.intVigencia_ctrFR = 1");
            $query->order("ctr.intCodigo_pry DESC");

            $db->setQuery( (string) $query );
            $db->query();
            
            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList(): array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.convenios.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}

