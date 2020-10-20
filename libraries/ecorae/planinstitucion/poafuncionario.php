<?php

jimport( 'ecorae.objetivos.objetivo.objetivos' );
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'planinstitucion.php';

class PoaFuncionario
{
    private $_idPlan;
    private $_lstFuncionarios;
    private $_tpoPln;
    
    public function __construct( $idPlan )
    {
        $this->_idPlan = $idPlan;
        $this->_tpoPln = 2;
        
        $db = JFactory::getDBO();
        $tbFuncionario = new jTableFuncionarioResponsable( $db );
        
        //  Obtengo lista de unidad de Gestion Vigentes
        $this->_lstFuncionarios = $tbFuncionario->getLstFuncionarios();
    }
    
    /**
     * 
     * Creo los Poas de las unidades de Gestion registradas en el sistema
     * 
     * @return Lista de Poas a Registrar
     * 
     */
    public function crearPoasxFuncionarios()
    {
        //  Recorro la lista de Unidades de Gestion
        foreach( $this->_lstFuncionarios as $funcionario ){
            $dtaObjFuncionario = $this->_getDtaOF( $funcionario->idFuncionario );
            
            //  Obtengo una lista unica de Planes con su respectiva informacion
            $lstPlanes = $this->_lstUnicaPlanes( $dtaObjFuncionario );
            
            if( count( $lstPlanes ) ){
                $this->_crearPoaFuncionario( $lstPlanes, $funcionario, $dtaObjFuncionario );
            }
        }

        return;
    }
    
    private function _getDtaOF( $idFuncionario )
    {
        $objetivos = new Objetivos();

        //  Obtengo informacion de Planes, objetivos, acciones, indicadores asociados a un Funcionario
        return $objetivos->getLstObjetivosFuncionarios( $idFuncionario, $this->_idPlan );
    }
    
    private function _crearPoaFuncionario( $lstPlanes, $funcionario, $dtaObjFuncionario )
    {
        foreach( $lstPlanes as $plan ){
            $datetime1 = new DateTime( $plan->fchInicio );

            $dtaPoa["idPln"]               = $this->_getIdPln( $funcionario->idFuncionario, $plan );
            $dtaPoa["intId_tpoPlan"]       = 6;
            $dtaPoa["intCodigo_ins"]       = 1;
            $dtaPoa["intIdPadre_pi"]       = $plan->idPln;
            $dtaPoa["intIdentidad_ent"]    = $funcionario->idEntidad;
            $dtaPoa["strDescripcion_pi"]   = 'POA_Funcionario_'. $funcionario->nombre . '_' . $datetime1->format( 'Y' );
            $dtaPoa["dteFechainicio_pi"]   = $plan->fchInicio;
            $dtaPoa["dteFechafin_pi"]      = $plan->fchFin;

            //  Obtengo informacion de objetivos que pertenecen a un determinado plan
            $dtaPoa["lstObjetivos"] = $this->_getDtaObjetivoXPlan( $plan->idPln, $dtaObjFuncionario );

            //  Gestiono la creacion de un plan de tipo POA - FUNCIONARIO
            $dtaPlanPoa = new Plan( $dtaPoa, $this->_tpoPln, 2 );

            //  Gestiono el proceso de registro de informacion del POA - FUNCIONARIO
            $this->_guardarPlanPoa( $dtaPlanPoa->__toString() );
        }

        return;
    }

    /**
     * 
     * Verifica la existencia de un determinado plan, en caso que el plan exista 
     * retorna su identificador de registro, caso contrario retorna "0" ( cero )
     * 
     * @param type $dtaPlan     datos del plan
     */
    private function _getIdPln( $idFuncionario, $plan )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTableFuncionarioResponsable( $db );

