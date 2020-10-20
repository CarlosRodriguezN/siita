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
class ConflictosTableTipoFuente extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_tipo_fuente', 'intId_tf', $db );
    }

    /**
     * 
     */
    public function saveTipoFuente( $data )
    {
        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idFuente = $this->intId_tf;
            }
        }
        return $idFuente;
    }

    /**
     *  Retorna la lista de tipos de fuente
     * @return type
     */
    public function getTiposFuente()
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 'tf.intId_tf AS id, 
                         upper( tf.strDescripcion_tf ) AS nombre' );
            $query->from( '#__gc_tipo_fuente AS tf' );
            $query->where( 'tf.published = 1' );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.tema.log.php' );
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
    public function avalibleDeleteTF( $id, $op ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( 'f.intId_fte' );
            $query->from( '#__gc_fuente AS f' );
            $query->where( 'f.intId_tf = ' .  (int)$id );
            if ($op == 1) {
                $query->where( 'f.published = 1' );
            }
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? false : true;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.tema.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public function deleteLogicoTF( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->update( '#__gc_tipo_fuente' );
            $query->set( 'published = 0' );
            $query->where( 'intId_tf = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.tema.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function deleteFisicoTF( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->delete( '#__gc_tipo_fuente' );
            $query->where( 'intId_tf = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.tema.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
}