<?php

jimport( 'ecorae.planinstitucion.plnopranual' );
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.tablenested' );

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class jTableUndGestionResponsable extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__ind_ug_responsable', 'intId_ugr', $db );
    }

    /**
     * 
     * Gestiona informacion de un funcionario responsable de un determinado
     * Indicador - Entidad
     * 
     * @param type $idRegIndEntidad     Identificador de Indicador - Entidad
     * @param type $idUndGestion        Identificador de Unidad de Gestion
     * 
     */
    public function registrarUndGesResp( $dtaUGR )
    {
        if( !$this->save( $dtaUGR ) ){
            echo $this->getError();
            exit;
        }

        return $this->intId_ugr;
    }

    /**
     * 
     * Retorno el identificador de la Unidad de Gestion responsable de un 
     * determinado indicador
     * 
     * @param type $idIndEntidad    Identificador del Indicador Entidad
     * 
     */
    public function getUndGestionIndicador( $idIndEntidad )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intId_ugr AS idUGR,
                                t1.intCodigo_ug AS idUndGestion,
                                t1.dteFechaInicio_ugr AS fecha,
                                t2.intIdentidad_ent AS idEntidadUG' );
            $query->from( '#__ind_ug_responsable t1 ' );
            $query->innerJoin( '#__gen_unidad_gestion t2 ON t2.intCodigo_ug = t1.intCodigo_ug' );
            $query->where( 't1.intIdIndEntidad = ' . $idIndEntidad );
            $query->where( 't1.inpVigencia_ugr = 1 LIMIT 1' );

            $db->setQuery( ( string ) $query );
            $db->query();

            $idUndGestion = ( $db->getNumRows() > 0 ) ? $db->loadObject() : false;
            return $idUndGestion;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Actualizo a cero "0", la vigencia de "una" Unidad de Gestion en "un" 
     * indicador
     * 
     * @param type $idIndEntidad    Identificador Indicador - Entidad
     * @param type $idUGR           Identificador de la Unidad de Gestion Responsable
     * @param type $fchFinGestion   Fecha de fin de gestion
     * 
     * @return type
     * 
     */
    public function updVigenciaUndGestion( $idIndEntidad, $idUGR, $fchFinGestion )
    {
        $db = JFactory::getDBO();            
        try{
            $db->transactionStart();
            $query = $db->getQuery( true );

            $query->update( '#__ind_ug_responsable t1' );
            $query->set( ' t1.inpVigencia_ugr = 0 ' );
            $query->set( ' t1.dteFechaFin_ugr = DATE_SUB("' . $fchFinGestion . '", INTERVAL 1 DAY) ' );
            $query->where( ' t1.intIdIndEntidad = ' . $idIndEntidad );
            $query->where( ' t1.intCodigo_ug = ' . $idUGR );
            
            $db->setQuery( ( string ) $query );
            $db->query();

            $rst = TRUE;

            $db->transactionCommit();
            
            return $rst;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            JErrorPage::render( $e );
            
            exit;
        }
    }

    public function deleteRelacionIndUG( $idUGR )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $query = $db->getQuery( true );
            $query->delete( '#__ind_ug_responsable' );
            $query->where( 'intId_ugr = ' . $idUGR );

            $db->setQuery( ( string ) $query );
            $db->query();

            $result = ( $db->getNumRows() > 0 ) ? true  
                                                : false;
            
            $db->transactionCommit();

            return $result;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );

            JErrorPage::render( $e );
        }
    }

    private function _getPoaVigente( $idEntidadUG )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( "DATE_FORMAT( dteFechainicio_pi,  '%Y' ) AS anio" );
            $query->from( '#__pln_plan_institucion' );
            $query->where( "intIdentidad_ent = {$idEntidadUG}" );
            $query->where( "blnVigente_pi = 1" );

            $db->setQuery( ( string ) $query );
            $db->query();

            $result = ( $db->getNumRows() > 0 ) ? $db->loadObject() : false;
            return $result;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    public function getPeiVigente()
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( "blnVigente_pi AS vigente,
                            intId_pi AS idPlan" );
            $query->from( '#__pln_plan_institucion' );
            $query->where( "published = 1" );
            $query->where( "blnVigente_pi = 1" );
            $query->where( "intId_tpoPlan = 1" );

            $db->setQuery( ( string ) $query );
            $db->query();

            $result = ( $db->getNumRows() > 0 ) ? $db->loadObject() : false;
            return $result;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorno la Ultima Unidad de Gestion "VIGENTE" responsable del indicador
     * en caso que no exista una unidad de gestion retorna CERO "0"
     * 
     * @param int $idIndEntidad    Identificador del Indicador Entidad
     * 
     * @return int  Identificador de la Ultima Unidad de Gestion Responsable del indicador
     * 
     */
    public function ultimaUGResponsable( $idIndEntidad )
    {
        try{
            $idUGResponsable = 0;

            $db = JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( "t1.intCodigo_ug AS idUGResponsable" );
            $query->from( '#__ind_ug_responsable t1' );
            $query->where( "t1.intIdIndEntidad = " . $idIndEntidad );
            $query->where( "t1.inpVigencia_ugr = 1" );

            $db->setQuery( ( string ) $query );
            $db->query();

            if( $db->getNumRows() > 0 ){
                $result = $db->loadObject();
                $idUGResponsable = $result->idUGResponsable;
            }

            return $idUGResponsable;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Verifico la existencia de un Plan de tipo POA, en un determinado 
     * periodo de tiempo,
     * 
     * @param int $idUGResponsable      Identificador de la Unidad de Gestion Responsable
     * @param object $plan              Datos de un determinado plan
     * 
     * @return Boolean
     * 
     */
    public function _existePlanUG( $idUGResponsable, $plan )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intId_pi             AS idPlan,
                                t3.intId_ob             AS idObj, 
                                t3.intIdentidad_ent     AS idEntidad, 
                                t3.intId_pln_objetivo	AS idPlnObjetivo' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->join( 'INNER', '#__gen_unidad_gestion t2 ON t1.intIdentidad_ent = t2.intIdentidad_ent AND t1.blnVigente_pi = 1' );
            $query->join( 'INNER', '#__pln_plan_objetivo t3 ON t1.intId_pi = t3.intId_pi' );
            $query->where( "t2.intCodigo_ug = " . $idUGResponsable );
            $query->where( "t1.dteFechainicio_pi = '" . $plan->fchInicio . "'" );
            $query->where( "t1.dteFechafin_pi = '" . $plan->fchFin . "'" );
            $query->order( "t3.intId_ob" );

            $db->setQuery( ( string ) $query );
            $db->query();
            
            $rst = ( $db->getNumRows() > 0 )? $db->loadObject()
                                            : FALSE;
            
            return $rst;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return;
    }
    
    /**
     * 
     * Elimino las unidades de gestion responsables de un indicador
     * 
     * @param int $idIndEntidad    Identificador Indicador Entidad
     * 
     * @return int
     * 
     */
    public function deleteUGResponsable( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->delete( '#__ind_ug_responsable' );
            $query->where( 'intIdIndEntidad = '. $idIndEntidad );
            
            $db->setQuery( (string)$query );
            $db->query();

            return $db->getNumRows();
        } catch(Exception $e) {
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
