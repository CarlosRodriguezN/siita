<?php

class PlanPoaUG
{
    private $_dtaObjetivo;
    private $_plan;
    private $_lstUGResponsables;
    
    public function __construct( $dtaObjetivo, $plan )
    {
        $this->_dtaObjetivo = clone $dtaObjetivo;
        $this->_plan = $plan;

        //  Obtengo una lista de unidades de gestion asociadas a un Objetivo
        $this->_lstUGResponsables = $this->_getLstUnidadesGestion();
    }
    
    
    /**
     * 
     * Retorno una lista de Funcionarios y Unidades Responsables de Indicadores 
     * y Acciones de un Objetivo
     * 
     * @return Array
     * 
     */
    private function _getLstUnidadesGestion()
    {
        $lstUGestion = array();
        $lstIndicadores = $this->_dtaObjetivo->lstIndicadores;
        $lstAcciones    = $this->_dtaObjetivo->lstAcciones;
        
        //  Recorro la lista de indicadores y obtengo Unidades de Gestion 
        //  y Funcionarios Responsables
        if( count( (array)$lstIndicadores ) ){
            foreach( $lstIndicadores as $indicador ){
                $lstUGestion[]      = $indicador->idUGResponsable;
            }
        }
        
        //  Recorro la lista de acciones y obtengo Unidades de Gestion 
        //  y Funcionarios Responsables
        if( count( (array)$lstAcciones ) ){
            foreach( $lstAcciones as $accion ){
                $lstUGestion[]  = $accion->idUniGes;
            }
        }
        
        return array_unique( $lstUGestion );
    }
    
    
    public function registrarPlanPoaUG()
    {
        //  Recorro la lista de Unidades de Gestion Responsables con la finalidad 
        //  de crear POA's por Unidad de Gestion
        if( count( (array) $this->_lstUGResponsables ) ) {
            foreach( $this->_lstUGResponsables as $ugr ){
                //  Creo un nuevo objetivo al cual le vamos a agregar indicadores y 
                //  acciones de una determinada Unidad de Gestion
                $newObjetivo = clone $this->_dtaObjetivo;
                $newObjetivo->lstIndicadores = $this->_lstIndicadoresXUG( $this->_dtaObjetivo, $ugr );
                $newObjetivo->lstAcciones = $this->_lstAccionesXUG( $this->_dtaObjetivo, $ugr );

                //  Obtengo los datos del plan de Tipo POA de una determinada 
                //  Unidad de Gestion
                $dtaPlanPoa = $this->_getDtaPlnUG( $ugr, $this->_plan );

                //  A los datos del Plan agrego los objetivos que son de 
                //  responsabilidad del una determinada Unidad de Gestion
                $dtaPlanPoa["lstObjetivos"][] = $newObjetivo;

                $plnPoaUG = new Plan( $dtaPlanPoa, $this->_plan->idTpoPlan );

                $this->_registroPlanPoaUG( $plnPoaUG->__toString() );
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
     * @param int       $idUGResponsable    Identificador del la Unidad de Gestion Responsable
     * 
     * @return array
     * 
     */
    private function _lstIndicadoresXUG( $dtaPlnObj, $idUGResponsable )
    {
        $lstIndicadores = array();
        
        if( count( (array) $dtaPlnObj->lstIndicadores ) ){
            foreach( $dtaPlnObj->lstIndicadores as $indicador ){
                if( $indicador->idUGResponsable == $idUGResponsable ){
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
     * @param int       $idUGResponsable    Identificador de la Unidad de 
     *                                      Gestion Responsable
     * 
     * @return array
     * 
     */
    private function _lstAccionesXUG( $dtaPlnObj, $idUGResponsable )
    {
        $lstAcciones = array();
        
        if( count( (array) $dtaPlnObj->lstAcciones ) ){
            foreach( $dtaPlnObj->lstAcciones as $accion ){
                if( $accion->idUniGes == $idUGResponsable ){
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
     * @param Object $idUGResponsable   dta del Indicador
     * 
     * @return int  
     * 
     */
    private function _getDtaPlnUG( $idUGResponsable, $plan )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        
        //  Busco la existencia de un determinado plan POA cuyo responsable es 
        //  una determinada Unidad de Gestion
        $idPln = $tbPlan->getIdPlanPoaUG( $idUGResponsable, $plan );
        
        //  Obtengo informacion de la Unidad de Gestion Responsable
        $dtaUG = $this->_getDtaUGestion( $idUGResponsable );

        //  Creo el Plan POA - UG
        $dtaPln = $this->_crearPlanPoa( $idPln, $dtaUG, $plan );
        
        return $dtaPln;
    }
    
    
    /**
     * 
     * Obtengo informacion de una determinada Unidad de Gestion
     * 
     * @param int $idUGestion      Identificador de la Unidad de Gestion
     * 
     * @return Object
     * 
     */
    private function _getDtaUGestion( $idUGestion )
    {
        $db = JFactory::getDBO();
        $tbUG = new JTableUnidadGestion( $db );

        //  Obtengo datos de la Unidad de Gestion
        return $tbUG->getEntidadUG( $idUGestion );
    }
    
    
    /**
     * 
     * Creamos un Plan de tipo POA
     * 
     * @param Object $dtaUG         Dta de la Unidad de Gestion
     * @param Object $plan          Dta del Plan con el que se va a crear el 
     *                              PLAN de tipo POA
     */
    private function _crearPlanPoa( $idPln, $dtaUG, $plan )
    {
        $datetime1 = new datetime( $plan->fchInicio );
        
        $dtaPlan["idPln"]               = $idPln;
        $dtaPlan["intId_tpoPlan"]       = 2;
        $dtaPlan["intCodigo_ins"]       = 1;
        $dtaPlan["intIdPadre_pi"]       = $plan->idPadrePln;
        $dtaPlan["intIdentidad_ent"]    = $dtaUG->idEntidadUG;
        $dtaPlan["strDescripcion_pi"]   = 'POA_'. $dtaUG->nombreUG . '_'. $datetime1->format('Y');
        $dtaPlan["dteFechainicio_pi"]   = $plan->fchInicio;
        $dtaPlan["dteFechafin_pi"]      = $plan->fchFin;

        return $dtaPlan;
    }
    
    
    private function _registroPlanPoaUG( $plnPoaUG )
    {
        //  Obtengo el idRegistro Registro un nuevo Plan de tipo POA
        $idPlanPoa = $this->_regDtaPlanPoa( $plnPoaUG );

        if( $idPlanPoa && count( (array)$plnPoaUG->lstObjetivos ) ) {
            foreach( $plnPoaUG->lstObjetivos as $objetivo ){
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
    private function _regDtaPlanPoa( $dtaPlan )
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