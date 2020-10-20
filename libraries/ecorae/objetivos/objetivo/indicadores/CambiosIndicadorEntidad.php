<?php

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'entidad' . DS . 'entidad.php';

class CambiosIndicadorEntidad
{

    private $_indicador;
    private $_idEntidad;
    private $_idIndEntidad;
    private $_dtaGralIndEntidad;
    private $_lstPlnObjetivos;
    private $_lstTmpPlanes;
    private $_lstIndicadores;
    private $_dataObj;

    /**
     * 
     * Constructor de la clase Cambios Indicador - Entidad
     * 
     * @param Object $indicador     Datos del indicador entidad
     * @param int $idEntidad        Identificador de la entidad del OBJETIVO, al que esta asociado el indicador
     * @param int $idIndEntidad     Identificador del Indicador - Entidad PADRE
     * @param int $idIndicador      Identificador del Indicador - Entidad PADRE
     * 
     */
    public function __construct( $indicador, $idEntidad, $idIndEntidad, $dataObj = NULL )
    {
        $this->_indicador           = clone $indicador;
        $this->_idEntidad           = $idEntidad;
        $this->_idIndEntidad        = $idIndEntidad;
        $this->_dtaGralIndEntidad   = $this->_getDtaGralIndEntidad();
        $this->_dataObj             = $dataObj;

        $this->_lstTmpPlanes        = array();
        $this->_lstIndicadores      = array();
        $this->_lstPlnObjetivos     = array();
    }

    /**
     * 
     * Retorno una lista de Objetivos hijos de un determinado Objetivo
     * 
     * @param int $idEntidad    Identificador de la entidad del OBJETIVO al que esta 
     *                          asociado un determinado objetivo
     * 
     * @return type
     * 
     */
    private function _getLstPlnObjetivos( $idEntidad )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanObjetivo( $db );

