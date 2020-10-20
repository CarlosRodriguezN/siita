<?php

class PlanLineaBase
{
    private $_idFuente;
    private $_idLineaBase;
    private $_descripcion;
    private $_valor;
    private $_fchInicio;
    private $_fchFin;
    private $_tpoLB;
    private $_isNew;
    
    /**
     * 
     * Constructor de la clase Linea Base
     * 
     * @param int       $idFuente       Identificador de la fuente
     * @param int       $idLineaBase    Identificador de la Linea Base
     * @param String    $descripcion    Descripcion de la Linea Base
     * @param float     $valor          Valor
     * @param date      $fchInicio      Fecha de Inicio de vigencia de Linea Base
     * @param date      $fchFin         Fecha de Fin de vigencia de Linea Base
     * @param int       $tpoLB          Tipo de Linea Base:
     *                                      0:  Reales, son creados a partir de informacion 
     *                                          de instituciones publicas, p.e.: INEC
     * 
     *                                      1:  Supuestos, son creados a partir de 
     *                                          proceso de prorrateo de indicadores.
     * 
     */
    public function __construct( $idFuente, $idLineaBase, $descripcion, $valor, $fchInicio, $fchFin, $tpoLB = 1 )
    {
        $this->_idFuente    = $idFuente;
        $this->_idLineaBase = $idLineaBase;
        
        $this->_descripcion = ( !is_null( $descripcion ) && $tpoLB == 0 )
                                    ? $descripcion 
                                    : 'ECORAE';

        $this->_valor       = ( is_null( $valor ) ) ? 0
                                                    : $valor;

        $this->_fuente      = ( !is_null( $descripcion ) && $tpoLB == 0 )
                                    ? $descripcion 
                                    : 'ECORAE';

        $this->_fchInicio   = $fchInicio;
        $this->_fchFin      = $fchFin;
        $this->_tpoLB       = $tpoLB;
        $this->_isNew       = 1;

        return;
    }

    
    public function __toString()
    {
        $dtaLB["idFuente"]      = $this->_idFuente;
        $dtaLB["idLineaBase"]   = $this->_idLineaBase;
        $dtaLB["descripcion"]   = $this->_descripcion;
        $dtaLB["valor"]         = round( $this->_valor, 2 );
        $dtaLB["fuente"]        = $this->_fuente;
        $dtaLB["fchInicio"]     = $this->_fchInicio;
        $dtaLB["fchFin"]        = $this->_fchFin;
        $dtaLB["tpoLB"]         = $this->_tpoLB;
        $dtaLB["isNew"]         = $this->_isNew;
        
        return (object)$dtaLB;
    }
}