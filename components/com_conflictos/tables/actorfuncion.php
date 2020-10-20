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
class ConflictosTableActorFuncion extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_actor_funcion', 'intId_af', $db );
    }

    /**
     * Gestiona la informacion del tema.
     * @param object $incidencia        Informaciona Alamcena|Editar de la incidencia.
     * @return int                      Identificador del tema gestionado.
     */
    public function saveActorFuncion( $funcion, $idActor )
    {
        $data["intId_af"] = $funcion->idActFunc;
        $data["intId_fcn"] = (int) $funcion->idFuncion;
        $data["intId_act"] = (int) $idActor;
        $data["dteFecha_inicio_af"] = $funcion->fechaDesde;
        $data["dteFecha_fin_af"] = $funcion->fechaHasta;
        $data["published"] = $funcion->published;
        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idActorIncidencia = $this->intId_af;
            }
        }
        return $idActorIncidencia;
    }

    /**
     *  Recupera una fuente dado el identificador de la fuente 
     * @param type $idActor    Identificador de la fuente.
     */
    public function getFuncionesActor( $idActor )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( ' 
                                afn.intId_af                AS	idActFunc,
                                afn.intId_fcn               AS	idFuncion,
                                upper( fnc.strNombre_fcn)   AS  nmbFuncion,
                                afn.dteFecha_inicio_af      AS	fechaDesde,
                                afn.dteFecha_fin_af         AS  fechaHasta ,    
                                afn.published               AS  published
                            ' );
            $query->from( '#__gc_actor_funcion AS afn' );
            $query->join( 'inner', '#__gc_funcion AS fnc ON fnc.intId_fcn = afn.intId_fcn' );
            $query->where( 'afn.intId_act = ' . $this->_db->quote( $idActor ) );
            $query->where( 'afn.published = 1' );
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

}