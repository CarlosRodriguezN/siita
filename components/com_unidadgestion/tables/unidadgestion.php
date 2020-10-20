<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class UnidadGestionTableUnidadGestion extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db)
    {
        parent::__construct('#__gen_unidad_gestion', 'intCodigo_ug', $db);
    }

    public function registroUndGes( $unidadGestion )
    {
        if (!$this->save($unidadGestion)) {
            echo $this->getError();
            exit;
        }
        
        return $this->intCodigo_ug;
    }

    public function getLstUnidadesGestion($idInstitucion) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intCodigo_ug, 
                            intIdentidad_ent, 
                            tb_intCodigo_ug, 
                            intCodigo_ins, 
                            strNombre_ug, 
                            strAlias_ug, 
                            published");
            $query->from( "#__gen_unidad_gestion" );
            $query->where( "intCodigo_ins = " . $idInstitucion );
            $query->where( "published = 1" );
            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Inserta el registro SIN FUNCIONARIO de una unidad de gestion 
     * 
     * @param int       $idUndGes       Id de la unidad de gestion
     * @return type
     */
     public function resgistroSinFuncionarioUG( $idUndGes ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->insert( "#__gen_ug_funcionario" );
            $query->columns( "intCodigo_ug, intId_ugc, intCodigo_fnc, dteFechaInicio_ugf, dteFechaFin_ugf, dteFechaRegistro_ugf,  dteFechaModificacion_ugf,  published" );
            $query->values( $idUndGes . ", 0, 0, '0000-00-00', '0000-00-00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1" );
            $db->setQuery( $query );
            $db->query();
            $result = ($db->getAffectedRows() > 0) ? true : false;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * Recupera la unidad de gestion 
     * @param type $idUG
     * @return type
     */
    public function getUnidadGestion($idUG)
    {
       try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->select( 'intCodigo_ug       AS idUG,
                             intIdentidad_ent   AS idEntidadUG,
                             strNombre_ug       AS nombreUG,
                             strAlias_ug        AS aliasUG,
                             tb_intCodigo_ug    AS ugOwner,
                             intCodigo_ins      AS institucionOwner,
                             intTpoUG_ug        AS tipoUG'
                    );
            $query->from( '#__gen_unidad_gestion' );
            $query->where( "intCodigo_ug = {$idUG}" );
            $db->setQuery( (string) $query );
            $db->query();
            $idUndGestion = ($db->getAffectedRows() > 0) ? $db->loadObject() : array();
            return $idUndGestion;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_unidadgestion.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Eliminar logicamente un registro de unidad de gestion
     * @param type $idUG           ID de la Unidad de Gestion
     */ 
    public function delLogicaUG( $idUG )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->update( "#__gen_unidad_gestion" );
            $query->set( "published = 0" );
            $query->where( "intCodigo_ug = " . $idUG );
            $db->setQuery( $query );
            $result = ( $db->query() ) ? true : false;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Eliminar a nivel fisico un registro de unidad de gestion
     * @param type $idUG           ID de la Unidad de Gestion
     */ 
    public function eliminarUG( $idUG )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->delete( "#__gen_unidad_gestion" );
            $query->where( "intCodigo_ug = " . $idUG );
            $db->setQuery( $query );
            
            $result = ( $db->query() ) ? true : false;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna los planes relacionados a un funcionario
     * @param type $idEntidadUG        ID de entidad del funcionario
     * @return type
     */
    public function getPlnsUG( $idEntidadUG )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select( 't1.intId_pi AS Plan' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->where( 't1.intIdentidad_ent = '. $idEntidadUG );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $dtaFnc = ( $db->getAffectedRows() > 0  ) ? $db->loadObjectList() : array();
            return $dtaFnc;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna los Programas relacionados a una unidad de gestion
     * @param type $idUG        ID de la Unidad de Gestion
     * @return type
     */
    public function getPrgsUG( $idUG )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select( 't1.intCodigo_prg AS idPrograma' );
            $query->from( '#__prg_ug_responsable t1' );
            $query->where( 't1.intCodigo_ug = '. $idUG );
            $db->setQuery( (string)$query );
            $db->query();
            $dtaFnc = ( $db->getAffectedRows() > 0  ) ? $db->loadObjectList() : array();
            return $dtaFnc;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna los Proyectos relacionados a una unidad de gestion
     * @param type $idUG        ID de la Unidad de Gestion
     * @return type
     */
    public function getPrysUG( $idUG )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $$query->select( 't1.intCodigo_pry AS idProyecto' );
            $query->from( '#__pry_ug_responsable t1' );
            $query->where( 't1.intCodigo_ug = '. $idUG );
            $db->setQuery( (string)$query );
            $db->query();
            $dtaFnc = ( $db->getAffectedRows() > 0  ) ? $db->loadObjectList() : array();
            return $dtaFnc;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna los Contratos relacionados a una unidad de gestion
     *  Retorna los Convenios relacionados a una unidad de gestion
     * @param type $idUG        ID de la Unidad de Gestion
     * @return type
     */
    public function getCtrsUG( $idUG )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select( 't1.intIdContrato_ctr AS idContrato' );
            $query->from( '#__ctr_ug_responsable t1' );
            $query->where( 't1.intCodigo_ug = '. $idUG );
            $db->setQuery( (string)$query );
            $db->query();
            $dtaFnc = ( $db->getAffectedRows() > 0  ) ? $db->loadObjectList() : array();
            return $dtaFnc;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Actualiza las opciones adicionales de una determinada unidad de gestion
     * @param type $idUG
     * @param type $data
     * @return type
     */
    public function updateOpAdd( $idUG, $data )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->update( '#__gen_unidad_gestion' );
            $query->set( "strOpAdd_ug = '{$data}'" );
            $query->where( "intCodigo_ug = {$idUG}" );
            $db->setQuery( (string)$query );
            $db->query();
            $result = ( $db->getAffectedRows() > 0  ) ? true : false;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna las opciones adicionales de una unidad de gestion
     * @param type $idUG
     * @return type
     */
    public function getLstOpcioneaAdd( $idUG )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select( 'strOpAdd_ug' );
            $query->from( '#__gen_unidad_gestion' );
            $query->where( 'intCodigo_ug = '. $idUG );
            $db->setQuery( (string)$query );
            $db->query();
            $dtaFnc = ( $db->getAffectedRows() > 0  ) ? json_decode( $db->loadResult() ) : array();
            return $dtaFnc;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna el id del grupo del sistema relacionado
     * @param type $idUG
     * @return type
     */
    public function getIdGrupo($idUG)
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select( 'intIdGrupo_ug' );
            $query->from( '#__gen_unidad_gestion' );
            $query->where( 'intCodigo_ug = '. $idUG );
            $db->setQuery( (string)$query );
            $db->query();
            $dtaFnc = ( $db->getAffectedRows() > 0  ) ? $db->loadResult() : false;
            return $dtaFnc;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}