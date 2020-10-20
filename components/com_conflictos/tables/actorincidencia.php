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
class ConflictosTableActorIncidencia extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_actor_incidencia', 'intId_ai', $db );
    }

    /**
     * Gestiona la informacion del tema.
     * @param object $incidencia        Informaciona Alamcena|Editar de la incidencia.
     * @return int                      Identificador del tema gestionado.
     */
    public function saveActorIncidencia( $incidencia, $idActor )
    {
        $data["intId_ai"] = $incidencia->idActInci;
        $data["intId_act"] = $idActor;
        $data["intId_inc"] = $incidencia->idIncidencia;
        $data["dteFecha"] = $incidencia->fecha;
        $data["published"] = $incidencia->published;

        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idActorIncidencia = $this->intId_ai;
            }
        }
        return $idActorIncidencia;
    }

    /**
     *  Recupera una fuente dado el identificador de la fuente 
     * @param type $idActor    Identificador de la fuente.
     */
    public function getIncidenciaActor( $idActor )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '  
                            ain.intId_ai                AS  idActInci,    
                            ain.intId_act               AS  idActor,
                            ain.intId_inc               AS  idIncidencia,
                            upper(inc.strNombre_inc)    AS  nmbIncidencia,
                            ain.dteFecha                AS  fecha,
                            ain.published               AS  published
                           ' );
            $query->from( '#__gc_actor_incidencia AS ain' );
            $query->join( 'inner', '#__gc_incidencia AS inc ON inc.intId_inc = ain.intId_inc' );
            $query->where( 'ain.intId_act = ' . $this->_db->quote( $idActor ) );
            $query->where( 'ain.published = 1' );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->loadObjectList() ) ? $db->loadObjectList() : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.tema.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}