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
class ConflictosTableActorTema extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_actor_detalle', 'intId_ad', $db );
    }

    /**
     * Gestiona el actor y un tema
     * @param object $actDeta objeto que contriene la informacion del actor
     * @return type
     */
    function saveActDeta( $actDeta, $idTema )
    {
        $idActDeta = 0;

        $data["intId_ad"] = (int) $actDeta->idActorDetalle;
        $data["intId_tma"] = $idTema;
        $data["intId_act"] = (int) $actDeta->idActor;
        $data["dteFecha_ad"] = $actDeta->fecha;
        $data["strDescripcion_ad"] = $actDeta->descripcion;
        $data["published"] = $actDeta->published;

        if( $this->save( $data ) ) {
            $idActDeta = $this->intId_ad;
        }
        return $idActDeta;
    }

    /**
     *  Retorna la lista de actores de un tema 
     * @param type $idTema  Identificador del tema
     * @return objet list
     */
    function getActoresTema( $idTema )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '  
                            acd.intId_ad            AS  idActorDetalle,
                            acd.intId_act           AS  idActor,
                            upper(CONCAT(act.strNombre_act," ",act.strApellido_act)) AS nmbActor,
                            acd.dteFecha_ad         AS  fecha,
                            acd.strDescripcion_ad   AS  descripcion,
                            acd.published           AS  published
                           ' );
            $query->from( '#__gc_actor_detalle AS acd' );
            $query->join( 'inner', '#__gc_actor AS act ON acd.intId_act = act.intId_act' );
            $query->where( 'acd.intId_tma = ' . $this->_db->quote( $idTema ) );
            $query->where( 'acd.published = 1 ' );
            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_contratos.table.tiposcontratista.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}