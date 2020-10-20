<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Cargo ( #__gen_cargo_funcionario )
 * 
 */
class MantenimientoTableCargoFnc extends JTable 
{
    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) 
    {
        parent::__construct('#__gen_cargo_funcionario', 'inpCodigo_cargo', $db);
    }

    /**
     *  Ejecuta la sentencia sql en la base para realizar un nuevo registro
     * @param type $data    data para el registro
     * @return type
     */
    public function registrarCargo($data) 
    {
        if (!$this->save($data)) {
            echo 'error al guardar cargo';
            exit;
        }
        return $this->inpCodigo_cargo;
    }

    /**
     *  Retorna True si esxiste alguna ralacion valida de funcionarios con el cargo
     * caso contrario False
     * @param type $idCargo
     * @return type
     */
    function getFncByCargo( $idCargo )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intId_ugf");
            $query->from("#__gen_ug_funcionario");
            $query->where("inpCodigo_cargo = {$idCargo}");
            $query->where("published = 1");
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? true : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargofnc.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna TRUE en el caso de que se pueda eliminar caso contrario FALSE
     * @param type $idCargo
     * @return type
     */
    function validateDelete( $idCargo )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intId_ugc");
            $query->from("#__gen_ug_cargo");
            $query->where("inpCodigo_cargo = {$idCargo}");
            $query->where("published = 1");
            $db->setQuery($query);
            $db->query();
            $result = ( $db->getAffectedRows() > 0 ) ? false : true ;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargofnc.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Realiza la elimanacion logica de un registro
     * @param type $idCargo
     * @return type
     */
    function eliminacionLogica( $idCargo )
    {
       try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update("#__gen_cargo_funcionario");
            $query->set("published = 0");
            $query->where("inpCodigo_cargo = {$idCargo}");
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? true : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargofnc.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna el ID del grupo creado para el cargo SIN CARGO
     * @return type
     */
    function getSinGrupo()
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intIdGrupo_cargo AS id");
            $query->from("#__gen_cargo_funcionario");
            $query->where("inpCodigo_cargo = 0");
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? $db->loadResult() : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargofnc.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Ejecuta una eliminacion fisica de un registro en la tabla de Cargo
     * @param type $idCargo
     * @return type
     */
    function eliminarCargo( $idCargo )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->delete("#__gen_cargo_funcionario");
            $query->where("inpCodigo_cargo = {$idCargo}");
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? true : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargofnc.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Realiza el cambio de grupos a usuarios perteneientea a un determinado grupo
     * @param type $idGrupo             ID del grupo actual
     * @param type $idGrpSinCrg         ID del nuevo grupo al que pertenecen
     * @return type
     */
    function changeGrupoFuncionarios($idGrupo, $idGrpSinCrg)
    {
       try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update("#__user_usergroup_map");
            $query->set("group_id = {$idGrpSinCrg}");
            $query->where("group_id = {$idGrupo}");
            $db->setQuery($query);
            
            $result = ( $db->query() ) ? true : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargofnc.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Realiza el cambio de cargos a funcionarios perteneientes a un determinado Cargo
     * @param type $idCargoOld          ID del cargo actual
     * @param type $idCargoNew          ID del nuevo cargo al que pertenecen
     * @return type
     */
    function changeCargoFuncionarios( $idCargoOld, $idCargoNew )
    {
       try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update("#__gen_ug_funcionario");
            $query->set("inpCodigo_cargo = {$idCargoNew}");
            $query->where("inpCodigo_cargo = {$idCargoOld}");
            $query->where("published = 1");
            $db->setQuery($query);
            
            $result = ( $db->query() ) ? true : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargofnc.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Elimia fisicamente al a un grupo espesifico
     * @param type $idGrupo
     * @return type
     */
    function eliminarGroup($idGrupo)
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->delete("#__usergroups");
            $query->where("id = {$idGrupo}");
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? true : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargofnc.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    public function getGrupoPadre( $idGroCargo )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
             $query->select("parent_id");
            $query->from("#__usergroups");
            $query->where("id = {$idGroCargo}");
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 ) ? $db->loadResult() : false ;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargofnc.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna la lista de cargos del sistema
     * @return type
     */
    public function getCargosFnc()
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
             $query->select("inpCodigo_cargo    AS idCargo,"
                     . "strNombre_cargo     AS nombreCargo,"
                     . "published");
            $query->from("#__gen_cargo_funcionario");
            $query->where("published = 1");
            $db->setQuery($query);
            $db->query();
            $result = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array() ;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargofnc.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}