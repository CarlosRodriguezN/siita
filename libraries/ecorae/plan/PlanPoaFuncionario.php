<?php

class PlanPoaFuncionario
{
    private $_dtaObjetivo;
    private $_plan;
    private $_lstFuncionarios;
    
    public function __construct( $dtaObjetivo, $plan )
    {
        $this->_dtaObjetivo = clone $dtaObjetivo;
        $this->_plan = $plan;

        //  Obtengo una lista de unidades de gestion asociadas a un Objetivo
        $this->_lstFuncionarios = $this->_getLstFuncionarios();
    }
    
    
    /**
     * 
     * Retorno una lista de Funcionarios Responsables de Indicadores 
     * y Acciones de un Objetivo
     * 
     * @return Array
     * 
     */
    private function _getLstFuncionarios()
    {
        $lstFuncionarios = array();
        $lstIndicadores = $this->_dtaObjetivo->lstIndicadores;
        $lstAcciones    = $this->_dtaObjetivo->lstAcciones;

        //  Recorro la lista de indicadores y obtengo Unidades de Gestion 
        //  y Funcionarios Responsables
        if( count( (array)$lstIndicadores ) ){
            foreach( $lstIndicadores as $indicador ){
                $lstFuncionarios[]  = $indicador->idResponsable;
            }
        }
        
        //  Recorro la lista de acciones y obtengo Unidades de Gestion 
        //  y Funcionarios Responsables
        if( count( (array)$lstAcciones ) ){
            foreach( $lstAcciones as $accion ){
                $lstFuncionarios[]  = $accion->idFunResp;
            }
        }

        return array_unique( $lstFuncionarios );
    }
    
    /**
     * 
     * Realiza el proceso de registro de un Plan POA - Funcionario
     * 
     * @return type
     * 
     */
    public function registrarPlanPoaFuncionario()
    {
        //  Recorro la lista de Unidades de Gestion Responsables con la finalidad 
        //  de crear POA's por Unidad de Gestion
        if( count( (array) $this->_lstFuncionarios ) ) {
            foreach( $this->_lstFuncionarios as $idFuncionario ){
                //  Creo un nuevo objetivo al cual le vamos a agregar indicadores y 
                //  acciones de una determinada Unidad de Gestion
                $newObjetivo = clone $this->_dtaObjetivo;
                $newObjetivo->lstIndicadores= $this->_lstIndicadoresXF( $this->_dtaObjetivo, $idFuncionario );
                $newObjetivo->lstAcciones   = $this->_lstAccionesXF( $this->_dtaObjetivo, $idFuncionario );

                //  Obtengo los datos del plan de Tipo POA de una determinada 
                //  Unidad de Gestion
                $dtaPlanPoa = $this->_getDtaPlnF( $idFuncionario, $this->_plan );

                //  A los datos del Plan agrego los objetivos que son de 
                //  responsabilidad del una determinada Unidad de Gestion
                $dtaPlanPoa["lstObjetivos"][] = $newObjetivo;

                $plnPoaF = new Plan( $dtaPlanPoa, $this->_plan->idTpoPlan );

                $this->_registroPlanPoaF( $plnPoaF->__toString() );
            }
        }

        return;
    }
    
    
    /**
     * 
     * Retorno una lista de Indicadores que se encuentra bajo la responsabilidad 
     * de una determinada Unidad de Gestion
     * 
     * @param Object    $dtaPlnObj          Datos del Objetivo Prorrateado
     * @param int       $idFuncionario      Identificador del la Unidad de Gestion Responsable
     * 
     * @return array
     * 
     */
    private function _lstIndicadoresXF( $dtaPlnObj, $idFuncionario )
    {
        $lstIndicadores = array();
        
        if( count( (array) $dtaPlnObj->lstIndicadores ) ){
            foreach( $dtaPlnObj->lstIndicadores as $indicador ){
                if( $indicador->idResponsable == $idFuncionario ){
                    $lstIndicadores[] = $indicador;
                }
            }
        }
        
        return $lstIndicadores;
    }
    
    /**
     * 
     * Retorno una lista de Acciones que se encuentra bajo la responsabilidad 
     * de una determinada Unidad de Gestion
     * 
     * @param Object    $dtaPlnObj          Datos del Objetivo Prorrateado
     * @param int       $idFuncionario      Identificador del Funcionario 
     *                                      Responsable
     * 
     * @return array
     * 
     */
    private function _lstAccionesXF( $dtaPlnObj, $idFuncionario )
    {
        $lstAcciones = array();
        
        if( count( (array) $dtaPlnObj->lstAcciones ) ){
            foreach( $dtaPlnObj->lstAcciones as $accion ){
                if( $accion->idFunResp == $idFuncionario ){
                    $lstAcciones[] = $accion;
                }
            }
        }

        return $lstAcciones;
    }

