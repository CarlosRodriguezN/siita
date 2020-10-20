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
class ConflictosTableActor extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_actor', 'intId_act', $db );
    }

    /**
     * Gestiona los datos del actor 
     * @param type $actor
     * @return type
     */
    public function saveActor( $actor )
    {
        $idActor = 0;
        $data["intId_act"] = $actor->idActor;
        $data["strNombre_act"] = $actor->actNombre;
        $data["strApellido_act"] = $actor->actApellido;
        $data["strCorreo_act"] = $actor->correo;
        $data["published"] = $actor->published;
        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idActor = $this->intId_act;
            }
        }
        return $idActor;
    }

    /**
     *  Recupera una actor dado el identificador de la actor 
     * @param type $idActor    Identificador de la actor.
     */
    public function getActor( $idActor )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( '  
                            act.intId_act           AS  idActor,
                            act.strNombre_act       AS  actNombre,
                            act.strApellido_act     AS  actApellido,
                            act.strCorreo_act       AS  correo,
                            act.published           AS  published
                           ' );
            $query->from( '#__gc_actor AS act' );
            $query->where( 'act.intId_act = ' . $this->_db->quote( $idActor ) );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->loadObject() ) ? $db->loadObject() : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.actor.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Valida se se uede elimnar un registro de actors de forma fisica o logica
     *  
     * @param type $id      ID del registro
     * @param type $op      1: logica, 2: fisica
     * @return type
     */
    public function validoEliminarActor( $id, $op ){
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( 'intId_ad' );
            $query->from( '#__gc_actor_detalle' );
            $query->where( 'intId_act = ' .  (int)$id );
            if ( $op == 1) {
                $query->where( 'published = 1');
            }
            // Segunda sentencia para la union .
            $query2 = $db->getQuery(true);
            $query2->select('intId_af');
            $query2->from('#__gc_actor_funcion');
            $query2->where('intId_act = ' . (int)$id );
            if ( $op == 1) {
                $query2->where( 'published = 1');
            }
            // Tercera sentencia para la union .
            $query3 = $db->getQuery(true);
            $query3->select('intId_ai');
            $query3->from('#__gc_actor_incidencia');
            $query3->where('intId_act = ' . (int)$id );
            if ( $op == 1) {
                $query3->where( 'published = 1');
            }
            // Tercera sentencia para la union .
            $query4 = $db->getQuery(true);
            $query4->select('intId_al');
            $query4->from('#__gc_actor_legitimidad');
            $query4->where('intId_act = ' . (int)$id );
            if ( $op == 1) {
                $query4->where( 'published = 1');
            }
            //  Realisa la union de selects y asinga el query
            $subunion = $db->getQuery(true);
            $subunion->union(array($query2, $query3, $query4));
            $queryAll = $query . ' ' .  $subunion->union;
            $db->setQuery( $queryAll );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? false : true;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.actor.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Elimina de forma fisica un registro
     * 
     * @param type $id
     * @return type
     */
    public function eliminadoFisico( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->delete( '#__gc_actor' );
            $query->where( 'intId_act = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.actor.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Elimina de forma logica un registro de actors
     * 
     * @param type $id
     * @return type
     */
    public function eliminadoLogico( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->update( '#__gc_actor' );
            $query->set( 'published = 0' );
            $query->where( 'intId_act = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.actor.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
 

}