<?php

jimport( 'ecorae.planinstitucion.planinstitucion' );
jimport( 'ecorae.objetivos.objetivo.acciones.accion' );
jimport( 'ecorae.objetivos.objetivo.objetivo' );
//  Agrego Clase de Retorna informacion Especifica de un Indicador
jimport( 'ecorae.objetivos.objetivo.indicadores.indicador' );
//  Adjunto Tablas asociadas 
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'planobjetivo.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'unidadgestion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'planinstitucion.php';

class PlnOprAnual
{
    public function __construct()
    {
        
    }
    
    /**
     *  Asigna una accion al Poa de la Unidad de Gestion responsable, si no exixste el POA lo crea
     * 
     * @param object        $accion         Objeto con la información de la acción         
     * @param int           $dataPlnObj     Data de la relacion del objetivo con el plan (PEI)
     * @return type
     */
    public function asignarAccionPoaUG( $accion, $dataPlnObj )
    {
        //  Obtengo el anio de la fecha de inicio de la accion
        $fechaInicio = explode("-", $accion->fechaInicioUGR);
        $aInicio = $fechaInicio[0];
        
        //  La fecha de finalizacion de responsabilidad de la unidad de gestion
        //  por defecto es la fecha de fin de la acciíon
        $fechaFin = explode("-", $accion->fechaFinAccion);
        $aFin = $fechaFin[0];
        
        //  Obtengo el ID de entidad de la Unidad de Gestion
        $dtaUniGes = $this->getEntidadUG( $accion->idUniGes );
        
        if ( isset($aInicio) && isset($aFin) ) {
            if ( $aInicio == $aFin) {
                    $result = $this->addRelacionPoaUG($accion, $dataPlnObj, $aInicio, $dtaUniGes );
            } else {
                for ( $index = $aInicio; $index <= $aFin; $index++  ) {
                    $result = $this->addRelacionPoaUG($accion, $dataPlnObj, $index, $dtaUniGes );
                }
            }
        }
        
        return $result;
    }
    
    /**
     *  Registra la relación entre el POA de la Unidad de Gestion con una 
     *  accion de un determinado objetivo
     * 
     * @param object        $objAddPoa      Data del Objeto a relacionarce con el POA 
     *                                      object: Accion
     *                                      boolean(false) : Indicador
     * @param object        $dataPlnObj     Data de la relacion del Plan(pei) con un objetivo
     * @param int           $anio           Año del POA
     * @param object        $dtaUniGes      Data de la unidad de gestion responsable 
     * @return type
     */
    public function addRelacionPoaUG($objAddPoa, $dataPlnObj, $anio, $dtaUniGes) 
    {
        if ( $dtaUniGes ) {
            $result = TRUE;
            //  Confirmo si existe un Poa para la unidad de gestion en ese anio
            $idPoaUg = $this->existPoaUG( (int)$dtaUniGes->idEntidadUG, (int)$anio );

            if ( !$idPoaUg ) {
                var_dump("va a crear el poa");
                $oPlnInstitucion = new PlanInstitucion();
                $idPadre = $oPlnInstitucion->getIdPeiObj( $dataPlnObj );
                $idPoaUg = $oPlnInstitucion->registrarPoaUG( $dtaUniGes, $idPadre, (int)$anio );
            }

            //  Si existe un POA asignado, registro la accion al objetivo del POA
            $idPlnObjPoa = $this->getIdPlnObjPoa( (int)$idPoaUg, $dataPlnObj->idObj );

            if ( !$idPlnObjPoa ) {
                var_dump("va a crear la relacion con el obj");
                $objetivo = new Objetivo();
                $idEntidadObj = $objetivo->reistroEntidad();
                $idPlnObjPoa = $objetivo->registroPlanObjetivo( 0, $dataPlnObj->idObj, (int)$idPoaUg, $idEntidadObj );
                
//                $idPlnObjPoa = $this->registrarPlnObjPoa( (int)$idPoaUg, $dataPlnObj->idObj);
            }
            
            if ( $objAddPoa->idAccion ) {
                $oAccion = new Accion();
                $result = $oAccion->registroAccPlnObj($objAddPoa->idAccion, (int) $idPlnObjPoa);
            }
        } else {
            $result = $dtaUniGes;
        }
        return $result;
    }
    
