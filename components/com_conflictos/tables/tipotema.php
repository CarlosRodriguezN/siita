<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla tipo tema ( #__gc_tipo_tema )
 * 
 */
class ConflictosTableTipoTema extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__gc_tipo_tema', 'intId_tt', $db );
    }

    /**
     * 
     */
    public function saveTipoTema( $data )
    {
        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idTpoTma = $this->intId_tt;
            }
        }
        return $idTpoTma;
    }

    /**
     *  Retorna la lista de tipos de tema
     * @return type
     */
    public function getListTposTema()
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 'tt.intId_tt as id, 
                             upper( tt.strNombre_tt ) as nombre' );
            $query->from( '#__gc_tipo_tema tt' );
            $query->where( 'tt.published = 1' );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.tpotema.log.php' );
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
    public function avalibleDeleteTT( $id, $op ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( 'tm.intId_tma' );
            $query->from( '#__gc_tema AS tm' );
            $query->where( 'tm.intId_tt = ' .  (int)$id );
            if ($op == 1) {
                $query->where( 'tm.published = 1' );
            }
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? false : true;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.tpotema.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public function eliminadoFisicoTT( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->delete( '#__gc_tipo_tema' );
            $query->where( 'intId_tt = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.tpotema.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function eliminadoLogicoTT( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->update( '#__gc_tipo_tema' );
            $query->set( 'published = 0' );
            $query->where( 'intId_tt = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.tpotema.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
}