        return $tbPlan->getPlnObjetivos( $idEntidad, $this->_dataObj );
    }
    
    
    private function _getLstPlnObjInd( $idIndEntidad )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanObjetivo( $db );

        return $tbPlan->getPlnObjIndicadores( $idIndEntidad );
    }
    
    /**
     * 
     * Retorno informacion del Indicador Entidad
     * 
     * @return Object
     */
    public function dtaIndEntidad()
    {
        return $this->_dtaGralIndEntidad;
    }

    /**
     * 
     * Retorno informacion general de un indicador
     * 
     * @return Object   caso contrario retorna FALSE
     * 
     */
    private function _getDtaGralIndEntidad()
    {
        $db = JFactory::getDBO();
        $tbIndEntidad = new jTableIndicadorEntidad( $db );

        //  Retorno informacion general de indicador
        return $tbIndEntidad->getDtaIndicadorEntidad( $this->_idIndEntidad );
    }

    /**
     * 
     * Realizo procesos de gestion de cambios de UN INDICADOR - ENTIDAD
     * 
     * @return type
     * 
     */
    public function gcIndicadorEntidad()
    {
        //  Instancio la clase Ajuste Indicador - Entidad
        $aie = new AjusteIndEntidad( $this->_dtaGralIndEntidad, $this->_idEntidad );

        //  En caso que el indicador sea nuevo genero los indicadores 
        //  asociados( hijos ) a este indicador
        if( ( int ) $this->_indicador->idIndEntidad == 0 ){
            $aie->creacionIndicadoresHijo();
        }

        return;
    }

    /**
     * 
     * Gestiono la creacion de indicadores asociados( hijo ) de un determinado 
     * indicador
     * 
     * @param type $idIndEntidadPadre   Identificador del indicador padre
     * 
     * @return type
     * 
     */
    public function creacionIndicadoresHijo()
    {
        $ban = FALSE; 
        $lstPlnObjetivos = $this->_getLstPlnObjetivos( $this->_idEntidad );

        //  Prorrateo nuevo indicador de acuerdo a informacion de los planes a 
        //  los que pertecene el objetivo al que esta asociado este indicador
        if( count( $lstPlnObjetivos ) ){

            $ban = TRUE;
            $this->_crearIndHijo(   $lstPlnObjetivos, 
                                    $this->_indicador->umbral, 
                                    $this->_idIndEntidad, 
                                    $this->_indicador->lstLineaBase );
        }

        return $ban;
    }

    /**
     * 
     * Realizo el proceso de PRORRATEO de un indicador de acuerdo al tipo de plan
     * 
     * @param Object    $dtaIndicador    Data del indicador a Prorratear
     * @param date      $fchInicio       Fecha de inicio
     * @param date      $fchFin          Fecha de Fin
     * @param int       $idTpoPln        Identificador del tipo de plan
     * 
     * @return Object
     */
    private function _prorrateoIndicador( $dtaIndicador, $fchInicio, $fchFin, $idTpoPln )
    {
        //  Creo el nuevo indicador
        $objIndicador = new PlanIndicador( $dtaIndicador, $fchInicio, $fchFin, $idTpoPln );

        //  Obtengo informacion del NUEVO INDICADOR PRORRATEADO
        return $objIndicador->prorrateoIndicador();
    }

    /**
     * 
     * Realizo el proceso de registro de un nuevo indicador
     * 
     * @param Object $dtaIndicador
     * 
     * @return int
     * 
     */
    private function _regNuevoIndicador( $idEntidad, $dtaIndicador )
    {
        $objIndProrrateado = new Indicador();

        //  Registro el nuevo indicador
        return $objIndProrrateado->registroIndicador( $idEntidad, $dtaIndicador, 0, $this->_dataObj );
    }

    /**
     * 
     * Creo indicadores asociados a un nuevo indicador
     * 
     * @param type $lstPlanes   Lista de Planes
     * @param type $idPadre     Padre del plan
     * @param type $umbral      Valor meta del nuevo indicador
     * @param type $idPadreNI   Padre del nuevo indicador
     * 
     * @return type
     * 
     */
    private function _crearIndHijo( $lstPlanes, $nuevoUmbral, $idPadreNI, $lb )
    {
        $dtaIndicador = clone $this->_indicador;

        foreach( $lstPlanes as $plan ){

            $dtaIndicador->idIEPadre    = $idPadreNI;
            $dtaIndicador->umbral       = $nuevoUmbral;
            $dtaIndicador->lstLineaBase = $lb;

            //  Creo un nuevo indicador y lo prorrateo
            $dtaIndProrrateado = $this->_prorrateoIndicador(    $dtaIndicador, 
                                                                $plan->fchInicio, 
                                                                $plan->fchFin, 
                                                                $plan->idTpoPln );

            if( $dtaIndProrrateado ){
                $idIndEntidad = $this->_regNuevoIndicador( $plan->idEntidad, $dtaIndProrrateado );

                if( $plan->idTpoPln == 3 ){
                    $dtaIndProrrateado->idIndEntidad = $idIndEntidad;

                    //  Gestiono Indicadores asociados a una determinada Unidad de Gestion Responsable
                    $this->_setUndGestionResponsable( $plan, $dtaIndProrrateado );

                    //  Gestiono Indicadores asociados a un determinado Funcionario Responsable
                    $this->_setFuncionariosResponsables( $plan, $dtaIndProrrateado );
                }
            }

        }   //  Fin del ForEach

        return;
    }
    
    
    private function _setUndGestionResponsable( $plan, $dtaIndProrrateado )
    {
        if( (int)$dtaIndProrrateado->idUGResponsable ){
            //  Verifico la existencia de un plan de tipo POA asociado a una 
            //  determinada Unidad de Gestion Responsable, si NO EXISTE lo creo
            $dtaPlnUG = $this->_verificarExistenciaPlanUG(  $plan, 
                                                            $dtaIndProrrateado->idUGResponsable );

            if( $dtaPlnUG == FALSE ){
                $this->_nuevoPlanUndGestion(    $dtaIndProrrateado->idUGResponsable, 
                                                $plan, 
                                                $dtaIndProrrateado );
            }else{
                if( !is_null( $this->_dataObj ) && $dtaPlnUG->idObj != $this->_dataObj->idObj ){
                    
                    $dIP = clone $dtaIndProrrateado;
                    $dIP->idIndEntidad = 0;
                    
                    //  Creo un objetivo al cual se asocio al plan correspondiente
                    $objetivo = new stdClass();
                    
                    $objetivo->idPlnObjetivo    = 0;
                    $objetivo->idObjetivo       = $this->_dataObj->idObj; 
                    $objetivo->idPlanPoa        = $dtaPlnUG->idPlan; 
                    $objetivo->idEntidad        = $this->_crearIdEntidadObjetivo();
                    $objetivo->idPadreObj       = $plan->idEntidad;
                    $objetivo->lstIndicadores[] = $dIP;

                    $this->_crearObjetivo( $objetivo );
                }
            }
        }

        return;
    }
    
    
    
    private function _crearObjetivo( $objetivo )
    {
        //  Creo el Objeto Objetivo que gestiona informacion de un objetivo
        $objObjetivo = new Objetivo();
        $dtaObjetivo = new stdClass();

        //  Registro la relacion entre un plan y un objetivo
        $idPlanObjetivo = $objObjetivo->registroPlanObjetivo(   $objetivo->idPlnObjetivo, 
                                                                $objetivo->idObjetivo, 
                                                                $objetivo->idPlanPoa, 
                                                                $objetivo->idEntidad, 
                                                                $objetivo->idPadreObj );

        $dtaObjetivo->idObj         = $objetivo->idObjetivo;
        $dtaObjetivo->idEntidad     = $objetivo->idEntidad;
        $dtaObjetivo->idPlnObjetivo = $idPlanObjetivo;

        $objObjetivo->registroObjetivosProrrateados( $objetivo, $dtaObjetivo );
        
        return;
    }
    
    
    
    /**
     * 
     * Retorno el identificador de Entidad del Objetivo a construir
     * 
     * @return type
     */
    private function _crearIdEntidadObjetivo()
    {
        $db = JFactory::getDBO();
        $tblEntidad = new jTableEntidad( $db );

        return $tblEntidad->saveEntidad( 0, 4 );
    }
    

    private function _nuevoPlanUndGestion( $idUGResponsable, $plan, $dtaIndProrrateado )
    {
        $dtaUG = $this->_getDtaUGR( $idUGResponsable );
        return $this->_crearPlanUG( $dtaUG, $plan, $dtaIndProrrateado, 1 );
    }
    
    
    private function _setFuncionariosResponsables( $plan, $dtaIndProrrateado )
    {
        if( (int)$dtaIndProrrateado->idResponsable != 0 ){
            $dtaPlnFuncionario = $this->_verificarExistenciaPlanFuncionario(    $dtaIndProrrateado->idResponsable, 
                                                                                $plan );

            //  Verifico la existencia de un plan de tipo POA asociado a un 
            //  determinado Funcionario
            if( $dtaIndProrrateado->idResponsable && $dtaPlnFuncionario == FALSE ){
                $this->_nuevoPlanFuncionario(   $dtaIndProrrateado->idResponsable, 
                                                $plan, 
                                                $dtaIndProrrateado );
            }else{
                
                if( !is_null( $this->_dataObj ) && $dtaPlnFuncionario->idObj != $this->_dataObj->idObj ){
                    
                    $dIP = clone $dtaIndProrrateado;
                    $dIP->idIndEntidad = 0;
                    
                    //  Creo un objetivo al cual se asocio al plan correspondiente
                    $objetivo = new stdClass();
                    
                    $objetivo->idPlnObjetivo    = 0;
                    $objetivo->idObjetivo       = $this->_dataObj->idObj; 
                    $objetivo->idPlanPoa        = $dtaPlnFuncionario->idPlan; 
                    $objetivo->idEntidad        = $this->_crearIdEntidadObjetivo();
                    $objetivo->idPadreObj       = $plan->idEntidad;
                    $objetivo->lstIndicadores[] = $dIP;
    
                    $this->_crearObjetivo( $objetivo );
                }

            }
        }

        return;
    }
    
    
    private function _nuevoPlanFuncionario( $idResponsable, $plan, $dtaIndProrrateado )
    {
        $dtaFuncionario = $this->_getDtaFuncionario( $idResponsable );
        return $this->_crearPlanFuncionario( $dtaFuncionario, $plan, $dtaIndProrrateado );
    }

    /**
     * 
     * Verifico la existencia de un Plan de Tipo POA asociado a una determinada 
     * Unidad de Gestion Responsable.
     * 
     * En caso que exista retorna informacion especifica del Plan.
     * En caso que NO exista retorna informacion especifica de la creacion del nuevo Plan
     * 
     * @param object $plan                  Datos del Plan
     * @param object $idUGResponsable       Identificador de la unidad de gestion responsable
     * 
     * @return object   Informacion del Plan
     * 
     */
    private function _verificarExistenciaPlanUG( $plan, $idUGResponsable )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();
        $tbUGR = new jTableUndGestionResponsable( $db );

        return $tbUGR->_existePlanUG( $idUGResponsable, $plan );
    }

    private function _verificarExistenciaPlanFuncionario( $idResponsable, $plan )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();
        $tbFR = new jTableFuncionarioResponsable( $db );

        return $tbFR->existePlanFR( $idResponsable, $plan );
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

    private function _crearPlanUG( $dtaUG, $plan, $dtaIndProrrateado, $ban = 0 )
    {
        $datePOA = new DateTime( $plan->fchInicio );

        $dtaPoaUG["idPln"]            = 0;
        $dtaPoaUG["intId_tpoPlan"]    = 2;
        $dtaPoaUG["intCodigo_ins"]    = 1;
        $dtaPoaUG["intIdPadre_pi"]    = $plan->idPlan;
        $dtaPoaUG["intIdentidad_ent"] = $dtaUG->idEntidadUG;
        $dtaPoaUG["strDescripcion_pi"]= 'POA_' . $dtaUG->nombreUG . '_' . $datePOA->format( 'Y' );
        $dtaPoaUG["dteFechainicio_pi"]= $plan->fchInicio;
        $dtaPoaUG["dteFechafin_pi"]   = $plan->fchFin;

        //  Obtengo informacion del objetivo 
        //  posteriormente vamos a setear informacion de indicadores con el indicador prorrateado
        $dtaPoaUG["lstObjetivos"][] = $this->_getDtaObjetivo(   $plan->idObjetivo, 
                                                                $plan->idEntidad, 
                                                                $dtaIndProrrateado );

        //  Gestiono la creacion de un plan de tipo POA
        $dtaPlanPoa = new Plan( $dtaPoaUG, 2, 2 );
        $dtaPln = $this->_guardarPlanPoa( $dtaPlanPoa->__toString() );

        return (object)$dtaPln;
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

            //  Arma el Objeto Plan de tipo PEI
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
     * Retorna informacion de un determinado objetivo
     * 
     * @param int $idObjetivo       Identificador del objetivo
     * @param object $dtaIndicador  Informacion del indicador
     * 
     * @return type
     */
    private function _getDtaObjetivo( $idObjetivo, $idEntObjPadre, $dtaIndicador )
    {
        $objObjetivo = new Objetivo();
    
        $dtaObjetivo = $objObjetivo->getDtaObjetivo( $idObjetivo );
        $dtaObjetivo->idEntidad = $idEntObjPadre;
        
        //  Adjunto a este objeto el indicador prorrateado
        $dtaObjetivo->lstIndicadores[] = $dtaIndicador;

        return $dtaObjetivo;
    }

    private function _getDtaFuncionario( $idResponsable )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();
        $tbFR = new jTableFuncionarioResponsable( $db );

        //  Obtengo informacion del funcionario
        $rst = $tbFR->getDtaFuncionario( $idResponsable );

        return $rst;
    }

    private function _crearPlanFuncionario( $dtaFuncionario, $plan, $dtaIndProrrateado )
    {
        $datetime1 = new DateTime( $plan->fchInicio );

        $dtaPoa["idPln"]            = 0;
        $dtaPoa["intId_tpoPlan"]    = 6;
        $dtaPoa["intCodigo_ins"]    = 1;
        $dtaPoa["intIdPadre_pi"]    = $plan->idPlan;
        $dtaPoa["intIdentidad_ent"] = $dtaFuncionario->idEntidadF;
        $dtaPoa["strDescripcion_pi"]= 'POA_Funcionario_' . $dtaFuncionario->nombreF . '_' . $datetime1->format( 'Y' );
        $dtaPoa["dteFechainicio_pi"]= $plan->fchInicio;
        $dtaPoa["dteFechafin_pi"]   = $plan->fchFin;

        //  Obtengo informacion del objetivo 
        //  posteriormente vamos a setear informacion de indicadores con el indicador prorrateado
        $dtaPoa["lstObjetivos"][] = $this->_getDtaObjetivo( $plan->idObjetivo, 
                                                            $plan->idEntidad, 
                                                            $dtaIndProrrateado );

        //  Gestiono la creacion de un plan de tipo POA - Funcionario
        $dtaPlanPoa = new Plan( $dtaPoa, $this->_tpoPln, 6 );

        $dtaPlnFuncionario = $this->_guardarPlanPoa( $dtaPlanPoa->__toString() );
        
        return (object)$dtaPlnFuncionario;
    }

    private function _guardarPlanPoa( $dtaPlan )
    {
        //  Obtengo el idRegistro Registro un nuevo Plan de tipo POA
        $idPlanPoa = $this->_regDtaPlanPoa( $dtaPlan );

        if( $idPlanPoa ){
            //  Identificador del Tpo de Entidad PEI
            $idTpoPEI = 1;
            
            foreach( $dtaPlan->lstObjetivos as $objetivo ){

                //  Creo el Objeto Objetivo que gestiona informacion de un objetivo
                $objObjetivo = new Objetivo();
                $dtaObjetivo = new stdClass();

                //  Registro la relacion entre un plan y un objetivo
                $idPlanObjetivo = $objObjetivo->registroPlanObjetivo(   $objetivo->idPlnObjetivo, 
                                                                        $objetivo->idObjetivo, 
                                                                        $idPlanPoa, 
                                                                        $objetivo->idEntidad, 
                                                                        $objetivo->idPadreObj );

                $dtaObjetivo->idPlnPOA      = $idPlanPoa;
                $dtaObjetivo->idObj         = $objetivo->idObjetivo;
                $dtaObjetivo->idEntidad     = $objetivo->idEntidad;
                $dtaObjetivo->idPlnObjetivo = $idPlanObjetivo;

                $objObjetivo->registroObjetivosProrrateados( $objetivo, $dtaObjetivo );
            }
        } else{
            $idPlanPoa = array();
        }

        return $dtaObjetivo;
    }

    /**
     * 
     *  Realizo el proceso de actualizacion de Unidades de Gestion responsables 
     *  de un indicador, asociadas ( Hijos ) a un determinado Plan
     * 
     *  @param int $idIndEntPadre     Indentificador del Indicador Entidad Padre
     * 
     *  @return type
     * 
     */
    public function updUGResponsable( $idIndEntPadre )
    {
        $lstPlnObjetivos = $this->_getLstPlnObjInd( $idIndEntPadre );

        if( count( $lstPlnObjetivos ) ){
            foreach( $lstPlnObjetivos as $plnObjetivo ){

                if( $plnObjetivo->vigencia == 1 ){
                    //  Ejecuto proceso de Actualizacion de UG Responsables
                    $this->_updDtaUGResponsable(    $plnObjetivo, 
                                                    $this->_indicador->oldIdUGResponsable, 
                                                    $this->_indicador->idUGResponsable );

                    if( (int)$plnObjetivo->idTpoPln == 2 ){

                        $dtaPlnUGDst = $this->_verificarExistenciaPlanUG(   $plnObjetivo, 
                                                                            $this->_indicador->idUGResponsable );
                        
                        if( $dtaPlnUGDst == FALSE ){

                            $po = clone $plnObjetivo;
                            $po->idPlan     = $plnObjetivo->idPlanPadre;
                            $po->idEntidad  = $plnObjetivo->idPadre;

                            //  Creo un nuevo plan asociado a la NUEVA unidad de gestion ( Sin Indicadores )
                            $dtaPlnUGR = $this->_nuevoPlanUndGestion( $this->_indicador->idUGResponsable, 
                                                                        $po, 
                                                                        new stdClass() );

                            //  Actualizo la entidad al que un determinado indicador esta asociado
                            $this->_updPOEIndicador(    $po->idIndEntidad, 
                                                        $dtaPlnUGR->idEntidad );
                        }

                        //  Asocio al objetivo que esta asociado el indicador al plan de la nueva UG-Responsable
                        $this->_updPlanObjetivo(    $this->_indicador->idUGResponsable, 
                                                    $plnObjetivo );
                    }

                    //  Llamo nuevamente a la funcion
                    $this->updUGResponsable( $plnObjetivo->idIndEntidad );
                }
            }
        }

        return;
    }

    /**
     * 
     * Gestiono el proceso de Actualizacion de Funcionarios Responsables de un 
     * Indicador asociados a un determinado plan
     * 
     * @param type $idEntPadre      Identificador del indicador padre
     * 
     * @return type
     */
    public function updFuncionarioResponsable( $idIndEntPadre )
    {
        $lstPlnObjetivos = $this->_getLstPlnObjInd( $idIndEntPadre );

        if( count( $lstPlnObjetivos ) ){
            foreach( $lstPlnObjetivos as $plnObjetivo ){

                //  Valido si el plan a gestionar este vigente
                if( $plnObjetivo->vigencia == 1 ){
                    //  Ejecuto proceso de actualizacion de Funcionario Responsable
                    $this->_updDtaFuncionarioResponsable(   $plnObjetivo, 
                                                            $this->_indicador->oldIdResponsable, 
                                                            $this->_indicador->idResponsable );

                    if( $plnObjetivo->idTpoPln == 6 ){
                        $dtaPlanFuncionario = $this->_verificarExistenciaPlanFuncionario(   $this->_indicador->idResponsable, 
                                                                                            $plnObjetivo );

                        if( $dtaPlanFuncionario == FALSE ){

                            $po = clone $plnObjetivo;
                            $po->idPlan     = $plnObjetivo->idPlanPadre;
                            $po->idEntidad  = $plnObjetivo->idPadre;

                            $dtaNPF = $this->_nuevoPlanFuncionario( $this->_indicador->idResponsable,   
                                                                    $po,
                                                                    new stdClass() );
                            
                            $this->_updPOEIndicador(    $po->idIndEntidad,
                                                        $dtaNPF->idEntidad );
                        }

                    }

                    //  Asocio al objetivo que esta asociado el indicador al plan de la nueva UG-Responsable
                    $this->_updPlanObjetivo(    $this->_indicador->idResponsable, 
                                                $plnObjetivo );
                }
                
                //  Llamo nuevamente a la funcion
                $this->updFuncionarioResponsable( $plnObjetivo->idIndEntidad );
            }
        }

        return;
    }

    /**
     * 
     * Actualizo informacion de una Unidad de Gestion Responsable de un indicador, 
     * y la replico a sus indicadores asociados ( hijo )
     * 
     * @param type $plnObjetivo         Datos del plan al que esta asociado el indicador
     * @param type $aUGResponsable      Identificador de la ANTERIOR unidad de Gestion responsable del indicador
     * @param type $nUGResponsable      Identificador de la NUEVA unidad de Gestion responsable del indicador
     * 
     * @return type
     */
    private function _updDtaUGResponsable( $plnObjetivo, $aUGResponsable, $nUGResponsable )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();
        try{
            $db->transactionStart();

            $tbUGR = new jTableUndGestionResponsable( $db );

            //  Cambio la vigencia del anterior Unidad de Gestion Responsable
            $rst = $tbUGR->updVigenciaUndGestion(   $plnObjetivo->idIndEntidad, 
                                                    $aUGResponsable, 
                                                    $plnObjetivo->fchInicio );

            if( $rst == TRUE ){
                //  Informacion de nueva Unidad de gestion responsable del indicador
                $dtaUndGestionResponsable["intId_ugr"]          = 0;
                $dtaUndGestionResponsable["intIdIndEntidad"]    = $plnObjetivo->idIndEntidad;
                $dtaUndGestionResponsable["intCodigo_ug"]       = $nUGResponsable;
                $dtaUndGestionResponsable["dteFechaInicio_ugr"] = $plnObjetivo->fchInicio;
                $dtaUndGestionResponsable["inpVigencia_ugr"]    = 1;
                
                $rst = $tbUGR->registrarUndGesResp( $dtaUndGestionResponsable );
            }

            $db->transactionCommit();

            return $rst;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    
    
    private function _updPOEIndicador( $idIndEntidad, $idEntidad )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();
        try{
            $db->transactionStart();

            $tbIE = new jTableIndicadorEntidad( $db );
            $tbIE->updEntidadIndicador( $idIndEntidad, $idEntidad );

            $db->transactionCommit();
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     * 
     * Asocio al objetivo que esta asociado el indicador al plan de la nueva UG-Responsable
     * 
     * @param int $idResponsable    id unidad de gestion responsable
     * @param object $plnObjetivo   objeto con informacion del plan gestionado
     */
    private function _updPlanObjetivo( $idResponsable, $plnObjetivo )
    {
        $idPO   = 0;
        $idPlan = FALSE;
        
        switch ( $plnObjetivo->idTpoPln ){
            //  POA - IG
            case 2: 
                $idPlan = $this->_getPlanUndGestion( $idResponsable, $plnObjetivo );
            break;
        
            //  POA - F
            case 6:
                //  Obtengo informacion si el POA UG / F gestiona informacion de un determinado objetivo
                $idPlan = $this->_getPlanFuncionario( $idResponsable, $plnObjetivo );
            break;
        }
        
        $apof = ( $idPlan != FALSE )? $this->_getAPFO( $idPlan, $plnObjetivo )
                                    : FALSE;
        
        //  Verifico si existe una relacion entre plan del funcionario y un objetivo
        if( $idPlan != FALSE && $apof == FALSE ){
            $idPO = $this->_addPO( $idPlan, $plnObjetivo );
        }
        
        return $idPO;
    }

    /**
     * 
     * Retorno el identificador de un plan de tipo POA - UG / F en un 
     * determinado periodo que pertenece a una determinada unidad de gestion
     * 
     * @param int       $idResponsable      Identificador del Funcionario Responsable
     * @param objeto    $plnObjetivo        Datos del plan buscar
     * 
     * @return int      Identificador del plan, en caso de no existir retorna FALSE
     * 
     */
    private function _getPlanUndGestion( $idResponsable, $plnObjetivo )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();
        $tbPF = new jTablePlanObjetivo( $db );
        
        return $tbPF->getPlanUndGestion( $idResponsable, $plnObjetivo );
    }
    
    
    /**
     * 
     * Retorno el identificador de un plan de tipo POA - UG / F en un 
     * determinado periodo que pertenece a una determinada unidad de gestion
     * 
     * @param int       $idResponsable      Identificador del Funcionario Responsable
     * @param objeto    $plnObjetivo        Datos del plan buscar
     * 
     * @return int      Identificador del plan, en caso de no existir retorna FALSE
     * 
     */
    private function _getPlanFuncionario( $idResponsable, $plnObjetivo )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();
        $tbPF = new jTablePlanObjetivo( $db );
        
        return $tbPF->getPlanFuncionario( $idResponsable, $plnObjetivo );
    }

    
    private function _getAPFO( $idPlan, $plnObjetivo )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();
        $tbPF = new jTablePlanObjetivo( $db );

        return $tbPF->getAPOF( $idPlan, $plnObjetivo );
    }

    private function _addPO( $idPlan, $plnObjetivo )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();
        $tbPF = new jTablePlanObjetivo( $db );

        $dtaPlnObj["intId_pi"]          = $idPlan;
        $dtaPlnObj["intId_ob"]          = $plnObjetivo->idObjetivo;
        $dtaPlnObj["intIdentidad_ent"]  = $plnObjetivo->idEntidad;
        $dtaPlnObj["intIdPadre"]        = $plnObjetivo->idPadre;

        echo 'dtaPlnObj: <br>'. json_encode( $dtaPlnObj ) .'<hr>';

        return $tbPF->registroPlnObj( $dtaPlnObj );
    }

    /**
     * 
     * Actualizo informacion de un Funcionario Responsable de un determinado indicador
     * 
     * @param Objetivo $plnObjetivo     Datos del plan al que esta asociado el indicador
     * @param int $aIdResponsable       Identificador del ANTERIOR Funcionario Responsable 
     * @param int $nIdResponsable       Identificador del NUEVO Funcionario Responsable 
     * 
     * @return type
     * 
     */
    private function _updDtaFuncionarioResponsable( $plnObjetivo, $aIdResponsable, $nIdResponsable )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();

        try{
            $rst = TRUE;
            
            $db->transactionStart();

            //  Instacio la tabla Funcionario Responsable
            $tbFuncionario = new jTableFuncionarioResponsable( $db );

            //  Cambio la vigencia del anterior Unidad de Gestion Responsable
            $rst = $tbFuncionario->updVigenciaFuncionario( $plnObjetivo->idIndEntidad, $aIdResponsable, $plnObjetivo->fchInicio );

            if( $rst == TRUE ){
                //  Informacion de nueva Unidad de gestion responsable del indicador
                $dtaFuncionarioResponsable["intIdIndEntidad"]   = $plnObjetivo->idIndEntidad;
                $dtaFuncionarioResponsable["intId_ugf"]         = $nIdResponsable;
                $dtaFuncionarioResponsable["dteFechaInicio_fgr"]= $plnObjetivo->fchInicio;
                $dtaFuncionarioResponsable["intVigencia_fgr"]   = 1;

                $rst = $tbFuncionario->registrarFuncionarioResponsable( $dtaFuncionarioResponsable );
            }

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
     * Retorna una lista de indicadores hijos de un determinado indicador padre
     * 
     * @param type $idIndPadre      Identificador Indicador Padre
     * 
     * @return type
     * 
     */
    private function _getlstIndHijo( $idIndPadre )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();

        $tbIndEntidad = new jTableIndicadorEntidad( $db );

        return $tbIndEntidad->getLstIndEntidadHijos( $idIndPadre );
    }

    /**
     * 
     * Lista de Indicadores Hijo
     * 
     * @param type $idIndPadre      Identificador de indicador Padre
     * @return type
     */
    private function _lstIndicadoresHijo( $idIndPadre )
    {
        $lstHijos = $this->_getlstIndHijo( $idIndPadre );

        if( count( $lstHijos ) > 0 ){
            foreach( $lstHijos as $hijo ){
                $this->_lstIndicadores[] = $hijo;
                $this->_lstIndicadoresHijo( $hijo->idIndEntidad );
            }
        } else{
            return;
        }
    }

    /**
     * 
     * @return type
     */
    public function updUmbral()
    {
        //  Listo todos los Indicador-Entidad, Indicadores y lineas base de un 
        //  determinado idIndEntidad
        //  $this->_lstIndicadoresHijo( $this->_idIndEntidad );
        //  Elimino todas las Lineas Base
        $this->_delLineasBase();

        //  Elimino todos los Indicadores-Entidad asociados a un determinado Indicador entidad
        $this->_delIndicadorEntidad();

        //  Elimino todos los indicadores asociados a un determinado Indicador-Entidad
        $this->_delIndicadores();

        //  Creo nuevamente los indicadores
        $this->creacionIndicadoresHijo();

        return;
    }

    /**
     * 
     * Elimino las lineas base asociadas a un determinado Indicador-Entidad
     * 
     * @return type
     * 
     */
    private function _delLineasBase()
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Unidad de Gestion
        $tbLB = new jTableLineaBase( $db );

        return $tbLB->delLineasBase( $this->_lstIndicadores );
    }

    /**
     * 
     * Elimino Indicadores-Entidad
     * 
     * @return type
     * 
     */
    private function _delIndicadorEntidad()
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Unidad de Gestion
        $tbIndEntidad = new jTableIndicadorEntidad( $db );

        return $tbIndEntidad->delLstIndicadorEntidad( $this->_lstIndicadores );
    }

    private function _delIndicadores()
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Unidad de Gestion
        $tbIndicador = new jTableIndicador( $db );

        return $tbIndicador->delLstIndicadores( $this->_lstIndicadores );
    }

    /**
     * 
     * Actualizo la Vigencia de los indicadores 
     * 
     * @param type $idIndEntidad
     * @return type
     */
    public function updVigenciaIndicadores( $idIndEntidad )
    {
        $lstIndHijo = $this->_getLstIndicadoresHijo( $idIndEntidad );

        foreach( $lstIndHijo as $indHijo ){
            $this->_updVigenciaIndHijo( $indHijo->idIndEntidad );
            
            $this->updVigenciaIndicadores( $indHijo->idIndEntidad );
        }
        
        return;
    }
    
    private function _getLstIndicadoresHijo( $idIndEntidad )
    {
        $db = JFactory::getDBO();
        $tbIndEntidad = new jTableIndicadorEntidad( $db );

        return $tbIndEntidad->getLstIndEntidadHijos( $idIndEntidad );
    }
    
    private function _updVigenciaIndHijo( $idIndEntidadHijo )
    {
        $db = JFactory::getDBO();
        $tbIndEntidad = new jTableIndicadorEntidad( $db );
        
        return $tbIndEntidad->updVigenciaIndEntidad( $idIndEntidadHijo );
    }
    
    
    public function homolarIndicadores( $idIndEntPadre )
    {
        $lstPlnObjetivos = $this->_getLstPlnObjInd( $idIndEntPadre );

        if( count( $lstPlnObjetivos ) ){
            foreach( $lstPlnObjetivos as $plan ){
                
                $objIndicador = new Indicador( 0, $plan->idTpoPln );

                $this->_indicador->idIndEntidad = $plan->idIndEntidad;
                $this->_indicador->idIndicador  = $plan->idIndicador;
                $this->_indicador->umbral       = $plan->umbral;

                $objIndicador->registroIndicador( $plan->idEntidad, $this->_indicador, 0 );
                
                $this->homolarIndicadores( $plan->idIndEntidad );
            }
        }

        return;
    }
    
    
    
    public function updUndTerritorial( $idIndEntPadre, $lstUndsTerritoriales )
    {
        $lstPlnObjetivos = $this->_getLstPlnObjInd( $idIndEntPadre );

        if( count( $lstPlnObjetivos ) ){
            foreach( $lstPlnObjetivos as $plan ){
                
                if( $plan->vigencia == 1 ){

                    //  Borro las unidades territoriales asociadas a indicador - entidad
                    $this->delUndTerritorialIndicador( $plan->idIndEntidad );

                    //  Registro Unidades territoriales 
                    $this->addUTIndicador(  $plan->idIndEntidad, 
                                            $lstUndsTerritoriales );
                }
                
                $this->updUndTerritorial(   $plan->idIndEntidad, 
                                            $lstUndsTerritoriales );
            }
            
        }

        return;
    }
    
    public function delUndTerritorialIndicador( $idIndEntidad )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Funcionario
        $tbUT = new jTableIndicadorUT( $db );
        
        return $tbUT->deleteUnidadTerritorial( $idIndEntidad );
    }
    
    
    public function addUTIndicador( $idIndEntidad, $lstUndsTerritoriales )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Funcionario
        $tbUT = new jTableIndicadorUT( $db );

        return $tbUT->registroIndicadorUndTerritorial(  $idIndEntidad, 
                                                        $lstUndsTerritoriales );
    }
    
    
    
    public function updDtaProgramacion( $idIndEntPadre, $dtaPlanificacion )
    {
        $lstPlnObjetivos = $this->_getLstPlnObjInd( $idIndEntPadre );
        
        if( count( $lstPlnObjetivos ) ){
            foreach( $lstPlnObjetivos as $plan ){
                
                if( $plan->fchInicio <= $dtaPlanificacion->fecha && $dtaPlanificacion->fecha <= $plan->fchFin ){
                    //  Borro planificaciones asociadas a un deteminado indicador
                    $this->_delPlanificacion( $plan->idIndEntidad );
                    
                    //  Registro nueva informacion de planificacion
                    $this->_gestionPlanificacion( $plan->idIndEntidad, $dtaPlanificacion );
                }
                
                $this->updDtaProgramacion( $plan->idIndEntidad, $dtaPlanificacion );
            }
        }

        return;
    }
    
    private function _delPlanificacion( $idIndEntidad )
    {
        $db = JFactory::getDBO();
        $tbPlanificacion = new jTablePlanificacionIndicador( $db );
        
        return $tbPlanificacion->deletePlanificacion( $idIndEntidad );
    }
    
    
    private function _gestionPlanificacion( $idIndEntidad, $dtaPlanificacion )
    {
        $db = JFactory::getDBO();
        $tbPlanificacion = new jTablePlanificacionIndicador( $db );

        $dtaProgramacion["intIdIndEntidad"] = $idIndEntidad;
        $dtaProgramacion["dteFecha_plan"]   = $dtaPlanificacion->fecha;
        $dtaProgramacion["dcmValor_plan"]   = $dtaPlanificacion->valor;

        return $tbPlanificacion->registroPlanificacion( $dtaProgramacion );
    }
    

    public function __destruct()
    {
        return;
    }
}