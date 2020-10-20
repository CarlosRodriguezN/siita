<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.tablenested' );

/**
 * 
 * Clase que gestiona informacion de la tabla Funcionario Responsable ( tb_ind_funcionario_responsable )
 * 
 */
class jTableFuncionarioResponsable extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__ind_funcionario_responsable', 'intId_fgr', $db );
    }

    /**
     * 
     * Retorno el identificador del funcionario responsable de un 
     * determinado indicador
     * 
     * @param type $idIndEntidad    Identificador de Indicador/Entidad
     * 
     */
    public function getFuncionarioIndicador( $idIndEntidad )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intId_fgr AS idFR,
                                t1.intId_ugf AS idFuncionario,
                                t1.dteFechaInicio_fgr AS fecha' );
            $query->from( '#__ind_funcionario_responsable t1 ' );
            $query->where( 't1.intIdIndEntidad = ' . $idIndEntidad );
            $query->where( 't1.intVigencia_fgr = 1' );
            $query->order( "t1.intId_fgr DESC LIMIT 1" );

            $db->setQuery( ( string ) $query );
            $db->query();

            $dtaIndicadores = ( $db->getNumRows() > 0 ) ? $db->loadObject() : array();
            return $dtaIndicadores;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Actualizo a cero "0", la vigencia de "un" funcionario en "un" indicador
     * 
     * @param type $idIndEntidad    Identificador Indicador - Entidad
     * @param type $idFuncionario   Identificador del Funcionario
     * @param type $fchFinGestion   Fecha de Fin de Gestion del funcionario
     * 
     * @return type
     * 
     */
    public function updVigenciaFuncionario( $idIndEntidad, $idFuncionario, $fchFinGestion )
    {
        $db = JFactory::getDBO();
        try{
            $db->transactionStart();
            $query = $db->getQuery( true );

            $query->update( '#__ind_funcionario_responsable ' );
            $query->set( ' intVigencia_fgr = 0' );
            $query->set( ' dteFechaFin_fgr = "' . $fchFinGestion . '" ' );
            $query->where( ' intIdIndEntidad = ' . $idIndEntidad . ' ' );
            $query->where( ' intId_ugf = ' . $idFuncionario . ' ' );
            $query->where( ' intVigencia_fgr = 1' );

            $db->setQuery( ( string ) $query );
            $db->execute();

            $result = TRUE;

            $db->transactionCommit();

            return $result;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            
            JErrorPage::render($e);
        }
    }

    /**
     * 
     * Gestiona informacion de un funcionario responsable de un determinado
     * Indicador - Entidad
     * 
     * @param type $idRegIndEntidad     Identificador de Indicador - Entidad
     * @param type $indicador       Identificador Funcinario
     * 
     */
    public function registrarFuncionarioResponsable( $dtaIndVariable )
    {
        if( !$this->save( $dtaIndVariable ) ){
            echo $this->getError();
            exit;
        }

        return $this->intId_fgr;
    }

    /**
     * 
     * Retorna una lista de funcionarios 
     * 
     * @return type
     * 
     */
    public function getLstFuncionarios()
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t2.intId_ugf AS idFuncionario, 
                                t1.intIdentidad_ent AS idEntidad,
                                CONCAT( t1.strApellido_fnc, " ", t1.strNombre_fnc ) AS nombre' );
            $query->from( '#__gen_funcionario t1 ' );
            $query->join( 'INNER', '#__gen_ug_funcionario t2 ON t1.intCodigo_fnc = t2.intCodigo_fnc ' );
            $query->where( 't1.published = 1' );

            $db->setQuery( ( string ) $query );
            $db->query();

            $dtaLstFuncionarios = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $dtaLstFuncionarios;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorna una lista de funcionarios activos 
     * y asociados a una unidad de gestion
     * 
     * @return type
     * 
     */
    public function lstFuncionariosActivos()
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intId_ugf AS idFuncionario, 
                                t2.intIdentidad_ent AS idEntidadUG,
                                CONCAT( t3.strApellido_fnc, " ", strNombre_fnc ) AS nombre' );
            $query->from( '#__gen_ug_funcionario t1' );
            $query->join( 'INNER', '#__gen_unidad_gestion t2 ON t1.intCodigo_ug = t2.intCodigo_ug' );
            $query->join( 'INNER', '#__gen_funcionario t3 ON t3.intCodigo_fnc = t1.intCodigo_fnc' );
            $query->where( 't1.published = 1' );

            $db->setQuery( ( string ) $query );
            $db->query();

            $dtaLstFuncionarios = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $dtaLstFuncionarios;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * @param type $idIndEntidad
     * 
     */
    public function ultimoFuncionarioResponsable( $idIndEntidad )
    {
        try{
            $idFuncionario = 0;

            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 't1.intId_ugf AS idFuncionario' );
            $query->from( '#__ind_funcionario_responsable t1' );
            $query->where( 't1.intIdIndEntidad = ' . $idIndEntidad );
            $query->where( 't1.intVigencia_fgr = 1' );
            
            $db->setQuery( ( string ) $query );
            $db->execute();

            if( $db->getNumRows() > 0 ){
                $dtaFuncionario = $db->loadObject();
                $idFuncionario = $dtaFuncionario->idFuncionario;
            }

            return $idFuncionario;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorno el identificador del plan de tipo POA-Funcionario, de un determinado 
     * Funcionario Responsable. 
     * 
     * Esta informacion SE FILTRA EN FUNCION DE LAS FECHAS DE INICIO Y FIN DEL PLAN.
     * 
     * @param int       $idFuncionario      Identificador del Funcionario Responsable
     * @param object    $plan               Datos del plan
     * 
     * @return int      Identificador del Plan de tipo POA asociado a un Funcionario
     *                  en un periodo de tiempo determinado por el plan, 
     *                  en el caso de no existir retorna cero "0"
     */
    public function getIdPlanPoaF( $idFuncionario, $plan )
    {
        try{
            $idPlan = 0;

            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( 't1.intId_pi AS idPlan' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->join( 'INNER', '#__gen_funcionario t2 ON t1.intIdentidad_ent = t2.intIdentidad_ent' );
            $query->join( 'INNER', '#__gen_ug_funcionario t3 ON t2.intCodigo_fnc = t3.intCodigo_fnc' );
            $query->where( ' t3.intId_ugf = ' . $idFuncionario );
            $query->where( ' t1.dteFechainicio_pi = "' . $plan->fchInicio . '"' );
            $query->where( ' t1.dteFechafin_pi = "' . $plan->fchFin . '"' );
            $query->where( ' t1.published = 1' );

            $db->setQuery( $query );
            $db->query();

            if( $db->getNumRows() ){
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

    /**
     * 
     * Retorno informacion general de un determinado funcionario
     * 
     * @param int $idFuncionario    Identificador del funcionario
     * 
     * @return Object   
     * 
     */
    public function getDtaFuncionario( $idFuncionario )
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t1.intIdentidad_ent                                 AS idEntidadF, 
                                t1.intIdUser_fnc                                    AS idUsrRegistrado, 
                                t1.strCI_fnc                                        AS cedula, 
                                CONCAT( t1.strApellido_fnc, " " , t1.strNombre_fnc )AS nombreF, 
                                t1.strCorreoElectronico_fnc                         AS correo, 
                                t1.strTelefono_fnc                                  AS telefono, 
                                t1.strCelular_fnc                                   AS celular' );
            $query->from( '#__gen_funcionario t1' );
            $query->join( 'INNER', '#__gen_ug_funcionario t2 ON t1.intCodigo_fnc = t2.intCodigo_fnc' );
            $query->where( ' t2.intId_ugf = ' . $idFuncionario );
            $query->where( ' t2.published = 1' );
            
            $db->setQuery( $query );
            $db->query();

            $rstConsulta = ( $db->getNumRows() )? $db->loadObject() 
                                                : FALSE;

            return $rstConsulta;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Busco la existencia de una 
     * 
     * @param type $idResponsable   Identificador del Funcionario Responsable
     * @param type $plan            Datos del plan
     * 
     * @return type
     */
    public function existePlanFR( $idResponsable, $plan )
    {
        try{
            $db = & JFactory::getDBO();
            $query = $db->getQuery( TRUE );

            $query->select( '   t4.intId_ob             AS idObj, 
                                t4.intIdentidad_ent     AS idEntidad, 
                                t4.intId_pln_objetivo	AS idPlnObjetivo,
                                t1.intId_pi				AS idPlan' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->join( 'INNER', '#__gen_funcionario t2 ON t1.intIdentidad_ent = t2.intIdentidad_ent AND t1.blnVigente_pi = 1' );
            $query->join( 'INNER', '#__gen_ug_funcionario t3 ON t2.intCodigo_fnc = t3.intCodigo_fnc' );
            $query->join( 'INNER', '#__pln_plan_objetivo t4 ON t1.intId_pi = t4.intId_pi' );
            $query->where( 't3.intId_ugf = ' . $idResponsable );
            $query->where( "t1.dteFechainicio_pi = '" . $plan->fchInicio . "'" );
            $query->where( "t1.dteFechafin_pi = '" . $plan->fchFin . "'" );
            $query->order( "t4.intId_ob" );
            
            $db->setQuery( $query );
            $db->query();

            $rst = ( $db->getNumRows() > 0 )? $db->loadObject() 
                                            : FALSE;

            return $rst;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    
    
    public function deleteFuncionarioResponsable( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->delete( '#__ind_funcionario_responsable' );
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