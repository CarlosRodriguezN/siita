<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

/**
 * 
 * Clase que gestiona informacion de la tabla Funcion ( #__gc_funcion )
 * 
 */
class ConflictosTableFuncion extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_funcion', 'intId_fcn', $db );
    }

    /**
     * 
     */
    public function saveFuncion( $data )
    {
        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idFnc = $this->intId_fcn;
            }
        }
        return $idFnc;
    }

    /**
     *  Retorna la lista de Inicidencias
     * @return type
     */
    public function getListFunciones()
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 'f.intId_fcn AS id, 
                         upper( f.strNombre_fcn ) AS nombre' );
            $query->from( '#__gc_funcion AS f' );
            $query->where( 'f.published = 1' );
            
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.funcion.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Restorna True si el registro se puede eliminar, caso contrario retorna False
     * @param type $id
     * @return type
     */
    public function avalibleDeleteFnc( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select( 'f.intId_af' );
            $query->from( '#__gc_actor_funcion AS f' );
            $query->where( 'f.intId_fcn = ' .  (int)$id );
            $query->where( 'f.published = 1' );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? false : true;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.funcion.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *  Restorna True si el registro no tiene ninguna relacion y se puede eliminar 
     *  fisicamente, caso contrario retorna False
     * @param type $id
     * @return type
     */
    public function avalibleDeletePhysical( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select( 'f.intId_af' );
            $query->from( '#__gc_actor_funcion AS f' );
            $query->where( 'f.intId_fcn = ' .  (int)$id );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? false : true;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.funcion.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function eliminadoLogicoFnc( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->update( '#__gc_funcion' );
            $query->set( 'published = 0' );
            $query->where( 'intId_fcn = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.funcion.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function eliminadoFisicoFnc( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->delete( '#__gc_funcion' );
            $query->where( 'intId_fcn = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.funcion.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
}