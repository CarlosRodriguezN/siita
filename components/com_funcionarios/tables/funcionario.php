<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

/**
 * 
 * Clase que gestiona informacion de la tabla categoria ( #__categoria )
 * 
 */
class FuncionariosTableFuncionario extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gen_funcionario', 'intCodigo_fnc', $db );
    }
    
    /**
     *  Guarda un registro con la data general de un funcionario
     * @param type $dataFnc             Data funcionario
     * @return type
     */
    public function guardarFuncionario( $dataFnc ) 
    {
        if (!$this->save($dataFnc)) {
            echo $this->getError();
            exit;
        }

        return $this->intCodigo_fnc;
    }

    /**
     *  Eliminar logicamente un registro de funcionario
     * @param type $idFnc           Id del duncionario
     */ 
    public function delLogicalFuncionario( $idFnc )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->update( "#__gen_funcionario" );
            $query->set( "published = 0" );
            $query->where( "intCodigo_fnc = " . $idFnc );
            $db->setQuery( $query );
            
            $result = ( $db->query() ) ? true : false;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_funcionarios.tables.ugfuncionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Eliminar a nivel fisico un registro de funcionario
     * @param type $idFnc           Id del duncionario
     */ 
    public function eliminarFuncionario( $idFnc )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->delete( "#__gen_funcionario" );
            $query->where( "intCodigo_fnc = " . $idFnc );
            $db->setQuery( $query );
            
            $result = ( $db->query() ) ? true : false;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_funcionarios.tables.ugfuncionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna el objeto funcionario
     * @param type $id
     * @return type
     */
    public function getFuncionario( $id )
    {
         try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select( '   t1.intCodigo_fnc    AS idFuncionario,
                                t1.intIdentidad_ent AS idEntidadFun,
                                t1.intIdUser_fnc    AS idUsuarioFnc,
                                t1.strApellido_fnc  AS apellidoFnc,
                                t1.strNombre_fnc    AS nombreFun,
                                t1.strCI_fnc        AS ciFun' );
            $query->from( '#__gen_funcionario t1' );
            $query->where( 't1.intCodigo_fnc = '. $id );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $dtaFnc = ( $db->getAffectedRows() > 0  )? $db->loadObject() : array();
            return $dtaFnc;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.funcionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna los planes relacionados a un funcionario
     * @param type $idEntidadFnc        ID de entidad del funcionario
     * @return type
     */
    public function getPlnsFnc( $idEntidadFnc )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select( 't1.intId_pi AS Plan' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->where( 't1.intIdentidad_ent = '. $idEntidadFnc );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $dtaFnc = ( $db->getAffectedRows() > 0  ) ? $db->loadObjectList() : array();
            return $dtaFnc;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.funcionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna los Programas relacionados a un funcionario
     * @param type $idFnc        ID del funcionario
     * @return type
     */
    public function getPrgsFnc( $idFnc )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select( 't1.intCodigo_prg AS idPrograma' );
            $query->from( '#__prg_funcionario_responsable t1' );
            $query->innerJoin( '#__gen_ug_funcionario t2 ON t1.intId_ugf = t2.intId_ugf');
            $query->where( 't2.intCodigo_fnc = '. $idFnc );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $dtaFnc = ( $db->getAffectedRows() > 0  ) ? $db->loadObjectList() : array();
            return $dtaFnc;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.funcionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna los Proyectos relacionados a un funcionario
     * @param type $idFnc        ID del funcionario
     * @return type
     */
    public function getPrysFnc( $idFnc )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $$query->select( 't1.intCodigo_pry AS idProyecto' );
            $query->from( '#__pry_funcionario_responsable t1' );
            $query->innerJoin( '#__gen_ug_funcionario t2 ON t1.intId_ugf = t2.intId_ugf');
            $query->where( 't2.intCodigo_fnc = '. $idFnc );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $dtaFnc = ( $db->getAffectedRows() > 0  ) ? $db->loadObjectList() : array();
            return $dtaFnc;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.funcionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna los Contratos relacionados a un funcionario
     *  Retorna los Convenios relacionados a un funcionario
     * @param type $idFnc        ID del funcionario
     * @return type
     */
    public function getCtrsFnc( $idFnc )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select( 't1.intIdContrato_ctr AS idContrato' );
            $query->from( '#__ctr_funcionario_responsable t1' );
            $query->innerJoin( '#__gen_ug_funcionario t2 ON t1.intId_ugf = t2.intId_ugf');
            $query->where( 't2.intCodigo_fnc = '. $idFnc );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $dtaFnc = ( $db->getAffectedRows() > 0  ) ? $db->loadObjectList() : array();
            return $dtaFnc;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.funcionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}