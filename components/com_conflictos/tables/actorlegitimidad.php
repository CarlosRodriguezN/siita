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
class ConflictosTableActorLegitimidad extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_actor_legitimidad', 'intId_al', $db );
    }

    /**
     * Gestiona la informacion del tema.
     * @param object $fueLegitmidad        Informaciona Alamcena|Editar de la legitimidad .
     * @return int                      Identificador del tema gestionado.
     */
    public function saveActorLegitimidad( $fueLegitimidad, $idActor )
    {
        $idActoteLegitimidad = 0;
        $data["intId_al"]           = $fueLegitimidad->idActLegi;
        $data["intId_act"]          = $idActor;
        $data["intId_leg"]          = $fueLegitimidad->idLegitimidad;
        $data["strDescripcion_al"]  = $fueLegitimidad->descripcion;
        $data["published"]         = $fueLegitimidad->published;
        
        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idActoteLegitimidad = $this->intId_al;
            }
        }
        return $idActoteLegitimidad;
    }

    /**
     *  Recupera una fuente dado el identificador de la fuente 
     * @param type $idActor    Identificador de la fuente.
     */
    public function getLegitimidadActor( $idActor )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '  
                            fle.intId_al                    AS idActLegi,
                            fle.intId_leg                   AS idLegitimidad,
                            upper(leg.strDescripcion_leg)   AS nmbLegitimidad,
                            fle.strDescripcion_al           AS descripcion,
                            fle.published                   AS published
                         ' );
            $query->from( '#__gc_actor_legitimidad AS fle' );
            $query->join( 'inner', '#__gc_legitimidad AS leg ON fle.intId_leg = leg.intId_leg' );
            $query->where( 'fle.intId_act = ' . $this->_db->quote( $idActor ) );
            $query->where( 'fle.published = 1' );
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