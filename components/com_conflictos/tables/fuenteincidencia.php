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
class ConflictosTableFuenteIncidencia extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_fuente_incidencia', 'intId_fi', $db );
    }

    /**
     * Gestiona la informacion del tema.
     * @param object $incidencia        Informaciona Alamcena|Editar de la incidencia.
     * @return int                      Identificador del tema gestionado.
     */
    public function saveFuenteIncidencia( $incidencia, $idFuente )
    {
        $idFuenteTema = 0;
                                
        $data["intId_fi"] = $incidencia->idFuIncidencia;
        $data["intId_fte"] = $idFuente;
        $data["intId_inc"] = $incidencia->idIndidencia;
        $data["dtefecha_fi"] = $incidencia->fecha;
        $data["published"] = $incidencia->published;
        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idFuenteTema = $this->intId_fi;
            }
        }
        return $idFuenteTema;
    }

    /**
     *  Recupera una fuente dado el identificador de la fuente 
     * @param type $idFuente    Identificador de la fuente.
     */
    public function getIncidenciaFuente( $idFuente )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '  
                        fin.intId_fi                AS idFuIncidencia,         
                        fin.intId_inc               AS idIndidencia,
                        upper(inc.strNombre_inc)    AS nmbIncidencia,
                        fin.dtefecha_fi             AS fecha,
                        fin.published               AS published  
                           ' );
            $query->from( '#__gc_fuente_incidencia AS fin' );
            $query->join( 'inner', '#__gc_incidencia AS inc ON inc.intId_inc = fin.intId_inc' );
            $query->where( 'fin.intId_fte = ' . $this->_db->quote( $idFuente ) );
            $query->where( 'fin.published = 1' );
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