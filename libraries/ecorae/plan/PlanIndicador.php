<?php

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'ProrrateoIndicador.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanProgramacion.php';
        
class PlanIndicador
{
    private $_dtaIndicador;
    private $_tpoPln;
    private $_fchInicio;
    private $_fchFin;

    /**
     * 
     * Creacion del Objeto Indicador
     * 
     * @param Objeto    $dtaIndicador   Objeto de Inidicador
     * @param date      $fchInicio      Fecha de Inicio    
     * @param date      $fchFin         Fecha de Fin
     * @param int       $tpoPln         Tipo de Plan
     * 
     */
    public function __construct( $dtaIndicador, $fchInicio, $fchFin, $tpoPln )
    {   
        $this->_dtaIndicador                        = clone $dtaIndicador;        
        $this->_dtaIndicador->idTpoPln              = $tpoPln;
        $this->_dtaIndicador->idIEPadre             = $this->_dtaIndicador->idIndEntidad;
        $this->_dtaIndicador->idIndEntidad          = 0;
        $this->_dtaIndicador->idIndicador           = 0;
        $this->_dtaIndicador->fchInicioUG           = $fchInicio;
        $this->_dtaIndicador->fchInicioFuncionario  = $fchInicio;
        
        //  Seteo a CERO el valor del IdReg de Funcionario Y Unidad de Gestion Responsables del Indicador 
        //  ya que estamos generando un nuevo indicador y por lo tanto un nuevo registro de responsables
        $this->_dtaIndicador->idRegFR   = 0;
        $this->_dtaIndicador->idRegUGR  = 0;

        $this->_tpoPln      = $tpoPln;
        $this->_fchInicio   = $fchInicio;
        $this->_fchFin      = $fchFin;
    }
    
    /**
     * 
     * Retorno Informacion de la clase
     * 
     * @return type
     */
    public function __toString()
    {
        return $this->_dtaIndicador;
    }
    
    /**
     * 
     * Setea informacion de un Indicador de acuerdo a la 
     * informacion de un determinado Plan
     * 
     * @param type $fchInicio   Fecha de Inicio de un Plan
     * @param type $fchFin      Fecha de Fin de un Plan
     * 
     */
    public function prorrateoIndicador()
    {
        $objProrrateo = new ProrrateoIndicador( $this->_dtaIndicador, 
                                                $this->_fchInicio, 
                                                $this->_fchFin, 
                                                $this->_tpoPln );

        $dtaProrrateoInd = $objProrrateo->prorrateoIndicador();
        
        if( $dtaProrrateoInd ){
            $this->_dtaIndicador->fchHorzMimimo = $dtaProrrateoInd[0]->fchInicio;
            $this->_dtaIndicador->fchHorzMaximo = $dtaProrrateoInd[0]->fchFin;
            $this->_dtaIndicador->umbral        = $dtaProrrateoInd[0]->meta;
            $this->_dtaIndicador->lstLineaBase  = $dtaProrrateoInd[0]->lbProrrateada;
            
            $this->_dtaIndicador->lstPlanificacion  = $this->_generoProgramacion(   $this->_dtaIndicador, 
                                                                                    $this->_tpoPln );
        }else{
            $this->_dtaIndicador = $dtaProrrateoInd;
        }

        return $this->_dtaIndicador;
    }
    
    /**
     * 
     * Genero una propuesta de programacion para un determinando indicador, 
     * en function del tipo de Plan y su Frecuencia de Monitoreo ( Diaria, Mensual, etc ), 
     * 
     * @param object $dtaIndicador  Datos del Indicador
     * @param int $tpoPln           Tipo de plan
     * 
     * @return type
     */
    private function _generoProgramacion( $dtaIndicador, $tpoPln )
    {
        $objProgramacion = new PlanProgramacion( $dtaIndicador, $tpoPln );

        return $objProgramacion->generarProgramacion();
    }
}