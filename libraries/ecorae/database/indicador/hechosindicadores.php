<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.tablenested');
 
/**
 * 
 * Clase que gestiona informacion de Indicadores
 * 
 */

class jTableHechosIndicador extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__hechos_indicadores', 'idIndicador', $db );
    }
    
    /**
     * 
     * Retorno datos de Indicadores de  FIJOS - ECONOMICOS - FINANCIEROS - 
     *                                  BENEFICIARIOS DIRECTOS - INDIRECTOS
     * 
     * @param int $idEntidad  Identificador del entidad del programa, proyecto, contrato
     * 
     * @return type
     */
    public function getDataIndicadores()
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   DISTINCT t1.intIdIndEntidad                                                                             AS idIndEntidad,
                                t1.intCodigo_ind                                                                                        AS idIndicador,
                                t2.strNombre_ind                                                                                        AS nombreIndicador,
                                t5.strAlias_unianl                                                                                      AS modeloIndicador,
                                t1.dcmValor_ind                                                                                         AS umbral,
                                t1.intTendencia_indEnt                                                                                  AS tendencia,
                                t2.strDescripcion_ind                                                                                   AS descripcion,
                                t2.inpCodigo_unianl                                                                                     AS idUndAnalisis,
                                t5.strDescripcion_unianl                                                                                AS undAnalisis,
                                t7.intId_tum                                                                                            AS idTpoUndMedida,
                                t2.intCodigo_unimed                                                                                     AS idUndMedida,
                                t7.strDescripcion_unimed                                                                                AS undMedida,
                                t2.intCodigoTipo_ind                                                                                    AS idTpoIndicador,
                                IF( t2.strFormula_ind IS NULL, "", t2.strFormula_ind )                                                  AS formula,
                                IF( t1.dteHorizonteFchInicio_indEnt IS NULL, "0000-00-00", DATE( t1.dteHorizonteFchInicio_indEnt ) )    AS fchHorzMimimo,
                                IF( t1.dteHorizonteFchFin_indEnt IS NULL, "0000-00-00", DATE( t1.dteHorizonteFchFin_indEnt ) )          AS fchHorzMaximo,
                                t1.fltUmbralMinimo_indEnt                                                                               AS umbMinimo,
                                t1.fltUmbralMaximo_indEnt               AS umbMaximo,
                                t2.inpCodigo_claseind                   AS idClaseIndicador,
                                t1.intcodigo_per                        AS idFrcMonitoreo,
                                t6.intId_enfoque                        AS idEnfoque,
                                t6.strNombre_enfoque                    AS enfoque,
                                t3.intId_dim                            AS idDimension,
                                t4.strDescripcion_dim                   AS dimension,
                                t2.strDescripcion_ind                   AS descripcion,
                                t2.inpCategoria_ind                     AS categoriaInd,
                                IF( t8.intCodigo_ug IS NULL, 0, t8.intCodigo_ug ) AS idUGResponsable,
                                IF( t8.dteFechaInicio_ugr IS NULL, "0000-00-00", DATE( t8.dteFechaInicio_ugr ) )    AS fchInicioUG,
                                IF( t9.intId_ugf IS NULL, 0, t9.intId_ugf )     AS idResponsable,
                                IF( t9.dteFechaInicio_fgr IS NULL, "0000-00-00", DATE( t9.dteFechaInicio_fgr ) )    AS fchInicioFuncionario,
                                IF( t10.intCodigo_ug IS NULL, 0, t10.intCodigo_ug ) AS idResponsableUG, 
                                t2.intIdDimension_ind                   AS idDimIndicador,
                                t1.strAccesoTableu_indEnt               AS accesoTableu' );
            $query->from( '#__ind_indicador_entidad t1' );
            $query->join( '', '#__ind_indicador t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
            $query->leftJoin( '#__ind_dimension_indicador t3 ON t2.intCodigo_ind = t3.intCodigo_ind' );
            $query->leftJoin( '#__gen_dimension t4 ON t4.intId_dim = t3.intId_dim' );
            $query->leftJoin( '#__gen_unidad_analisis t5 ON t5.inpCodigo_unianl = t2.inpCodigo_unianl' );
            $query->leftJoin( '#__gen_enfoque t6 ON t6.intId_enfoque = t4.intId_enfoque' );
            $query->leftJoin( '#__gen_unidad_medida t7 ON t7.intCodigo_unimed = t2.intCodigo_unimed' );
            $query->leftJoin( '#__ind_ug_responsable t8 ON t8.intIdIndEntidad = t1.intIdIndEntidad AND t8.inpVigencia_ugr = 1' );
            $query->leftJoin( '#__ind_funcionario_responsable t9 ON t9.intIdIndEntidad = t1.intIdIndEntidad AND t9.intVigencia_fgr = 1' );
            $query->leftJoin( '#__gen_ug_funcionario t10 ON t10.intId_ugf = t9.intId_ugf' );
            
            $db->setQuery( (string)$query );
            $db->query();

            $dtaIndicadores = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() 
                                                        : array();

             return $dtaIndicadores;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Retorno datos de un determinado Indicador
     * 
     * @param type $idIndEntidad  Identificador del Indicador Entidad
     * 
     * @return type
     * 
     */
    public function getDataIndByEntidad( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   DISTINCT 
                                    t1.intIdIndEntidad          AS idIndEntidad,
                                    t1.intCodigo_ind            AS idIndicador,
                                    t2.strNombre_ind            AS nombreIndicador,
                                    t5.strAlias_unianl          AS modeloIndicador,
                                    t1.dcmValor_ind             AS umbral,
                                    t1.intTendencia_indEnt      AS tendencia,
                                    t2.strDescripcion_ind       AS descripcion,
                                    t2.inpCodigo_unianl         AS idUndAnalisis,
                                    t5.strDescripcion_unianl    AS undAnalisis,
                                    t7.intId_tum                AS idTpoUndMedida,
                                    t2.intCodigo_unimed         AS idUndMedida,
                                    t7.strDescripcion_unimed    AS undMedida,
                                    t2.intCodigoTipo_ind        AS idTpoIndicador,
                                    IF( t2.strFormula_ind IS NULL, "", t2.strFormula_ind )  AS formula,
                                    DATE( t1.dteHorizonteFchInicio_indEnt ) AS fchHorzMimimo,
                                    DATE( t1.dteHorizonteFchFin_indEnt )    AS fchHorzMaximo,
                                    t1.fltUmbralMinimo_indEnt       AS umbMinimo,
                                    t1.fltUmbralMaximo_indEnt       AS umbMaximo,
                                    t2.inpCodigo_claseind           AS idClaseIndicador,
                                    t1.intcodigo_per                AS idFrcMonitoreo,
                                    t11.strdescripcion_per          AS frcMonitoreo,
                                    t6.intId_enfoque                AS idEnfoque,
                                    t6.strNombre_enfoque            AS enfoque,
                                    t3.intId_dim                    AS idDimension,
                                    t4.strDescripcion_dim           AS dimension,
                                    t2.strDescripcion_ind           AS descripcion,
                                    t2.inpCategoria_ind             AS categoriaInd,
                                    t8.intCodigo_ug                 AS idUGResponsable,
                                    t12.strNombre_ug                AS UGResponsable,
                                    t9.intId_ugf                    AS idResponsable,
                                    CONCAT( t13.strApellido_fnc ," ", t13.strApellido_fnc ) AS responsable,
                                    t10.intCodigo_ug                AS idResponsableUG,
                                    t14.strNombre_ug                AS UGFuncionario,
                                    t2.intIdDimension_ind           AS idDimIndicador ' );
            
            $query->from( '#__ind_indicador_entidad t1' );
            $query->leftJoin( '#__ind_indicador t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
            $query->leftJoin( '#__ind_dimension_indicador t3 ON t2.intCodigo_ind = t3.intCodigo_ind' );
            $query->leftJoin( '#__gen_dimension t4 ON t4.intId_dim = t3.intId_dim' );
            $query->leftJoin( '#__gen_unidad_analisis t5 ON t5.inpCodigo_unianl = t2.inpCodigo_unianl' );
            $query->leftJoin( '#__gen_enfoque t6 ON t6.intId_enfoque = t4.intId_enfoque' );
            $query->leftJoin( '#__gen_unidad_medida t7 ON t7.intCodigo_unimed = t2.intCodigo_unimed' );
            $query->leftJoin( '#__ind_ug_responsable t8 ON t8.intIdIndEntidad = t1.intIdIndEntidad AND t8.inpVigencia_ugr = 1' );
            $query->leftJoin( '#__gen_unidad_gestion t12 ON t8.intCodigo_ug = t12.intCodigo_ug AND t8.inpVigencia_ugr = 1' );
            $query->leftJoin( '#__ind_funcionario_responsable t9 ON t9.intIdIndEntidad = t1.intIdIndEntidad AND t9.intVigencia_fgr = 1' );
            $query->leftJoin( '#__gen_ug_funcionario t10 ON t10.intId_ugf = t9.intId_ugf' );
            $query->leftJoin( '#__gen_funcionario t13 ON t10.intCodigo_fnc = t13.intCodigo_fnc' );
            $query->leftJoin( '#__gen_unidad_gestion t14 ON t10.intCodigo_ug = t14.intCodigo_ug' );
            $query->leftJoin( '#__gen_periodicidad t11 ON t1.intcodigo_per = t11.intcodigo_per' );
            
            $query->where( 't1.intIdIndEntidad = '. $idIndEntidad );
            
            $db->setQuery( (string)$query );
            $db->query();

            $dtaIndicadores = ( $db->getNumRows() > 0 ) ? $db->loadObject() 
                                                        : false;

            return $dtaIndicadores;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Retorna Lista de Otros Indicadores 
     * 
     * @param type $idProyecto  Identificador del Proyecto
     * @return type
     * 
     */
    public function getLstOtrosIndicadores( $idEntidad, $idEntFnc = null )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   DISTINCT t1.intIdIndEntidad                                                                 AS idIndEntidad,
                                t1.intCodigo_ind                                                                            AS idIndicador,
                                IF( t2.strNombre_ind IS NULL, "", t2.strNombre_ind )                                        AS nombreIndicador,
                                IF( t5.strAlias_unianl IS NULL, 0, t5.strAlias_unianl )                                     AS modeloIndicador,
                                IF( t1.dcmValor_ind IS NULL, 0, t1.dcmValor_ind)                                            AS umbral,
                                IF( t1.intTendencia_indEnt IS NULL, 0, t1.intTendencia_indEnt )                             AS tendencia,
                                IF( t2.strDescripcion_ind IS NULL, "", t2.strDescripcion_ind )                              AS descripcion,
                                IF( t2.inpCodigo_unianl IS NULL, "", t2.inpCodigo_unianl )                                  AS idUndAnalisis,
                                IF( t5.strDescripcion_unianl IS NULL, "", t5.strDescripcion_unianl )                        AS undAnalisis,
                                IF( t7.intId_tum IS NULL, 0, t7.intId_tum )                                                 AS idTpoUndMedida,
                                IF( t2.intCodigo_unimed IS NULL, 0, t2.intCodigo_unimed )                                   AS idUndMedida,
                                IF( t7.strDescripcion_unimed IS NULL, "", t7.strDescripcion_unimed )                        AS undMedida,
                                IF( t2.intCodigoTipo_ind IS NULL, 0, t2.intCodigoTipo_ind )                                 AS idTpoIndicador,
                                IF( t2.strFormula_ind IS NULL, "", t2.strFormula_ind )                                      AS formula,
                                IF( t1.dteHorizonteFchInicio_indEnt IS NULL, 0, DATE( t1.dteHorizonteFchInicio_indEnt ) )   AS fchHorzMimimo,
                                IF( t1.dteHorizonteFchFin_indEnt IS NULL, 0, DATE( t1.dteHorizonteFchFin_indEnt ) )         AS fchHorzMaximo,
                                IF( t1.fltUmbralMinimo_indEnt IS NULL, 0, t1.fltUmbralMinimo_indEnt )                       AS umbMinimo,
                                IF( t1.fltUmbralMaximo_indEnt IS NULL, 0, t1.fltUmbralMaximo_indEnt )                       AS umbMaximo,
                                IF( t2.inpCodigo_claseind IS NULL, 0, t2.inpCodigo_claseind )                               AS idClaseIndicador,
                                IF( t1.intcodigo_per IS NULL, 0, t1.intcodigo_per )                                         AS idFrcMonitoreo,
                                IF( t2.strDescripcion_ind IS NULL, "", t2.strDescripcion_ind )                              AS descripcion,
                                IF( t2.inpCategoria_ind IS NULL, "", t2.inpCategoria_ind )                                  AS categoriaInd,
                                IF( t1.intIdHorizonte_ind IS NULL, 0, t1.intIdHorizonte_ind )                               AS idHorizonte,
                                IF( t8.intId_ugr IS NULL, 0, t8.intId_ugr )                                                 AS idRegUGR,
                                IF( t8.intCodigo_ug IS NULL, 0, t8.intCodigo_ug )                                           AS idUGResponsable,
                                IF( t8.dteFechaInicio_ugr IS NULL, 0, DATE( t8.dteFechaInicio_ugr ) )                       AS fchInicioUG,
                                IF( t9.intId_fgr IS NULL, 0, t9.intId_fgr )                                                 AS idRegFR,
                                IF( t9.intId_ugf IS NULL, 0, t9.intId_ugf )                                                 AS idResponsable,
                                IF( t10.intCodigo_ug IS NULL, 0, t10.intCodigo_ug )                                         AS idResponsableUG,
                                IF( t9.dteFechaInicio_fgr IS NULL, 0, DATE( t9.dteFechaInicio_fgr ) )                       AS fchInicioFuncionario, 
                                IF( t2.intIdDimension_ind IS NULL, 0, t2.intIdDimension_ind )                               AS idDimIndicador,
                                IF( t1.strAccesoTableu_indEnt IS NULL, "", t1.strAccesoTableu_indEnt )                      AS accesoTableu,
                                t1.intIdIEPadre_indEnt                                                                      AS idIEPadre' );
            $query->from( '#__ind_indicador_entidad t1' );
            $query->join( 'INNER', '#__ind_indicador t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
            $query->leftJoin( '#__gen_unidad_analisis t5 ON t5.inpCodigo_unianl = t2.inpCodigo_unianl' );
            $query->leftJoin( '#__gen_unidad_medida t7 ON t7.intCodigo_unimed = t2.intCodigo_unimed' );
            $query->leftJoin( '#__ind_ug_responsable t8 ON t8.intIdIndEntidad = t1.intIdIndEntidad AND t8.inpVigencia_ugr = 1' );
            $query->leftJoin( '#__ind_funcionario_responsable t9 ON t9.intIdIndEntidad = t1.intIdIndEntidad AND t9.intVigencia_fgr = 1' );
            $query->leftJoin( '#__gen_ug_funcionario t10 ON t10.intId_ugf = t9.intId_ugf' );
            $query->leftJoin( '#__gen_funcionario t11 ON t10.intCodigo_fnc = t11.intCodigo_fnc' );
            $query->where( 't2.inpCategoria_ind = 0' );
            $query->where( 't1.intIdentidad_ent = '. $idEntidad );
            $query->where( 't1.intVigencia_indEnt = 1' );

            if( $idEntFnc ){
                $query->where( 't11.intIdentidad_ent = '. $idEntFnc );
            }

            $db->setQuery( (string)$query );
            $db->query();
            
            $lstOtrosInd = ( $db->getNumRows() > 0 )    ? $db->loadObjectList() 
                                                        : array();

            return $lstOtrosInd;

        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Retorna Lista de Otros Indicadores 
     * 
     * @param type $idProyecto  Identificador del Proyecto
     * @return type
     * 
     */
    public function getLstIndUG( $idEntidad, $idEntUG )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   DISTINCT t1.intIdIndEntidad             AS idIndEntidad,
                                t1.intCodigo_ind                        AS idIndicador,
                                IF( t2.strNombre_ind IS NULL, "", t2.strNombre_ind )                                        AS nombreIndicador,
                                IF( t5.strAlias_unianl IS NULL, 0, t5.strAlias_unianl )                                     AS modeloIndicador,
                                IF( t1.dcmValor_ind IS NULL, 0, t1.dcmValor_ind)                                            AS umbral,
                                IF( t1.intTendencia_indEnt IS NULL, 0, t1.intTendencia_indEnt )                             AS tendencia,
                                IF( t2.strDescripcion_ind IS NULL, "", t2.strDescripcion_ind )                              AS descripcion,
                                IF( t2.inpCodigo_unianl IS NULL, "", t2.inpCodigo_unianl )                                  AS idUndAnalisis,
                                IF( t5.strDescripcion_unianl IS NULL, "", t5.strDescripcion_unianl )                        AS undAnalisis,
                                IF( t7.intId_tum IS NULL, 0, t7.intId_tum )                                                 AS idTpoUndMedida,
                                IF( t2.intCodigo_unimed IS NULL, 0, t2.intCodigo_unimed )                                   AS idUndMedida,
                                IF( t7.strDescripcion_unimed IS NULL, "", t7.strDescripcion_unimed )                        AS undMedida,
                                IF( t2.intCodigoTipo_ind IS NULL, 0, t2.intCodigoTipo_ind )                                 AS idTpoIndicador,
                                IF( t2.strFormula_ind IS NULL, "", t2.strFormula_ind )                                      AS formula,
                                IF( t1.dteHorizonteFchInicio_indEnt IS NULL, 0, DATE( t1.dteHorizonteFchInicio_indEnt ) )   AS fchHorzMimimo,
                                IF( t1.dteHorizonteFchFin_indEnt IS NULL, 0, DATE( t1.dteHorizonteFchFin_indEnt ) )         AS fchHorzMaximo,
                                IF( t1.fltUmbralMinimo_indEnt IS NULL, 0, t1.fltUmbralMinimo_indEnt )                       AS umbMinimo,
                                IF( t1.fltUmbralMaximo_indEnt IS NULL, 0, t1.fltUmbralMaximo_indEnt )                       AS umbMaximo,
                                IF( t2.inpCodigo_claseind IS NULL, 0, t2.inpCodigo_claseind )                               AS idClaseIndicador,
                                IF( t1.intcodigo_per IS NULL, 0, t1.intcodigo_per )                                         AS idFrcMonitoreo,
                                IF( t2.strDescripcion_ind IS NULL, "", t2.strDescripcion_ind )                              AS descripcion,
                                IF( t2.inpCategoria_ind IS NULL, "", t2.inpCategoria_ind )                                  AS categoriaInd,
                                IF( t8.intCodigo_ug IS NULL, 0, t8.intCodigo_ug )                                           AS idUGResponsable,
                                IF( t8.dteFechaInicio_ugr IS NULL, 0, DATE( t8.dteFechaInicio_ugr ) )                       AS fchInicioUG,
                                IF( t9.intId_fgr IS NULL, 0, t9.intId_fgr )                                                 AS idRegFR,
                                IF( t9.intId_ugf IS NULL, 0, t9.intId_ugf )                                                 AS idResponsable,
                                IF( t10.intCodigo_ug IS NULL, 0, t10.intCodigo_ug )                                         AS idResponsableUG,
                                IF( t9.dteFechaInicio_fgr IS NULL, 0, DATE( t9.dteFechaInicio_fgr ) )                       AS fchInicioFuncionario, 
                                IF( t2.intIdDimension_ind IS NULL, 0, t2.intIdDimension_ind )                               AS idDimIndicador,
                                IF( t1.strAccesoTableu_indEnt IS NULL, "", t1.strAccesoTableu_indEnt )                      AS accesoTableu' );
            $query->from( '#__ind_indicador_entidad t1' );
            $query->join( 'INNER', '#__ind_indicador t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
            $query->leftJoin( '#__gen_unidad_analisis t5 ON t5.inpCodigo_unianl = t2.inpCodigo_unianl' );
            $query->leftJoin( '#__gen_unidad_medida t7 ON t7.intCodigo_unimed = t2.intCodigo_unimed' );
            $query->leftJoin( '#__ind_ug_responsable t8 ON t8.intIdIndEntidad = t1.intIdIndEntidad AND t8.inpVigencia_ugr = 1' );
            $query->leftJoin( '#__ind_funcionario_responsable t9 ON t9.intIdIndEntidad = t1.intIdIndEntidad AND t9.intVigencia_fgr = 1' );
            $query->leftJoin( '#__gen_ug_funcionario t10 ON t10.intId_ugf = t9.intId_ugf' );
            $query->leftJoin( '#__gen_unidad_gestion t11 ON t8.intCodigo_ug = t11.intCodigo_ug' );
            $query->where( 't2.inpCategoria_ind = 0' );
            $query->where( 't1.intIdentidad_ent = '. $idEntidad );
            $query->where( 't11.intIdentidad_ent = '. $idEntUG );
            $query->where( 't1.intVigencia_indEnt = 1' );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $lstOtrosInd = ( $db->getNumRows() > 0 )    ? $db->loadObjectList() 
                                                        : array();

            return $lstOtrosInd;

        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    /**
     * 
     * Retorna indicadores de tipo Contextos
     * 
     * @param type $idEntidad   Identificador de la entidad de tipo Contexto
     * 
     * @return type
     * 
     */
    public function getLstIndContexto( $idEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   DISTINCT 
                                    t1.intIdIndEntidad                                      AS idIndEntidad,
                                    t1.intCodigo_ind                                        AS idIndicador,
                                    t2.strNombre_ind                                        AS nombreIndicador,
                                    t5.strAlias_unianl                                      AS modeloIndicador,
                                    t1.dcmValor_ind                                         AS umbral,
                                    t1.intTendencia_indEnt                                  AS tendencia,
                                    t2.strDescripcion_ind                                   AS descripcion,
                                    t2.inpCodigo_unianl                                     AS idUndAnalisis,
                                    t5.strDescripcion_unianl                                AS undAnalisis,
                                    t7.intId_tum                                            AS idTpoUndMedida,
                                    t2.intCodigo_unimed                                     AS idUndMedida,
                                    t7.strDescripcion_unimed                                AS undMedida,
                                    t2.intCodigoTipo_ind                                    AS idTpoIndicador,
                                    IF( t2.strFormula_ind IS NULL, "", t2.strFormula_ind )  AS formula,
                                    t2.intIdMetodoCalculo_ind                               AS idMetodoCalculo,
                                    DATE( t1.dteHorizonteFchInicio_indEnt )                 AS fchHorzMimimo,
                                    DATE( t1.dteHorizonteFchFin_indEnt )                    AS fchHorzMaximo,
                                    t1.fltUmbralMinimo_indEnt                               AS umbMinimo,
                                    t1.fltUmbralMaximo_indEnt                               AS umbMaximo,
                                    t2.inpCodigo_claseind                                   AS idClaseIndicador,
                                    t1.intcodigo_per                                        AS idFrcMonitoreo,
                                    t2.strDescripcion_ind                                   AS descripcion,
                                    t2.inpCategoria_ind                                     AS categoriaInd,
                                    t8.intCodigo_ug                                         AS idUGResponsable,
                                    t9.intId_ugf                                            AS idResponsable,
                                    t10.intCodigo_ug                                        AS idResponsableUG,
                                    t2.intIdDimension_ind                                   AS idDimIndicador  ' );
            $query->from( '#__ind_indicador_entidad t1' );
            $query->join( 'INNER', '#__ind_indicador t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
            $query->leftJoin( '#__gen_unidad_analisis t5 ON t5.inpCodigo_unianl = t2.inpCodigo_unianl' );
            $query->leftJoin( '#__gen_unidad_medida t7 ON t7.intCodigo_unimed = t2.intCodigo_unimed' );
            $query->leftJoin( '#__ind_ug_responsable t8 ON t8.intIdIndEntidad = t1.intIdIndEntidad AND t8.inpVigencia_ugr = 1' );
            $query->leftJoin( '#__ind_funcionario_responsable t9 ON t9.intIdIndEntidad = t1.intIdIndEntidad AND t9.intVigencia_fgr = 1' );
            $query->leftJoin( '#__gen_ug_funcionario t10 ON t10.intId_ugf = t9.intId_ugf' );
            $query->where( 't2.inpCategoria_ind = 9' );
            $query->where( 't1.intIdentidad_ent = '. $idEntidad );

            $db->setQuery( (string)$query );
            $db->query();
            
            $lstIndContextos = ( $db->getNumRows() > 0 )? $db->loadObjectList() 
                                                        : FALSE;

            return $lstIndContextos;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
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
            $query = $db->getQuery(true);
            
            $query->select( '   t1.intId_dim AS id, 
                                t1.strDescripcion_dim AS nombre' );
            $query->from( '#__gen_dimension t1' );
            $query->where( 't1.intId_enfoque = '. $idTipo );
            $query->order( 't1.strDescripcion_dim' );

            $db->setQuery( (string)$query );
            $db->query();
            
            $lstEnfIgualdad = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() 
                                                        : FALSE;

             return $lstEnfIgualdad;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Retorna una lista de informacion de enfoques de igualdad
     * 
     * @param type $idProyecto  Identificador del Proyecto
     * @return type
     */
    public function getLstEnfoquesIgualdad( $idProyecto )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   ta.idDimension,
                                ta.descripcion,
                                ta.idTpoEnfoque,
                                ta.tipoEnfoque,
                                GROUP_CONCAT( if( ta.idEnfoque = 2, ta.valor, NULL ) ) AS MASCULINO,
                                GROUP_CONCAT( if( ta.idEnfoque = 1, ta.valor, NULL ) ) AS FEMENINO,
                                GROUP_CONCAT( if( ta.idEnfoque = 6, ta.valor, NULL ) ) AS TOTAL,
                                ta.published' );

            $query->from( ' (   SELECT  t1.intcodigo_ind AS idIndicador,
                                        t1.intId_dim AS idDimension,
                                        t3.intId_td AS idTpoEnfoque,
                                        t3.strDescripcion_td AS tipoEnfoque,
                                        t2.strDescripcion_dim AS descripcion,
                                        t1.intId_enfoque AS idEnfoque, 
                                        t1.dcmValor_ind AS valor,
                                        t1.published
                                FROM #__ind_indicador t1
                                JOIN #__gen_dimension t2 ON
                                t1.intId_dim = t2.intId_dim
                                JOIN #__gen_tipo_dimension t3 ON
                                t2.intId_td = t3.intId_td AND t3.intPadre_td = 1
                                WHERE t1.intCodigo_pry = '. $idProyecto .'
                                ORDER BY t2.strDescripcion_dim, t3.strDescripcion_td
                            ) ta' );
            
            $query->group( 'ta.idDimension' );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $lstIndGAP = ( $db->getNumRows() > 0 )  ? $db->loadObjectList() 
                                                    : FALSE;
            
             return $lstIndGAP;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    /**
     * 
     * Retorna una lista de informacion de enfoques de igualdad
     * 
     * @param type $idProyecto  Identificador del Proyecto
     * @return type
     */
    public function getLstEnfoqueEcorae( $idProyecto )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   ta.idDimension,
                                ta.descripcion,
                                GROUP_CONCAT( if( ta.idEnfoque = 2, ta.valor, NULL ) ) AS MASCULINO,
                                GROUP_CONCAT( if( ta.idEnfoque = 1, ta.valor, NULL ) ) AS FEMENINO,
                                GROUP_CONCAT( if( ta.idEnfoque = 6, ta.valor, NULL ) ) AS TOTAL,
                                ta.published' );

            $query->from( ' (   SELECT  t1.intcodigo_ind AS idIndicador,
                                        t1.intId_dim AS idDimension,
                                        t2.strDescripcion_dim AS descripcion,
                                        t1.intId_enfoque AS idEnfoque, 
                                        t1.dcmValor_ind AS valor,
                                        t1.published
                                FROM #__ind_indicador t1
                                JOIN #__gen_dimension t2 ON t1.intId_dim = t2.intId_dim AND t2.intId_td = 5
                                WHERE t1.intCodigo_pry = '. $idProyecto .'
                                ORDER BY t2.strDescripcion_dim
                            ) ta' );
            
            $query->group( 'ta.idDimension' );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $lstIndGAP = ( $db->getNumRows() > 0 )  ? $db->loadObjectList() 
                                                    : FALSE;
            
             return $lstIndGAP;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
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
            $query = $db->getQuery(true);
            
            $query->select( 't1.intId_dim AS idDimension' );
            $query->from( '#__gen_dimension t1' );
            $query->where( 't1.intId_td = 7' );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $lstIndGAP = false;
            
            if( $db->getNumRows() > 0 ){
                $lstGap = $db->loadObjectList();
                foreach( $lstGap as $gap ){
                    $lstIndGAP[] = $gap->idDimension;
                }
            }
             
            return $lstIndGAP;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
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
            $query = $db->getQuery(true);
            
            $query->select( 't1.intId_dim AS idDimension' );
            $query->from( '#__gen_dimension t1' );
            $query->where( 't1.intId_td != 5' );
            $query->where( 't1.intId_td IN (    SELECT t2.intId_td
                                                FROM #__gen_enfoque t2
                                                WHERE t2.intPadre_td =1 )' );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $lstIndEI = false;
            
            if( $db->getNumRows() > 0 ){
                $lstEI = $db->loadObjectList();
                foreach( $lstEI as $ei ){
                    $lstIndEI[] = $ei->idDimension;
                }
            }
             
            return $lstIndEI;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
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
            $query = $db->getQuery(true);
            
            $query->select( 't1.intId_dim AS idDimension' );
            $query->from( '#__gen_dimension t1' );
            $query->where( 't1.intId_td = 5' );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $lstIndEE = false;
            
            if( $db->getNumRows() > 0 ){
                $lstEE = $db->loadObjectList();
                foreach( $lstEE as $ee ){
                    $lstIndEE[] = $ee->idDimension;
                }
            }
             
            return $lstIndEE;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Retorna una lista de Otros Indicadores
     * 
     * @return type
     */
    public function lstOtrosIndicadores()
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            
            $query->select( 't1.intId_dim AS idDimension' );
            $query->from( '#__gen_dimension t1' );
            $query->where( 't1.intId_td != 5' );
            $query->where( 't1.intId_td IN (    SELECT t2.intId_td
                                                FROM #__gen_enfoque t2
                                                WHERE t2.intPadre_td = 1 )' );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $lstIndEI = false;
            
            if( $db->getNumRows() > 0 ){
                $lstEI = $db->loadObjectList();
                foreach( $lstEI as $ei ){
                    $lstIndEI[] = $ei->idDimension;
                }
            }
             
            return $lstIndEI;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    /**
     * 
     * Lista de Indicadores Asociados a una Entidad y a una Unidad de Medida
     * 
     * @param type $idEntidad   Identificador de Entidad
     * @param type $idUM        Identificador de Unidad de Medida
     * 
     * @return type
     */
    public function lstIndicadores( $idEntidad, $idUM )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   DISTINCT t2.intCodigo_ind AS id, 
                                t2.strNombre_ind AS nombre' );
            $query->from( '#__ind_indicador_entidad t1' );
            $query->join( 'INNER', '#__ind_indicador t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
            
            if( $idEntidad != 0 ){
                $query->where( 't1.intIdentidad_ent = '. $idEntidad );
            }
            
            $query->where( 't2.intCodigo_unimed = '. $idUM );
            $query->order( 't2.strNombre_ind' );

            $db->setQuery( (string)$query );
            $db->query();
            
            $rst = ( $db->getNumRows() > 0 )? $db->loadObjectList()
                                            : array();
             
            return $rst;

        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    /**
     * 
     * Lista de Responsables de un determinado indicador
     * 
     * @param type $idEntidad       Identificador de la Entidad
     * @param type $idIndicador     Identificador del Indicador
     * 
     * @return type
     * 
     */
    public function getResponsablesIndicador( $idEntidad, $idIndicador )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   UPPER( t5.strNombre_ug ) AS UGResponsable,
                                UPPER( t6.strNombre_ug ) AS responsableUG,
                                UPPER( CONCAT ( t7.strApellido_fnc, " ", strNombre_fnc ) ) AS responsable' );
            $query->from( '#__ind_indicador_entidad t1' );
            $query->join( 'INNER', '#__ind_ug_responsable t2 ON t1.intIdIndEntidad = t2.intIdIndEntidad' );
            $query->join( 'INNER', '#__ind_funcionario_responsable t3 ON t1.intIdIndEntidad = t3.intIdIndEntidad' );
            $query->join( 'INNER', '#__gen_ug_funcionario t4 ON t3.intId_ugf = t4.intId_ugf' );
            $query->join( 'INNER', '#__gen_unidad_gestion t5 ON t2.intCodigo_ug = t5.intCodigo_ug' );
            $query->join( 'INNER', '#__gen_unidad_gestion t6 ON t4.intCodigo_ug = t6.intCodigo_ug' );
            $query->join( 'INNER', '#__gen_funcionario t7 ON t4.intCodigo_fnc = t7.intCodigo_fnc' );
            $query->where( 't1.intIdentidad_ent = '. $idEntidad );
            $query->where( 't1.intCodigo_ind = '. $idIndicador );
            $query->where( 't2.inpVigencia_ugr = 1 ' );
            $query->where( 't3.intVigencia_fgr = 1' );

            $db->setQuery( (string)$query );
            $db->query();

            $rst = ( $db->getNumRows() > 0 )? $db->loadObjectList()
                                            : array();

            return $rst;

        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * 
     * 
     * @param type $idDimension
     * @param type $idCategoria
     * @return type
     */
    public function dtaPlantillaPorDimension( $idDimension, $idCategoria )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intId_pi             AS idIndPlantilla,
                                t1.intCodigoTipo_pi     AS idTpoIndicador, 
                                t1.inpCodigo_claseind   AS idClaseIndicador, 
                                t2.intId_tum            AS idTpoUndMedida,
                                t1.intCodigo_unimed     AS idUndMedida, 
                                t1.inpCodigo_unianl     AS idUndAnalisis,
                                t1.strAlias_unianl      AS modeloIndicador,
                                t3.intId_dim            AS idDimension,
                                t1.strNombre_pi         AS nombreIndicador, 
                                t1.strDescripcion_pi    AS descripcion, 
                                t1.strFormula_pi        AS formula' );
            $query->from( '#__ptlla_indicador t1' );
            $query->join( 'INNER', '#__gen_unidad_medida t2 ON t1.intCodigo_unimed = t2.intCodigo_unimed' );
            $query->join( 'INNER', '#__ptlla_dimension_indicador t3 ON t1.intId_pi = t3.intId_pi' );
            $query->where( 't1.inpCategoria_ind = '. $idCategoria );
            $query->where( 't3.intId_dim = '. $idDimension );

            $db->setQuery( (string)$query );
            $db->query();
            
            $rst = ( $db->getNumRows() > 0 )? $db->loadObjectList()
                                            : array();

            return $rst;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    
    /**
     * 
     * Retorna Informacion de un indicador de tipo plantilla de acuerdo a un determinado indicador
     * 
     * @param type $idPlantilla     Identificador de la Plantilla
     * @return type
     * 
     */
    public function getDtaPlantilla( $idPlantilla )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intId_pi                                                         AS idIndPlantilla,
                                t1.intCodigoTipo_pi                                                 AS idTpoIndicador, 
                                t1.inpCodigo_claseind                                               AS idClaseIndicador, 
                                t2.intId_tum                                                        AS idTpoUndMedida,
                                t1.intCodigo_unimed                                                 AS idUndMedida, 
                                IF( t2.strDescripcion_unimed IS NULL, "", t2.strDescripcion_unimed )AS undMedida,
                                t1.inpCodigo_unianl                                                 AS idUndAnalisis,
                                IF( t3.intId_dim IS NULL, 0, t3.intId_dim )                         AS idDimension,
                                t1.strNombre_pi                                                     AS nombreIndicador, 
                                IF( t1.strDescripcion_pi IS NULL, "", t1.strDescripcion_pi )        AS descripcion, 
                                IF( t1.strFormula_pi IS NULL, "", t1.strFormula_pi )                AS formula' );
            $query->from( '#__ptlla_indicador t1' );
            $query->join( 'INNER', '#__gen_unidad_medida t2 ON t1.intCodigo_unimed = t2.intCodigo_unimed' );
            $query->leftJoin( '#__ptlla_dimension_indicador t3 ON t1.intId_pi = t3.intId_pi' );
            $query->where( 't1.intId_pi = '. $idPlantilla );

            $db->setQuery( (string)$query );
            $db->query();
            
            $rst = ( $db->getNumRows() > 0 )? $db->loadObject()
                                            : array();

            return $rst;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Responsables 
     * 
     * @param type $idUndGestion
     * 
     */
    public function getResponsablesVariable( $idUndGestion )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   intId_ugf AS id, 
                                CONCAT( t2.strApellido_fnc, " ", t2.strNombre_fnc ) AS nombre' );
            $query->from( '#__gen_ug_funcionario t1' );
            $query->join( 'INNER', '#__gen_funcionario t2 ON t1.intCodigo_fnc = t2.intCodigo_fnc' );
            $query->where( 't1.intCodigo_ug = '. $idUndGestion );
            $query->where( 't1.published = 1' );
            
            $db->setQuery( (string)$query );
            $db->query();

            $rstFuncionarios = ( $db->getNumRows() > 0 )? $db->loadObjectList()
                                                        : array();

            return $rstFuncionarios;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    
    /**
     * 
     * Retorna Informacion de gestion de Indicadores
     * 
     * @param type $idEntidad    Identificador del Indicador Entidad
     * @return type
     */
    public function getIndEntidad( $idEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   DISTINCT t1.intCodigo_ind AS id, 
                                UPPER( t1.strNombre_ind ) AS nombre' );
            $query->from( '#__ind_indicador t1' );
            $query->join( 'INNER', '#__ind_indicador_entidad t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
            $query->where( 't2.intIdentidad_ent = '. $idEntidad );
            
            $db->setQuery( (string)$query );
            $db->query();

            $dtaIndicador = ( $db->getNumRows() > 0 )   ? $db->loadObjectList() 
                                                        : array();

            return $dtaIndicador;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
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
    public function registroDtaIndicador( $indicador )
    {
        $dtaIndicador["intCodigo_ind"]          = ( is_null( $indicador->idIndicador ) )? 0 
                                                                                        : $indicador->idIndicador;

        $dtaIndicador["intCodigoTipo_ind"]      = ( is_null( $indicador->idTpoIndicador ) ) ? 0 
                                                                                            :$indicador->idTpoIndicador;

        $dtaIndicador["inpCodigo_claseind"]     = ( is_null( $indicador->idClaseIndicador ) )   ? 0 
                                                                                                : $indicador->idClaseIndicador;

        $dtaIndicador["intCodigo_unimed"]       = ( is_null( $indicador->idUndMedida ) )? 0 
                                                                                        :$indicador->idUndMedida;

        $dtaIndicador["inpCodigo_unianl"]       = ( is_null( $indicador->idUndAnalisis ) )  ? 0 
                                                                                            : $indicador->idUndAnalisis;

        $dtaIndicador["strNombre_ind"]          = ( is_null( $indicador->nombreIndicador ) )? 0 
                                                                                            : $indicador->nombreIndicador;

        $dtaIndicador["strDescripcion_ind"]     = ( is_null( $indicador->descripcion ) )? 0 
                                                                                        : $indicador->descripcion;

        $dtaIndicador["strFormula_ind"]         = ( is_null( $indicador->formula ) )? 0 
                                                                                    :$indicador->formula;
        
        $dtaIndicador["intIdMetodoCalculo_ind"] = ( is_null( $indicador->idMetodoCalculo ) )? 1 
                                                                                            : $indicador->idMetodoCalculo;

        $dtaIndicador["inpCategoria_ind"]       = ( is_null( $indicador->idCategoria ) )? 0 
                                                                                        : $indicador->idCategoria;

        $dtaIndicador["intIdDimension_ind"]     = ( is_null( $indicador->idDimension ) )? 0 
                                                                                        : $indicador->idDimension;

        if( $indicador->idIndicador == 0 ){
            $dtaIndicador["dteFechaRegistro_ind"]   = date("Y-m-d H:i:s");
        }
        
        $dtaIndicador["dteFechaModificacion_ind"]   = date("Y-m-d H:i:s");

        if( !$this->save( $dtaIndicador ) ){
            echo $this->getError(); 
            exit;
        }

        return $this->intCodigo_ind;
    }
    
    
    
    /**
     * 
     * Borro Lista de Indicadores
     * 
     * @param object $lstIndicadores  Lista de indicadores a eliminar
     * 
     * @return type
     * 
     */
    private function _delLstIndicadores( $lstIndicadores )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            
            $query->delete( '#__ind_indicador' );
            $query->where( ' intCodigo_ind IN ( '. implode( ',', $lstIndicadores ) .' )' );
            
            $db->setQuery( (string)$query );
            $db->query();

            $ban = ( $db->getAffectedRows() >= 0 )  ? TRUE 
                                                    : FALSE;

            return $ban;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    public function delLstIndicadores( $lstIndicadores )
    {
        $lstInd = array();
        
        foreach( $lstIndicadores as $ind ){
            $lstInd[] = $ind->idIndicador;
        }
        
        return $this->_delLstIndicadores( $lstInd );
    }
    
    /**
     * 
     * Retorno informacion de seguimiento que se realiza a una variable o indicador
     * 
     * @param int $idIndicador      Identificador Indicador
     * 
     * @return object
     * 
     */
    public function getDtaSeguimientoIndicador( $idIndicador, $idIndVariable )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            
            $query->select( '   DISTINCT 
                                    t1.intId_seg    AS idSeg, 
                                    t1.intId_iv     AS idIV,
                                    t1.dteFecha_seg AS fecha, 
                                    t1.dcmValor_seg AS valor, 
                                    t1.published' );
            $query->from( '#__ind_seguimiento t1' );
            $query->join( 'INNER', '#__ind_indicador_entidad t2 ON t1.intIdIndEntidad = t2.intIdIndEntidad' );
            $query->join( 'INNER', '#__ind_indVariable_indicador t3 ON t1.intId_iv = t3.intId_iv' );
            $query->where( 't3.intCodigo_ind = '. $idIndicador );
            $query->where( 't1.intId_iv = '. $idIndVariable );

            $db->setQuery( (string)$query );
            $db->query();

            $rst = ( $db->getNumRows() > 0 )? $db->loadObjectList()
                                            : array();

            return $rst;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Gestiono la eliminacion de un determinado indicador
     * 
     * @param int $idIndEntidad  Identificador del Indicador
     * 
     * @return int
     */
    public function deleteIndicador( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->delete( '#__ind_indicador' );
            $query->where( 'intCodigo_ind IN (  SELECT t2.intCodigo_ind
                                                FROM tb_ind_indicador_entidad t2
                                                WHERE t2.intIdIndEntidad = '. $idIndEntidad .' )' );
            
            $db->setQuery( (string)$query );
            $db->query();

            return $db->getNumRows();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    public function __destruct()
    {
        return;
    }

}