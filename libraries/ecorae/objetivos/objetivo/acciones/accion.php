<?php

jimport( 'ecorae.planinstitucion.plnopranual' );
//  Importa la tabla ACCION 
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'plnaccion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'plnobjaccion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'unidadgestion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'accfncresponsable.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'accugresponsable.php';

/**
 * Gestiona la accion
 */
class Accion
{
    private $_origen;

    public function __construct( $origen = 0 )
    {
        $this->_origen = $origen;
    }

    /**
     *  Registra la una accion de un objetivo
     * 
     *  @param  Object      $accion          Datos de la accion
     *  @param  int         $planObjetivo    Idetificador del objetivo
     * 
     *  @return int         Identificador de la accion.
     * 
     */
    public function saveAccion( $accion, $planObjetivo )
    {
        try{
            if( !is_null( $accion->idAccion ) ){
                
                $db = JFactory::getDBO();
                $db->transactionStart();

                $tblPlnAcc = new jTablePlnAccion( $db );
                $newAcc = ( (int)$accion->idAccion == 0 )   ? true 
                                                            : false;

                //  Registro dta general de Accion
                $accion->idAccion = $tblPlnAcc->registroAccion( $accion );

                //  Registro asociacion entre el objetivo y la accion 
                $accion->idPlnObjAccion = $this->registroPlanObjetivoAccion(    $accion,
                                                                                $planObjetivo->idPlnObjetivo );

                $accion->idPlnObjetivo  = $planObjetivo->idPlnObjetivo;

                //  Registro la Unidad de Gestion Responsable de la Accion
                $this->_registrarUniGesRes( $accion, $planObjetivo );

                //  Registro al Funcionario Responsable de la Accion
                $this->_registrarFuncionarioRes( $accion, $planObjetivo );

                //  Si es una nueva accion creo los planes complementarios
                if( $newAcc ){
                    $this->_creacionPlanesComplementarios( $accion, $planObjetivo );
                }else{
                    //  Actualizo informacion general de una accion y de sus acciones hijas
                    $this->_updDtaGralAccion( $accion, $planObjetivo );
                    
                    //  valido si existen procesos de upd las UGR acciones hijas
                    $this->_updUGRAccionesHijas( $accion, $planObjetivo );

                    //  valido si existen procesos de upd las FR acciones hijas
                    $this->_updFRAccionesHijas( $accion, $planObjetivo );

                    //  valido si existen procesos de upd de las acciones transformadas en objetivos
                    $this->_updUGRAccionesTransfObjetivos( $accion, $planObjetivo );

                    //  valido si existen procesos de upd de las acciones transformadas en objetivos
                    $this->_updFRAccionesTransfObjetivos( $accion, $planObjetivo );
                }

                $db->transactionCommit();
            }

            return $accion;
        }catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    
    /**
     * 
     * Retorna Lista de Planes asociados a un determinado Objetivo
     * 
     * @param int $idEntidad    Identificador de la entidad del Objetivo
     * 
     * @return array
     * 
     */
    private function _getLstPlanesAcciones( $idEntidad )
    {
        $db = JFactory::getDBO();
        $tbPlnObjetivo = new jTablePlanObjetivo( $db );

        return $tbPlnObjetivo->getPlnObjetivosAcciones( $idEntidad );
    }
    
    
    
    private function _creacionPlanesComplementarios( $accion, $planObjetivo )
    {
        $lstPlanesAcciones = $this->_getLstPlanesAcciones( $planObjetivo->idEntidad );
        
        if( count( $lstPlanesAcciones ) ){
            foreach( $lstPlanesAcciones as $plnAccion ){
                
                //  Validamos que el plan no sea POA - UG ( 2 ) y POA - F ( 6 ), 
                //  debido a que las acciones en estos planes se transforman en objetivos del plan.
                if( $plnAccion->idTpoPln != 2 && $plnAccion->idTpoPln != 6 ){
                    
                    $nPlanObjetivo = new stdClass();
                    $this->_crearAcciones( $accion, $plnAccion );

                    $nPlanObjetivo->idObj        = $plnAccion->idObjetivo;
                    $nPlanObjetivo->idEntidad    = $plnAccion->idEntidad;
                    $nPlanObjetivo->idPlnObjetivo= $plnAccion->idPlnObjetivo;

                    if( $plnAccion->idTpoPln == 3 ){
                        //  Creo POA UG
                        $this->_crearPoaUg( $accion, $plnAccion );

                        //  Creo POA FR
                        $this->_crearPoaFuncionario( $accion, $plnAccion );
                    }

                    $this->_creacionPlanesComplementarios( $accion, $nPlanObjetivo );
                }
                
            }
        }

        return;
    }
    
    
    /**
     * 
     * Crear nuevas acciones
     * 
     * @param object $dtaAccion
     * @param object $plan
     * 
     * @return type
     */
    private function _crearAcciones( $dtaAccion, $plan )
    {
        $plnObjetivo = new stdClass();
        
        $plnObjetivo->idObj        = $plan->idObjetivo;
        $plnObjetivo->idEntidad    = $plan->idEntidad;
        $plnObjetivo->idPlnObjetivo= $plan->idPlnObjetivo;

        //  Creo una nueva Accion
        $objAccion = new PlanAccion( $dtaAccion, $plan->fchInicio, $plan->fchFin, $plan->idTpoPln );

        //  Instancio la clase accion que gestiona informacion de acciones
        $oAccion = new Accion();

        return $oAccion->saveAccion( $objAccion->__toString(), $plnObjetivo );
    }
    

    /**
     * 
     * Creo POA - UG
     * 
     * @param object $accion        Data 
     * @param object $plnAccion
     * @return type
     */
    private function _crearPoaUg( $accion, $plnAccion )
    {
        $dtaObjAccion = new stdClass();
        
        //  Seteo Solamente la descripcion del Objetivo ya que estamos creando de una Accion un Objetivo
        $dtaObjAccion->idObjetivo   = 0;
        $dtaObjAccion->idTpoObj     = 1;
        $dtaObjAccion->descObjetivo = $accion->descripcionAccion;
        $dtaObjAccion->idEntidad    = $plnAccion->idEntidad;
        $dtaObjAccion->lstAcciones  = array();

        //  Creo un nuevo objetivo con la informacion de una accion
        $objAccionObjetivo = new PlanObjetivo(  $dtaObjAccion, 
                                                $plnAccion->fchInicio, 
                                                $plnAccion->fchFin, 2, 2 );

        //  Verifico si existe un plan de vigente asignado a una unidad de gestion en un determinado periodo de tiempo
        //  en caso que "NO EXISTA" "LO CREO" un nuevo plan de tipo POA - UG y le asigno el objetivo creado
        $dtaPlanUG = $this->_existePlanUG( $accion->idUniGes, $plnAccion );
        
        if( $dtaPlanUG == false ){
            //  Obtengo informacion de la unidad de gestion
            $dtaUG = $this->_getDtaUGR( $accion->idUniGes );

            //  Creo y registro el nuevo Plan - UG
            $this->_crearPlanUG( $dtaUG, $plnAccion, $objAccionObjetivo );
        }else{
            $this->_registroPlanObjetivo(   $objAccionObjetivo->__toString(), 
                                            $dtaPlanUG->idPlan );
        }

        return;
    }
    
    
    private function _existePlanUG( $idUGResponsable, $plan )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();
        $tbUGR = new jTableUndGestionResponsable( $db );

        return $tbUGR->_existePlanUG( $idUGResponsable, $plan );
    }
    
    
    
    
    private function _crearPlanUG( $dtaUG, $plan, $objAccionObjetivo = null )
    {
        $dtaPoaUGR = new stdClass();
        $datePOA = new DateTime( $plan->fchInicio );

        $dtaPoaUGR->idPln              = 0;
        $dtaPoaUGR->intId_tpoPlan      = 2;
        $dtaPoaUGR->intCodigo_ins      = 1;
        $dtaPoaUGR->intIdPadre_pi      = $plan->idPlan;
        $dtaPoaUGR->intIdentidad_ent   = $dtaUG->idEntidadUG;
        $dtaPoaUGR->strDescripcion_pi   = 'POA_' . $dtaUG->nombreUG . '_' . $datePOA->format( 'Y' );
        $dtaPoaUGR->dteFechainicio_pi   = $plan->fchInicio;
        $dtaPoaUGR->dteFechafin_pi      = $plan->fchFin;

        //  Obtengo informacion del objetivo 
        //  posteriormente vamos a setear informacion de indicadores con el indicador prorrateado
        $dtaPoaUGR->lstObjetivos[]   = ( is_null( $objAccionObjetivo ) ) 
                                            ? new stdClass()
                                            : $objAccionObjetivo->__toString();

        return $this->_guardarPlanPoa( $dtaPoaUGR );
    }
    
    
    
