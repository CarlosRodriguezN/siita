<?php

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'ProrrateoAccion.php';

class PlanAccion
{
    private $_dtaAccion;
    private $_fchInicioPln;
    private $_fchFinPln;
    private $_idTpoPlan;
    
    public function __construct( $dtaAccion, $fchInicioPln, $fchFinPln, $idTpoPlan )
    {
        $this->_dtaAccion = clone $dtaAccion;

        $this->_dtaAccion->idPadreAccion    = $dtaAccion->idAccion;
        $this->_dtaAccion->idAccion         = 0;
        $this->_dtaAccion->idPlnObjAccion   = 0;
        $this->_fchInicioPln                = $fchInicioPln;
        $this->_fchFinPln                   = $fchFinPln;
        $this->_idTpoPlan                   = $idTpoPlan;

        $this->_prorrateoAccion();
    }
    
    
    public function __toString()
    {
        return $this->_dtaAccion;
    }
    
    
    /**
     * 
     * Retorno informacion general del objeto
     * 
     * @return type
     */
    public function getDtaAccion()
    {
        return $this->_dtaAccion;
    }
    
    /**
     * 
     * Setea informacion de una Accion de acuerdo a la 
     * informacion de un determinado Plan
     * 
     * @param type $fchPlnInicio   Fecha de Inicio de un Plan
     * @param type $fchPlnFin      Fecha de Fin de un Plan
     * 
     */
    private function _prorrateoAccion()
    {
        $ap = new ProrrateoAccion(  $this->_dtaAccion, 
                                    $this->_fchInicioPln, 
                                    $this->_fchFinPln, 
                                    $this->_idTpoPlan );

        $dtaAccionProrrateada = $ap->prorrateoAccion();

        if( !empty( $dtaAccionProrrateada ) ){
            $this->_dtaAccion->fechaInicioAccion= $dtaAccionProrrateada["fchInicio"];
            $this->_dtaAccion->fechaFinAccion   = $dtaAccionProrrateada["fchFin"];
            $this->_dtaAccion->presupuestoAccion= $dtaAccionProrrateada["monto"];
        }  else {
            $this->_dtaAccion = new stdClass();
        }
        
        return $this->_dtaAccion;
    }
    
    public function __destruct()
    {
        return;
    }
}