<?php

//  Importa la tabla necesaria para hacer la gestion
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivosOperativos' . DS . 'tables' . DS . 'planAccionOperativo.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'plnaccion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'accugresponsable.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'accfncresponsable.php';

// Adjunto libreria de gestion las acciones
jimport('ecorae.objetivos.objetivo.acciones.accion');


/**
 * Getiona el plan de accion del objetivo operativo
 */
class PlanAccionOperativo {

    public function __construct() {
        return;
    }

    /**
     * 
     * @param type $lstAcciones
     * @param type $idObjOpe
     */
    public function savePlanAccionOperativo($lstAcciones, $idObjOpe ) 
    {
        $result = array();
        foreach ($lstAcciones AS $accion) {
            if ( $accion->published == 1 ) {
                
                $accion->idAccion = (is_numeric($accion->idAccion) && (int)$accion->idAccion > 0) ? (int)$accion->idAccion : 0;
                $newAcc = ( $accion->idAccion == 0 ) ? true : false;
                $idAcc = $this->_saveAccionOperativa( $accion );
                
                if ( $idAcc ) {
                    //  Si es nuevo, registro la relacion de la accion con el objetivo operativo
                    if ( $accion->idAccion == 0 ){
                        $idAccObjOpe = $this->_savePlnAccObjOpr( $idObjOpe, $idAcc );
                    }
                    
                    $accion->idAccion = $idAcc;
                    $accion->idPlnObjAccion = $idAccObjOpe;
                    
                    //  Registro la Unidad de Gestion Responsable de la Accion
                    $this->_registrarUniGesRes( $idAcc, $accion, $newAcc );

                    //  Registro al Funcionario Responsable de la Accion
                    $this->_registrarFuncionarioRes( $idAcc, $accion, $newAcc );
                }
                
            } else if ( $accion->idAccion != 0 ) {
                //  Registra la eliminacion logica de la accion
                $this->_deleteLogicalAccion($accion->idAccion);
            }
        }
        return $lstAcciones;
    }

    /**
     *  Registra un una accion de un objetivo operativo
     * @param array $accion         Objeto accion
     * @return int
     */
    private function _saveAccionOperativa( $accion ) 
    {
        $db = JFactory::getDBO();
        $tbPlnAccion = new jTablePlnAccion($db);
        $idAccion = $tbPlnAccion->registroAccion( $accion );
        return $idAccion;
    }
    
    /**
     *  Registra la relacion entre le objetivo y la accion
     * @param type $idObjOpe        Id de objetivo operatio
     * @param type $idAcc           Id de la accion
     * @return type
     */
    private function _savePlnAccObjOpr( $idObjOpe, $idAcc ) 
    {
        $db = JFactory::getDBO();
        $tbPlnAccOpr = new jTablePlanAccionOperativo($db);
        
        $dta['intId_plnAcc_objOpr']     = 0;
        $dta['intId_plnAccion']         = $idAcc;
        $dta['intIdObjetivo_operativo'] = $idObjOpe;
        
        $idAccOpr = $tbPlnAccOpr->savePlnAccOperativo( $dta );
        return $idAccOpr;
    }
    
    /**
     * Registra una eliminacion logica de un accion
     * @param type $idAccion
     * @return type
     */
    private function _deleteLogicalAccion( $idAccion )
    {
        $db = JFactory::getDBO();
        $tbPlnAccion = new jTablePlnAccion($db);
        return $tbPlnAccion->deleteAccion( $idAccion );
    }
    