    private function _guardarPlanPoa( $dtaPlan )
    {
        //  Obtengo el idRegistro Registro un nuevo Plan de tipo POA
        $idPlanPoa = $this->_regDtaPlanPoa( $dtaPlan );

        if( $idPlanPoa ){
            //  Identificador del Tpo de Entidad PEI
            $idTpoPEI = 1;

            if( count( $dtaPlan->lstObjetivos ) ){
                
                foreach( $dtaPlan->lstObjetivos as $objetivo ){

                    if( !is_null( $objetivo->idPlnObjetivo ) ){
                        //  Creo el Objeto Objetivo que gestiona informacion de un objetivo
                        $objObjetivo = new Objetivo();
                        $dtaObjetivo = new stdClass();

                        //  Registro la relacion entre un plan y un objetivo
                        $idPlanObjetivo = $objObjetivo->registroPlanObjetivo(   $objetivo->idPlnObjetivo, 
                                                                                $objetivo->idObjetivo, 
                                                                                $idPlanPoa, 
                                                                                $objetivo->idEntidad, 
                                                                                $objetivo->idPadreObj );

                        $dtaObjetivo->idObj         = $objetivo->idObjetivo;
                        $dtaObjetivo->idEntidad     = $objetivo->idEntidad;
                        $dtaObjetivo->idPlnObjetivo = $idPlanObjetivo;

                        $objObjetivo->registroObjetivosProrrateados(    $objetivo, 
                                                                        $dtaObjetivo );
                    }
                    
                }
            }

        } else{
            $idPlanPoa = array();
        }

        return $idPlanPoa;
    }
    
    
    
