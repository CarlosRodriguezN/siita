<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.tablenested' );

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class jTablePlanInstitucion extends JTable
{

    /**
     *  Constructor
     *
     *  @param object Database connector object
     */
    function __construct( &$db )
    {
        parent::__construct( '#__pln_plan_institucion', 'intId_pi', $db );
    }

    /**
     * 
     * Gestiona el registro de informacion de un plan
     * 
     * @param array $dtaPlan     Datos de un plan
     * 
     * @return int  Identificador del plan registrado en la BBDD
     * 
     */
    public function registroPlan( $dtaPlan )
    {
        if(!$this->save( $dtaPlan )) {
            echo $this->getError();
            exit;
        }

        return $this->intId_pi;
    }

    /**
     *  Retorna el Id de entidad de una Institucion
     * @param int       $idIns      Id de la institucion
     * @return type
     */
    public function getIdEntidadIns( $idIns )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );
            $query->select( "indIdentidad_ent" );
            $query->from( "#__gen_institucion" );
            $query->where( "intCodigo_ins= " . $idIns );
            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0) ? $db->loadObject() : array();

            return $result;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'librares.tables.planinstitucion.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    ///////////////////////////////////////////////////////////////////
    //                  funciones para revisar su funcionalidad
    ///////////////////////////////////////////////////////////////////


    public function getPoasUG( $idEntidadUG )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( "intId_pi AS idPoa,
                             DATE_FORMAT(dteFechainicio_pi, '%Y') AS anio" );
            $query->from( "#__pln_plan_institucion" );
            $query->where( "published = 1" );
            $query->where( "intIdentidad_ent = {$idEntidadUG}" );

            $db->setQuery( $query );
            $db->query();
            $result = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();
            return $result;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *  Retorno Informacion General de un determinado PEI
     * @param type $idPei   Identificador del PEI
     * @return type
     */
    public function getDtaPei( $idPei )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intId_pi             AS idPln,
                                t1.intCodigo_ins        AS idInstitucion,
                                t1.strDescripcion_pi    AS descripcion,
                                t1.intIdentidad_ent     AS idEntidad,
                                t1.dteFechainicio_pi    AS fchInicio,
                                t1.dteFechafin_pi       AS fchFin' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->where( 't1.blnVigente_pi = 1' );
            $query->where( 't1.intId_pi = ' . $idPei );

            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->loadObjectList() ) ? $db->loadObject() 
                                                : array();

            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * Retorno Informacion General de un determinado plan
     * @param type $idPei   Identificador del PEI
     * @return type
     */
    public function getDtaPlan( $idPlan )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intId_pi AS idPln,
                                t1.intCodigo_ins AS idInstitucion,
                                t1.strDescripcion_pi AS descripcion,
                                t1.dteFechainicio_pi AS fchInicio,
                                t1.dteFechafin_pi AS fchFin' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->where( 't1.intId_pi = ' . $idPlan );
            //  $query->where( 't1.blnVigente_pi = 1' );

            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->loadObjectList() ) ? $db->loadObject() : array();

            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *  Obtengo una lista de PPPP cuyo padre es un determinado PEI
     * @param type $idPei   Identificador de un PEI
     */
    public function getLstPPPP( $idPei )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intId_pi,
                                t1.strDescripcion_pi, 
                                t1.intIdentidad_ent AS idEntidad,
                                t1.dteFechainicio_pi, 
                                t1.dteFechafin_pi' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->where( 't1.intIdPadre_pi = ' . $idPei );
            
            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    
    
    
    public function getIdPPPP( $plan )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( 't1.intId_pi AS idPlan' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->where( 't1.dteFechainicio_pi = '. $plan->fchInicio );
            $query->where( 't1.dteFechafin_pi = '. $plan->fchFin );
            $query->where( 't1.intId_tpoPlan = 3' );
            $query->where( 't1.published = 1' );

            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObject()->idPlan 
                                                : 0;
            
            
            
            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    //////////////// ANALIZAR LA UTILIDAD DE ESTA FUNCION O SI NO //////////////////////
    ///////// EN SU LUGAR UTILIZAR LA FUNCION getPlanVigente(1, 0) /////////////////////
    /**
     * RECUPERA de la base de datos el el PEI vigente
     * @return type
     */
    public function getPeiVigente()
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( ''
                    . 't1.intId_pi          AS idPln,'
                    . 't1.intId_tpoPlan     AS tipoPln,'
                    . 't1.strDescripcion_pi AS descPln'
                    . '' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->where( 't1.published = 1' );
            $query->where( 't1.blnVigente_pi = 1' );
            $query->where( 't1.intId_tpoPlan = 1' );

            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObject() : array();
            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *  Retorna la data del plan vigente de un determinado tipo de plan
     * @param type $plnOwner       Id del plan padre
     * @param type $tpoPln      Tipo de plan
     * @return type
     */
    public function getPlanVigente( $plnOwner, $tpoPln, $entidadOwner = 0 )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( 't1.intId_pi            AS idPln,
                            t1.intId_tpoPlan        AS tipoPln,
                            t1.intIdentidad_ent     AS idEntidadPln,
                            t1.intIdPadre_pi        AS idPadrePln,
                            t1.strDescripcion_pi    AS descripcionPln,
                            t1.dteFechainicio_pi    AS fechaInicioPln,
                            t1.dteFechafin_pi       AS fechaFinPln' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->where( 't1.intId_tpoPlan = ' . $tpoPln );
            $query->where( 't1.blnVigente_pi = 1' );
            $query->where( 't1.published = 1' );
            
            if((int) $tpoPln == 2) {
                $sql = ($plnOwner)  ? '(t1.intIdPadre_pi = ' . $plnOwner . ' OR t1.intIdPadre_pi = 0 )' 
                                    : 't1.intIdPadre_pi = 0 ';

                $query->where( $sql );
                $query->where( 't1.intIdentidad_ent = ' . $entidadOwner );
            } else {
                $query->where( 't1.intIdPadre_pi = ' . $plnOwner );
            }
            
            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObject() 
                                                : array();

            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * Retorna una lista de planes asociados a un determinado Plan
     * @param type $idPlan  Identificador del Plan
     * @return type
     */
    public function getLstPlanes( $idPlan )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intId_pi             AS idPlan, 
                                t1.intId_tpoPlan        AS idTpoPlan, 
                                t1.dteFechaInicio_pi    AS fchInicio, 
                                t1.dteFechaFin_pi       AS fchFin, 
                                t3.intIdIndEntidad      AS idIndEntidad,
                                t2.intId_pln_objetivo   AS idPlnObjetivo,
                                t2.intIdentidad_ent     AS idEntidadObjetivo, 
                                t2.intIdPadre           AS idEntPadre,
                                t1.blnVigente_pi        AS vigente' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->leftJoin( '#__pln_plan_objetivo t2 ON t1.intId_pi = t2.intId_pi ' );
            $query->leftJoin( '#__ind_indicador_entidad t3 ON t2.intIdentidad_ent = t3.intIdentidad_ent' );
            $query->where( 't1.intIdPadre_pi = ' . $idPlan );

            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->query() )  ? $db->loadObjectList() 
                                        : array();

            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * Retorno informacion de planes hijo de un determinado plan
     * @param type $idPlan      Identificador del plan padre
     * @return type
     */
    public function getLstPlanesHijo( $idPlan )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intId_pi             AS idPlan, 
                                t1.intIdentidad_ent     AS idEntidad,
                                t1.dteFechainicio_pi    AS fchInicio, 
                                t1.dteFechafin_pi       AS fchFin,
                                t1.intIdPadre_pi        AS idPadrePln,
                                t1.intId_tpoPlan        AS idTpoPlan, 
                                t2.strAlias_tpoPlan     AS plan' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->join( 'INNER', '#__pln_tipo_plan_ins t2 ON t1.intId_tpoPlan = t2.intId_tpoPlan AND t1.blnVigente_pi = 1' );
            $query->where( 't1.intIdPadre_pi = ' . $idPlan );
            $query->where( 't1.intId_tpoPlan IN ( "3", "4" ) ' );

            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->query() )  ? $db->loadObjectList() 
                                        : array();

            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Lista de planes asociados
     * 
     * @param type $idPlan
     * @return type
     */
    public function getLstPlanesAsociados( $idPlan )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intId_pi             AS idPlan, 
                                t1.intIdentidad_ent     AS idEntidad,
                                t1.dteFechainicio_pi    AS fchInicio, 
                                t1.dteFechafin_pi       AS fchFin,
                                t1.intIdPadre_pi        AS idPadrePln,
                                t1.intId_tpoPlan        AS idTpoPlan, 
                                t2.strAlias_tpoPlan     AS plan,
                                t1.blnVigente_pi        AS vigencia' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->join( 'INNER', '#__pln_tipo_plan_ins t2 ON t1.intId_tpoPlan = t2.intId_tpoPlan' );
            $query->where( 't1.intIdPadre_pi = ' . $idPlan );
            
            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->query() )  ? $db->loadObjectList() 
                                        : array();

            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /////////////////// FUNCIONES PARA LA GESTION DE VIGENCIAS DE PLANES /////////////////////////////////////

    /**
     *  Actualiza los registros de planes activos a NO VIGENTE
     * @return type
     */
    public function noVigente( $tpoPln, $idEntidad = 0 )
    {
        $db = & JFactory::getDBO();
        
        try {
            $db->transactionStart();
            
            $query = $db->getQuery( TRUE );
            $query->update( "#__pln_plan_institucion" );
            $query->set( "blnVigente_pi = 0" );
            $query->where( "published = 1" );
            switch ( $tpoPln ) {
                case 1:
                    $query->where( "intId_tpoPlan >= 1" );
                    break;
                case 2:
                    $query->where( "intId_tpoPlan = 2" );
                    $query->where( "intIdPadre_pi = 0" );
                    $query->where( "intIdentidad_ent = " . $idEntidad );
                    break;
                case 3:
                    $query->where( "intId_tpoPlan > 1" );
                    break;
                case 4:
                    $query->where( "intId_tpoPlan = 4" );
                    break;
            }
            $db->setQuery( $query );
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 )? true 
                                                    : false;
            
            $db->transactionCommit();
            
            return $result;
        } catch (Exception $e) {
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            
            JErrorPage::render($e);
        }
    }

    /**
     *  Actualiza la vigencia de un determinado PLAN
     * @param type $idPlan       Id del Plan  actualizar la vigencia
     * @param type $opVgen      Opcion de la vigencia (0, 1)
     * @return type
     */
    public function updVigencia( $idPlan, $opVgen )
    {
        $db = & JFactory::getDBO();

        try {
            $db->transactionStart();            
            $query = $db->getQuery( TRUE );

            $query->update( "#__pln_plan_institucion" );
            $query->set( "blnVigente_pi = ". $opVgen );
            $query->where( "intId_pi = ". $idPlan );

            $db->setQuery( $query );
            $db->query();

            $result = ( $db->getAffectedRows() > 0 )? true 
                                                    : false;

            $db->transactionCommit();

            return $result;
        } catch (Exception $e) {
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            
            JErrorPage::render($e);            
        }
    }

    /**
     *  Retorna la lista de Planes de un Plan Padre (PEI)
     * @param int       $idPlnPadre     Id del plan PEI
     * @param int       $tpoPlan        Id de tipo de plan a obtener
     * @return type
     */
    public function getLstPlanesByTpo( $idPlnPadre, $tpoPlan, $idEntidad = 0 )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( "   t1.intId_pi            AS idPlan, 
                                t1.intIdentidad_ent    AS idEntidad, 
                                t1.intIdPadre_pi       AS idPlnPadre, 
                                t1.intId_tpoPlan       AS tipoPln, 
                                t1.strDescripcion_pi   AS descripcionPln,  
                                t1.dteFechainicio_pi   AS fechaInicioPln, 
                                t1.dteFechafin_pi      AS fechaFinPln, 
                                t1.published           AS published,
                                ( SELECT IF( t1.blnVigente_pi = 1 AND NOW() BETWEEN t1.dteFechainicio_pi AND t1.dteFechafin_pi, 1, 0 )) AS vigentePln" );
            $query->from( "#__pln_plan_institucion t1" );
            $query->where( "t1.intIdPadre_pi = " . $idPlnPadre );
            $query->where( "t1.intId_tpoPlan = " . $tpoPlan );
            $query->where( "t1.published = 1" );
            
            if((int) $tpoPlan == 2) {
                $query->where( "t1.intIdentidad_ent = " . $idEntidad );
            }
            
            $db->setQuery( $query );
            $db->query();

            $result = ( $db->getAffectedRows() > 0 )? $db->loadObjectList() 
                                                    : array();

            return $result;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *  Actualiza la vigencia de planes de acuedo a u ntipo y la fecha del plan padre
     * @param type $fecha
     * @param type $tpoPln
     * @return type
     */
    public function actualizarPlnVigente( $fecha, $tpoPln, $idEntidad )
    {
        $db = & JFactory::getDBO();
        
        try {
            
            $db->transactionStart();
            
            $query = $db->getQuery( TRUE );
            $query->update( "#__pln_plan_institucion" );
            $query->set( "blnVigente_pi = 1" );
            $query->where( "published = 1" );
            $query->where( "dteFechainicio_pi <= '{$fecha}'" );
            $query->where( "dteFechafin_pi >= '{$fecha}'" );

            switch ( $tpoPln ) {
                case 1:
                    $query->where( "intId_tpoPlan > 1" );
                    break;
                case 2:
                    $query->where( "intId_tpoPlan > 1" );
                    $query->where( "intIdPadre_pi = 0" );
                    $query->where( "intIdentidad_ent = " . $idEntidad );
                    break;
                case 3:
                    $query->where( "(intId_tpoPlan = 2 OR intId_tpoPlan = 4)" );
                    break;
                case 4:
                    $query->where( "intId_tpoPlan = 4 " );
                    break;
            }
            
            $db->setQuery( $query );
            $result = ( $db->query() )  ? true 
                                        : false;

            $db->transactionCommit();
            
            return $result;
        } catch (Exception $e) {
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            
            JErrorPage::render( $e );            
        }
    }

    /**
     * Retorna un objeto con al data general de un Plan
     * @param type $idPlan   Identificador del Plan
     * @return type
     */
    public function getDataPlan( $idPlan )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intId_pi             AS idPln,
                                t1.intId_tpoPlan        AS idTpoPln,
                                t1.intCodigo_ins        AS idInstitucion,
                                t1.intIdPadre_pi        AS idPadre,
                                t1.intIdentidad_ent     AS idEntidad,
                                t1.strDescripcion_pi    AS descripcion,
                                t1.dteFechainicio_pi    AS fchInicio,
                                t1.dteFechafin_pi       AS fchFin,
                                t1.published            AS published' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->where( 't1.intId_pi = ' . $idPlan );

            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->loadObjectList() ) ? $db->loadObject() : array();

            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *  
     *  Retorna el plan POA vigente relacionada a una entidad UG - FUN
     *  
     *  @param type $idEntidad       Id de entidad Unidad de gestion o Funcionario
     * 
     *  @return type
     */
    public function getPoaVigenteByEnt( $idEntidad )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intId_pi             AS idPln,
                                t1.intId_tpoPlan        AS tipoPln,
                                t1.intIdentidad_ent     AS idEntidadPln,
                                t1.intIdPadre_pi        AS idPadrePln,
                                t1.strDescripcion_pi    AS descripcionPln,
                                t1.dteFechainicio_pi    AS fechaInicioPln,
                                t1.dteFechafin_pi       AS fechaFinPln,
                                ( SELECT IF( DATE( NOW() ) BETWEEN t1.dteFechainicio_pi AND t1.dteFechafin_pi, 1, 0 )) AS vigenciaPoa' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->where( 't1.blnVigente_pi = 1' );
            $query->where( 't1.published = 1' );
            $query->where( 't1.intId_tpoPlan = 2' );
            $query->where( 't1.intIdentidad_ent = '. $idEntidad );
            
            $db->setQuery( $query );
            $db->query();
            $result = ( $db->getAffectedRows() > 0 )? $db->loadObject() 
                                                    : array();
            
            return $result;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *  Retorna los planes de tipo POA desde el POA vigente, asociados a una 
     *  determinada Entidad (UG-FUN)
     * @param type $idEntidad           Identificador Entidad
     * @param type $fPlanVigene         fecha de inicio de plan vigente
     * @return type
     */
    public function getLstPoasByEntidad( $idEntidad, $fPlanVigene )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( "   t1.intId_pi            AS idPoa, 
                                t1.intId_tpoPlan       AS idTipoPlanPoa, 
                                t1.intCodigo_ins       AS idInstitucionPoa, 
                                t1.intIdentidad_ent    AS idEntidadPoa, 
                                t1.intIdPadre_pi       AS idPadrePoa, 
                                t1.strDescripcion_pi   AS descripcionPoa,
                                t1.dteFechainicio_pi   AS fechaInicioPoa,
                                t1.dteFechafin_pi      AS fechaFinPoa,
                                ( SELECT IF( DATE( NOW() ) BETWEEN t1.dteFechainicio_pi AND t1.dteFechafin_pi, 1, 0 )) AS vigenciaPoa,
                                t1.strAlias_pi         As aliasPoa,
                                t1.published" );
            $query->from( "#__pln_plan_institucion t1" );
            $query->where( 't1.blnVigente_pi = 1' );
            $query->where( 't1.intIdentidad_ent = '. $idEntidad );
            $query->where( 't1.dteFechainicio_pi >= "'. $fPlanVigene .'"' );

            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0)  ? $db->loadObjectList() 
                                                    : array();

            return $result;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Verifica la existencia de un plan de un determinao tipo en estado vigente 
     * 
     * @param int $tpoPlan     Tipo de plan, por defautl Tipo de plan PEI
     * @return Boolean
     * 
     */
    public function existePlanVigente( $idTpoPln = 1, $idPlan = null )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( "COUNT(*) AS cantidad" );
            $query->from( "#__pln_plan_institucion t1" );
            $query->where( "t1.blnVigente_pi = 1" );

            if( $idPlan ){
                $query->where( "t1.intId_pi != " . $idPlan );
            }

            $query->where( "t1.intId_tpoPlan = " . $idTpoPln );
            
            $db->setQuery( $query );
            $db->query();

            $rstConsulta = $db->loadObject();

            $rst = ( (int) $rstConsulta->cantidad > 0 ) ? 0 
                                                        : 1;

            return $rst;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorno el identificador del plan de tipo POA-UG, de una determinada 
     * Unidad de Gestion Responsable. 
     * 
     * Esta informacion SE FILTRA EN FUNCION DE LAS FECHAS DE INICIO Y FIN DEL PLAN.
     * 
     * @param int       $idUGResponsable     Identificador de la Unidad de Gestion Responsable
     * @param object    $plan                Datos del plan
     * 
     * @return int      Identificador del Plan asociado a la Unidad de Gestion 
     *                  en un periodo de tiempo determinado por el plan, 
     *                  en el caso de no existir retorna FALSE
     */
    public function getIdPlanPoaUG( $idUGResponsable, $plan )
    {
        try {
            $idPlan = 0;

            $db = &JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( 't1.intId_pi AS idPlan' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->join( 'INNER', '#__gen_unidad_gestion t2 ON t1.intIdentidad_ent = t2.intIdentidad_ent' );
            $query->where( 't2.intCodigo_ug = ' . $idUGResponsable );
            $query->where( 't1.dteFechainicio_pi = "' . $plan->fchInicio . '"' );
            $query->where( 't1.dteFechafin_pi = "' . $plan->fchFin . '"' );
            $query->where( 't1.blnVigente_pi = 1' );

            $db->setQuery( (string) $query );
            $db->query();

            if( $db->getNumRows() ) {
                $rstConsulta = $db->loadObject();
                $idPlan = $rstConsulta->idPlan;
            }

            return $idPlan;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    public function getDtaPlanPoaUG( $indicador )
    {
        try {

            $idPlan = false;

            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( 't1.intId_pi AS idPlan' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->join( 'INNER', '#__gen_unidad_gestion t2 ON t1.intIdentidad_ent = t2.intIdentidad_ent' );
            $query->where( 't2.intCodigo_ug = ' . $indicador->idUGResponsable );
            $query->where( 't1.dteFechainicio_pi = ' . $indicador->fchHorzMimimo );
            $query->where( 't1.dteFechafin_pi = ' . $indicador->fchHorzMaximo );
            $query->where( 't1.blnVigente_pi = 1' );

            $db->setQuery( $query );
            $db->query();

            if($db->getNumRows()) {
                $rstConsulta = $db->loadObject();
                $idPlan = $rstConsulta->idPlan;
            }

            return $idPlan;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    //////////////      ELIMINACION DE PLANES       ///////////////////
    
    public function deleteLogicoPlan( $idPln )
    {
        $db = & JFactory::getDBO();

        try {
            $db->transactionStart();

            $query = $db->getQuery( TRUE );
            $query->update( "#__pln_plan_institucion" );
            $query->set( "published = 0" );
            $query->where( "intId_pi = {$idPln}" );
            $db->setQuery( $query );

            $result = ( $db->query() )  ? true 
                                        : false;

            $db->transactionCommit();

            return $result;
        } catch (Exception $e) {
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            
            JErrorPage::render($e);            
        }
    }
    
    public function deletePlan( $idPln )
    {
        $db = JFactory::getDBO();

        try {
            $db->transactionStart();
            $query = $db->getQuery( true );

            $query->delete( '#__pln_plan_institucion' );
            $query->where( 'intId_pi = '. $idPln );
            
            $db->setQuery( ( string ) $query );
            $db->query();
            
            $ban = ( $db->getAffectedRows() >= 0 )  ? TRUE   
                                                    : FALSE;

            $db->transactionCommit();

            return $ban;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Retorna una lista de planes asociados a una determinada entidad
     * 
     * @param int $idEntidad    Identificador de la Entidad
     * @param int $idTpoPlan    Identificador del Tipo de Plan
     * 
     * @return object
     * 
     */
    public function getPlanesPorEntidad( $idEntidad, $idTpoPlan = 2 )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( "   t1.intId_pi            AS idPlan, 
                                t1.intId_tpoPlan       AS idTpoPlan, 
                                t1.intCodigo_ins       AS idInstitucion, 
                                t1.intIdentidad_ent    AS idEntidad, 
                                t1.intIdPadre_pi       AS idPadrePlan, 
                                t1.strDescripcion_pi   AS descripcionPln,
                                t1.dteFechainicio_pi   AS fchInicio,
                                t1.dteFechafin_pi      AS fchFin,
                                t1.blnVigente_pi       AS vigentePln, 
                                t1.strAlias_pi         As aliasPlan" );
            $query->from( "#__pln_plan_institucion t1" );
            $query->where( "intIdentidad_ent= " . $idEntidad );

            $db->setQuery( $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0)  ? $db->loadObjectList() 
                                                    : array();

            return $result;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    
    public function lstPlanesPOA( $idPlan )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intId_pi             AS idPlan, 
                                t1.intId_tpoPlan        AS idTpoPlan, 
                                t1.dteFechaInicio_pi    AS fchInicio, 
                                t1.dteFechaFin_pi       AS fchFin, 
                                t3.intIdIndEntidad      AS idIndEntidad,
                                t2.intId_pln_objetivo   AS idPlnObjetivo,
                                t2.intIdentidad_ent     AS idEntidadObjetivo, 
                                t2.intIdPadre           AS idEntPadre' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->join( 'INNER', '#__pln_plan_objetivo t2 ON t1.intId_pi = t2.intId_pi ' );
            $query->join( 'INNER', '#__ind_indicador_entidad t3 ON t2.intIdentidad_ent = t3.intIdentidad_ent' );
            $query->where( 't1.intIdPadre_pi IN (   SELECT  ta.intId_pi 
                                                    FROM tb_pln_plan_institucion ta
                                                    WHERE ta.intId_tpoPlan = 3 
                                                    AND ta.intIdPadre_pi = '. $idPlan .' )' );

            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->query() )  ? $db->loadObjectList() 
                                        : array();

            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    
    /**
     * 
     * Retorna lista de plan objetivo
     * 
     * @param int $idPlan       Identificador del Plan
     * @param int $idObjetivo   Identificador del Objetivo
     * 
     * @return type
     * 
     */
    public function getDtaPlanObjetivo( $idObjetivo, $idPlan )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intId_pi             AS idPlan, 
                                t1.intId_tpoPlan        AS idTpoPlan, 
                                t1.dteFechaInicio_pi    AS fchInicio, 
                                t1.dteFechaFin_pi       AS fchFin, 
                                t2.intId_pln_objetivo   AS idPlnObjetivo,
                                t2.intId_ob             AS idObjetivo,
                                t2.intIdentidad_ent     AS idEntidadObjetivo, 
                                t2.intIdPadre           AS idEntPadre' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->join( 'INNER', '#__pln_plan_objetivo t2 ON t1.intId_pi = t2.intId_pi ' );
            $query->where( 't1.intIdPadre_pi = '. $idPlan );
            $query->where( 't2.intId_ob = '. $idObjetivo );

            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->loadObjectList() ) ? $db->loadObject() 
                                                : array();

            return $retval;
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

 
    
    public function getLstOtrosPlanes( $idPlan, $tpo )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intId_pi AS idPlan' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->where( 't1.intId_tpoPlan = '. $tpo );
            $query->where( 't1.intId_pi <> '. $idPlan );

            $db->setQuery( $query );
            $db->query();

            return $db->loadObjectList();
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
 
    
    
    public function getFechasPlanVigente()
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   DISTINCT 
                                    t1.dteFechainicio_pi AS fchInicio, 
                                    t1.dteFechafin_pi AS fchFin' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->where( 't1.intId_tpoPlan IN ( 2, 5, 6 ) ' );
            $query->where( 't1.blnVigente_pi = 1' );

            $db->setQuery( $query );
            $db->query();

            return $db->loadObject();
        } catch (Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_poa.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }


    
    public function __destruct()
    {
        return;
    }    

}