<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

//  Import Joomla JUser Library
jimport( 'joomla.user.user' );

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class ProgramaTableIndicador extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__ind_indicador', 'intCodigo_ind', $db );
    }

    function getIndicadorById( $idIndicador )
    {
        try {
            $db = &JFactory::getDBO();
            $db->getQuery( true );
            $query = $db->getQuery( true );
            $query->select( '*' );
            $query->from( '#__ind_indicador' );
            $query->where( "intCodigo_ind =" . $idIndicador );
            $db->setQuery( $query );
            $db->query();
            $retval = ($db->loadObject()) ? $db->loadObject() : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_adminmapa.table.entidad.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorno datos de Indicadores
     * 
     * @param type $idEntidad  Identificador del entidad
     * @return type
     */
    public function getDataIndicadores( $idEntidad )
    {     
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   DISTINCT t1.intIdIndEntidad AS idIndEntidad,
                                t1.intCodigo_ind            AS idIndicador,
                                t6.intId_td                 AS idEnfoque,
                                t6.strDescripcion_td        AS enfoque,
                                t3.intId_dim                AS idDimension,
                                t4.strDescripcion_dim       AS dimension,
                                t2.strNombre_ind            AS nombre,
                                t2.strDescripcion_ind       AS descripcion,
                                t2.inpCodigo_unianl         AS idUndAnalisis,
                                t5.strAlias_unianl          AS alias,
                                t7.intId_tum                AS idTpoUndMedida,                                
                                t2.intCodigo_unimed         AS idUndMedida,
                                t7.strDescripcion_unimed    AS undMedida,                                
                                t1.intcodigo_per            AS idPeriodicidad,
                                IF( t2.strFormula_ind IS NULL, "", t2.strFormula_ind )  AS formula,
                                t1.dcmValor_ind         AS valor' );
            $query->from( '#__ind_indicador_entidad t1' );
            $query->join( 'INNER', '#__ind_indicador t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
            $query->join( 'INNER', '#__ind_dimension_indicador t3 ON t2.intCodigo_ind = t3.intCodigo_ind' );
            $query->join( 'INNER', '#__gen_dimension t4 ON t4.intId_dim = t3.intId_dim' );
            $query->join( 'INNER', '#__gen_unidad_analisis t5 ON t5.inpCodigo_unianl = t2.inpCodigo_unianl' );
            $query->join( 'INNER', '#__gen_enfoque t6 ON t6.intId_td = t4.intId_td' );
            $query->join( 'INNER', '#__gen_unidad_medida t7 ON t7.intCodigo_unimed = t2.intCodigo_unimed' );
            $query->where( 't1.intIdentidad_ent = ' . $idEntidad );

            $db->setQuery( (string) $query );
            $db->query();

            $dtaIndicadores = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() 
                                                        : FALSE;

            return $dtaIndicadores;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorna una lista de Enfoque de acuerdo a un determinado tipo
     * 
     * @param type $idTipo  Identificador del tipo de Enfoque de Igualdad
     * 
     * @return type
     * 
     */
    public function getLstTiposEnfoqueIgualdad( $idTipo )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intId_dim AS id, 
                                t1.strDescripcion_dim AS nombre' );
            $query->from( '#__gen_dimension t1' );
            $query->where( 't1.intId_td = ' . $idTipo );
            $query->order( 't1.strDescripcion_dim' );

            $db->setQuery( (string) $query );
            $db->query();

            $lstEnfIgualdad = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : FALSE;

            return $lstEnfIgualdad;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *  Retorno una lista de indicadores pertenecen a un enfoque de Atencion Prioritaria
     */
    public function lstIdIndicadoresGap()
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 't1.intId_dim AS idDimension' );
            $query->from( '#__gen_dimension t1' );
            $query->where( 't1.intId_td = 7' );

            $db->setQuery( (string) $query );
            $db->query();

            $lstIndGAP = false;

            if( $db->getNumRows() > 0 ) {
                $lstGap = $db->loadObjectList();
                foreach( $lstGap as $gap ) {
                    $lstIndGAP[] = $gap->idDimension;
                }
            }

            return $lstIndGAP;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorna lista (array) dimensiones de enfoque de Igualdad
     * 
     * @return type
     */
    public function lstIdIndEIgualdad()
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 't1.intId_dim AS idDimension' );
            $query->from( '#__gen_dimension t1' );
            $query->where( 't1.intId_td != 5' );
            $query->where( 't1.intId_td IN (    SELECT t2.intId_td
                                                FROM #__gen_enfoque t2
                                                WHERE t2.intPadre_td =1 )' );

            $db->setQuery( (string) $query );
            $db->query();

            $lstIndEI = false;

            if( $db->getNumRows() > 0 ) {
                $lstEI = $db->loadObjectList();
                foreach( $lstEI as $ei ) {
                    $lstIndEI[] = $ei->idDimension;
                }
            }

            return $lstIndEI;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * @return type
     */
    public function lstIdIndEEcorae()
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 't1.intId_dim AS idDimension' );
            $query->from( '#__gen_dimension t1' );
            $query->where( 't1.intId_td = 5' );

            $db->setQuery( (string) $query );
            $db->query();

            $lstIndEE = false;

            if( $db->getNumRows() > 0 ) {
                $lstEE = $db->loadObjectList();
                foreach( $lstEE as $ee ) {
                    $lstIndEE[] = $ee->idDimension;
                }
            }

            return $lstIndEE;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /////////////////////////////////////////////////////////
    //  REGISTRO DE INDICADORES
    /////////////////////////////////////////////////////////

    /**
     * 
     * Gestiona el Registro de un indicador
     * 
     * @param int $idIndicador     Identificador del Indicador
     * @param int $idTpoIndicador  Identificador del Tipo de Indicador
     * @param int $idClase         Identificador de la Clase de Indicador
     * @param int $idUndMedida     Identificador de la Unidad de Medida
     * @param int $idUndAnalisis   Identificador de la Unidad de Analisis
     * @param String $nombre          Nombre del indicador
     * @param String $descripcion     Descripcion del indicador
     * 
     * @return int Identificador de Registro del Indicador
     * 
     * @throws Exception
     */
    public function registroDtaIndicador( $idIndicador, $idTpoIndicador, $idClase, $idUndMedida, $idUndAnalisis, $nombre = '', $descripcion = '', $formula = '' )
    {
        $dtaIndicador["intCodigo_ind"] = $idIndicador;
        $dtaIndicador["intCodigo_indPadre"] = null;
        $dtaIndicador["intCodigoTipo_ind"] = $idTpoIndicador;
        $dtaIndicador["inpCodigo_claseind"] = $idClase;
        $dtaIndicador["intCodigo_unimed"] = $idUndMedida;
        $dtaIndicador["inpCodigo_unianl"] = $idUndAnalisis;
        $dtaIndicador["strNombre_ind"] = $nombre;
        $dtaIndicador["strDescripcion_ind"] = $descripcion;
        $dtaIndicador["strFormula_ind"] = $formula;

        if( $idIndicador == 0 ) {
            $dtaIndicador["dteFechaRegistro_ind"] = date( "Y-m-d H:i:s" );
        }

        $dtaIndicador["dteFechaModificacion_ind"] = date( "Y-m-d H:i:s" );

        if( !$this->save( $dtaIndicador ) ) {
            throw new Exception( JText::_( 'COM_PROYECTOS_REGISTRO_INDICADORES' ) );
        }

        return $this->intCodigo_ind;
    }

    /**
     * 
     * Gestiono el registro de lineas base de un deteminado indicador
     * 
     * @param type $idIndicador     Identificador del indicador
     * @param type $dtaLineaBase    Datos de la linea base a registrar
     * 
     */
    public function registroLineaBase( $idIndicador, $dtaLineaBase )
    {
        //  Borro las lineas base de un determinado indicador
        if( (int) $this->_delLineasBase( $idIndicador ) >= 0 ) {
            //  Registro las nuevas lineas base pertenecientes a un determinado indicador
            $this->_registroLineasBase( $idIndicador, $dtaLineaBase );
        }
    }

    /**
     * 
     * Elimino las lineas base de un determinado indicador
     * 
     * @param type $idIndicador     Identificador de un determinado indicador
     * 
     * @return type
     * 
     */
    private function _delLineasBase( $idIndicador )
    {
        try {

            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->delete( '#__ind_linea_base_indicador ' );
            $query->where( 'intCodigo_ind = ' . $idIndicador );

            $db->setQuery( (string) $query );
            $db->query();
            
            return $db->getAffectedRows();
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    private function _registroLineasBase( $idIndicador, $dtaLineaBase )
    {
        try {
            $db = JFactory::getDBO();

            $sql = 'INSERT INTO #__ind_linea_base_indicador( intCodigo_lbind, intCodigo_ind ) VALUES';

            foreach( $dtaLineaBase as $lineaBase ) {
                $sql .= '( '. $lineaBase .', '. $idIndicador .' ),';
            }
            
            $db->setQuery( rtrim( $sql, ',' ) );
            $db->query();

            return $db->getAffectedRows();
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorna una lista de lineas base relacionadas a un determinado indicador
     * 
     * @param type $idIndicador     Identificador de Indicador
     * 
     * @return type
     * 
     */
    public function getLstLineasBase( $idIndicador )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 't1.intCodigo_lbind as idLineaBase' );
            $query->from( '#__ind_linea_base_indicador t1' );
            $query->where( 't1.intCodigo_ind = ' . $idIndicador );

            $db->setQuery( (string) $query );
            $db->query();

            $lstLB = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : false;

            return $lstLB;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
}