    /**
     * 
     * Registro Informacion Plan de Objetivo
     * 
     * @param Object $objetivo      Datos del Objetivo a Registrar
     * @param int $idPlan           Identificador del Plan al que pertenece
     * 
     * @return type
     * 
     */
    private function _registroPlanObjetivo( $objetivo, $idPlan )
    {
        //  Creo el Objeto Objetivo que gestiona informacion de un objetivo
        $objObjetivo = new Objetivo();
        $dtaObjetivo = new stdClass();

        //  Registro el Plan Objetivo
        $idPlanObjetivo = $objObjetivo->registroPlanObjetivo(   $objetivo->idPlnObjetivo, 
                                                                $objetivo->idObjetivo, 
                                                                $idPlan, 
                                                                $objetivo->idEntidad, 
                                                                $objetivo->idPadreObj );

        $dtaObjetivo->idObj         = $objetivo->idObjetivo;
        $dtaObjetivo->idEntidad     = $objetivo->idEntidad;
        $dtaObjetivo->idPlnObjetivo = $idPlanObjetivo;
        
        return $objObjetivo->registroObjetivosProrrateados( $objetivo, $dtaObjetivo, 3 );
    }
    
    
    /**
     * 
     *  Registro Informacion general de un plan
     * 
     *  @param JSon $dtaPlan     Informacion General de un Plan
     */
    private function _regDtaPlanPoa( $dtaPlan )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $tbPlan = new jTablePlanInstitucion( $db );

            //  Arma el Objeto Plan de tipo POA
            $dta["intId_pi"]            = $dtaPlan->idPln;
            $dta["intId_tpoPlan"]       = $dtaPlan->intId_tpoPlan;
            $dta["intCodigo_ins"]       = $dtaPlan->intCodigo_ins;
            $dta["intIdentidad_ent"]    = $dtaPlan->intIdentidad_ent;
            $dta["strDescripcion_pi"]   = $dtaPlan->strDescripcion_pi;
            $dta["dteFechainicio_pi"]   = $dtaPlan->dteFechainicio_pi;
            $dta["dteFechafin_pi"]      = $dtaPlan->dteFechafin_pi;
            $dta["intIdPadre_pi"]       = $dtaPlan->intIdPadre_pi;

            $rst = $tbPlan->registroPlan( $dta );

