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
class MantenimientoTableCargoUG extends JTable 
{
    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) 
    {
        parent::__construct('#__gen_ug_cargo', 'intId_ugc', $db);
    }

    /**
     *  Ejecuta la sentencia sql en la base para realizar un nuevo registro
     * @param type $data    data para el registro
     * @return type
     */
    public function guardarCargoUG($data) 
    {
        if (!$this->save($data)) {
            echo 'error al guardar cargo';
            exit;
        }
        return $this->intId_ugc;
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
    
    #########################   Gestion Cargo de Unidad de Gestion  ############################
    
    public function getCargoUG($id)
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select('   cf.inpCodigo_cargo,
                               cf.strNombre_cargo,
                               ugc.intId_ugc,
                               ugc.intCodigo_ug,
                               ugc.intIdGrupo_cargo,
                               ugc.strDescripcion_cargo,
                               ugc.published');
            $query->from('#__gen_cargo_funcionario as cf');
            $query->innerJoin('#__gen_ug_cargo as ugc on ugc.inpCodigo_cargo = cf.inpCodigo_cargo');
            $query->where('ugc.published = 1');
            $query->where("cf.published = 1");
            $query->where("ugc.intId_ugc = {$id}");
            $db->setQuery($query);
            $db->query();
            $result = ( $db->getAffectedRows() > 0 ) ? $db->loadObject() : false ;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargoug.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    public function eliminarCargoUG($id)
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update('#__gen_ug_cargo');
            $query->set('published = 0');
            $query->where("intId_ugc = {$id}");
            $db->setQuery($query);
            $db->query();
            $result = ( $db->getAffectedRows() > 0 ) ? true : false ;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargoug.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    public function updFuncionariosSC($id)
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update('#__gen_ug_funcionario');
            $query->set('intId_ugc = 0');
            $query->where("intId_ugc = {$id}");
            $query->where("published = 1");
            $db->setQuery($query);
            $db->query();
            $result = ( $db->getAffectedRows() > 0 ) ? true : false ;
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.cargoug.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}