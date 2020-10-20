<?php

//  Agrego Clase de Gestion de los Planes Institucionales
jimport( 'ecorae.planinstitucion.planinstitucion' );
jimport( 'ecorae.objetivos.objetivo.objetivo' );

// agrego la clse ALINEACIONOPERATIVA
jimport('ecorae.objetivosOperativos.alineacionOperativa');
jimport('ecorae.objetivosOperativos.objetivoOperativo');

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanObjetivo.php';


class AjusteObjetivosPlan
{
    private $_dtaObjetivo;
    private $_idPlan;
    private $_lstPlanes;
    
    /**
     * 
     * Constructor de la Clase AjusteObjetivosPlan
     * 
     * @param type $dtaObjetivo     Datos del objetivo a ajustar
     * @param type $idPlan          Identificador del plan padre
     * 
     */
    public function __construct( $dtaObjetivo, $idPlan )
    {
        $this->_dtaObjetivo = $dtaObjetivo;
        $this->_idPlan      = $idPlan;

        //  Obtengo una lista de todos los planes hijo de un determinado Plan
        $this->_getLstPlanesHijo( $this->_idPlan );
    }
    
    /**
     * 
     * Retorna una lista de planes asociados a un determinado plan
     * 
     * @return type
     * 
     */
    private function _getLstPlanes( $idPlan )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );

        return $tbPlan->getLstPlanesHijo( $idPlan );
    }
    
    
    /**
     * 
     * Funcion recursiva que gestiona informacion de planes hijo de un 
     * determinado plan
     * 
     * @param type $idPlan      Identificador de un plan
     * @return type
     * 
     */
    private function _getLstPlanesHijo( $idPlan )
    {
        $lstHijos = $this->_getLstPlanes( $idPlan );
        
        if( count( $lstHijos ) ){
            foreach( $lstHijos as $hijo ){
                $this->_lstPlanes[] = $hijo;
                $this->_getLstPlanesHijo( $hijo->idPlan );
            }
        }else{
            return;
        }
    }

    /**
     * 
     * Gestiono el registro de un nuevo objetivo a un determinado plan
     * 
     * @return type
     * 
     */
    public function ajusteObjetivo()
    {
        if( count( $this->_lstPlanes ) ){
            foreach( $this->_lstPlanes as $plan ){
                $this->_gestionarNuevoObjetivo( $plan );
            }
        }
        
        return;
    }
    
    /**
     * 
     * Gestiona la Creacion de un nuevo objetivo, en funcion 
     * a un determinado PLAN ( PPPP, PAPP, etc )
     * 
     * @param type $plan    Datos del plan a gestionar
     * 
     * @return type
     */
    private function _gestionarNuevoObjetivo( $plan )
    {
        $banAtributos = false;
        
        //  Con la informacion del objetivo, creo los planes a los que este se debe asociar
        $plnObjetivo = new PlanObjetivo( $this->_dtaObjetivo, $plan->fchInicio, $plan->fchFin, $plan->idTpoPlan );

        //  Verifico si el nuevo objetivo tiene Indicadores ó Acciones
        if( !count( (array)$this->_dtaObjetivo->lstIndicadores ) || !count( (array)$this->_dtaObjetivo->lstAcciones ) ){
            $banAtributos = true;
        }
        
        $dtaPlnObjetivo = $plnObjetivo->__toString();
        
        if( $plan->idTpoPlan != 2 ){
            $this->_procesoRegistroObjetivo( $dtaPlnObjetivo, $plan->idPlan );
        }elseif ( $plan->idTpoPlan == 2 && $banAtributos ) {
            //  Realizo el proceso de creacion de POA's, en funcion de una 
            //  Unidad de Gestion responsable y las fechas de inicio y fin de cada plan
            //  $this->_crearPOAs( $dtaPlnObjetivo, $plan );
        }

        return;
    }
    
    
    private function _procesoRegistroObjetivo( $dtaObjetivo, $idPlan )
    {
        //  Registro el nuevo Objetivo procesado en funcion al plan que esta siendo procesado 
        $dtaNuevoObjetivo = $this->_registroNuevoObjetivo( $dtaObjetivo, $idPlan );

        //  Registro informacion complementaria de Objetivos como es alineacion, indicadores, acciones
        $this->_registroObjetivosProrrateados( $dtaObjetivo, $dtaNuevoObjetivo );
    }
    
    
    
    /**
     * 
     * Registro nuevo objetivo en funcion al Plan al que esta procesado
     * 
     * @param Object $dtaObjetivo     Data del objetivo a registrar
     * 
     * @return Object
     * 
     */
    private function _registroNuevoObjetivo( $dtaObjetivo, $idPlan )
    {
        //  Creo el Objeto Objetivo que gestiona informacion de un objetivo prorrateado al plan al que pertenece
        $objObjetivo = new Objetivo();

        //  Registro el Plan Objetivo del objetivo
        $idPlanObjetivo = $objObjetivo->registroPlanObjetivo(   $dtaObjetivo->idPlnObjetivo, 
                                                                $dtaObjetivo->idObjetivo, 
                                                                $idPlan,
                                                                $dtaObjetivo->idEntidad, 
                                                                $dtaObjetivo->idPadreObj );

        $dtaNuevoObjetivo["idObj"]           = $dtaObjetivo->idObjetivo;
        $dtaNuevoObjetivo["idEntidad"]       = $dtaObjetivo->idEntidad;
        $dtaNuevoObjetivo["idPlnObjetivo"]   = $idPlanObjetivo;
        
        return (object)$dtaNuevoObjetivo;
    }
    
    /**
     * 
     * Registro informacion de Acciones e Indicadores asociados a un Objetivo 
     * en un determinado Plan
     * 
     * @param type $dtaObjetivo         Datos del Objetivo Prorrateado
     * @param type $dtaNuevoObjetivo    
     * @return type
     */
    private function _registroObjetivosProrrateados( $dtaObjetivo, $dtaNuevoObjetivo )
    {
        //  Creo el Objeto Objetivo que gestiona informacion de un objetivo prorrateado al plan al que pertenece
        $objObjetivo = new Objetivo();
        
        $objObjetivo->registroObjetivosProrrateados( $dtaObjetivo, $dtaNuevoObjetivo );
        
        return;
    }
    
    
    /**
     * 
     * Creo los POA's, asociados al Plan Vigente
     * 
     * @param Object $dtaObjetivo     Datos del Objetivo Prorrateado
     * @param Object $plan            Datos del Plan al que estamos homologando
     * 
     * @return type
     */
    private function _crearPOAs( $dtaObjetivo, $plan )
    {
        //  Recorro lista de Indicadores de un objetivo
        if( count( (array)$dtaObjetivo->lstIndicadores ) ){
            foreach( $dtaObjetivo->lstIndicadores as $indicador ){
                //  Obtengo idPlan al que esta asociada la UG en el PEI VIGENTE
                $idPlan = $this->_getIdPlanPoaUG( $indicador );

                $idPlnPoaUG = ( $idPlan )   ? $idPlan 
                                            : $this->_crearPlnPoaUG( $indicador->idUGResponsable, $plan );

                //  Registro y asocio el nuevo objetivo
                $this->_procesoRegistroObjetivo( $dtaObjetivo, $idPlnPoaUG );
            }
        }
        
        //  Recorro lista de Acciones de un Objetivo
//        if( count( (array)$dtaObjetivo->lstAcciones ) ){
//            foreach( $dtaObjetivo->lstAcciones as $accion ){
//                $this->_procesoAccionesPOA();
//            }
//        }

        return;
    }
    
    /**
     * 
     * @param type $indicador
     * 
     */
    private function _getIdPlanPoaUG( $indicador )
    {
        //  Verifico la existencia del plan de tipo POA UG en funcion a las 
        //  fecha de inicio y fin de cada plan
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );

        return $tbPlan->getIdPlanPoaUG( $indicador );
    }
    
    /**
     * 
     * Creo el Plan en funcion en funcion a la UG Responsable de Indicador 
     * y las fechas de Inicio y Fin
     * 
     * @param type $indicador   Datos del indicador
     * @param type $plan        Datos del Plan
     * 
     * @return type
     */
    private function _crearPlnPoaUG( $idUGestion, $plan )
    {
        $db = JFactory::getDBO();
        $tbUG = new JTableUnidadGestion( $db );

        //  Obtengo datos de la Unidad de Gestion
        $dtaUG = $tbUG->getEntidadUG( $idUGestion );

        $dtaPln["intId_tpoPlan"]    = $plan->idTpoPln;
        $dtaPln["intIdPadre_pi"]    = $plan->idPadrePln;
        $dtaPln["intIdentidad_ent"] = $dtaUG->idEntidadUG;
        $dtaPln["strDescripcion_pi"]= $plan->plan. '_'. $dtaUG->nombreUG .'_'.date( 'Y', $plan->fchInicio );
        $dtaPln["dteFechainicio_pi"]= $plan->fchInicio;
        $dtaPln["dteFechafin_pi"]   = $plan->fchFin;
        
        return $this->_crearPlan( $dtaPln );
    }
    
    
    private function _crearPlan( $dtaPlan )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        
        return $tbPlan->registroPlan( $dtaPlan );
    }
    
}