    /**
     *  Devuelve el Id de entidad de una unidad de gestion
     * 
     * @param int       $idUGR      Id de la Unidad de Gestion
     * @return type
     */
    public function getEntidadUG( $idUGR )
    {
        $db = JFactory::getDBO();
        $tbUG = new JTableUnidadGestion( $db );
        $result = $tbUG->getEntidadUG( $idUGR );
        return $result;
    }
    
    /**
     *  Verifica si existe creado un POA para un determinado año
     * 
     * @param int       $idEntUG        Id de la entidad de la Unidad de Gestion
     * @param int       $anio           El año del POA
     * @return type
     */
    public function existPoaUG( $idEntUG, $anio )
    {
        $db = JFactory::getDBO();
        $tbUG = new JTableUnidadGestion( $db );
        $result = $tbUG->existPoaUG( $idEntUG, $anio );
        return $result;
    }
    
    /**
     *  Retorna el Id del la ralacion del un POA cun un objetivo en el caso 
     *  de que esxista, si no devuelve False
     * 
     * @param int       $idPoa      Id del POA
     * @param int       $idObj      Id del Objetivo
     * @return type
     */
    public function getIdPlnObjPoa($idPoa, $idObj) 
    {
        $db = JFactory::getDBO();
        $tbUG = new jTablePlanObjetivo( $db );
        $result = $tbUG->getPlanObjetivo( $idPoa, $idObj );
        return $result;
    }
     
    public function asignarPrgPoaUG( $indicador, $dtaObj )
    {
        //  Obtengo el anio de la fecha de inicio de la accion
        $fechaInicio = explode("-", $indicador->fchInicioUG);
        $aInicio = $fechaInicio[0];
        
        //  La fecha de finalizacion de responsabilidad de la unidad de gestion
        //  por defecto es la fecha de fin del Pei al que pretence el Objetivo
        $fechaInicioPie = $this->getFechaInicioPei( $dtaObj );
        $fechaFin = explode("-", $fechaInicioPie);
        $aFin = $fechaFin[0];
        
        //  Obtengo el ID de entidad de la Unidad de Gestion
        $dtaUniGes = $this->getEntUGRInd($indicador->idUGResponsable);
        $tpoObjetoInd = false;
        
        if ($aInicio && $aFin) {
            if ($aInicio == $aFin) {
                $result = $this->addRelacionPoaUG($tpoObjetoInd, $dtaObj, $aInicio, $dtaUniGes);
                if ($result) {
                    if ( !empty( $indicador->lstProgramacion ) && $indicador->lstProgramacion[0]->idProgramacion == 0) {
                        $objIndicador = new Indicador();
                        // asignar la programacion del indicador a la Unidad de Gestion 
                        $objIndicador->asignarPrgIndPoaUG($indicador->idIndEntidad, $indicador->lstProgramacion, $aInicio);
                    }
                }
            } else {
                for ($index = $aInicio; $index <= $aFin; $index++) {
                    $result = $this->addRelacionPoaUG($tpoObjetoInd, $dtaObj, $index, $dtaUniGes);
                    if ($result) {
                        if ( !empty( $indicador->lstProgramacion ) && $indicador->lstProgramacion[0]->idProgramacion == 0) {
                            $objIndicador = new Indicador();
                            // asignar la programacion del indicador a la Unidad de Gestion 
                            $objIndicador->asignarPrgIndPoaUG($indicador->idIndEntidad, $indicador->lstProgramacion, $index);
                        }
                    }
                }
            }
        }
        return $result;
    }
    
    public function getEntUGRInd( $idUGR )
    {
        $db = JFactory::getDBO();
        $tbUG = new JTableUnidadGestion( $db );
        $result = $tbUG->getEntUGRInd( $idUGR );
        return $result;
    }
    
