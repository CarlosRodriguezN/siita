<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.tablenested' );

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class jTableEntidad extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gen_entidad', 'intIdentidad_ent', $db );
    }

    
    public function crearEntidad( $idTpoEntidad )
    {
        $dtaEntidad[ "intIdtipoentidad_te" ] = $idTpoEntidad;
        
        if( !$this->save( $dtaEntidad ) ){
            echo $this->getError();
            exit;
        }

        return $this->intIdentidad_ent;
    }
    
    
    /**
     * 
     * Gestiona el Registro de una Entidad
     * 
     * @param type $idTpoEntidad    Tipo de Entidad ( PEI, POA, Programa, Proyecto, Contrato )
     * 
     * @return string
     * 
     */
    function saveEntidad( $idEntidad, $idTpoEntidad, $urlTableU = '' )
    {
        $dtaEntidad[ "intIdentidad_ent" ] = $idEntidad;
        $dtaEntidad[ "intIdtipoentidad_te" ] = $idTpoEntidad;
        $dtaEntidad[ "strUrlTableU_ent" ] = $urlTableU;
        if( !$this->save( $dtaEntidad ) ){
            echo $this->getError();
            exit;
        }

        return $this->intIdentidad_ent;
    }

    /**
     * 
     * Actualiza el campo entidad la tabla entidad
     * 
     * @param type $idEntidad
     * @param type $urlTableU
     * @return type
     */
    function updUrlEntidad( $idEntidad, $urlTableU = '' )
    {
        $dtaEntidad[ "intIdentidad_ent" ] = $idEntidad;
        $dtaEntidad[ "strUrlTableU_ent" ] = $urlTableU;

        if( !$this->save( $dtaEntidad ) ){
            echo $this->getError();
            exit;
        }

        return $this->intIdentidad_ent;
    }

    /**
     *  Recupera la informacion de una entidad.
     * @param int $idEntidad    Identificador de la Entidad
     * @return JSON
     */
    public function getEntidad( $idEntidad )
    {
        try{

            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 'intIdentidad_ent        AS idEntidad,
                            intIdtipoentidad_te     AS idTipoEntidad,
                            strUrlTableU_ent        AS urlTableU' );
            $query->from( '#__gen_entidad AS t1' );
            $query->where( 't1.intIdentidad_ent = ' . $idEntidad );

            $db->setQuery( (string)$query );
            $db->query();
            
            $retval = ( $db->getNumRows() ) ? $db->loadObject()
                                            : false;
            
            return $retval;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Verifico la existencia de una variable asociada a un determinado indicador - entidad
     * 
     * @param type $idIndEntidad    Identificador Indicador - Entidad
     * 
     * @return type
     * 
     */
    public function getEntidades( $idTpoEntidad )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 'COUNT( t1.intIdVariableIndicador_var ) AS cantidad' );
            $query->from( '#__ind_variables_indicador t1' );
            $query->where( 't1.intIdIndEntidad = ' . $idIndEntidad );

            $db->setQuery( (string)$query );
            $db->query();

            $existe = ( (int)$db->loadObject()->cantidad > 0 ) ? true : false;

            return $existe;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorna informacion de Programas
     * 
     * @param type $idTipoEntidad   Identificador del tipo de programa
     * 
     * @return type
     * 
     */
    public function getLstProgramas()
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   ta.intIdEntidad_ent AS id, 
                                UPPER( ta.strNombre_prg ) AS nombre' );
            $query->from( '#__pfr_programa ta' );
            $query->where( 'ta.intIdEntidad_ent IN (    SELECT tb.intIdentidad_ent
                                                        FROM tb_gen_entidad tb
                                                        WHERE tb.intIdtipoentidad_te = 1 )' );
            $query->order( 'ta.strNombre_prg' );

            $db->setQuery( (string)$query );
            $db->query();

            $rst = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $rst;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Gestiona el Retorno de Entidades de tipo proyecto
     * 
     * @return type
     */
    public function getLstProyectos()
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   ta.intIdEntidad_ent AS id, 
                                UPPER( ta.strNombre_pry ) AS nombre' );
            $query->from( '#__pfr_proyecto_frm ta' );
            $query->where( 'ta.intIdEntidad_ent IN (    SELECT tb.intIdentidad_ent
                                                        FROM tb_gen_entidad tb
                                                        WHERE tb.intIdtipoentidad_te = 2 )' );
            $query->order( 'ta.strNombre_pry' );

            $db->setQuery( (string)$query );
            $db->query();

            $rst = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $rst;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Gestiona el retorno de una lista de contratos asociados
     * 
     * @return type
     */
    public function getLstContratos()
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   ta.intIdentidad_ent AS id, 
                                UPPER( ta.strDescripcion_ctr ) AS nombre' );
            $query->from( '#__ctr_contrato ta' );
            $query->where( 'ta.intIdentidad_ent IN (SELECT tb.intIdentidad_ent
                                                    FROM tb_gen_entidad tb
                                                    WHERE tb.intIdtipoentidad_te = 3 )' );
            $query->order( 'ta.strDescripcion_ctr' );
            
            $db->setQuery( (string)$query );
            $db->query();

            $rst = ( $db->getNumRows() > 0 )? $db->loadObjectList() 
                                            : array();

            return $rst;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    public function getLstObjetivosPEI()
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t2.intIdentidad_ent     AS id, 
                                t3.strDescripcion_ob    AS nombre' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->join( 'INNER', '#__pln_plan_objetivo t2 ON t1.intId_pi = t2.intId_pi' );
            $query->join( 'INNER', '#__pln_objetivo_institucion t3 ON t2.intId_ob = t3.intId_ob' );
            $query->where( 't1.intId_tpoPlan = 1' );
            $query->where( 't1.blnVigente_pi = 1' );
            $query->order( 't3.strDescripcion_ob' );

            $db->setQuery( (string)$query );
            $db->query();

            $rst = ( $db->getNumRows() > 0 )? $db->loadObjectList() 
                                            : array();

            return $rst;
        }catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
}