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
class ConflictosTableFuenteLegitimidad extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_fuente_legitimidad', 'intId_fl', $db );
    }

    /**
     * Gestiona la informacion del tema.
     * @param object $fueLegitmidad        Informaciona Alamcena|Editar de la legitimidad .
     * @return int                      Identificador del tema gestionado.
     */
    public function saveFuenteLegitimidad( $fueLegitmidad, $idFuente )
    {
        $idFuenteLegitimidad = 0;
        
        $data["intId_fl"] = $fueLegitmidad->idFutLegi;
        $data["intId_leg"] = $fueLegitmidad->idLegitimidad;
        $data["intId_fte"] = $idFuente;
        $data["strDescripcion_fl"] = $fueLegitmidad->descripcion;
        $data["dteFecha_fl"] = $fueLegitmidad->fecha;
        $data["published"] = $fueLegitmidad->published;
        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idFuenteLegitimidad = $this->intId_fl;
            }
        }
        return $idFuenteLegitimidad;
    }

    /**
     *  Recupera una fuente dado el identificador de la fuente 
     * @param type $idFuente    Identificador de la fuente.
     */
    public function getLegitimidadFuente( $idFuente )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '  
                            fle.intId_fl                    AS idFutLegi,
                            fle.intId_leg                   AS idLegitimidad,
                            upper(leg.strDescripcion_leg)   AS nmbLegitimidad,
                            fle.strDescripcion_fl           AS descripcion,
                            fle.dteFecha_fl                 AS fecha,
                            fle.published                   AS published
                         ' );
            $query->from( '#__gc_fuente_legitimidad AS fle' );
            $query->join( 'inner', '#__gc_legitimidad AS leg ON fle.intId_leg = leg.intId_leg' );
            $query->where( 'fle.intId_fte = ' . $this->_db->quote( $idFuente ) );
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