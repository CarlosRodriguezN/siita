<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

/**
 * 
 * Clase que gestiona informacion de la tabla Legitimidad ( #__gc_legitimidad )
 * 
 */
class ConflictosTableLegitimidad extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_legitimidad', 'intId_leg', $db );
    }

    /**
     * 
     */
    public function saveLegitimidad( $data )
    {
        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idFuente = $this->intId_leg;
            }
        }
        return $idFuente;
    }

    /**
     *  Retorna la lista de Inicidencias
     * @return type
     */
    public function getListLegs()
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 'lg.intId_leg AS id, 
                         upper( lg.strDescripcion_leg ) AS nombre' );
            $query->from( '#__gc_legitimidad AS lg' );
            $query->where( 'lg.published = 1' );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.legitimidad.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Restorna True si el registro se puede eliminar, caso contrario retorna False
     * @param type $id      ID del registro s eliminar
     * @param type $op      1: para eliminar del sistema
     *                      2: para eliminar fisicamente
     * @return type
     */
    public function avalibleDeleteLeg( $id, $op ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( 'intId_fl' );
            $query->from( '#__gc_fuente_legitimidad' );
            $query->where( 'intId_leg = ' .  (int)$id );
            if ( $op == 1) {
                $query->where( 'published = 1');
            }
            // Segunda sentencia para la union .
            $query2 = $db->getQuery(true);
            $query2->select('intId_al');
            $query2->from('#__gc_actor_legitimidad');
            $query2->where('intId_leg = ' . (int)$id );
            if ( $op == 1) {
                $query2->where( 'published = 1');
            }
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
            $log = &JLog::getInstance( 'com_conflictos.table.legitimidad.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public function eliminadoLogicoLeg( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->update( '#__gc_legitimidad' );
            $query->set( 'published = 0' );
            $query->where( 'intId_leg = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.legitimidad.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function eliminadoFisicoLeg( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->delete( '#__gc_legitimidad' );
            $query->where( 'intId_leg = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.legitimidad.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
}