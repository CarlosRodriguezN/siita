<?php

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'indicadorentidad.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'funcionarioresponsable.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'undgestionresponsable.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'rango.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'indicadorut.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'programacion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'programaciondetalle.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'programacionindicador.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'grupoindicador.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'planificacionindicador.php';

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos' . DS . 'objetivo' . DS . 'indicadores' . DS . 'CambiosIndicadorEntidad.php';

class IndicadorEntidad
{

    private $_origen;
    private $_dtaIndicador;
    private $_idIndicador;
    private $_idEntidad;
    private $_dtaIndEntidad;
    private $_idIndEntidad;
    private $_isNew;
    private $_tpoPlan;
    private $_dataObj;

    /**
     * 
     * Contructor de la clase IndicadorEntidad
     * 
     * @param Object $indicador     Datos del indicador
     * @param int $idIndicador      Identificador del Indicador
     * @param int $idEntidad        Identificador de la entidad a la que esta asociado el indicador
     * @param int $origen           Origen de Indicador 1: estrategico, 0: operativo
     * @param int $tpoPlan          Tipo de plan a que esta asociado el indicador ( PEI, PPPP, PAPP, POA, etc )
     * @param int $ban              Bandera de control, 0: si es informacion para gestion, 
     *                                                  1 : si es informacion a eliminar.
     * 
     * @param int $dataObj          Data del Objetivo asociado al indicador
     * 
     */
    public function __construct( $indicador, $idIndicador, $idEntidad, $origen = 0, $tpoPlan = 1, $ban = 0, $dataObj )
    {
        $this->_dtaIndicador= clone $indicador;
        $this->_idIndicador = $idIndicador;
        $this->_idEntidad   = $idEntidad;
        $this->_origen      = $origen;
        $this->_tpoPlan     = $tpoPlan;
        $this->_dataObj     = $dataObj;

        $this->_isNew = ( intval( $this->_dtaIndicador->idIndEntidad ) == 0 )   
                            ? TRUE 
                            : FALSE;

        if( $ban == 0 ){
            //  Seteo informacion del indicador
            $this->_dtaIndEntidad = $this->_setDtaIndEntidad();
            
            //  Gestiono informacion del INDICADOR - ENTIDAD
            $this->_idIndEntidad = $this->_dtaIndicador->idIndEntidad = $this->_registroIndicadorEntidad();
        }

    }

    public function __toString()
    {
        return $this->_dtaIndicador;
    }

    /**
     * 
     * Seteo los datos del Indicador - Entidad
     * 
     * @return type
     */
    private function _setDtaIndEntidad()
    {
        $dtaIndEntidad = array();

        $dtaIndEntidad["intIdIndEntidad"] = ( $this->_isNew )   
                                                ? 0
                                                : $this->_dtaIndicador->idIndEntidad;

        $dtaIndEntidad["intIdentidad_ent"]              = $this->_idEntidad;
        $dtaIndEntidad["intCodigo_ind"]                 = $this->_idIndicador;
        $dtaIndEntidad["intcodigo_per"]                 = $this->_dtaIndicador->idFrcMonitoreo;
        $dtaIndEntidad["dcmValor_ind"]                  = floatval( $this->_dtaIndicador->umbral );
        $dtaIndEntidad["intIdHorizonte_ind"]            = $this->_dtaIndicador->idHorizonte;
        $dtaIndEntidad["dteHorizonteFchInicio_indEnt"]  = $this->_dtaIndicador->fchHorzMimimo;
        $dtaIndEntidad["dteHorizonteFchFin_indEnt"]     = $this->_dtaIndicador->fchHorzMaximo;
        $dtaIndEntidad["fltUmbralMinimo_indEnt"]        = floatval( $this->_dtaIndicador->umbMinimo );
        $dtaIndEntidad["fltUmbralMaximo_indEnt"]        = floatval( $this->_dtaIndicador->umbMaximo );

        $dtaIndEntidad["intTendencia_indEnt"]           = is_null( $this->_dtaIndicador->idTendencia )  
                                                            ? 1 
                                                            : $this->_dtaIndicador->idTendencia;

        $dtaIndEntidad["intIdIEPadre_indEnt"]           = $this->_dtaIndicador->idIEPadre;
        $dtaIndEntidad["strAccesoTableu_indEnt"]        = $this->_dtaIndicador->accesoTableu;
        $dtaIndEntidad["intSenplades_indEnt"]           = $this->_dtaIndicador->senplades;
        $dtaIndEntidad["strAccesoTableu_indEnt"]        = $this->_dtaIndicador->nombreReporte;

        return $dtaIndEntidad;
    }

