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
class UnidadGestionTableFuncionario extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db)
    {
        parent::__construct('#__gen_ug_funcionario', 'intId_ugf', $db);
    }
    
    /**
     *  Registra un funcionario para una determinada unidad de gestion
     * 
     * @param type      $data       Data general del funcionario y la unidad de gestion
     * @return type
     */
    function saveFuncionarioUG( $data ) 
    {
        if (!$this->save($data)) {
            echo 'error al guardar funcionario de la unidad de gestion';
            exit;
        }
        return $this->intId_ugf;
    }
    
    
    /**
     *  Lista los funcionarios relacionados con una unidad de gestión. 
     *  
     * @param int       $idUG       Id de unidad de gestión
     * @return type
     */
    public function getLstFuncionarios($idUG) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("ugf.intId_ugf                   AS idUgFnci, 
                            ugf.intCodigo_ug                AS idUg, 
                            ug.strNombre_ug                 AS nombreUg, 
                            ugf.intId_ugc                   AS idCargoFnci, 
                            cf.strDescripcion_cargo         AS descCargoFnci, 
                            ugf.intCodigo_fnc               AS idFuncionario, 
                            CONCAT(fun.strNombre_fnc, ' ', fun.strApellido_fnc ) AS nombreFnci, 
                            ugf.dteFechaInicio_ugf          AS fechaInicio, 
                            ugf.dteFechaFin_ugf             AS fechaFin,
                            ugf.dteFechaModificacion_ugf    AS fechaUpd,
                            ugf.published                   AS published");
            $query->from("#__gen_ug_funcionario ugf");
            $query->innerJoin("#__gen_unidad_gestion ug ON ug.intCodigo_ug = ugf.intCodigo_ug");
            $query->innerJoin("#__gen_funcionario fun ON fun.intCodigo_fnc = ugf.intCodigo_fnc");
            $query->innerJoin("#__gen_ug_cargo cf ON cf.intId_ugc = ugf.intId_ugc");
            $query->where("ugf.intCodigo_ug = " . $idUG);
            $query->where("ugf.published = 1");
            $query->where("ugf.intCodigo_fnc <> 0");
            $db->setQuery($query);
            $db->query();
            $result = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : array();
            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function getAccsFnciResp( $idUGFuncionario )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("afr.intId_plnAccion             AS idAccion, 
                            afr.intId_plnFR                 AS idAccFR");
            $query->from("#__pln_funcionario_responsable afr");
            $query->where("afr.intId_ugf = " . $idUGFuncionario);
            $query->where("afr.intVigencia_plnFR = 1");
             
            $db->setQuery($query);
            $db->query();
            
            $result = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    public function getIndsFnciResp( $idUGFuncionario )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("ifr.intId_fgr             AS idIndFR, 
                            ifr.intIdIndEntidad       AS idIndicador");
            $query->from("#__ind_funcionario_responsable ifr");
            $query->where("ifr.intId_ugf = " . $idUGFuncionario);
            $query->where("ifr.intVigencia_plnFR = 1");
             
            $db->setQuery($query);
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
     *  Elimina de manera logica un registro de funcionario con la unidad de 
     *  gestion dado si id de relacion
     * @param type $idUgFnci
     * @return type
     */
    public function deleleteUGFnci( $idUgFnci )
    {
         try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update( "#__gen_ug_funcionario" );
            $query->set( "published = 0 " );
            $query->where("intId_ugf = {$idUgFnci}");
             
            $db->setQuery($query);
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
     *  Realiza una eliminacion logica de la relacion de un funionario con una unidad de gestion
     * @param type $idFuncionario       ID del funcionario 
     * @param type $idUg                ID de la unidad de gestion
     * @return type
     */
    public function updateFncUg( $idFuncionario, $idUg )
    {
         try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update( "#__gen_ug_funcionario" );
            $query->set( "published = 0" );
            $query->where("intCodigo_fnc = {$idFuncionario}");
            $query->where("intCodigo_ug = {$idUg}");
            $query->where("published = 1");
             
            $db->setQuery($query);
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
     *  Realiza la asignacion de un funcionario a SIN UNIDAD DE GESTION
     * @param type $idFuncionario
     */
    public function asignarFncSinUg( $idFuncionario )
    {
        
        $dtaFuncionario["intId_ugf"]                = 0;
        $dtaFuncionario["intCodigo_ug"]             = 0;
        $dtaFuncionario["intId_ugc"]                = 0;
        $dtaFuncionario["intCodigo_fnc"]            = $idFuncionario;
        $dtaFuncionario["dteFechaInicio_ugf"]       = date("Y-m-d");
        $dtaFuncionario["dteFechaFin_ugf"]          = date("Y-m-d");
        $dtaFuncionario["dteFechaRegistro_ugf"]     = date("Y-m-d");
        $dtaFuncionario["dteFechaModificacion_ugf"] = date("Y-m-d");
        $dtaFuncionario["published"]                = 1;

        $this->saveFuncionarioUG($dtaFuncionario); 
        
    }
    
    /**
     *  Obtiene toda la informacion de un derterminado fucninario
     * @param type $id
     * @return type
     */
    public function getDataFuncionario ( $id )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select( "*" );
            $query->from( "#__gen_funcionario" );
            $query->where("intCodigo_fnc = {$id}");
             
            $db->setQuery($query);
            $db->query();
            
            $result = ($db->getAffectedRows() > 0) ? $db->loadObject() : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * 
     * @param type $idFuncionario
     * @return type
     */
//    public function getGruposByFnc( $idFuncionario)
//    {
//        try {
//            $db = & JFactory::getDBO();
//            $query = $db->getQuery(TRUE);
//            $query->select( "ugm.group_id AS idGrupo" );
//            $query->from( "#__user_usergroup_map ugm" );
//            $query->innerJoin( "#__gen_funcionario f ON f.intIdUser_fnc = ugm.user_id");
//            $query->where("f.intCodigo_fnc = {$idFuncionario}");
//             
//            $db->setQuery($query);
//            $db->query();
//            
//            $result = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : array();
//
//            return $result;
//        } catch (Exception $e) {
//            jimport('joomla.error.log');
//            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
//            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
//        }
//    }
    
    
    public function getOpAddFnc( $idFuncionario )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select( "ugm.group_id AS idGrupo" );
            $query->from( "#__user_usergroup_map ugm" );
            $query->innerJoin( "#__gen_funcionario f ON f.intIdUser_fnc = ugm.user_id");
            $query->innerJoin( "#__gen_ug_funcionario ugf ON ugf.intCodigo_fnc = f.intCodigo_fnc");
            $query->innerJoin( "#__gen_unidad_gestion ug ON ug.intCodigo_ug = ugf.intCodigo_ug");
            $query->innerJoin( "#__gen_ug_cargo ugc ON ugc.intId_ugc = ugf.intId_ugc");
            $query->where("f.intCodigo_fnc = {$idFuncionario}");
            $query->where("ugc.intIdGrupo_cargo <> ugm.group_id");
            $query->where("ug.intIdGrupo_ug <> ugm.group_id");
            $query->where("ugm.group_id <> 12");
             
            $db->setQuery($query);
            $db->query();
            
            $result = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : array();

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
}