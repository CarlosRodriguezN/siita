<?php

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'planobjetivo.php';

class AjusteIndEntidad
{
    private $_idEntidadObj;
    private $_dtaIndEntidad;
    private $_lstPlnObjetivos;

    /**
     * 
     * @param type $dtaIndEntidad
     * @param type $idEntidadObj
     */
    public function __construct( $dtaIndEntidad, $idEntidadObj )
    {
        $this->_dtaIndEntidad = $dtaIndEntidad;
        $this->_idEntidadObj = $idEntidadObj;

        //  Genero una lista de planes asociados al objetivo que pertenece 
        //  este indicador
        $this->_lstObjPlanHijo( $this->_idEntidadObj );
    }

    /**
     * 
     * Retorno una lista de Objetivos hijos de un determinado Objetivo
     * 
     * @param type $idEntidad   Identificador de la entidad que asocia un 
     *                          indicador con un determinado objetivo
     * 
     * @return type
     * 
     */
    private function _getLstObjHijo( $idEntidad )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanObjetivo( $db );

        return $tbPlan->getPlnObjetivosHijo( $idEntidad );
    }
    
    
    /**
     * 
     * Funcion "recursiva" que gestiona informacion de Objetivos - Plan hijo de un 
     * determinado Objetivo
     * 
     * @param type $idEntidadObj      Identificador de la entidad padre
     * 
     * @return type
     * 
     */
    private function _lstObjPlanHijo( $idEntidadObj )
    {
        $lstHijos = $this->_getLstObjHijo( $idEntidadObj );
        
        if( count( $lstHijos ) > 0 ){
            foreach( $lstHijos as $hijo ){
                $this->_lstPlnObjetivos[] = $hijo;
                $this->_lstObjPlanHijo( $hijo->idEntidad );
            }
        }else{
            return;
        }
    }
    
    /**
     * 
     * Actualiza el valor del umbral de los indicadores hijos
     *  
     * @param type $umbral      Nuevo valor de umbral
     * @param type $idEntidad   Identificador del indicador Padre
     * 
     */
    private function _actualizoValorUmbral( $umbral, $idEntidad )
    {        
        foreach( $this->_lstPlnObjetivos as $plnObj ){
            if( $plnObj->idPadre == $idEntidad ){
                $plnObj->umbral = $umbral;
            }
        }   
    }
    
    /**
     * 
     * Gestiono la creacion de indicadores asociados( hijo ) de un determinado indicador
     * 
     * @param type $idIndEntidadPadre   Identificador del indicador padre
     * 
     * @return type
     * 
     */
    public function creacionIndicadoresHijo()
    {
        if( count( $this->_lstPlnObjetivos ) ){
            foreach( $this->_lstPlnObjetivos as $plnObjetivo ){

                $objIndicador = new PlanIndicador(  $this->_dtaIndEntidad,
                                                    $plnObjetivo->fchInicio, 
                                                    $plnObjetivo->fchFin, 
                                                    $plnObjetivo->idTpoPln );

                $dtaIndProrrateado = $objIndicador->prorrateoIndicador();

                if( $dtaIndProrrateado ){
                    if( $plnObjetivo->umbral == 0 ){
                        $this->_actualizoValorUmbral( $dtaIndProrrateado->umbral, $plnObjetivo->idEntidad );
                    }

                    //  Gestion de Indicadores generados a partir del prorrateo de un indicador
                    $objIndProrrateado = new Indicador();
                    $idIndPadre = $objIndProrrateado->registroIndicador( $plnObjetivo->idEntidad, $dtaIndProrrateado, 2 );
                }
            }
        }
        
        return;
    }
    
    
    /**
     * 
     * Realizo el proceso de actualizacion de Unidades de Gestion responsables 
     * de un indicador, asociadas ( Hijos ) a un determinado Plan
     * 
     * @param type $nuevoIdUGResponsable    Identificador de la nueva Unidad de 
     *                                      Gestion Responsable del indicador
     * 
     * @return type
     * 
     */
    public function updUGResponsable( $nuevoIdUGResponsable )
    {
        echo 'a actualizar::......................';
        var_dump( $this->_lstPlnObjetivos ); exit;

        if( count( $this->_lstPlnObjetivos ) ){
            foreach( $this->_lstPlnObjetivos as $plnObjetivo ){
                //  Ejecuto proceso de Actualizacion de UG Responsables
                $this->_updDtaUGResponsable( $plnObjetivo, $nuevoIdUGResponsable );
            }
        }

        return;
    }
    
    /**
     * 
     * Actualizo informacion de una Unidad de Gestion Responsable de un indicador
     * 
     * @param type $plnObjetivo             Datos del plan al que esta asociado el indicador
     * @param type $nuevoIdUGResponsable    Identificador de la NUEVA unidad de Gestion responsable del indicador
     * 
     */
    private function _updDtaUGResponsable( $plnObjetivo, $nuevoIdUGResponsable )
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();
        $tbUGR = new jTableUndGestionResponsable( $db );

        //  Cambio la vigencia del anterior Unidad de Gestion Responsable
        $tbUGR->updVigenciaUndGestion(  $plnObjetivo->idIndEntidad, 
                                        $this->_dtaIndEntidad->idUGResponsable, 
                                        $plnObjetivo->fchInicio );

        //  Informacion de nueva Unidad de gestion responsable del indicador
        $dtaUndGestionResponsable["intIdIndEntidad"]    = $plnObjetivo->idIndEntidad;
        $dtaUndGestionResponsable["intCodigo_ug"]       = $nuevoIdUGResponsable;
        $dtaUndGestionResponsable["dteFechaInicio_ugr"] = $plnObjetivo->fchInicio;
        $dtaUndGestionResponsable["inpVigencia_ugr"]    = 1;

        return $tbUGR->registrarUndGesResp( $dtaUndGestionResponsable );
    }
    
    
    
    /**
     * 
     * Gestiono el proceso de Actualizacion de Funcionarios Responsables de un Indicador 
     * asociados a un determinado plan
     * 
     * @param type $nuevoIdResponsable  Identificador del Nuevo Funcionario Responsable
     * 
     * @return type
     */
    public function funcionarioResponsable( $nuevoIdResponsable )
    {
        if( count( $this->_lstPlnObjetivos ) ){
            foreach( $this->_lstPlnObjetivos as $plnObjetivo ){
                //  Ejecuto proceso de actualizacion de Funcionario Responsable
                $this->_updDtaFuncionarioResponsable( $plnObjetivo, $nuevoIdResponsable );
            }
        }

        return;
    }
    
    
    /**
     * 
     * Actualizo informacion de un Funcionario Responsable de un determinado indicador
     * 
     * @param Objetivo $plnObjetivo         Datos del plan al que esta asociado el indicador
     * @param int $nuevoIdResponsable       Identificador del NUEVO Funcionario Responsable 
     * 
     * @return type
     * 
     */
    private function _updDtaFuncionarioResponsable( $plnObjetivo, $nuevoIdResponsable )
    {        
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();
        
        //  Instacio la tabla Funcionario Responsable
        $tbFuncionario = new jTableFuncionarioResponsable( $db );

        //  Cambio la vigencia del anterior Unidad de Gestion Responsable
        $tbFuncionario->updVigenciaFuncionario( $plnObjetivo->idIndEntidad, 
                                                $this->_dtaIndEntidad->idFuncionario, 
                                                $plnObjetivo->fchInicio );

        //  Informacion de nueva Unidad de gestion responsable del indicador
        $dtaFuncionarioResponsable["intIdIndEntidad"]   = $plnObjetivo->idIndEntidad;
        $dtaFuncionarioResponsable["intId_ugf"]         = $nuevoIdResponsable;
        $dtaFuncionarioResponsable["dteFechaInicio_fgr"]= $plnObjetivo->fchInicio;
        $dtaFuncionarioResponsable["intVigencia_fgr"]   = 1;

        return $tbFuncionario->registrarFuncionarioResponsable( $dtaFuncionarioResponsable );
    }
}