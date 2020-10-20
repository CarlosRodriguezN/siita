<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla de Entidad ( #__gen_entidad )
 * 
 */
class FuncionariosTableUgFuncionario extends JTable {

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
     *  Registra la ralacion del funcionario con la unidad de gestion
     * 
     * @param type $dataFncUg
     * @return type
     */
    public function registrarFuncionarioUG( $dataFncUg ) 
    {
        if (!$this->save($dataFncUg)) {
            echo $this->getError();
            exit;
        }

        return $this->intId_ugf;
    }
    
    /**
     *  Retorna el Id de la unidad de gestion de un funcionario
     * 
     * @param type $idUgFnc
     * @return type
     */
    public function getIdUgFuncionario( $idUgFnc ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intCodigo_ug");
            $query->from( "#__gen_ug_funcionario" );
            $query->where("intId_ugf = {$idUgFnc}" );
            
            $db->setQuery($query);
            $db->query();
            
            $result = ($db->getAffectedRows() > 0) ? $db->loadResult() : false;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_funcionarios.tables.ugfuncionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
            
        }
    }
    
    /**
     *  Retorna la data actual de la unidad de gestion relacionada a un funcionario
     * 
     * @param type $idFncUg
     * @return type
     */
    public function getActulUgF( $idFncUg )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select( 'fug.intId_ugf      AS idFncUg,
                        fug.intCodigo_ug        AS idUg,
                        fug.intId_ugc           AS idCargoFncUg,
                        fug.dteFechaInicio_ugf  AS fchInicioFncUg,
                        fug.dteFechaFin_ugf     AS fchFinFncUg,
                        fug.published           AS publishedFncUg' );
            $query->from( '#__gen_ug_funcionario fug' );
            $query->where('fug.intId_ugf = ' . $idFncUg );
            
            $db->setQuery($query);
            $db->query();
            
            $result = ($db->getAffectedRows() > 0) ? $db->loadObject() : false;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_funcionarios.tables.ugfuncionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna la informacion de la unidad de gestion de un funcionario
     * 
     * @param type $idFnc
     * @return type
     */
    public function getDataUG($idFnc) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("ugf.intCodigo_ug as idUG,
                            ugf.intId_ugc as idCargo,
                            ugf.intId_ugf as idFncUG,
                            ugf.dteFechaInicio_ugf as fechaInicio,
                            ugf.dteFechaFin_ugf as fechaFin");
            $query->from("#__gen_ug_funcionario ugf");
            $query->where("ugf.intCodigo_fnc = {$idFnc}");
            $query->where("ugf.published = 1");

            $db->setQuery((string) $query);
            $db->query();

            $result = ( $db->loadObject() ) ? $db->loadObject() : false;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_funcionarios.tables.ugfuncionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna la data del ususario del sistema de un funcionario
     * 
     * @param type $idUser
     * @return type
     */
    public function getDataUser( $idUser ) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("password");
            $query->from("#__users");
            $query->where("id = {$idUser}");

            $db->setQuery((string) $query);
            $db->query();

            $result = ( $db->loadResult() ) ? $db->loadResult() : false;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_funcionarios.tables.ugfuncionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

     /**
     *  Elinina a nivel logico unregisto de funcionario-unidadgestion de las base de datos
     * @param type $idFncUG         ID de la relacion entre unidad de gestion y funcionario
     * @return type
     */
    public function deleteLogicalFncUG( $idFncUG ) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->update( "#__gen_ug_funcionario" );
            $query->set( "published = 0" );
            $query->where("intId_ugf = " . $idFncUG);
            $db->setQuery($query);

            $result = ( $db->query() ) ? true : false;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_funcionarios.tables.ugfuncionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Elimina a nivel fisico los registros de funcionario-unidadgestion de las base de datos
     * @param type $idFnc         ID del funcionario a eliminar
     * @return type
     */
    public function eliminarFncUG( $idFnc ) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->delete( "#__gen_ug_funcionario" );
            $query->where("intCodigo_fnc = " . $idFnc);
            $db->setQuery($query);

            $result = ( $db->query() ) ? true : false;
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_funcionarios.tables.ugfuncionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}