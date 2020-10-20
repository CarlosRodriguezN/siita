<?php

//  
jimport( 'ecorae.objetivos.objetivo.acciones.accion' );
jimport( 'ecorae.planinstitucion.plnopranual' );

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'planinstitucion.php';

class Acciones
{
    private $_origen;
    private $_lstPlanes;

    public function __construct( $origen = 0 )
    {
        $this->_origen = $origen;
    }

    /**
     *  Gestiona las acciones  de un objetivo.
     *  
     *  @param object    $lstAcciones       Lista de acciones de un objetivo
     *  @param int       $dtaPlnObj         Identificador del objetivo
     * 
     *  @return array   Lista de acciones de un objetivo
     * 
     */
    public function saveAcciones( $lstAcciones, $dtaPlnObj, $idTpoPln = 0 )
    {
        $resultLst = array();

        if( count( ( array ) $lstAcciones ) > 0 ){
            foreach( $lstAcciones AS $accion ){
                $oAccion = new Accion();
                
                if( $accion->published == 1 ){
                    //  Gestion informacion de acciones
                    $dtaAccion = $oAccion->saveAccion(  $accion, 
                                                        $dtaPlnObj );

                }else{
                    $oAccion->deleteAccion( $accion->idAccion );
                }
                
                if ( $dtaAccion->idAccion != 0 ) {
                    $resultLst[(int)$dtaAccion->registroAcc] = $dtaAccion;
                }
            }
        }

        return $resultLst;
    }

    /**
     * 
     * Obtengo el identificador del plan al aque esta asociado el plan
     * 
     * @param int $idPlnObjetivo    Identificador del plan objetivo asociado a 
     *                              una determinada accion
     * 
     * @return array
     */
    private function _getIdPlan( $idPlnObjetivo )
    {
        $db = JFactory::getDBO();
        $tbPlnObjetivo = new jTablePlanObjetivo( $db );

        return $tbPlnObjetivo->getIdPeiObjetivo( $idPlnObjetivo );
    }

    /**
     * 
     * Funcion recursiva que genera una lista de planes asociados 
     * a un determinado Plan
     * 
     * @param type $idPlan      Identificador de un plan
     * 
     */
    private function _lstPlanes( $idPlan )
    {
        //  Genero una lista de planes que estan asociados ( hijos ), 
        //  de un determinado plan
        $lstPlanes = $this->_getPlanes( $idPlan );

        if( count( $lstPlanes ) > 0 ){
            foreach( $lstPlanes as $plan ){
                $this->_lstPlanes[] = $plan;
                $this->_lstPlanes( $plan->idPlan );
            }
        } else{
            return;
        }
    }

    /**
     * 
     * Retorna una lista de planes asociados a un determinado Plan
     * 
     * @param type $idPlan      Identificador del Plan
     * 
     * @return type
     * 
     */
    private function _getPlanes( $idPlan )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );

        return $tbPlan->getLstPlanesHijo( $idPlan );
    }

    /**
     * 
     *  Busca si la entidad de una unidad de gestion esta asociada 
     *  a una lista de planes
     * 
     *  @param type $lstPlanes       Lista de planes asociados   
     *  @param type $idEntidadUG     Entidad de una unidad de gestion
     * 
     *  @return boolean     TRUE: SI existe
     *                      FALSE: si NO existe
     * 
     */
    private function _existeEntidad( $lstPlanes, $idEntidadUG )
    {
        $ban = false;

        foreach( $lstPlanes as $plan ){
            if( $plan->idEntidad == $idEntidadUG ){
                $ban = $plan->idPlan;
                break;
            }
        }

        return $ban;
    }

    /**
     * 
     * Crea un Objetivo a partir de la informacion de una accion
     * 
     * @param type $dtaAccion   Datos de la accion a convertir en objetivo
     * @param type $plan        Informacion del plan 
     * 
     * @return type
     * 
     */
    private function _crearObjetivoAccion( $dtaAccion, $plan )
    {
        //  Creo Objetivo con informacion de accion
        $dtaObjetivo = $this->_crearPlanObjetivo( $dtaAccion, $plan );

        //  Creo el Objeto Objetivo que gestiona informacion de un objetivo
        $objObjetivo = new Objetivo();

        //  Registro la relacion entre un plan y un objetivo
        $idPlanObjetivo = $objObjetivo->registroPlanObjetivo( $dtaObjetivo->idPlnObjetivo, $dtaObjetivo->idObjetivo, $plan->idPlan, $dtaObjetivo->idEntidad, $dtaObjetivo->idPadreObj );

        return $idPlanObjetivo;
    }

    /**
     * 
     * Creo el Plan Objetivo con informacion de la Accion
     * 
     * @param object $dtaAccion   Datos de la Accion a crear 
     * @param object $plan        Datos del Plan
     * 
     * @return Object
     * 
     */
    private function _crearPlanObjetivo( $dtaAccion, $plan )
    {
        //  Seteo Solamente la descripcion del Objetivo ya que estamos 
        //  creando de una Accion un Objetivo
        $dtaObjAccion["idObjetivo"] = 0;
        $dtaObjAccion["idTpoObj"] = 1;
        $dtaObjAccion["descObjetivo"] = $dtaAccion->descripcionAccion;

        //  Creo el Objetivo con informacion de la acccion
        $objAO = new PlanObjetivo( ( object ) $dtaObjAccion, $plan->fchInicio, $plan->fchFin, $plan->idTpoPlan );

        return $objAO->__toString();
    }

    /**
     * 
     * Realizo el proceso de gestion y prorrateo de acciones asociadas a un 
     * determinado objetivo
     * 
     * @param object $accion    Dta de la Accion a gestionar y prorratear
     * @param int $idObj        Identificador del objetivo a que esta asociada la accion
     * 
     * @return type
     * 
     */
    private function _ajusteAcciones( $accion, $idObj )
    {
        //  Obtengo el identificador del plan a la que esta asociado un determinado plan
        $idPlan = $this->_getIdPlan( $accion->idPlnObjetivo );

        //  Genero una lista de planes que estan asociados a un determinado plan
        $this->_lstPlanes( $idPlan->idPei );

        if( count( $this->_lstPlanes ) > 0 ){
            foreach( $this->_lstPlanes as $plan ){
                //  Objeto PlanAccion tiene como proposito prorratear la informacion 
                //  de la accion de acuerdo a un determinado tipo de plan
                $plnAccion = new PlanAccion( $accion, $plan->fchInicio, $plan->fchFin, $plan->idTpoPlan );

                //  Datos de la accion prorrateada
                $dtaAP = $plnAccion->__toString();

                //  Verifico que las acciones a prorratear sean del tipo PEI - PPPP - PAPP
                if( $plan->idTpoPlan != '2' ){
                    $oAccion = new Accion();
                    $dtaAccion = $oAccion->saveAccion( $dtaAP, $idObj );
                } else if( $plan->idTpoPlan == '2' ){
                    //  SI la accion es de tipo POA ( 2 ), prorrateo la informacion 
                    //  y creo un objetivo de esta accion
                    $this->_crearObjetivoAccion( $accion, $plan );
                }
            }
        }

        return;
    }

}
