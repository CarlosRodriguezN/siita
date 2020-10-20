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
class jTableIndicadorVariable extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__ind_indicador_variables', 'intId_iv', $db );
    }

    /**
     * 
     * Gestiona el registro de la Relacione entre un Indicador y las variables 
     * que forman parte de la formula de calculo
     * 
     * @param Object $dtaVariable       Datos de la variable
     * @param Int $idIndicador          Identificador del Indicador
     * @param Int $idVariable           Identificador de la Variable con la se 
     *                                  va a asociar el indicador
     * 
     * @return type
     * @throws Exception
     * 
     */
    public function registroIndicadorVariable( $dtaVariable, $idIndicador, $idVariable )
    {
        if ( $dtaVariable->published == 1 ){
            $dtaIndVariable["intId_iv"] = ( empty( $dtaVariable->idIndVariable ) )
                    ? 0
                    : $dtaVariable->idIndVariable;

            $dtaIndVariable["intCodigo_ind"] = $idIndicador;
            $dtaIndVariable["intIdVariable_var"] = $idVariable;
            $dtaIndVariable["intIdTpoVariable_iv"] = $dtaVariable->idTpoElemento;

            $dtaIndVariable["dcmFactorPonderacion_iv"] = ( is_numeric( $dtaVariable->factorPonderacion ) )
                    ? $dtaVariable->factorPonderacion
                    : 1;

            if ( empty( $dtaVariable->idIndVariable ) ){
                $dtaIndVariable["dteFechaRegistro_iv"] = date( "Y-m-d H:i:s" );
            } else{
                $dtaIndVariable["dteFechaModificacion_iv"] = date( "Y-m-d H:i:s" );
            }

            if ( !$this->save( $dtaIndVariable ) ){
                echo $this->getError();
                exit;
            }
        } else{
            //  Borro la asociacion entre el indicador y la variable
            $this->delete( $dtaVariable->idIndVariable );
        }

        return $this->intId_iv;
    }

    public function getLstVariablesPorIndicador( $idIndicador )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intId_iv             AS idIndVariable, 
                                t1.intIdVariable_var    AS idVariable,
                                t2.intId_vugr           AS idVUGR' );
            $query->from( '#__ind_indicador_variables t1' );
            $query->join( 'INNER', '#__ind_variable_undGestion_responsable t2 ON t1.intId_iv = t2.intId_iv' );
            $query->where( 't1.intCodigo_ind = ' . $idIndicador );

            $db->setQuery( (string)$query );
            $db->query();

            $lstIndVariables = ( $db->getNumRows() > 0 )
                    ? $db->loadObjectList()
                    : array ();

            return $lstIndVariables;
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorno los elementos que forman parte de la formula de un indicador
     * 
     * @param int $idIndicador      Identificador del Indicador
     * @param int $idEntFnc         Identificador de la Entidad del Funcionario
     * 
     * @return object
     * 
     */
    public function getElementosIndicador( $idIndicador, $fchInicio, $fchFin )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   DISTINCT 
                                t1.intId_iv             AS idIndVariable,
                                t1.intIdVariable_var	AS idVariable,
                                t1.intIdTpoVariable_iv	AS idTpoElemento,
                                t1.intIdVariable_var	AS idElemento' );
            $query->from( '#__ind_indicador_variables t1' );
            $query->join( 'INNER', '#__ind_indVariable_indicador t2 ON t1.intId_iv = t2.intId_iv' );
            $query->join( 'INNER', '#__ind_indicador_entidad t3 ON t2.intCodigo_ind = t3.intCodigo_ind' );
            $query->where( 't2.intCodigo_ind = ' . $idIndicador );

            if ( !is_null( $fchInicio ) && !is_null( $fchFin ) ){
                $query->where( 't3.dteHorizonteFchInicio_indEnt >= "' . $fchInicio . '"' );
                $query->where( 't3.dteHorizonteFchFin_indEnt <= "' . $fchFin . '"' );
            }

            $db->setQuery( (string)$query );
            $db->query();

            $lstIndVariables = ( $db->getNumRows() > 0 )
                    ? $db->loadObjectList()
                    : array ();

            return $lstIndVariables;
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorno una lista de indicadores que tiene como responsable un determinado Funcionario
     * 
     * @param int $idIndicador      Identificador del Indicador
     * @param int $idEntFnc         Identificador de Entidad del funcionario
     * 
     * @return Object
     */
    public function getElementosIndicadorPorFuncionario( $idIndicador, $idEntFnc )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   DISTINCT 
                                    t4.intId_iv             AS idIndVariable,
                                    t2.intIdTpoVariable_iv  AS idTpoElemento,
                                    t2.intIdVariable_var    AS idElemento' );
            $query->from( '#__ind_indicador t1' );
            $query->join( 'INNER', '#__ind_indicador_variables t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
            $query->join( 'INNER', '#__ind_variable_funcionario_responsable t3 ON t2.intId_iv = t3.intId_iv' );
            $query->join( 'INNER', '#__ind_indVariable_indicador t4 ON t2.intId_iv = t4.intId_iv' );
            $query->join( 'INNER', '#__gen_ug_funcionario t5 ON t3.intId_ugf = t5.intId_ugf' );
            $query->join( 'INNER', '#__gen_funcionario t6 ON t5.intCodigo_fnc = t6.intCodigo_fnc' );
            $query->where( 't4.intCodigo_ind = ' . $idIndicador );
            $query->where( 't6.intIdentidad_ent = ' . $idEntFnc );

            $db->setQuery( (string)$query );
            $db->query();

            $lstIndVariables = ( $db->getNumRows() > 0 )
                    ? $db->loadObjectList()
                    : array ();

            return $lstIndVariables;
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorna Informacion de Elemento Tipo "Variable"
     * 
     * @param type $idElemento      Identificador del Elemento
     * @return type
     */
    public function getElementoVariable( $idIndicador, $idElemento )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intId_iv                                                     AS idIndVariable, 
                                    t1.intCodigo_ind                                                AS idIndicador, 
                                    t1.intIdVariable_var                                            AS idVariable,
                                    t1.intIdTpoVariable_iv                                          AS idTpoElemento,
                                    t1.intIdVariable_var                                            AS idElemento,
                                    0                                                               AS idTpoEntidad,
                                    0                                                               AS idEntidad,
                                    IF( t3.strNombre_var IS NULL, "", UPPER( t3.strNombre_var ) )   AS nombre, 
                                    UPPER( t3.strAliasVariable_var )                                AS alias,
                                    IF( t3.strDescripcion_var IS NULL, "", t3.strDescripcion_var )  AS descripcion,
                                    t4.intId_tum                                                    AS idTpoUM,
                                    t3.intCodigo_unimed                                             AS idUndMedida, 
                                    t4.strDescripcion_unimed                                        AS undMedida,
                                    t3.inpCodigo_unianl                                             AS idUndAnalisis,
                                    t5.strDescripcion_unianl                                        AS undAnalisis,
                                    t6.intId_vugr                                                   AS idVUGR,
                                    t6.intCodigo_ug                                                 AS idUGResponsable,
                                    t7.intId_vfr                                                    AS idVFR,
                                    t7.intId_ugf                                                    AS idFunResponsable,
                                    t8.intCodigo_ug                                                 AS idUGFuncionario,
                                    t2.intIdMetodoCalculo_ind                                       AS idMetodoCalculo,
                                    1                                                               AS published' );
            $query->from( '#__ind_indicador_variables t1' );

            $query->join( 'INNER', '#__ind_indicador t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
            $query->join( 'INNER', '#__gen_variables t3 ON t1.intIdVariable_var = t3.intIdVariable_var' );
            $query->join( 'LEFT', '#__gen_unidad_medida t4 ON t3.intCodigo_unimed = t4.intCodigo_unimed' );
            $query->join( 'LEFT', '#__gen_unidad_analisis t5 ON t5.inpCodigo_unianl = t3.inpCodigo_unianl' );
            $query->join( 'LEFT', '#__ind_variable_undGestion_responsable t6 ON t6.intId_iv = t1.intId_iv AND t6.intVigencia_vugr = 1' );
            $query->join( 'LEFT', '#__ind_variable_funcionario_responsable t7 ON t7.intId_iv = t1.intId_iv AND t7.intVigencia_vfr = 1 ' );
            $query->join( 'LEFT', '#__gen_ug_funcionario t8 ON t8.intId_ugf = t7.intId_ugf' );

            $query->where( 't1.intIdTpoVariable_iv = 1' );
            $query->where( 't1.intIdVariable_var = ' . $idElemento );

            $db->setQuery( (string)$query );
            $db->query();

            $lstIndVariables = ( $db->getNumRows() > 0 )
                    ? $db->loadObject()
                    : array ();

            return $lstIndVariables;
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorna Informacion del Elemento de Tipo Indicador
     * 
     * @param type $idElemento      Identificador del Elemento
     * 
     * @return type
     * 
     */
    public function getElementoIndicador( $idIndicador, $idElemento )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t2.intId_iv                 AS idIndVariable,
                                t2.intCodigo_ind            AS idIndicador,
                                t2.intIdTpoVariable_iv      AS idTpoElemento,
                                t2.intIdVariable_var        AS idElemento,

                                (   SELECT ta.intIdentidad_ent 
                                    FROM tb_ind_indicador_entidad ta 
                                    WHERE ta.intCodigo_ind = t2.intIdVariable_var LIMIT 1 ) AS idEntidad,

                                (   SELECT tc.intIdtipoentidad_te 
                                    FROM tb_ind_indicador_entidad tb 
                                    JOIN tb_gen_entidad tc ON tb.intIdentidad_ent = tc.intIdentidad_ent 
                                    WHERE tb.intCodigo_ind = t2.intIdVariable_var LIMIT 1 ) AS idTpoEntidad,

                                UPPER( t1.strNombre_ind )   AS nombre, 
                                t1.strDescripcion_ind       AS descripcion,
                                t3.intId_tum                AS idTpoUM,
                                t1.intCodigo_unimed         AS idUndMedida,
                                t3.strDescripcion_unimed    AS undMedida,
                                t1.inpCodigo_unianl         AS idUndAnalisis,
                                t4.strDescripcion_unianl    AS undAnalisis,
                                t2.dcmFactorPonderacion_iv  AS factorPonderacion,
                                t6.intId_ugr                AS idUGResponsable,
                                t7.intId_fgr                AS idFunResponsable,
                                t8.intCodigo_ug             AS idUGFuncionario,
                                1                           AS published' );
            $query->from( '#__ind_indicador t1' );
            $query->join( 'INNER', '#__ind_indicador_variables t2 ON t1.intCodigo_ind = t2.intIdVariable_var' );
            $query->join( 'LEFT', '#__gen_unidad_medida t3 ON t1.intCodigo_unimed = t3.intCodigo_unimed' );
            $query->join( 'LEFT', '#__gen_unidad_analisis t4 ON t1.inpCodigo_unianl = t4.inpCodigo_unianl' );
            $query->join( 'INNER', '#__ind_indicador_entidad t5 ON t1.intCodigo_ind = t5.intCodigo_ind' );
            $query->join( 'LEFT', '#__ind_ug_responsable t6 ON t5.intIdIndEntidad = t6.intIdIndEntidad' );
            $query->join( 'LEFT', '#__ind_funcionario_responsable t7 ON t5.intIdIndEntidad = t7.intIdIndEntidad' );
            $query->join( 'LEFT', '#__gen_ug_funcionario t8 ON t7.intId_ugf = t8.intId_ugf' );
            $query->join( 'INNER', '#__ind_indVariable_indicador t9 ON t2.intId_iv = t9.intId_iv' );

            $query->where( 't2.intIdTpoVariable_iv = 2' );
            $query->where( 't9.intCodigo_ind = ' . $idIndicador );
            $query->where( 't2.intIdVariable_var = ' . $idElemento );

            $db->setQuery( (string)$query );
            $db->query();

            $lstIndVariables = ( $db->getNumRows() > 0 )
                    ? $db->loadObject()
                    : array ();

            return $lstIndVariables;
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorno Informacion de las variables asociadas a un indicador 
     * registrado como plantilla
     * 
     * @param type $idIndPlantilla     Identificador de la plantilla
     * 
     */
    public function getLstIndVarPlantilla( $idIndPlantilla )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intIdVariable_pv                                         AS idVariable,
                                t1.strNombre_pv                                             AS nombre, 
                                IF( t1.strDescripcion_pv IS NULL, "", t1.strDescripcion_pv )AS descripcion, 
                                t3.intId_tum                                                AS idTpoUM,
                                t1.intCodigo_unimed                                         AS idUndMedida, 
                                t3.strDescripcion_unimed                                    AS undMedida,
                                t1.inpCodigo_unianl                                         AS idUndAnalisis,
                                t4.strDescripcion_unianl                                    AS undAnalisis,
                                1                                                           AS published' );
            $query->from( '#__ptlla_variables t1' );
            $query->join( 'INNER', '#__ptlla_indicador_variables t2 ON t1.intIdVariable_pv = t2.intIdVariable_pv' );
            $query->join( 'INNER', '#__gen_unidad_medida t3 ON t3.intCodigo_unimed = t1.intCodigo_unimed' );
            $query->join( 'INNER', '#__gen_unidad_analisis t4 ON t4.inpCodigo_unianl = t1.inpCodigo_unianl' );
            $query->where( 't2.intId_pi = ' . $idIndPlantilla );

            $db->setQuery( (string)$query );
            $db->query();

            $lstIndVariables = ( $db->getNumRows() > 0 )
                    ? $db->loadObjectList()
                    : array ();

            return $lstIndVariables;
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    public function deleteIndicadorVariable( $idIndicador )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->delete( '#__ind_indicador_variables' );
            $query->where( 'intCodigo_ind = ' . $idIndicador );

            $db->setQuery( (string)$query );
            $db->query();

            return $db->getNumRows();
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorna Informacion de Elemento Tipo "Variable"
     * 
     * @param int $idIndPtlla      Identificador del indicador
     * @return type
     */
    public function getVariablesPlla( $idIndPtlla )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   DISTINCT
                                    t2.intIdVariable_pv 											AS idVariable,
                                    IF( t2.strNombre_pv IS NULL, "", UPPER( t2.strNombre_pv ) )     AS nombre, 
                                    IF( t2.strDescripcion_pv IS NULL, "", t2.strDescripcion_pv )    AS descripcion,

                                    t3.intId_tum                                                    AS idTpoUM,
                                    t3.intCodigo_unimed                                             AS idUndMedida, 
                                    t3.strDescripcion_unimed                                        AS undMedida,

                                    t4.inpCodigo_unianl                                             AS idUndAnalisis,
                                    t4.strDescripcion_unianl                                        AS undAnalisis,
                                    1                                                               AS idTpoElemento,
                                    1                                                               AS published' );

            $query->from( ' #__ptlla_indicador_variables t1' );
            $query->join( 'INNER', '#__ptlla_variables t2 ON t1.intIdVariable_pv = t2.intIdVariable_pv' );
            $query->join( 'INNER', '#__gen_unidad_medida t3 ON t2.intCodigo_unimed = t3.intCodigo_unimed' );
            $query->join( 'INNER', '#__gen_unidad_analisis t4 ON t2.inpCodigo_unianl = t4.inpCodigo_unianl' );            
            $query->where( 't1.intId_pi = '. $idIndPtlla );

            $db->setQuery( (string)$query );
            $db->query();

            $lstIndVariables = ( $db->getNumRows() > 0 )
                    ? $db->loadObjectList()
                    : array ();

            return $lstIndVariables;
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    
    public function getLstVariablesIndPtlla( $idIndPtlla )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '' );
            $query->from( '' );
            $query->join( 'INNER', '' );
            $query->join( 'INNER', '' );
            $query->join( 'INNER', '' );
            $query->where( '' );
            
            $db->setQuery( (string)$query );
            $db->query();

            $lstIndVariables = ( $db->getNumRows() > 0 )
                    ? $db->loadObjectList()
                    : array ();

            return $lstIndVariables;
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    
    public function __destruct()
    {
        return;
    }

}
