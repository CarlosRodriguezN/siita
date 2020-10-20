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
class FuncionariosTableUsuario extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__users', 'id', $db );
    }
    
     /**
     * 
     * Registramos un funcionario como nuevo usuario
     * 
     * @param type $dtaUsuario  datos de un nuevo usuario
     * @return type
     * 
     */
    public function guardarUsuario( $dtaUsuario )
    {
        if( !$this->save( $dtaUsuario ) ){
            throw new Exception( 'Error al Registrar un nuevo usuario' );
            exit;
        }

        return $this->id;
    }
    
    /**
     * 
     * Registra un nuevo usuario
     * 
     * @param type $idProfesional       Identificador de registro de un nuevo usuario
     * @param type $dataProfesional     
     * 
     */
    public function registrarGrupoUsuario( $idUser )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->insert( '#__user_usergroup_map' );
            $query->columns( 'user_id, group_id' );
            $query->values( "{$idUser}, 12 ");  //  12 ID del grupo funcionarios
            $db->setQuery( $query );
            return ($db->query()) ? true : false;
            
        } catch (Exception $e) {
                    jimport('joomla.error.log');
                $log = &JLog::getInstance('com_funcionarios.tables.usuario.php');
                $log->add($e->getMessage(), JLog::WARNING, $e->getCode());
        }
    }
    
    /**
     * 
     * @param type $idUsr
     * @return type
     */
    public function getPassUsr( $idUsr )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select( 'password' );
            $query->from( '#__users' );
            $query->where( "id = {$idUsr}");
            $db->setQuery( $query );
            $db->query();
            $result = ($db->getAffectedRows() > 0) ? $db->loadResult() : array();
            return $result;
        } catch (Exception $e) {
                    jimport('joomla.error.log');
                $log = &JLog::getInstance('com_funcionarios.tables.usuario.php');
                $log->add($e->getMessage(), JLog::WARNING, $e->getCode());
        }
    }
    
    /**
     * 
     * @param type $idUsr
     * @param type $newPass
     * @return type
     */
    public function updPassUsr( $idUsr, $newPass )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->update( "#__users" );
            $query->set( "password = '{$newPass}'" );
            $query->where( "id = {$idUsr}" );
            $db->setQuery( $query );
            return ($db->query()) ? true : false;
        } catch (Exception $e) {
                    jimport('joomla.error.log');
                $log = &JLog::getInstance('com_funcionarios.tables.usuario.php');
                $log->add($e->getMessage(), JLog::WARNING, $e->getCode());
        }
    }
    
}