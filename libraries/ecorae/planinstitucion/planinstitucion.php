<?php

//  Importo la clase que gestiona los objetivos
jimport( 'ecorae.objetivos.objetivo.objetivos' );
jimport( 'ecorae.objetivos.objetivo.objetivo' );

//  Adjunto Tablas asociadas 
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'planinstitucion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'planinstitucion' . DS . 'pppp.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'planinstitucion' . DS . 'poa.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'planinstitucion' . DS . 'poafuncionario.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'AjusteFechaPlan.php';

class PlanInstitucion
{

    private $_idPlan;
    private $_lstPlanes;

    public function __construct( $idPlan = 0 )
    {
        $this->_idPlan = $idPlan;
    }

    public function getIdPlan()
    {
        return $this->_idPlan;
    }

    /**
     * 
     * Gestiono la creacion de PPPP, correspondientes de un determinado PEI
     * 
     * @param type $idPei   Identificador de un PEI
     * 
     */
    public function gestionarPPPP()
    {
        $pppp = new PPPP( $this->_idPlan );
        $pppp->gestionarPPPP();
    }

    /**
     *  Gestiono la Creacion de PAPP, correspondientes a un determinado PEI
     */
    public function gestionarPAPP()
    {
        $papp = new PAPP( $this->_idPlan );
        $papp->gestionarPAPP();
    }

    /**
     * 
     * Gestion la creacion de los POA's de todas las unidades de Gestion
     * 
     */
    public function gestionarPoasxUG()
    {
        $objPoa = new Poa( $this->_idPlan );
        $objPoa->crearPoasxUndGestion();
    }

    /**
     * Gestiono la creacion de Poas por Funcionarios
     */
    public function gestionarPoasXFuncionarios()
    {
        $objPoaFn = new PoaFuncionario( $this->_idPlan );
        $objPoaFn->crearPoasxFuncionarios();
    }

    /**
     * 
     * 
     * 
     * @param type $objPlan
     * @return type
     * 
     */
    public function guardarPlan( $objPlan )
    {
        $db = JFactory::getDBO();
        
        try{
            $db->transactionStart();
            $tbPlan = new jTablePlanInstitucion( $db );

            //  Guarda el PEI y obtiene su ID
            $idPlan = $tbPlan->registroPlan( $objPlan );
            
            $db->transactionCommit();
            
            return $idPlan;
        } catch (Exception $ex) {
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render($e);
        }
    }

