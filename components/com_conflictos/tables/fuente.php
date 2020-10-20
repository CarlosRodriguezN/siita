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
class ConflictosTableFuente extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_fuente', 'intId_fte', $db );
    }

    /**
     * 
     */
    public function saveFuente( $fuente )
    {
        $data["intId_fte"] = $fuente->idFuente;
        $data["intId_tf"] = $fuente->idTipoFuente;
        $data["intID_ut"] = (int) $fuente->idUnidadTerritorial;
        $data["strDescripcion_fte"] = $fuente->descripcion;
        $data["strObservaciones_fte"] = $fuente->observacion;
        $data["intVigencia_fte"] = $fuente->vigencia;
        $data["published"] = $fuente->published;
        if( $this->bind( $data ) ) {
            if( $this->save( $data ) ) {
                $idFuente = $this->intId_fte;
            }
        }
        return $idFuente;
    }

    /**
     * Recupera la informacion de una tema.
     * @param type $idTema
     * @return type
     */
    public function getFuenteTema( $idTema )
    {
        
    }

    public function getFuentesByTipo( $idTipo )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '  
                            tfu.intId_fte                   AS  idFuente,
                            tfu.intId_fte                   AS  nmbFuente,
                            tfu.intId_tf                    AS  idTipoFuente,
                            tfu.intId_tf                    AS  nmbTipoFuente,
                            tfu.intID_ut                    AS  idUnidadTerritorial,
                            upper(tfu.strDescripcion_fte)   AS  descripcion,
                            tfu.strObservaciones_fte        AS  observacion,
                            tfu.intVigencia_fte             AS  vigencia
                           ' );
            $query->from( '#__gc_fuente AS tfu' );
            $query->where( 'tfu.intId_tf = ' . $this->_db->quote( $idTipo ) );
            $query->where( 'tfu.published = 1' );
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

    /**
     *  Recupera una fuente dado el identificador de la fuente 
     * @param type $idFuente    Identificador de la fuente.
     */
    public function getFuente( $idFuente )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '  
                            tfu.intId_fte                   AS  idFuente,
                            tfu.intId_fte                   AS  nmbFuente,
                            tfu.intId_tf                    AS  idTipoFuente,
                            tfu.intId_tf                    AS  nmbTipoFuente,
                            tfu.intID_ut                    AS  idUnidadTerritorial,
                            tfu.strDescripcion_fte          AS  descripcion,
                            tfu.strObservaciones_fte        AS  observacion,
                            tfu.intVigencia_fte             AS  vigencia
                           ' );
            $query->from( '#__gc_fuente AS tfu' );
            $query->where( 'tfu.intId_fte = ' . $this->_db->quote( $idFuente ) );
            $query->where( 'tfu.published = 1' );
            $db->setQuery( $query );
            $db->query();
            $retval = ( $db->loadObject() ) ? $db->loadObject() : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.tema.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *  Valida se se uede elimnar un registro de fuentes de forma fisica o logica
     *  
     * @param type $id      ID del registro
     * @param type $op      1: logica, 2: fisica
     * @return type
     */
    public function validoEliminarFuente( $id, $op ){
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( 'intId_fl' );
            $query->from( '#__gc_fuente_legitimidad' );
            $query->where( 'intId_fte = ' .  (int)$id );
            if ( $op == 1) {
                $query->where( 'published = 1');
            }
            // Segunda sentencia para la union .
            $query2 = $db->getQuery(true);
            $query2->select('intId_fi');
            $query2->from('#__gc_fuente_incidencia');
            $query2->where('intId_fte = ' . (int)$id );
            if ( $op == 1) {
                $query2->where( 'published = 1');
            }
            // Tercera sentencia para la union .
            $query3 = $db->getQuery(true);
            $query3->select('intId_tf');
            $query3->from('#__gc_tema_fuente');
            $query3->where('intId_fte = ' . (int)$id );
            if ( $op == 1) {
                $query3->where( 'published = 1');
            }
            //  Realisa la union de selects y asinga el query
            $subunion = $db->getQuery(true);
            $subunion->union(array($query2, $query3));
            $queryAll = $query . ' ' .  $subunion->union;
            $db->setQuery( $queryAll );
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? false : true;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.fuente.log.php' );
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
            $query->delete( '#__gc_fuente' );
            $query->where( 'intId_fte = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.fuente.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     *  Elimina de forma logica un registro de fuentes
     * 
     * @param type $id
     * @return type
     */
    public function eliminadoLogico( $id ) 
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->update( '#__gc_fuente' );
            $query->set( 'published = 0' );
            $query->where( 'intId_fte = ' .  (int)$id );
            $db->setQuery( $query );
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.table.fuente.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
}