<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de Indicadores
 * 
 */

class ProyectosTableIndicador extends JTable
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
    
    /**
     * 
     * Retorno datos de Indicadores de tipo Economicos
     * 
     * @param type $idEntidad  Identificador del entidad
     * @return type
     */
    public function getDataIndicadores( $idEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intIdIndEntidad          AS idIndEntidad,
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
                                t1.dteHorizonteFchInicio_indEnt AS fchHorzMimimo,
                                t1.dteHorizonteFchFin_indEnt    AS fchHorzMaximo,
                                t1.fltUmbralMinimo_indEnt       AS umbMinimo,
                                t1.fltUmbralMaximo_indEnt       AS umbMaximo,
                                t2.inpCodigo_claseind           AS idClaseIndicador,
                                t1.intcodigo_per                AS idFrcMonitoreo,
                                t6.intId_enfoque                AS idEnfoque,
                                t6.strNombre_enfoque            AS enfoque,
                                t3.intId_dim                    AS idDimension,
                                t4.strDescripcion_dim           AS dimension,
                                t2.strDescripcion_ind           AS descripcion,
                                t2.inpCategoria_ind             AS categoriaInd,
                                t8.intCodigo_ug                 AS idUGResponsable,
                                t9.intId_ugf                    AS idResponsable,
                                t10.intCodigo_ug                AS idResponsableUG ' );
            $query->from( '#__ind_indicador_entidad t1' );
            $query->join( '', '#__ind_indicador t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
            $query->leftJoin( '#__ind_dimension_indicador t3 ON t2.intCodigo_ind = t3.intCodigo_ind' );
            $query->leftJoin( '#__gen_dimension t4 ON t4.intId_dim = t3.intId_dim' );
            $query->leftJoin( '#__gen_unidad_analisis t5 ON t5.inpCodigo_unianl = t2.inpCodigo_unianl' );
            $query->join( '', '#__gen_enfoque t6 ON t6.intId_enfoque = t4.intId_enfoque' );
            $query->leftJoin( '#__gen_unidad_medida t7 ON t7.intCodigo_unimed = t2.intCodigo_unimed' );
            $query->leftJoin( '#__ind_ug_responsable t8 ON t8.intIdIndEntidad = t1.intIdIndEntidad AND t8.inpVigencia_ugr = 1' );
            $query->leftJoin( '#__ind_funcionario_responsable t9 ON t9.intIdIndEntidad = t1.intIdIndEntidad AND t9.intVigencia_fgr = 1' );
            $query->leftJoin( '#__gen_ug_funcionario t10 ON t10.intId_ugf = t9.intId_ugf' );
            $query->where( 't2.inpCategoria_ind <> 2 ' );
            $query->where( 't1.intIdentidad_ent = '. $idEntidad );

            $db->setQuery( (string)$query );
            $db->query();

            $dtaIndicadores = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() 
                                                        : FALSE;

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
    public function getLstOtrosIndicadores( $idEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intIdIndEntidad          AS idIndEntidad,
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
                                t1.dteHorizonteFchInicio_indEnt AS fchHorzMimimo,
                                t1.dteHorizonteFchFin_indEnt    AS fchHorzMaximo,
                                t1.fltUmbralMinimo_indEnt       AS umbMinimo,
                                t1.fltUmbralMaximo_indEnt       AS umbMaximo,
                                t2.inpCodigo_claseind           AS idClaseIndicador,
                                t1.intcodigo_per                AS idFrcMonitoreo,
                                t2.strDescripcion_ind           AS descripcion,
                                t2.inpCategoria_ind             AS categoriaInd,
                                t8.intCodigo_ug                 AS idUGResponsable,
                                t9.intId_ugf                    AS idResponsable,
                                t10.intCodigo_ug                AS idResponsableUG ' );
            $query->from( '#__ind_indicador_entidad t1' );
            $query->join( 'INNER', '#__ind_indicador t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
            $query->leftJoin( '#__gen_unidad_analisis t5 ON t5.inpCodigo_unianl = t2.inpCodigo_unianl' );
            $query->leftJoin( '#__gen_unidad_medida t7 ON t7.intCodigo_unimed = t2.intCodigo_unimed' );
            $query->leftJoin( '#__ind_ug_responsable t8 ON t8.intIdIndEntidad = t1.intIdIndEntidad AND t8.inpVigencia_ugr = 1' );
            $query->leftJoin( '#__ind_funcionario_responsable t9 ON t9.intIdIndEntidad = t1.intIdIndEntidad AND t9.intVigencia_fgr = 1' );
            $query->leftJoin( '#__gen_ug_funcionario t10 ON t10.intId_ugf = t9.intId_ugf' );
            $query->where( 't2.inpCategoria_ind = 2' );
            $query->where( 't1.intIdentidad_ent = '. $idEntidad );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $lstOtrosInd = ( $db->getNumRows() > 0 )    ? $db->loadObjectList() 
                                                        : FALSE;

             return $lstOtrosInd;

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
        $dtaIndicador["intCodigo_ind"]      = $indicador->idIndicador;
        $dtaIndicador["intCodigoTipo_ind"]  = $indicador->idTpoIndicador;
        $dtaIndicador["inpCodigo_claseind"] = $indicador->idClaseIndicador;
        $dtaIndicador["intCodigo_unimed"]   = $indicador->idUndMedida;
        $dtaIndicador["inpCodigo_unianl"]   = $indicador->idUndAnalisis;
        $dtaIndicador["strNombre_ind"]      = $indicador->nombreInd;
        $dtaIndicador["strDescripcion_ind"] = $indicador->descripcion;
        $dtaIndicador["strFormula_ind"]     = $indicador->formula;
        $dtaIndicador["inpCategoria_ind"]   = $indicador->idCategoria;
        
        if( $idIndicador == 0 ){
            $dtaIndicador["dteFechaRegistro_ind"]   = date("Y-m-d H:i:s");
        }
        
        $dtaIndicador["dteFechaModificacion_ind"]= date("Y-m-d H:i:s");
        
        if( !$this->save( $dtaIndicador ) ){
            throw new Exception( JText::_( 'COM_PROYECTOS_REGISTRO_INDICADORES' ) );
        }
        
        return $this->intCodigo_ind;
    }

}