    /**
     *  Registra la informacion de un nuevo Poa generado
     * 
     * @param int $undGes       Data de la unidad de gestion que se va a genetrar el Poa (idEntidad, nombre, alias)
     * @param int $idPadre      Id del padre Pei que dellque se guenera el Poa
     * @param int $anioPoa      Año del que se genera el Poa
     * @return type
     */
    public function registrarPoaUG( $undGes, $idPadre, $anioPoa )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );

        $ugPoa['intId_tpoPlan']     = 2;      // 2 es el id del tipo de plan POA 
        $ugPoa['intCodigo_ins']     = 1;      // 1 Id de la intitucion ecorae por defecto
        $ugPoa['intIdPadre_pi']     = $idPadre->idPei;
        $ugPoa['intIdentidad_ent']  = $undGes->idEntidadUG;
        $ugPoa['strDescripcion_pi'] = 'POA - ' . $anioPoa . ' ' . $undGes->nombreUG;
        $ugPoa['dteFechainicio_pi'] = $anioPoa . '-01-01';
        $ugPoa['dteFechafin_pi']    = $anioPoa . '-12-31';
        $ugPoa['blnVigente_pi']     = $anioPoa;      // Año del Poa
        $ugPoa['strAlias_pi']       = 'POA - ' . $anioPoa . ' ' . $undGes->aliasUG;
        $ugPoa['published']         = 1;      // 1 por defecto para ser avilitado en el borrado logico

        $idPlan = $tbPlan->registroPlan( $ugPoa );
        return $idPlan;
    }

    /**
     *  Retorna el ID del PEI al que pertenece el Objetivo
     * 
     * @param int      $idPlnObj       Id del Plan Objetivo del PEI-OBJ
     * @return int
     */
    public function getIdPeiObj( $idPlnObj )
    {
        $db = JFactory::getDBO();
        $tbUG = new jTablePlanObjetivo( $db );
        $result = $tbUG->getIdPeiObjetivo( $idPlnObj->idPlnObjetivo );
        return $result;
    }

    /**
     *  RETORNA el PEI vijente
     * @return type
     */
    public function getPeiVigente()
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        $result = $tbPlan->getPlanVigente( 0, 1 );
        return $result;
    }

    /**
     * RETORNA el PEI vigente
     * 
     * @return type
     */
    public function getPlanVigente( $idPadre, $tpoPlan, $entidadOwner = 0 )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        $result = $tbPlan->getPlanVigente( $idPadre, $tpoPlan, $entidadOwner );

        return $result;
    }

    /**
     * 
     * @param type $idEntidad   id de la entidad de la unidad de gestion
     * @return type
     */
    public function getPoasByUndGesEnti( $idEntidad )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        $result = $tbPlan->getPoasUG( $idEntidad );
        return $result;
    }

    /**
     *  Actulaiza la vigencia de los planes de acuardo al tipo de plan
     * @param type $id      Id del plan a actualizar la vigencia
     * @param type $op      Opcion de vigente o no vigente 
     * @param type $tpo     Tipo del plan a canviar la vigencia 
     * @param type $entUG   Id de entidad a la que pertenece el plan por defecto cero "entidad de la unidad de gestion para poas"
     * @return type
     */
    public function _updVigenciaPlan( $id, $op, $tpo, $entUG = 0 )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        
        //  Actualizo a NO VIGENTE todos los registros de planes activos
        $tbPlan->noVigente( $tpo, $entUG );
        
        //  Actualizo la vigencia del plan seleccionado
        $res = $tbPlan->updVigencia( $id, $op );
        $dtaPlan = $tbPlan->getDataPlan( $id );
        if( $res && $dtaPlan && $op == 1 ){
            // Obtengo la lista de planes que tengan como padres el plan vigente
            $sltPlanesOwner = $tbPlan->getLstPlanesByTpo( $dtaPlan->idPadre, $tpo, $entUG );
            if( $tpo != 4 ){
                foreach( $sltPlanesOwner As $plan ){
                    if( $plan->idPlan == $id ){
                        //  Obtengo la fechas de inicio y fin del plan
                        $fIni = $plan->fechaInicioPln;
                        $fFin = $plan->fechaFinPln;
                    }
                }
                //  Actualiza la vigencia de los planes hijos del PEI
                $this->_updVigenciaPlanes( $fIni, $fFin, $tpo, $entUG );
            }
        }
        if( $tpo == 3 ){
            $lstPPPP = $sltPlanesOwner;
            foreach( $lstPPPP As $plan ){
                $lstPAPP = $tbPlan->getLstPlanesByTpo( $plan->idPlan, 4 );
                $sltPlanesOwner = array_merge( $lstPAPP, $sltPlanesOwner );
            }
        }

        $result             = new stdClass();
        $result->lstPlanes  = $sltPlanesOwner;
        $result->path       = $this->_getPlanesVigentes( $tpo );

        return $result;
    }

    /**
     * 
     * Actualizo la vigencia de un determinado Plan, en funcion de la opcion de vigencia, 
     * p.e.: cambiar un plan de vigencia "Activa" a "Inactiva", y viceversa
     * 
     * @param type $idPlan          Identificador del Plan
     * @param type $opVigencia      Opcion de vigencia
     * 
     */
    public function updVigenciaPlan( $idPlan, $opVigencia )
    {
        $this->_cambioVigenciaPlan( $idPlan, $opVigencia );
                
        //  Actualizo la vigencia de los planes asociados
        $this->_updVigenciaPlanesAsociados( $idPlan, $opVigencia );

        return true;
    }
    
    
    private function _updVigenciaPlanesAsociados( $idPlan, $opVigencia )
    {
        $lstPlanes = $this->_getLstPlanesAsociados( $idPlan );
        
        if( count( $lstPlanes ) ){
            foreach( $lstPlanes as $plan ){
                $this->_cambioVigenciaPlan( $plan->idPlan, $opVigencia );

                $this->_updVigenciaPlanesAsociados( $plan->idPlan, $opVigencia );
            }
        }

        return;
    }
    
    private function _getLstPlanesAsociados( $idPlan )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );

        //  Actualizo la vigencia del plan seleccionado
        return $tbPlan->getLstPlanesAsociados( $idPlan );
    }
    
    
    
    private function _cambioVigenciaPlan( $idPlan, $opVgen )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );

        //  Actualizo la vigencia del plan seleccionado
        return $tbPlan->updVigencia( $idPlan, $opVgen );
    }
    
    
    
    /**
     *  Actualiza la vigencia de los planes de un determinado Plan padre
     * @param type $fIni        Fecha inicio del Plan padre
     * @param type $fFin        Fecha fin del Plan padre
     * @param type $tpoPlan     Tipo del Plan
     * @return type
     */
    private function _updVigenciaPlanes( $fIni, $fFin, $tpoPlan, $idEntidad )
    {
        $inicio_ts = strtotime( $fIni );
        $fin_ts = strtotime( $fFin );
        $date_ts = strtotime( date( 'Y-m-d' ) );
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        switch( true ){
            case (($date_ts >= $inicio_ts) && ($date_ts <= $fin_ts)):
                $result = $tbPlan->actualizarPlnVigente( date( 'Y-m-d' ), $tpoPlan, $idEntidad );
                break;
            case ( $date_ts > $fin_ts):
                $result = $tbPlan->actualizarPlnVigente( $fFin, $tpoPlan, $idEntidad );
                break;
            case ( $date_ts < $inicio_ts):
                $result = $tbPlan->actualizarPlnVigente( $fIni, $tpoPlan, $idEntidad );
                break;
        }
        return $result;
    }

    private function _getPlanesVigentes( $tpo, $entUG = 1 )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );

        $resul = array();
        $pei = $tbPlan->getPlanVigente( 0, 1 );