    /**
     * 
     * Realizo el proceso de registro de un Indicador - Entidad
     * 
     * @return type
     */
    private function _registroIndicadorEntidad()
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            //  Instacio la tabla Identificador - Entidad
            $tbIndEntidad = new jTableIndicadorEntidad( $db );

            //  Gestiono el Registro la Relacion Indicador - Entidad
            $idIndEntidad = $tbIndEntidad->registrarIndicadorEntidad( $this->_dtaIndEntidad );

            $db->transactionCommit();

            return $idIndEntidad;
        }catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     * 
     * Procedimiento de registro de informacion adicional de un Indicador - Entidad 
     * 
     * @return type
     * 
     */
    public function gestionIndicadorEntidad()
    {
        //  Actualizo informacion complementaria del PEI
        $this->_informacionAdicionalInd();

        if( $this->_isNew ){
            $ai = new CambiosIndicadorEntidad(  $this->_dtaIndicador, 
                                                $this->_idEntidad, 
                                                $this->_idIndEntidad, 
                                                $this->_dataObj );

            $ai->creacionIndicadoresHijo();
        }

        return $this->_idIndEntidad;
    }
    
    /**
     * Gestiona Informacion adicional de un indicador
     */
    private function _informacionAdicionalInd()
    {
        if( (int)$this->_dtaIndicador->idUGResponsable != 0 ){
            $this->_gestionUGResponsable();
        }

        if( (int)$this->_dtaIndicador->idResponsable != 0 ){
            $this->_gestionFuncionarioResponsable();
        }

        //  Gestiono informacion de Rangos de Gestion de Indicador - Entidad
        $this->_rangosGestionIndicador();

        //  Registro unidad territorial asociada al indicador
        if( count( (array)$this->_dtaIndicador->lstUndsTerritoriales ) ){
            $this->_unidadTerritorialIndicador();
        }

        //  Registro la propuesta de Programacion para la gestion de un 
        //  determinado Indicador
        $this->_registroProgramacion();

        return;
    }

    
    private function _gestionUGResponsable()
    {
        if( (int)$this->_dtaIndicador->idUGResponsable != (int)$this->_dtaIndicador->oldIdUGResponsable 
            && (int)$this->_dtaIndicador->oldIdUGResponsable != 0 
            && (int)$this->_dtaIndicador->idUGResponsable != 0 ){

            //  Actualizo informacion de unidad de gestion responsable del Indicador Raiz
            $this->_updUndGestionResponsable();
            $ai = new CambiosIndicadorEntidad(  $this->_dtaIndicador, 
                                                $this->_idEntidad, 
                                                $this->_dtaIndicador->_idIndEntidad );

            //  Procedimiento de actualizacion de UG Responsable de los indicadores hijos de este indicador
            $ai->updUGResponsable( $this->_idIndEntidad );

        }else if( (int)$this->_dtaIndicador->idUGResponsable != 0 ){
            $this->_regUGResponsable();
        }

        return;
    }
    
    
    private function _gestionFuncionarioResponsable()
    {
        //  Verifico si a existido un cambio en la UG Responsable del Indicador - Entidad
        if( (int)$this->_dtaIndicador->idResponsable != (int)$this->_dtaIndicador->oldIdResponsable
            && (int)$this->_dtaIndicador->idResponsable != 0
            && (int)$this->_dtaIndicador->oldIdResponsable != 0 ){
            
            $this->_updFuncionarioResponsable(  $this->_idIndEntidad, 
                                                $this->_dtaIndicador, 
                                                $this->_dtaIndicador->oldIdResponsable );
            
            $ai = new CambiosIndicadorEntidad(  $this->_dtaIndicador, 
                                                $this->_idEntidad, 
                                                (int)$this->_dtaIndicador->_idIndEntidad );

            //  Procedimiento de actualizacion de UG Responsables del indicador
            $ai->updFuncionarioResponsable( $this->_idIndEntidad );
            
        }else if( (int)$this->_dtaIndicador->idResponsable != 0 ){
            $this->_regFuncionarioResponsable( $this->_idIndEntidad, $this->_dtaIndicador );
        }

        return;
    }
    
    
    /**
     * 
     * Gestiono el registro de la unidad responsable de un Indicador - Entidad
     * 
     * @return type
     */
    private function _updUndGestionResponsable()
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            //  Cambio la vigencia del anterior Unidad de Gestion Responsable
            $rst = $this->_updVigenciaUndGestion( $this->_idIndEntidad, $this->_dtaIndicador->oldIdUGResponsable );

            if( $rst == TRUE ){
                $idUGResponsable = $this->_regUGResponsable( 1 );
            }

            $db->transactionCommit();

            return $idUGResponsable;
        }catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     * 
     * Registro Informacion de Unidad de Gestion Responsable
     * 
     * @param int $ban  Bandera de control de registro
     *                  0: si es un nuevo registro
     *                  1: si es un registro de actualizacion de vigencia
     * 
     * @return int
     * 
     */
    private function _regUGResponsable( $ban = 0 )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            //  Asocio la nueva unidad de gestion responsable al indicador
            $tbUGR = new jTableUndGestionResponsable( $db );

            //  Informacion de Unidad de gestion responsable del indicador
            $dtaUndGestionResponsable["intId_ugr"]          = ( $ban == 0 ) ? (int)$this->_dtaIndicador->idRegUGR 
                                                                            : 0;

            $dtaUndGestionResponsable["intIdIndEntidad"]    = $this->_idIndEntidad;
            $dtaUndGestionResponsable["intCodigo_ug"]       = $this->_dtaIndicador->idUGResponsable;
            $dtaUndGestionResponsable["dteFechaInicio_ugr"] = $this->_dtaIndicador->fchInicioUG;
            $dtaUndGestionResponsable["inpVigencia_ugr"]    = 1;

            $idUGResponsable = $tbUGR->registrarUndGesResp( $dtaUndGestionResponsable );

            $db->transactionCommit();

            return $idUGResponsable;
        }catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     * 
     * Actualizo a estado NO VIGENTE a una determinada Unidad de Gestion
     * 
     * @param int $idIndEntidad     Identificador del Indicador Entidad a actualizar
     * @param int $idUGActual       Identificador de la ultima UG vigente
     * 
     * @return int
     */
    private function _updVigenciaUndGestion( $idIndEntidad, $idUGActual )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $tbUGR = new jTableUndGestionResponsable( $db );
            $rst = $tbUGR->updVigenciaUndGestion( $idIndEntidad, $idUGActual, date( "Y-m-d H:i:s" ) );

            $db->transactionCommit();

            return $rst;
        }catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     * 
     * Actualizo la informacion del funcionario responsable de un indicador
     * 
     * @param int       $idIndEntidad           Indentificador del Indicador - Entidad a Actualizar
     * @param Object    $dtaIndicador           Data del indicador
     * @param int       $idFunActual            Identificador de Funcionario actualmente responsable del indicador
     * 
     * @return int
     */
    private function _updFuncionarioResponsable( $idIndEntidad, $dtaIndicador, $idFunActual )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            //  Instacio la tabla Funcionario
            $tbFuncionario = new jTableFuncionarioResponsable( $db );

            //  Cambio la vigencia del anterior Funcionario Responsable
            $rst = $tbFuncionario->updVigenciaFuncionario( $idIndEntidad, $idFunActual, date( "Y-m-d H:i:s" ) );

            if( $rst == TRUE ){
                $this->_regFuncionarioResponsable( $idIndEntidad, $dtaIndicador );
            }

            $db->transactionCommit();

            return $rst;
        }catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     * 
     * Gestiona informacion de registro de funcionario
     * 
     * @param int $idIndEntidad     Identificador del indicador Entidad 
     * @param object $dtaIndicador  Datos del indicador  
     * @param int $ban              Bandera de control de registro
     *                                  0: si es un nuevo registro
     *                                  1: si es un registro de actualizacion de vigencia 
     * 
     * @return type
     * 
     */
    private function _regFuncionarioResponsable( $idIndEntidad, $dtaIndicador, $ban = 0 )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $dtaFunRespIndicador[ "intId_fgr" ]         = ( $ban == 0 ) ?$dtaIndicador->idRegFR 
                                                                        : 0;

            $dtaFunRespIndicador[ "intId_ugf" ]         = $dtaIndicador->idResponsable;
            $dtaFunRespIndicador[ "intIdIndEntidad" ]   = $idIndEntidad;
            $dtaFunRespIndicador[ "dteFechaInicio_fgr" ]= $dtaIndicador->fchInicioFuncionario;
            $dtaFunRespIndicador[ "intVigencia_fgr" ]   = 1;

            //  Registro el nuevo funcionario responsable del indicador
            $rst = $this->_registrarFuncionarioResponsable( $dtaFunRespIndicador );

            $db->transactionCommit();

            return $rst;
        }catch( Exception $ex ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     * 
     * Gestiona el registro de un nuevo funcionario responsable de un indicador
     * 
     * @param object $dtaIndVariable      Datos del nuevo funcionario responsable de indicador
     * 
     * @return int
     * 
     */
    private function _registrarFuncionarioResponsable( $dtaIndVariable )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            //  Instacio la tabla Funcionario
            $tbFuncionario = new jTableFuncionarioResponsable( $db );
            $idFuncionario = $tbFuncionario->registrarFuncionarioResponsable( $dtaIndVariable );

            $db->transactionCommit();

            return $idFuncionario;
        }catch( Exception $ex ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     * 
     * Gestiono Informacion de Rangos de Gestion de determinado Indicador - Entidad
     *  
     * @param type $lstRangos       Lista de Rangos de Gestion
     * 
     * @return type
     * 
     */
    private function _rangosGestionIndicador()
    {
        
        $db = JFactory::getDBO();

        //  Instacio la tabla Funcionario
        $tbRangosGestion = new jTableRango( $db );
        
        //  Elimino todos los rangos asociados un determinado indicador entidad
        $tbRangosGestion->deleteAllRangos( $this->_idIndEntidad );
        
        //  Recorro la lista
        if( count( $this->_dtaIndicador->lstRangos ) > 0 ){
            $this->_regRangosIndicador();
        }

        return;
    }

    
    private function _regRangosIndicador()
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Funcionario
        $tbRangosGestion = new jTableRango( $db );
        
        foreach( $this->_dtaIndicador->lstRangos as $rango ){
            $tbRangosGestion->registrarRango( $this->_idIndEntidad, $rango );
        }

        return;
    }
    

    /**
     * 
     * Gestion de Unidad Territorial
     * 
     * @param array $lstUT           Lista de Unidades territoriales
     * 
     * @return type
     * 
     */
    private function _unidadTerritorialIndicador()
    {
        $ai = new CambiosIndicadorEntidad(  $this->_dtaIndicador, 
                                            $this->_idEntidad, 
                                            $this->_dtaIndicador->idIndEntidad );

        //  elimino las UT asociadas al indicador
        $ai->delUndTerritorialIndicador( $this->_idIndEntidad );
        
        //  Registro las nuevas UT asociadas al indicador
        $ai->addUTIndicador( $this->_idIndEntidad, $this->_dtaIndicador->lstUndsTerritoriales );
        
        //  Procedimiento de actualizacion de UG Responsable de los indicadores hijos de este indicador
        $ai->updUndTerritorial( $this->_idIndEntidad, 
                                $this->_dtaIndicador->lstUndsTerritoriales );

        return;
    }

    /**
     * 
     * Gestiono el registro de planificacion de un indicador
     * 
     * @param type $lstProgramacion     Datos de Planificacion de un Indicador
     * 
     * @return type
     * 
     */
    private function _registroProgramacion()
    {
        $db = JFactory::getDBO();

        if( count( (array)$this->_dtaIndicador->lstPlanificacion ) > 0 ){
            
            foreach( $this->_dtaIndicador->lstPlanificacion as $planificacion ){
                $dtaPlanificacion = (object)$planificacion;
                $tbPlanificacion = new jTablePlanificacionIndicador( $db );

                $dtaProgramacion["intId_plan"]      = $dtaPlanificacion->idPln;
                $dtaProgramacion["intIdIndEntidad"] = $this->_idIndEntidad;
                $dtaProgramacion["dteFecha_plan"]   = $dtaPlanificacion->fecha;
                $dtaProgramacion["dcmValor_plan"]   = $dtaPlanificacion->valor;

                $idPln = $tbPlanificacion->registroPlanificacion( $dtaProgramacion );
                
                //  Si tiene Hijos actualizo informacion de planificacion
                if( $idPln ){
                    $this->_updDtaProgramacion( $dtaPlanificacion );
                }

            }
        }else{
            $objProrrateo = new ProrrateoIndicador( $this->_dtaIndicador, 
                                                    $this->_dtaIndicador->fchHorzMimimo, 
                                                    $this->_dtaIndicador->fchHorzMaximo, 
                                                    $this->_tpoPlan );

            $dtaProrrateoInd = $objProrrateo->prorrateoIndicador();
            
            if( $dtaProrrateoInd != false ){
                foreach( $dtaProrrateoInd as $dtaProrrateo ){
                    $tbPlanificacion = new jTablePlanificacionIndicador( $db );

                    $dtaProgramacion["intId_plan"]      = 0;
                    $dtaProgramacion["dteFecha_plan"]   = $dtaProrrateo->fchFin;
                    $dtaProgramacion["dcmValor_plan"]   = $dtaProrrateo->meta;
                    $dtaProgramacion["intIdIndEntidad"] = $this->_idIndEntidad;

                    $tbPlanificacion->registroPlanificacion( $dtaProgramacion );
                }
            }
        }

        return;
    }

    
    private function _updDtaProgramacion( $dtaPlanificacion )
    {
        $ai = new CambiosIndicadorEntidad(  $this->_dtaIndicador, 
                                            $this->_idEntidad, 
                                            $this->_idIndEntidad );
        
        return $ai->updDtaProgramacion( $this->_idIndEntidad, $dtaPlanificacion );
    }
    
        
    
    
    /**
     * 
     * Gestiona la creacion de informacion asociada a un determinado Indicador
     * 
     * @return type
     */
    public function gcIndicadorEntidad()
    {
        $ai = new CambiosIndicadorEntidad( $this->_dtaIndicador, $this->_idEntidad, $this->_idIndEntidad );

        //  En caso que el indicador sea nuevo genero los indicadores asociados( hijos ) a este indicador
        if( $this->_isNew ){
            $ai->creacionIndicadoresHijo();
        }else{
            //  Verifico si ha existido un cambio en Umbral o en Lineas Base asociadas al Indicador - Entidad
            if( $this->_dtaIndicador->umbral != $this->_dtaIndicador->oldUmbral ){
                $ai->updUmbral();
            }

            //  Verifico si a existido un cambio en la UG Responsable del Indicador - Entidad
            if( $this->_dtaIndicador->idUGResponsable != $this->_dtaIndicador->oldIdUGResponsable ){
                //  Procedimiento de actualizacion de UG Responsables del indicador
                $ai->updUGResponsable( $this->_idIndEntidad );
            }

            //  Verifico si a existido un cambio en funcionario responsable del Indicador - Entidad
            if( $this->_dtaIndicador->idResponsable != $this->_dtaIndicador->oldIdResponsable || $this->_dtaIndicador->idResponsableUG != $this->_dtaIndicador->oldIdResponsableUG ){
                //  Procedimiento de actualizacion de Funcionarios Responsables del indicador
                $ai->funcionarioResponsable( $this->_idIndEntidad );
            }

            //  Verifico si a existido un cambio en las fechas de inicio "o" fin del indicador
            if( strtotime( $this->_dtaIndicador->fchHorzMimimo ) != strtotime( $this->_dtaIndicador->oldFchHorzMimimo ) || strtotime( $this->_dtaIndicador->fchHorzMaximo ) != strtotime( $this->_dtaIndicador->oldFchHorzMaximo ) ){
                //  Cambio la vigencia de los indicadores
                $ai->updVigenciaIndicadores( $this->_dtaIndicador->idIndEntidad );

                //  Vuelvo a crear los indicadores
                $ai->creacionIndicadoresHijo();
            }
        }

        return;
    }

    private function _cambiosEnVariables()
    {
        $lstVariables = $this->_dtaIndicador->lstVariables;

        foreach( $lstVariables as $variable ){
            $ai = new CambiosIndicador( $this->_dtaIndicador, $this->_idEntidad, $this->_idIndEntidad );
        }
    }

    public function eliminacionIndicadorEntidad( $idIE = null )
    {
        $idIndEntidad = ( $idIE == null )   ? $this->_dtaIndicador->idIndEntidad 
                                            : $idIE;

        //  Eliminacion Planificacion
        $this->_deletePlanificacion( $idIndEntidad );

        //  Eliminacion Unidad de Gestion Responsable
        $this->_deleteUnidadGestionResponsable( $idIndEntidad );

        //  Eliminacion Funcionario Responsable
        $this->_deleteFuncionarioResponsable( $idIndEntidad );

        //  Eliminacion Unidad Territorial asociada al indicador
        $this->_deleteUnidadTerritorial( $idIndEntidad );

        //  Eliminacion de Indicador
        $this->_deleteIndicador( $idIndEntidad );

        //  Eliminacion de Indicador
        $this->_deleteIndicadorEntidad( $idIndEntidad );

        return;
    }

    /**
     * 
     * Gestiona la planificacion del Indicador Entidad
     * 
     * @param int $idIndEntidad     Identificador del Indicador Entidad
     * 
     * @return int
     * 
     */
    private function _deletePlanificacion( $idIndEntidad )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Identificador - variable
        $tbPlanificacion = new jTablePlanificacionIndicador( $db );

        return $tbPlanificacion->deletePlanificacion( $idIndEntidad );
    }

    private function _deleteUnidadGestionResponsable( $idIndEntidad )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Identificador - variable
        $tbUGR = new jTableUndGestionResponsable( $db );

        return $tbUGR->deleteUGResponsable( $idIndEntidad );
    }

    private function _deleteFuncionarioResponsable( $idIndEntidad )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Identificador - variable
        $tbFR = new jTableFuncionarioResponsable( $db );

        return $tbFR->deleteFuncionarioResponsable( $idIndEntidad );
    }

    private function _deleteUnidadTerritorial( $idIndEntidad )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Identificador - variable
        $tbUT = new jTableIndicadorUT( $db );

        return $tbUT->deleteUnidadTerritorial( $idIndEntidad );
    }

    /**
     * 
     * Gestiono la eliminacion de un Indicador asociado a un Indicador - Entidad
     * 
     * @param int $idIndEntidad     Identificador del Indicador - Entidad
     * 
     * @return int
     * 
     */
    private function _deleteIndicador( $idIndEntidad )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Identificador - variable
        $tbIndicador = new jTableIndicador( $db );

        return $tbIndicador->deleteIndicador( $idIndEntidad );
    }

    /**
     * 
     * Gestiono la eliminacion de Indicador Entidad
     * 
     * @param type $idIndEntidad    idenfificador del Indicador Entidad
     */
    private function _deleteIndicadorEntidad( $idIndEntidad )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Identificador - variable
        $tbIndicador = new jTableIndicadorEntidad( $db );

        return $tbIndicador->deleteIndEntidad( $idIndEntidad );
    }

    /**
     * 
     * Destructor de la clase IndicadorEntidad
     * 
     * @return type
     * 
     */
    public function __destruct()
    {
        return;
    }

}