    /**
     *  Gestiona las unidades de gestion responsables de un plan de accion
     * @param type $idAcc       Id de la accion 
     * @param type $accion      Objeto accion
     * @param type $newAcc      Bandera si es un accion nuevo igual true si no false 
     */
    private function _registrarUniGesRes( $idAcc, $accion, $newAcc )
    {
        $db = JFactory::getDBO();
        $tbUGR = new jTableAccUGResponsable( $db );
        
        $result = false;
        if( $newAcc ) {
            //  Registra una nueva relacion entre la acion y su unidad de gestiopn responsable
            $result = $tbUGR->registroUniGesRes( $idAcc, $accion );
        } else {
            //  Obtengo la data de la unidad de gestion actual si es que existe si no es un array vacio
            $dtaAcctualUGR = ( (int)$accion->idAccionUGR != 0 ) ? $tbUGR->getActualUGR( $accion->idAccionUGR ) : array();
            
            if ( !empty($dtaAcctualUGR) ) {
                switch (true){
                    //  En el caso que la UGR haya cambiado 
                    case ($dtaAcctualUGR->idUG != $accion->idUniGes):
                        $fechaFin = date("Y-m-d");
                        if ( $accion->fechaInicioUGR && $accion->fechaInicioUGR > $dtaAcctualUGR->fechaInicio ) {
                            $fecha = strtotime($accion->fechaInicioUGR);
                            //Le restamos 1 dia a la nueva fecha de inicio
                            $fechaFin = date("Y-m-d", strtotime("-1 days", $fecha));
                        }
                        $tbUGR->updDateFinUnidadGestion( $accion->idAccionUGR, $fechaFin );
                        $result = $tbUGR->registroUniGesRes( $idAcc, $accion );
                        break;
                        
                    //  En el caso que la fecha de inicio haya cambiado
                    case ($dtaAcctualUGR->idUG == $accion->idUniGes && $dtaAcctualUGR->fechaInicio != $accion->fechaInicioUGR):
                        $tbUGR->updDateUGR( $accion->idAccionUGR, $accion->fechaInicio );
                        $result = $accion->idAccionUGR;
                        break;
                }
                
            } else {
                //  Registra una nueva relacion entre la acion y su unidad de gestiopn responsable
                $result = $tbUGR->registroUniGesRes( $idAcc, $accion );
            }
        }
        
        return $result;
    }
    
    /**
     *  Gestiona funcionarios responsables de un plan de accion
     * @param type $idAcc       Id de la accion 
     * @param type $accion      Objeto accion
     * @param type $newAcc      Bandera si es un accion nuevo igual true si no false 
     */
    private function _registrarFuncionarioRes( $idAcc, $accion, $newAcc )
    {
        $db = JFactory::getDBO();
        $tbFR = new jTableAccFncResponsable( $db );
        
        $result = false;
        
        if( $newAcc ) {
            //  Registra una nueva relacion entre la acion y su unidad de gestiopn responsable
            $result = $tbFR->registroFunRes( $idAcc, $accion );
        } else {
            //  Obtengo la data de la unidad de gestion actual si es que existe si no es un array vacio
            $dtaAcctualFR = ( (int)$accion->idAccionFR != 0 ) ? $tbFR->getActualFR( $accion->idAccionFR ) : array();
            if ( !empty($dtaAcctualFR) ) {
                switch (true){
                    //  En el caso que la UGR haya cambiado 
                    case ($dtaAcctualFR->idFunc != $accion->idFunResp):
                        $fechaFin = date("Y-m-d");
                        if ( $accion->fechaInicioFR && $accion->fechaInicioFR > $dtaAcctualFR->fechaInicio ) {
                            $fecha = strtotime($accion->fechaInicioFR);
                            //Le restamos 1 dia a la nueva fecha de inicio
                            $fechaFin = date("Y-m-d", strtotime("-1 days", $fecha));
                        }
                        $tbFR->updAvalibleFunRes( $accion->idAccionFR, $fechaFin );
                        $result = $tbFR->registroFunRes( $idAcc, $accion );
                        break;
                        
                    //  En el caso que la fecha de inicio haya cambiado
                    case ($dtaAcctualFR->idFunc == $accion->idFunResp && $dtaAcctualFR->fechaInicio != $accion->fechaInicioFR):
                        $tbFR->updDateFunRes( $accion->idAccionFR, $accion->fechaInicio );
                        $result = $accion->idAccionFR;
                        break;
                }
                
            } else {
                //  Registra una nueva relacion entre la acion y su unidad de gestiopn responsable
                $result = $tbFR->registroFunRes( $idAcc, $accion );
            }
        }
        return $result;
    }

    /**
     * 
     * @param type $objetivo
     * @param type $tpoIdEntidadOwner Tipo de entidad a la que pertence el objetivo.
     * @return type
     */
    public function getPlanAccionOperativo($objetivo) 
    {
        $db = JFactory::getDBO();
        $tbPlnAccion = new jTablePlnAccion($db);
        $lstAcciones = $tbPlnAccion->getPlanAccionOperativo($objetivo->idObjetivo);
        
        if (count($lstAcciones) > 0) {
            foreach ($lstAcciones AS $key => $accion) {
                $accion->registroAcc = $key;
            }
        }
        $objetivo->lstAcciones = $lstAcciones;
        
    }


}
