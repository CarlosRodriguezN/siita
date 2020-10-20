<?php

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanObjetivo.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'planinstitucion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'plnaccion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'planobjetivo.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos' . DS . 'objetivo' . DS . 'indicadores'. DS . 'IndicadorEntidad.php';

class AjusteFechasPlanes
{
    public function __construct()
    {
        return;
    }

    public function eliminarPlanes( $idPlan )
    {
        $lstPlanes = $this->_getListaPlanes( $idPlan );

        if( count( $lstPlanes ) ){
            foreach( $lstPlanes as $plan ){
                if( $plan->idTpoPlan != 1 ){
                    //  Elimino Indicadores asociados al objetivo de un determinado plan
                    $this->_eliminarIndicadores( $plan->idEntidadObjetivo );

                    //  Elimino Objetivos
                    $this->_eliminarPlanObjetivos( $plan->idPlnObjetivo );
                    
                    //  Elimino Acciones
                    $this->_eliminarObjetivoAcciones( $plan->idPlnObjetivo );
                    
                    //  Elimino Planes complementarios ( PPPP, PAPP )
                    $this->_eliminarPlanesComplementarios( $plan->idPlan );

                    //  Llamo nuevamente a la misma funcion con la finalidad de eliminar 
                    //  los indicadores de los planes complementarios
                    $this->eliminarPlanes( $plan->idPlan );
                }

                $this->eliminarPlanes( $plan->idPlan );
            }
        }

        return;
    }

    private function _getListaPlanes( $idPlan )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        
        return $tbPlan->getLstPlanes( $idPlan );
    }
    
    
    private function _eliminarIndicadores( $idEntidad )
    {
        $lstIndicadores = $this->_getListaIndicadores( $idEntidad );
        
        if( count( $lstIndicadores ) ){
            foreach( $lstIndicadores as $ind ){
                //  Elimino Indicadores asociados a un determinado indicador - entidad
                $this->_deleteIndicadores( $ind->idIndicador );

                //  Elimino Indicador entidad
                $this->_deleteIndEntidad( $ind->idIndEntidad );
            }
        }

        return;
    }
    
    private function _getListaIndicadores( $idEntidad )
    {
        $db = JFactory::getDBO();
        $tbIE = new jTableIndicadorEntidad( $db );
        
        return $tbIE->_getLstIndicadores( $idEntidad );
    }
    
    /**
     * 
     * Elimino Indicadores asociados a un determinado Indicador - Entidad
     * 
     * @param int $idIndicador     Identificador de Indicador a eliminar
     * 
     * @return int
     * 
     */
    private function _deleteIndicadores( $idIndicador )
    {
        $ind = new Indicador();
        return $ind->eliminacionIndicador( $idIndicador );
    }
    
    /**
     * 
     * Gestiono la eliminacion de un Indicador Entidad
     * 
     * @param int $idIndEntidad     Identificador del Indicador Entidad
     * @return 
     * 
     */
    private function _deleteIndEntidad( $idIndEntidad )
    {
        $indicador = new stdClass();

        $objIndEntidad = new IndicadorEntidad( $indicador, 0, $idIndEntidad, 0, 0, 1 );
        return $objIndEntidad->eliminacionIndicadorEntidad( $idIndEntidad );
    }
    
    private function _eliminarObjetivoAcciones( $idPlanObjetivo )
    {
        $db = JFactory::getDBO();
        $tbAcciones = new jTablePlnAccion( $db );
        
        return $tbAcciones->eliminarAccionPlan( $idPlanObjetivo );
    }
    
    private function _eliminarPlanObjetivos( $idPlnObjetivo )
    {
        $db = JFactory::getDBO();
        $tbPO = new jTablePlanObjetivo( $db );
        
        return $tbPO->deletePlanObjetivo( $idPlnObjetivo );
    }
    
    private function _eliminarPlanesComplementarios( $idPlan )
    {
        $db = JFactory::getDBO();
        $tbPei = new jTablePlanInstitucion( $db );

        return $tbPei->deletePlan( $idPlan );
    }
    
    public function __destruct()
    {
        return;
    }
}