        //  Busco la existencia de un determinado plan POA, cuyo responsable es 
        //  un determinado Funcionario
        return $tbPlan->getIdPlanPoaF( $idFuncionario, $plan );
    }
    
    /**
     * 
     * Retorno una lista Unica de Planes de la lista de Planes asociados 
     * a un determinada Unidad de Gestion
     * 
     * @param Array $dtaObjUG   Lista de Informacion Planes asociados a una 
     *                          unidad de Gestion
     * 
     * @return Array
     * 
     */
    private function _lstUnicaPlanes( $dtaObjUG )
    {
        $lstPlanes = $lst = array();
        
        foreach( $dtaObjUG as $objUG ){
            $lst[] = $objUG->idPlan;
        }
        
        $lstUnica = ( count( $lst ) > 0 )   ? array_unique( $lst ) 
                                            : array();

        foreach( $lstUnica as $plan ){
            $db = JFactory::getDBO();
            $tbPlan = new jTablePlanInstitucion( $db );
            $lstPlanes[] = $tbPlan->getDtaPlan( $plan );
        }
        
        return $lstPlanes;
    }
    
    /**
     * 
     * Retorna una lista de Objetivos y Acciones de un Plan en una unidad de Gestion
     * 
     * @param int $idPlan       Identificador del Plan
     * @param object $dtaObjUG  Datos de los Planes asociados a una determinada Unidad de Gestion
     * 
     * @return Object
     * 
     */
    private function _getDtaObjetivoXPlan( $idPlan, $dtaObjUG )
    {
        $lstObjetivos = array();
        foreach( $dtaObjUG as $objUG ){
            if( $objUG->idPlan == $idPlan ){
                $objObjetivo = new Objetivo();
                $dtaObjetivo = $objObjetivo->getDtaObjetivo( $objUG->idObjetivo );
                $dtaObjetivo->idEntidad = $objUG->idEntidad;
                
                //  instancio la clase indicadores
                $indicadores = new Indicadores();

                //  Listo todos los indicadores asociados a un objetivo de un funcionario
                $dtaObjetivo->lstIndicadores = $indicadores->getLstOtrosIndicadores( $objUG->idEntidad );
                
                //  Listo todos las acciones asociada a un objetivo en una Unidad de Gestion
                $dtaObjetivo->lstAcciones = $objObjetivo->getLstAcciones( $objUG->idAccion );
                
                $lstObjetivos[] = $dtaObjetivo;
            }
        }

        return (object)$lstObjetivos;
    }

    
    
    /**
     * 
     * Gestiono el Registro de informacion de tipo POA
     * 
     * @param type $dtaPlan     Informacion del POA
     * 
     */
    private function _guardarPlanPoa( $dtaPlan )
    {
        //  Obtengo el idRegistro Registro un nuevo Plan de tipo POA - Funcionario
        $idPlanPoa = $this->_regDtaPlanPoa( $dtaPlan );

        if( $idPlanPoa ) {
            //  Identificador del Tpo de Entidad PEI
            $idTpoPEI = 1;

            foreach( $dtaPlan->lstObjetivos as $objetivo ){
                //  Creo el Objeto Objetivo que gestiona informacion de un objetivo
                $objObjetivo = new Objetivo();

                //  Registro la relacion entre un plan y un objetivo
                $idPlanObjetivo = $objObjetivo->registroPlanObjetivo(   $objetivo->idPlnObjetivo, 
                                                                        $objetivo->idObjetivo, 
                                                                        $idPlanPoa, 
                                                                        $objetivo->idEntidad, 
                                                                        $objetivo->idPadreObj );

                $dtaObjetivo["idObj"]           = $objetivo->idObjetivo;
                $dtaObjetivo["idEntidad"]       = $objetivo->idEntidad;
                $dtaObjetivo["idPlnObjetivo"]   = $idPlanObjetivo;

                $objObjetivo->registroObjetivosProrrateados( $objetivo, (object)$dtaObjetivo );
            }
        }else{
            $idPlanPoa = array();
        }
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
        
        return $tbPlan->registroPlan( $dta );
    }
}
