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
class ConflictosTableFuenteTema extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_tema_fuente', 'intId_tf', $db );
    }

    /**
     * Gestiona la informacion del tema.
     * @param object $tema      Informaciona Alamcena|Editar del tema.
     * @return int              Identificador del tema gestionado.
     */
    public function saveFuenteTema( $tema, $idTema )
    {
        $idFuenteTema = 0;

        $data["intId_tf"] = $tema->idFuenteTema;
        $data["intId_tma"] = $idTema;
        $data["intId_fte"] = $tema->idFuente;
        $data["dteFecha_tf"] = $tema->fecha;
        $data["strObservacion_tf"] = $tema->observacion;
        $data["published"] = $tema->published;

        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idFuenteTema = $this->intId_tf;
            }
        }
        return $idFuenteTema;
    }

    /**
     * Recupera la informacion de una tema.
     * @param type $idTema
     * @return type
     */
    public function getFuenteTema( $idTema )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '  
                            tfu.intId_tf                    AS  idFuenteTema,
                            tfu.intId_fte                   AS  idFuente,
                            upper(fue.strDescripcion_fte)   AS  nmbFuente,
                            fue.intId_tf                    AS  idTipoFuente,
                            upper(tpf.strDescripcion_tf)    AS  nmbTipoFuente,
                            tfu.dteFecha_tf                 AS  fecha,
                            tfu.strObservacion_tf           AS  observacion,
                            tfu.published                   AS  published
                           ' );
            $query->from( '#__gc_tema_fuente AS tfu' );
            $query->where( 'tfu.intId_tma = ' . $this->_db->quote( $idTema ) );
            $query->join('inner', '#__gc_fuente         AS fue ON fue.intId_fte = tfu.intId_fte');
            $query->join('inner', '#__gc_tipo_fuente    AS tpf ON tpf.intId_tf = fue.intId_tf');
            $query->where( 'tfu.published = 1' );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->loadObjectList() > 0 ) ? $db->loadObjectList() : array();
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.tema.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}