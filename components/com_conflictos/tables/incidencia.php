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
class ConflictosTableIncidencia extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_incidencia', 'intId_inc', $db );
    }

    /**
     * 
     */
    public function saveIncidencia( $data )
    {
        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idInc = $this->intId_inc;
            }
        }
        return $idInc;
    }

    /**
     *  Retorna la lista de Inicidencias
     * @return type
     */
    public function getListIncs()
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 'inc.intId_inc AS id, 
                         upper( inc.strNombre_inc ) AS nombre' );
            $query->from( '#__gc_incidencia AS inc' );
            $query->where( 'inc.published = 1' );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.incidencia.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Restorna True si el registro se puede eliminar, caso contrario retorna False
     * @param type $id
     * @return type
     */
    public function avalibleDeleteInc( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('fi.intId_fi');
            $query->from('#__gc_fuente_incidencia fi');
            $query->where('fi.intId_inc = ' . (int)$id . ' AND fi.published = 1');
            // Sesunda sentencia para la union .
            $query2 = $db->getQuery(true);
            $query2->select('intId_ai');
            $query2->from('#__gc_actor_incidencia');
            $query2->where('intId_inc = ' . (int)$id . ' AND published = 1');
            //  Realisa la union de selects y asinga el query
            $subunion = $db->getQuery(true);
            $subunion->union($query2);
            $queryAll = $query . ' ' .  $subunion->union;
            $db->setQuery( $queryAll );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? false : true;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.incidencia.log.php' );
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
            $query->select('fi.intId_fi');
            $query->from('#__gc_fuente_incidencia fi');
            $query->where('fi.intId_inc = ' . (int)$id );
            // Sesunda sentencia para la union .
            $query2 = $db->getQuery(true);
            $query2->select('intId_ai');
            $query2->from('#__gc_actor_incidencia');
            $query2->where('intId_inc = ' . (int)$id );
            //  Realisa la union de selects y asinga el query
            $subunion = $db->getQuery(true);
            $subunion->union($query2);
            $queryAll = $query . ' ' .  $subunion->union;
            $db->setQuery( $queryAll );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? false : true;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.incidencia.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function eliminadoLogicoInc( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->update( '#__gc_incidencia' );
            $query->set( 'published = 0' );
            $query->where( 'intId_inc = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.incidencia.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function eliminadoFisicoInc( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->delete( '#__gc_incidencia' );
            $query->where( 'intId_inc = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.incidencia.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
}