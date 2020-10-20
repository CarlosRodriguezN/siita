<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla estado ( #__gc_estado )
 * 
 */
class ConflictosTableEstado extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__gc_estado', 'intId_ec', $db );
    }

    /**
     * 
     */
    public function saveEstado( $data )
    {
        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idEstado = $this->intId_ec;
            }
        }
        return $idEstado;
    }

    /**
     *  Retorna la lista de tipos de tema
     * @return type
     */
    public function getListEstados()
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 'e.intId_ec as id, 
                         upper( e.strNombre_ec ) as nombre' );
            $query->from( '#__gc_estado e' );
            $query->where( 'e.published = 1' );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.estado.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Restorna True si el registro se puede eliminar, caso contrario retorna False
     * @param type $id
     * @return type
     */
    /**
     * 
     * @param type $id      ID del registro s eliminar
     * @param type $op      1: para eliminar del sistema
     *                      2: para eliminar fisicamente
     * @return type
     */
    public function avalibleDeleteEst( $id, $op ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( 'te.intId_te' );
            $query->from( '#__gc_tema_estado AS te' );
            $query->where( 'te.intId_ec = ' .  (int)$id );
            if ($op == 1) {
                $query->where( 'te.published = 1' );
            }
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? false : true;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.estado.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public function eliminadoFisicoEst( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->delete( '#__gc_estado' );
            $query->where( 'intId_ec = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.estado.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function eliminadoLogicoEst( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->update( '#__gc_estado' );
            $query->set( 'published = 0' );
            $query->where( 'intId_ec = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.estado.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
}