    public function getFechaInicioPei( $dtaObjPln ) 
    {
        $db = JFactory::getDBO();
        $tbPln = new jTablePlanObjetivo( $db );
        $result = $tbPln->getFchIniPei( $dtaObjPln->idPlnObjetivo );
        return $result;
    }
    
    /**
     *  Actulaiza la relacion de los POAs de una Unidad de Gestión responsable de una Acción 
     * 
     * @param int       $dtaAcctualUGR      Id de la unidad de gestión responsable OLD
     * @param int       $idAccion           Id de la Acción 
     * @param int       $fchInicioUGR       Fecha de inicio de gestión de la NEW Unidad de gestión
     * @return type
     */
    public function updAccionUGR( $dtaAcctualUGR, $idAccion, $fchInicioUGR, $idNewUGR ) 
    {
        //  Obtengo los poas de la anterior unidad de gestión responsable
        $lstPoasOldUGR = $this->getPoasOldUGR($dtaAcctualUGR->entidadUG);
        if ($lstPoasOldUGR) {
            foreach ($lstPoasOldUGR AS $poa) {
                //  Elimia la aciones relacionadas a la UGR con fecha de inicio 
                //  mayor o igual a la nueva fecha de inicio de gestion
                $this->delAccinesOldUGR($poa->idPoa, $idAccion, $fchInicioUGR, $dtaAcctualUGR->idUG);
                
                //  Actualiza las fechas de finalizacion de gestion de 
                //  las anteriores unidades de gestión responsables
                $this->updDateOldUGR($idAccion, $fchInicioUGR, $dtaAcctualUGR->idUG);

                //  Relaciona la nueva unidad de gestion a la actividad 
                $db = JFactory::getDBO();
                $tbPlanAccion = new jTablePlnAccion($db);
                $tbPlanAccion->registroUniGesRes( $idAccion, $idNewUGR, $fchInicioUGR );
            }
        }
        return $result;
    }
    
    public function delAccinesOldUGR( $idPoa, $idAccion, $fechaIniNew, $idUG )
    {
        $db = JFactory::getDBO();
        $tbPlanAccion = new jTablePlnAccion( $db );
        $result = $tbPlanAccion->delAccionesPoa( $idPoa, $idAccion, $fechaIniNew, $idUG );
        return $result;
    }
    
    public function updDateOldUGR( $idAccion, $fechaIniNew, $idUG )
    {
        $db = JFactory::getDBO();
        $tbPlanAccion = new jTablePlnAccion( $db );
        $result = $tbPlanAccion->updDateAccionesPoa( $idAccion, $fechaIniNew, $idUG );
        return $result;
    }
    
    /**
     *  Elimina la realacion de un accion con los poas de una desterminada unidad de gestion
     * 
     * @param int      $idEntidadUG     Id de estidad de la unidad de gestion   
     * @param int      $idAccion        Id de la accion relacionada
     * @param int      $anio            Año desde el cual se va a partir a eliminar la relacion
     * @return boolean
     */
    public function deleleAccionPoasUG($idEntidadUG, $idAccion, $anio) 
    {
        $db = JFactory::getDBO();
        $tbPlanAccion = new jTablePlnAccion( $db );
        
        //  Obtengo los poas de la anterior unidad de gestión responsable
        $lstPoasOldUGR = $this->getPoasOldUGR($idEntidadUG);
        if ( !empty($lstPoasOldUGR) ) {
            foreach ($lstPoasOldUGR AS $poa) {
                if ( $poa->anio >= $anio ) {
                    $tbPlanAccion->delAccionesPoa( $poa->idPoa, $idAccion );
                }
            }
        }
        return true;
    }
    
    /**
     *  Retorna una lista de poas de una Unidad de Gestion
     * 
     *  @param int      $idEntidadUG    Id de entidad de la unidad de gestion
     * 
     *  @return type
     * 
     */
    public function getPoasOldUGR($idEntidadUG)
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        $result = $tbPlan->getPoasUG($idEntidadUG);
        return $result;
    }
}