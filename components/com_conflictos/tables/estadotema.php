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
class ConflictosTableEstadoTema extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_tema_estado', 'intId_te', $db );
    }

    /**
     * Gestiona el registo del estado de un tema
     * @param array $estadoTema  Objeto estado tema
     * @param int   $idTema      identificador del tema al que se le asocia el estado.
     * @return int               identificador de estado_tema
     */
    public function saveEstadoTema( $estadoTema, $idTema )
    {
        $idEstadoTema = 0;

        $data["intId_te"] = $estadoTema->idTemaEstado;
        $data["intId_tma"] = $idTema;
        $data["intId_ec"] = $estadoTema->idEstado;
        $data["dteFechaInicio_te"] = $estadoTema->fechaInicio;
        $data["dteFechaFin_te"] = $estadoTema->fechaFin;
        $data["published"] = $estadoTema->published;
        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idEstadoTema = $this->intId_te;
            }
        }
        return $idTema;
    }

    /**
     *  Retorna la lista de estados de un tema
     * @param   int         $idTema     Identificador del tema
     * @return  object
     */
    public function getEstadosTema( $idTema )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '  
                            ett.intId_te            AS idTemaEstado,       
                            ett.intId_tma           AS idTema,
                            ett.intId_ec            AS idEstado,
                            upper(est.strNombre_ec) AS nmbEstado,
                            ett.dteFechaInicio_te   AS fechaInicio,
                            ett.dteFechaFin_te      AS fechaFin,
                            ett.published           AS published
                           ' );
            $query->from( '#__gc_tema_estado AS ett' );
            $query->join( 'inner', '#__gc_estado AS est ON est.intId_ec = ett.intId_ec' );
            $query->where( 'ett.intId_tma = ' . $this->_db->quote( $idTema ) );
            $query->where( 'ett.published = 1 ' );
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