//        if ( is_object($pei) ) {
//            $this->validatePlanVigente($pei);
//            $resul[] = $pei;
//        } else {
//            $resul[] = $this->planNoAsignado( 1 );
//        }
//        
//        $lstPoa = $tbPlan->getPoasVigentesByUG(0, 1);
//        
//        if ( !empty($lstPoa) ) {
//            foreach ( $lstPoa AS $poa ) {
//                $this->validatePlanVigente($poa);
//            }
//            $resul[] = $pei;
//        } else {
//            $resul[] = $this->planNoAsignado( 2 );
//        }
//        

        if( is_object( $pei ) ){
            $this->validatePlanVigente( $pei );
            $resul[] = $pei;
            $pppp = $tbPlan->getPlanVigente( $pei->idPln, 3 );
            if( is_object( $pppp ) ){
                $this->validatePlanVigente( $pppp );
                $resul[] = $pppp;
                $papp = $tbPlan->getPlanVigente( $pppp->idPln, 4 );
                if( is_object( $papp ) ){
                    $this->validatePlanVigente( $papp );
                } else{
                    $papp = $this->planNoAsignado( 4 );
                }
                $resul[] = $papp;
            } else{
                $resul[] = $this->planNoAsignado( 3 );
                $resul[] = $this->planNoAsignado( 4 );
            }
        } else{
            $resul[] = $this->planNoAsignado( 1 );
            $resul[] = $this->planNoAsignado( 3 );
            $resul[] = $this->planNoAsignado( 4 );
        }

        return $resul;
    }

    /**
     *  Retorna un objeto de un plan no vigente para el path de planes vigentes
     * @param type $tipo
     * @return type
     */
    public function planNoAsignado( $tipo )
    {
        $pei = ( object ) null;
        $pei->idPln = 0;
        $pei->tipoPln = $tipo;
        $pei->descripcionPln = "Plan no asignado";
        return $pei;
    }

    public function validatePlanVigente( $oPln )
    {
        $inicio_ts = strtotime( $oPln->fechaInicioPln );
        $fin_ts = strtotime( $oPln->fechaFinPln );
        $date_ts = strtotime( date( 'Y-m-d' ) );
        $oPln->color = ($date_ts >= $inicio_ts) && ($date_ts <= $fin_ts) ? 1 : 0;
        return $oPln;
    }

    /**
     * Recupera ls pppp de el pei vigente
     * @param type $idPlan
     */
    public function getLstPPPP( $idPlan )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        $lstPPPP = $tbPlan->getLstPlanesByTpo( $idPlan, 3, 0 );
    }

    public function getLstPlanesByTpo( $idPlnPadre, $tpoPlan, $idEntidad = 0 )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        $lstPPPP = $tbPlan->getLstPlanesByTpo( $idPlnPadre, $tpoPlan, $idEntidad );
    }

    ///////////////    Gestion de eliminacion logica o fisica de un plan    //////////////////
    
    /**
     *  Retorna True SI existe almenos una relacion con el Plan para una 
     * eliminacion logica del registro y se puede 
     * eliminar el registro fisicamente, caso contrario retorna False 
     * 
     * @param type $idPlan
     */
    public function controlEliminarPlan( $idPlan )
    {
        $objetivo = new objetivo();
        $result = $objetivo->getExistenciaRelacionPlan( $idPlan );
        return $result;
    }
    
    public function eliminarLogicoPlan( $idPlan )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        $result = $tbPlan->deleteLogicoPlan( $idPlan );
        return $result;
    }
    
    public function eliminarPlan( $idPlan )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        $result = $tbPlan->deletePlan( $idPlan );
        return $result;
    }
    
    
    public function eliminarPlanesAsociados( $idPlan )
    {
        $lstPlanes = $this->_getLstPlanesAsociados( $idPlan );

        if( count( $lstPlanes ) ){
            foreach( $lstPlanes as $plan ){
                $this->eliminarPlan( $plan->idPlan );
            }
        }
        
        $this->eliminarPlan( $idPlan );
    }
}
