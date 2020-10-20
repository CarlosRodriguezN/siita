<?php

jimport( 'ecorae.planinstitucion.planinstitucion' );
jimport( 'ecorae.objetivos.objetivo.objetivo' );
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanPoaUG.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanPoaFuncionario.php';

class PlanObjetivoAsociado
{
    private $_dtaObjetivo;
    private $_idPlanPadre;
    private $_lstPlanes;
    
    public function __construct( $dtaObjetivo, $idPlanPadre )
    {
        $this->_dtaObjetivo = clone $dtaObjetivo;
        $this->_idPlanPadre = $idPlanPadre;
        
        //  Obtengo la lista de planes de un plan 
        $this->_lstPlanes = $this->_getLstPlanes();
    }
    
    /**
     * 
     * Retorno una lista de planes hijos de un determinado plan
     * 
     * @return type
     */
    private function _getLstPlanes()
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );

        return $tbPlan->getLstPlanesHijo( $this->_idPlanPadre );
    }
    
    /**
     * 
     * Retorna la cantidad de planes asociados
     * 
     * @return type
     */
    public function getNumPlanes()
    {
        return count( $this->_lstPlanes );
    }
    
    /**
     * 
     * Registro el Plan Objetivo Prorrateado
     * 
     * @param type $objetivo    data del Objetivo Prorrateado
     * @param type $idPlan      Identificador del Plan al que pertenece
     * @return type
     */
    private function _registroPlanObjetivo( $objetivo, $idPlan )
    {
        //  Creo el Objeto Objetivo que gestiona informacion de un objetivo
        $objObjetivo = new Objetivo();

        //  Registro el Plan Objetivo
        $idPlanObjetivo = $objObjetivo->registroPlanObjetivo(   $objetivo->idPlnObjetivo, 
                                                                $objetivo->idObjetivo, 
                                                                $idPlan, 
                                                                $objetivo->idEntidad, 
                                                                $objetivo->idPadreObj );

        $dtaObjetivo["idObj"]           = $objetivo->idObjetivo;
        $dtaObjetivo["idEntidad"]       = $objetivo->idEntidad;
        $dtaObjetivo["idPlnObjetivo"]   = $idPlanObjetivo;

        return $objObjetivo->registroObjetivosProrrateados( $objetivo, (object)$dtaObjetivo );
    }
    
    /**
     * 
     * De Acuerdo al plan al que pertenece creamos sus respectivos planes asociados
     * 
     * @return type
     */
    public function crearPlanObjetivosAsociados()
    {
        $newObjetivo = clone $this->_dtaObjetivo;

        if( count( $this->_lstPlanes ) ){
            foreach( $this->_lstPlanes as $plan ){
                $newObjetivo->_idPadreObj = $plan->idEntidad;
                $plnObjetivo = new PlanObjetivo( $newObjetivo, $plan->fchInicio, $plan->fchFin, $plan->idTpoPlan );
                
                //  Registro el nuevo Objetivo prorrateado
                $this->_registroPlanObjetivo( $plnObjetivo->__toString(), $plan->idPlan );

                //  Tomo informacion solo de Tipo PPPP, con la finalidad de CREAR POA's
                if( $plan->idTpoPlan == 3 ) {
                    //  Creo un Plan POA - UG
                    $poaUG = new PlanPoaUG( $plnObjetivo->__toString(), $plan );

                    //  Registro el POA - UG
                    $poaUG->registrarPlanPoaUG();

                    //  Creo el POA - Funcionario
                    $poaF = new PlanPoaFuncionario( $plnObjetivo->__toString(), $plan );

                    //  Registro el POA - Funcionario
                    $poaF->registrarPlanPoaFuncionario();
                }

                $oap = new PlanObjetivoAsociado( $plnObjetivo->__toString(), $plan->idPlan );
                $oap->crearPlanObjetivosAsociados();
            }
        }

        return;
    }
}