    /**
     * 
     * Retorno el Identificador del Plan de Tipo POA, asociadoa una determinada 
     * Unidad de Gestion en un PEI Vigente.
     * 
     * En Caso de no tener una Unidad de Gestion retorna FALSE
     * 
     * @param Object $idFuncionario   dta del Indicador
     * 
     * @return int  
     * 
     */
    private function _getDtaPlnF( $idFuncionario, $plan )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTableFuncionarioResponsable( $db );
        
        //  Busco la existencia de un determinado plan POA, cuyo responsable es 
        //  un determinado Funcionario
        $idPln = $tbPlan->getIdPlanPoaF( $idFuncionario, $plan );
        
        //  Obtengo informacion de la Unidad de Gestion Responsable
        $dtaFuncionario = $this->_getDtaFuncionario( $idFuncionario );

        //  Creo el Plan POA - Funcionario
        $dtaPoaF = $this->_crearPlanPoaFuncionario( $idPln, $dtaFuncionario, $plan );
        
        return $dtaPoaF;
    }
    
    
    /**
     * 
     * Obtengo informacion de un determinado Funcionario
     * 
     * @param int $idFuncionario    Identificador del Funcionario
     * 
     * @return Object
     * 
     */
    private function _getDtaFuncionario( $idFuncionario )
    {
        $db = JFactory::getDBO();
        $tbF = new jTableFuncionarioResponsable( $db );

        //  Obtengo datos de la Unidad de Gestion
        return $tbF->getDtaFuncionario( $idFuncionario );
    }
    
    
    /**
     * 
     * Creamos un Plan de tipo POA
     * 
     * @param Object $dtaF         Dta de la Unidad de Gestion
     * @param Object $plan          Dta del Plan con el que se va a crear el 
     *                              PLAN de tipo POA
     */
    private function _crearPlanPoaFuncionario( $idPln, $dtaF, $plan )
    {
        $datetime1 = new datetime( $plan->fchInicio );
        
        $dtaPlan["idPln"]               = $idPln;
        $dtaPlan["intId_tpoPlan"]       = 2;
        $dtaPlan["intCodigo_ins"]       = 1;
        $dtaPlan["intIdPadre_pi"]       = $plan->idPadrePln;
        $dtaPlan["intIdentidad_ent"]    = $dtaF->idEntidadF;
        $dtaPlan["strDescripcion_pi"]   = 'POA_'. $dtaF->nombreF . '_'. $datetime1->format('Y');
        $dtaPlan["dteFechainicio_pi"]   = $plan->fchInicio;
        $dtaPlan["dteFechafin_pi"]      = $plan->fchFin;

        return $dtaPlan;
    }
    
    
    private function _registroPlanPoaF( $plnPoaF )
    {
        //  Obtengo el idRegistro Registro un nuevo Plan de tipo POA
        $idPlanPoa = $this->_regDtaPlanPoaF( $plnPoaF );

        if( $idPlanPoa && count( (array)$plnPoaF->lstObjetivos ) ) {
            foreach( $plnPoaF->lstObjetivos as $objetivo ){
                //  Creo el Objeto Objetivo que gestiona informacion de un objetivo
                $objObjetivo = new Objetivo();

                //  Registro la relacion entre un plan y un objetivo
                $idPlanObjetivo = $objObjetivo->registroPlanObjetivo( $objetivo->idPlnObjetivo, $objetivo->idObjetivo, $idPlanPoa, $objetivo->idEntidad, $objetivo->idPadreObj );

                $dtaObjetivo["idObj"]           = $objetivo->idObjetivo;
                $dtaObjetivo["idEntidad"]       = $objetivo->idEntidad;
                $dtaObjetivo["idPlnObjetivo"]   = $idPlanObjetivo;

                $objObjetivo->registroObjetivosProrrateados( $objetivo, (object)$dtaObjetivo );
            }
        }

        return;
    }
    
    
    /**
     * 
     * 
     * 
     * @param type $dtaPlan
     * 
     * @return type
     */
    private function _regDtaPlanPoaF( $dtaPlan )
    {        
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
     
        //  Arma el Objeto Plan de tipo POA UG
        $dta["intId_pi"]            = ( is_null( $dtaPlan->idPln ) )? 0 
                                                                    : $dtaPlan->idPln;

        $dta["intId_tpoPlan"]       = $dtaPlan->intId_tpoPlan;
        $dta["intCodigo_ins"]       = $dtaPlan->intCodigo_ins;
        $dta["intIdentidad_ent"]    = $dtaPlan->intIdentidad_ent;
        $dta["strDescripcion_pi"]   = $dtaPlan->strDescripcion_pi;
        $dta["dteFechainicio_pi"]   = $dtaPlan->dteFechainicio_pi;
        $dta["dteFechafin_pi"]      = $dtaPlan->dteFechafin_pi;
        $dta["intIdPadre_pi"]       = $dtaPlan->intIdPadre_pi;
        
        return $tbPlan->registroPlan( $dta );
    }
}