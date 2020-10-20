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
class ConflictosTableTema extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_tema', 'intId_tma', $db );
    }

    /**
     * Gestiona la informacion del tema.
     * @param object $tema      Informaciona Alamcena|Editar del tema.
     * @return int              Identificador del tema gestionado.
     */
    public function saveTema( $tema )
    {
        $idTema = 0;
        $data["intId_tma"] = $tema->idTema;
        $data["intId_tt"] = $tema->idTipoTema;
        $data["intId_ni"] = $tema->idNivelImpacto;
        $data["strTitulo_tma"] = $tema->titulo;
        $data["strResumen_tma"] = $tema->resumen;
        $data["strObservaciones_tma"] = $tema->observaciones;
        $data["strSugerencias_tma"] = $tema->sugerenncias;
        $data["published"] = $tema->published;
        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idTema = $this->intId_tma;
            }
        }
        return $idTema;
    }

    /**
     * Recupera la informacion de una tema.
     * @param type $idTema
     * @return type
     */
    public function getTema( $idTema )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '  
                                tma.intId_tma               AS  idTema,
                                tma.intId_tt                AS  idTipoTema,
                                tma.intId_ni                AS  idNivelImpacto,
                                tma.strTitulo_tma           AS  titulo,	
                                tma.strResumen_tma          AS	resumen,
                                tma.strObservaciones_tma    AS	observaciones,
                                tma.strSugerencias_tma      AS  sugerenncias,
                                tma.published               AS  published
                           ' );
            $query->from( '#__gc_tema AS tma' );
            $query->where( 'tma.intId_tma = ' . $this->_db->quote( $idTema ) );
            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObject() : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.tema.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Elimina un registro de temas
     * @param type $idTema
     * @return type
     */
    public function eliminarLogicaTema( $idTema )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->update( '#__gc_tema' );
            $query->set( 'published = 0' );
            $query->where( 'intId_tma = ' . $idTema );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.tema.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    

}