            $db->transactionCommit();
            return $rst;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }
    
    /**
     * 
     * Verifico la existencia del POA de una determinada Unidad de Gestion
     * 
     * @param int $idUGR        Identificador de la Unidad de Gestion - Responsable
     * @param object $plan      Informacion del periodo de tiempo a verificar
     * 
     * @return type
     * 
     */
    private function _getDtaUGR( $idUGR )
    {
        $db = JFactory::getDBO();
        $tbUG = new JTableUnidadGestion( $db );
        $dtaUG = $tbUG->getEntidadUG( $idUGR );

        return $dtaUG;
    }
    
    
    
    /**
     *  Registra el ingreso o actualizacion de un funcionario responsable de una acción
     * @param type $objAccion           Objeto accion con la data correspondiente
     * @param type $newAcc              Bandera en true si es nueva accion
     * @return type
     */
    private function _registrarFuncionarioRes( $dtaAccion, $planObjetivo )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();
            $objAccion = clone $dtaAccion;

            $tbFR = new jTableAccFncResponsable( $db );

            if( (int)$objAccion->idFunResp != (int)$objAccion->oldIdFunResp ){
                $tbFR->updAvalibleFunRes( $objAccion->idAccion );

                //  Registra una nueva relacion entre la acion y su unidad de gestion responsable
                $retval = $tbFR->registroFunRes( $objAccion );
            }

            $db->transactionCommit();
            
            return $retval;

        }catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }
    
    
    
    private function _updDtaGralAccion( $dtaAccion, $planObjetivo )
    {
        $accion = clone $dtaAccion;
        $idEntObjetivo = ( (int)$dtaAccion->idEntObjetivo == 0 ) 
                                ? $planObjetivo->idEntidad 
                                : $dtaAccion->idEntObjetivo;

        //  Obtengo lista de Acciones Hijas
        $lstAccionesHijas = $this->_getAccionesHijas( $idEntObjetivo, $dtaAccion->idAccion );

        if( count( $lstAccionesHijas ) ){
            foreach( $lstAccionesHijas as $dah ){
                $db = JFactory::getDBO();
                $tblPlnAcc = new jTablePlnAccion( $db );

                $accion->idAccion       = $dah->idAccion;
                $accion->idEntObjetivo  = $dah->idEntObjetivo;

                //  Registro dta general de Accion
                $tblPlnAcc->registroAccion( $accion );
            }
        }

        return;
    }
    
    
    
    
    
    private function _updUGRAccionesHijas( $dtaAccion, $planObjetivo )
    {
        $accion = clone $dtaAccion;
        $idEntObjetivo = ( (int)$dtaAccion->idEntObjetivo == 0 ) 
                                ? $planObjetivo->idEntidad 
                                : $dtaAccion->idEntObjetivo;

        //  Obtengo lista de Acciones Hijas
        $lstAccionesHijas = $this->_getAccionesHijas( $idEntObjetivo, $dtaAccion->idAccion );
        
        if( count( $lstAccionesHijas ) ){
            foreach( $lstAccionesHijas as $dah ){
                $accion->idAccion       = $dah->idAccion;
                $accion->idEntObjetivo  = $dah->idEntObjetivo;

                $this->_registrarUniGesRes( $accion, $planObjetivo );
                
                $this->_updUGRAccionesHijas( $accion, $planObjetivo );
            }
        }

        return;
    }
    
    
    private function _updFRAccionesHijas( $dtaAccion, $planObjetivo )
    {
        $accion = clone $dtaAccion;
        $idEntObjetivo = ( (int)$dtaAccion->idEntObjetivo == 0 ) 
                                ? $planObjetivo->idEntidad 
                                : $dtaAccion->idEntObjetivo;

        //  Obtengo lista de Acciones Hijas
        $lstAccionesHijas = $this->_getAccionesHijas( $idEntObjetivo, $dtaAccion->idAccion );
        
        if( count( $lstAccionesHijas ) ){
            foreach( $lstAccionesHijas as $dah ){
                $accion->idAccion       = $dah->idAccion;
                $accion->idEntObjetivo  = $dah->idEntObjetivo;

                $this->_registrarFuncionarioRes( $accion, $planObjetivo );
                
                $this->_updFRAccionesHijas( $accion, $planObjetivo );
            }
        }

        return;
    }

    
    private function _getAccionesHijas( $idEntObjetivo, $idAccion )
    {
        $db = JFactory::getDBO();
        $tblPlnAcc = new jTablePlnAccion( $db );
        
        return $tblPlnAcc->getAccionesHijas( $idEntObjetivo, $idAccion );
    }
    
    
    
    private function _updUGRAccionesTransfObjetivos( $dtaAccion, $planObjetivo )
    {
        $accion = clone $dtaAccion;
        $idEntObjetivo = ( (int)$dtaAccion->idEntObjetivo == 0 ) 
                                ? $planObjetivo->idEntidad 
                                : $dtaAccion->idEntObjetivo;

        //  Obtengo lista de Acciones transformados en objetivos
        $lstATO = $this->_getATO(   $idEntObjetivo, 
                                    $dtaAccion );
        
        if( count( $lstATO ) ){
            foreach( $lstATO as $ato ){
                
                //  Verifico si el plan es POA - UG y si tenemos que actualizar la UGResponsable
                if( (int)$ato->idTpoPlan == 2 
                    && ( (int)$accion->idUniGes != (int)$accion->oldIdUniGes )
                    && $ato->idObj != $planObjetivo->idObj ){
                    
                    //  Verifico si existe un plan asociado a la nueva UGResponsable
                    $dtaPlanUG = $this->_existePlanUG( $accion->idUniGes, $ato );

                    if( $dtaPlanUG == false ){
                        //  Obtengo informacion de la unidad de gestion
                        $dtaUG = $this->_getDtaUGR( $accion->idUniGes );

                        //  Creo y registro el nuevo Plan - UG
                        $idPlanUpd = $this->_crearPlanUG( $dtaUG, $ato, null );
                    }else{
                        $idPlanUpd = $dtaPlanUG->idPlan;
                    }
                    
                    //  Actualizo el identificador del plan
                    $this->_updPlanObjetivo( $idPlanUpd, $ato );
                }

                //  Recursiva a los hijos
                $accion->idEntObjetivo  = $ato->idEntObj;
                $this->_updUGRAccionesTransfObjetivos( $accion, $planObjetivo );
            }
        }

        return;
    }

    
    
    
    private function _updFRAccionesTransfObjetivos( $dtaAccion, $planObjetivo )
    {
        $accion = clone $dtaAccion;
        $idEntObjetivo = ( (int)$dtaAccion->idEntObjetivo == 0 ) 
                                ? $planObjetivo->idEntidad 
                                : $dtaAccion->idEntObjetivo;

        //  Obtengo lista de Acciones transformados en objetivos
        $lstATO = $this->_getATO(   $idEntObjetivo, 
                                    $dtaAccion );

        if( count( $lstATO ) ){
            foreach( $lstATO as $ato ){
                //  Verifico si el plan es POA - Funcionario y si tenemos que actualizar al FResponsable
                if( (int)$ato->idTpoPlan == 6 
                    && ( (int)$accion->idFunResp != (int)$accion->oldIdFunResp ) 
                    && $ato->idObj != $planObjetivo->idObj ){
                    
                    //  Verifico si existe un plan asociado a la nueva FResponsable
                    $dtaPlanFR = $this->_existePlanFuncionario( $accion->idFunResp, $ato );

                    if( $dtaPlanFR == false ){
                        //  Obtengo informacion de la unidad de gestion
                        $dtaFR = $this->_getDtaFR( $accion->idFunResp );

                        //  Creo y registro el nuevo Plan - UG
                        $idPlanUpd = $this->_crearPlanFR( $dtaFR, $ato, null );
                    }else{
                        $idPlanUpd = $dtaPlanFR->idPlan;
                    }
                    
                    //  Actualizo el identificador del plan
                    $this->_updPlanObjetivo( $idPlanUpd, $ato );
                }

                //  Recursiva a los hijos
                $accion->idEntObjetivo  = $ato->idEntObj;
                $this->_updFRAccionesTransfObjetivos( $accion, $planObjetivo );
            }
        }

        return;
    }
    
    private function _getATO( $idEntObjetivo, $dtaAccion )
    {
        $db = JFactory::getDBO();
        $tblPlnAcc = new jTablePlnAccion( $db );
        
        return $tblPlnAcc->getATO( $idEntObjetivo, $dtaAccion );
    }

    
    private function _updPlanObjetivo( $idPlanUpd, $ato )
    {
        $db = JFactory::getDBO();
        $tblPlnAcc = new jTablePlanObjetivo( $db );

        return $tblPlnAcc->updPlanObjetivo( $idPlanUpd, $ato );
    }

    /**
     *  Registra el ingreso o actualizacion de una unidad de gestión responsable de una acción
     * @param type $objAccion           Obj accion
     * @param type $newAcc              Bandera en true si es nueva accion
     */
    private function _registrarUniGesRes( $dtaAccion, $planObjetivo )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $objAccion = clone $dtaAccion;
            
            if( (int)$objAccion->idUniGes != (int)$objAccion->oldIdUniGes ){
                
                $tbUGR = new jTableAccUGResponsable( $db );
                
                //  Actualizo la vigencia a Cero "0" de UGR - Accion
                $tbUGR->updDateUGR( $objAccion->idAccion );

                //  Registra una nueva relacion entre la accion y su unidad de gestion responsable
                $retval = $tbUGR->registroUniGesRes( $objAccion );
            }
            
            $db->transactionCommit();
            
            return $retval;

        }catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     *  Recupera la lista de acciones de un objetivo
     * 
     * @param int       $idPlanObjetivo      Identificador del objetivo.
     * @return Array                Lista de acciones
     */
    public function getLstAcciones( $idPlanObjetivo )
    {
        $db = JFactory::getDBO();
        $tbPlanAccion = new jTablePlnAccion( $db );
        $result = $tbPlanAccion->getPlanAccion( $idPlanObjetivo );
        return $result;
    }

    /**
     *  Recupera la lista de acciones de un objetivo
     * 
     * @param int       $idPlanObjetivo      Identificador del objetivo.
     * @return Array                Lista de acciones
     */
    public function getLstAccionesUG( $idPlanObjetivo, $idEntUG )
    {
        $db = JFactory::getDBO();
        $tbPlanAccion = new jTablePlnAccion( $db );
        $result = $tbPlanAccion->getPlanAccionUG( $idPlanObjetivo, $idEntUG );
        return $result;
    }


    /**
     * 
     * Registro la relacion entre una accion y el objetivo al que pertenece
     * 
     * @param object $dtaAccion     Objecto con informacion de Accion
     * @param int $idPlnObjetivo    id Plan Objetivo
     * 
     * @return int
     * 
     */
    public function registroPlanObjetivoAccion( $dtaAccion,
                                                $idPlnObjetivo )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $tbPlnObjAcc = new jTablePlnObjAccion( $db );

            $data['intId_plnobj_accion']  = ( (int)$dtaAccion->idPlnObjAccion == 0 ) 
                                                    ? 0
                                                    : $dtaAccion->idPlnObjAccion;

            $data['intId_plnAccion']    = (int)$dtaAccion->idAccion;
            $data['intId_pln_objetivo'] = (int)$idPlnObjetivo;

            $idPlanObjAccion = $tbPlnObjAcc->registroPlnObjAccion( $data );

            $db->transactionCommit();

            return $idPlanObjAccion;
        }catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     *  Obtiene la lista de Poas relacionados a una unidad de gestión
     * 
     * @param int       $entidadUG      Id de la Unidad de Gestión
     * @return type
     */
    public function getPoasOldUGR( $entidadUG )
    {
        $db = JFactory::getDBO();

        $tbPlanUG = new JTableUnidadGestion( $db );
        $result = $tbPlanUG->getLstPoas( $entidadUG );
        return $result;
    }

    public function updDateUGR( $idAccUGR, $fechaIni )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $tbPlanAccion = new jTablePlnAccion( $db );
            $result = $tbPlanAccion->updDateUGR( $idAccUGR, $fechaIni );

            $db->transactionCommit();

            return $result;
        }catch( Exception $ex ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    public function delAccPoa( $idAccion, $anioOld )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $tbPlanAccion = new jTablePlnAccion( $db );
            $result = $tbPlanAccion->delAccionPoas( $idAccion, $anioOld );

            $db->transactionCommit();

            return $result;
        }catch( Exception $ex ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     * 
     *  Verifico si a existido un en la informacion de la accion a registrar
     * 
     *  @param int $idAccion         Identificador de la Accion
     *  @param object $dtaAccion     Datos de la accion a registrar
     * 
     *  @return int     Bandera de cambio
     *                      0: Sin Cambio
     *                      1: Cambio en la fecha de Fin de la Accion
     *                      2: Cambio en el Presupuesto de la accion
     *                      3: Cambio en Fecha como en Presupuesto
     * 
     */
    private function _cambioRegistroAccion( $idAccion, $dtaAccion )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $tbAccion = new jTablePlnAccion( $db );
            $banCambios = $tbAccion->revisionCambios( $idAccion, $dtaAccion );

            $db->transactionCommit();

            return $banCambios;
        }catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }
    
    public function deleteAccion( $idAccion )
    {
        $db = JFactory::getDBO();
        $tbPlanAccion = new jTablePlnAccion( $db );

        return $tbPlanAccion->delAccionPoas( $idAccion );
    }

    
    
    public function _crearPoaFuncionario( $accion, $plnAccion )
    {
        $dtaObjAccion = new stdClass();
        
        //  Seteo Solamente la descripcion del Objetivo ya que estamos creando de una Accion un Objetivo
        $dtaObjAccion->idObjetivo   = 0;
        $dtaObjAccion->idTpoObj     = 1;
        $dtaObjAccion->descObjetivo = $accion->descripcionAccion;
        $dtaObjAccion->idEntidad    = $plnAccion->idEntidad;
        $dtaObjAccion->lstAcciones  = array();
        
        //  Creo un nuevo objetivo con la informacion de una accion
        $objAccionObjetivo = new PlanObjetivo(  $dtaObjAccion, 
                                                $plnAccion->fchInicio, 
                                                $plnAccion->fchFin, 2, 2 );
        
        //  Verifico si existe un plan de vigente asignado a un Funcionario Responsable en un determinado periodo de tiempo
        //  en caso que "NO EXISTA" "LO CREO" un nuevo plan de tipo POA - FR y le asigno el objetivo creado
        $dtaPlanFR = $this->_existePlanFuncionario( $accion->idFunResp, $plnAccion );
        
        if( $dtaPlanFR == false ){
            //  Obtengo informacion de la unidad de gestion
            $dtaFR = $this->_getDtaFR( $accion->idFunResp );

            //  Creo y registro el nuevo Plan - FR
            $this->_crearPlanFR( $dtaFR, $plnAccion, $objAccionObjetivo );
        }else{
            $this->_registroPlanObjetivo( $objAccionObjetivo->__toString(), $dtaPlanFR->idPlan );
        }

        return;
        
    }
    
    
    private function _existePlanFuncionario( $idFuncionario, $plnAccion )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();
        $tbUGR = new jTableFuncionarioResponsable( $db );

        return $tbUGR->existePlanFR( $idFuncionario, $plnAccion );
    }

    
    /**
     * 
     * Verifico la existencia del POA de un determinado Funcionario
     * 
     * @param int $idFuncionario        Identificador del Funcionario - Responsable
     * 
     * @return type
     * 
     */
    private function _getDtaFR( $idFuncionario )
    {
        $db = JFactory::getDBO();
        $tbFR = new jTableFuncionarioResponsable( $db );
        $dtaFR = $tbFR->getDtaFuncionario( $idFuncionario );

        return $dtaFR;
    }
    
    
    
    
    private function _crearPlanFR( $dtaFR, $plan, $objAccionObjetivo )
    {
        $dtaPoaFR = new stdClass();
        $datePOA = new DateTime( $plan->fchInicio );

        $dtaPoaFR->idPln            = 0;
        $dtaPoaFR->intId_tpoPlan    = 6;
        $dtaPoaFR->intCodigo_ins    = 1;
        $dtaPoaFR->intIdPadre_pi    = $plan->idPlan;
        $dtaPoaFR->intIdentidad_ent = $dtaFR->idEntidadF;
        $dtaPoaFR->strDescripcion_pi= 'POA_' . $dtaFR->nombreF . '_' . $datePOA->format( 'Y' );
        $dtaPoaFR->dteFechainicio_pi= $plan->fchInicio;
        $dtaPoaFR->dteFechafin_pi   = $plan->fchFin;

        //  Obtengo informacion del objetivo 
        //  posteriormente vamos a setear informacion de indicadores con el indicador prorrateado
        $dtaPoaFR->lstObjetivos[] = ( is_null( $objAccionObjetivo ) ) 
                                        ? new stdClass() 
                                        : $objAccionObjetivo->__toString();

        return $this->_guardarPlanPoa( $dtaPoaFR );
    }
    
    
    
    
    public function __destruct()
    {
        return;
    }
}