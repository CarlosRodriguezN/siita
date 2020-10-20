<?php

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanLineaBase.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanProgramacion.php';

class EstimacionValores
{
    private $_fchInicio;
    private $_fchFin;
    private $_valor;
    private $_lstFechas;
    private $_idTpo;
    
    private $_lstValoresProrrateados;
    private $_deltaMes;

    /**
     * 
     * Constructor
     * 
     * @param type $valor           Valor a Estimar
     * @param type $fchInicio       Fecha de inicio
     * @param type $fchFin          Fecha de Fin
     * @param type $lstFechas       Lista de fechas en funcion al periodo a estimar
     * @param type $idTpo           Tipo de valor estimar
     *                                  0: Cualquier valor
     *                                  1: Valores Enteros ( porcentajes )  
     * 
     */
    public function __construct( $valor, $fchInicio, $fchFin, $lstFechas, $idTpo = 0 )
    {
        $this->_fchInicio       = $fchInicio;
        $this->_fchFin          = $fchFin;
        $this->_valor           = $valor;
        $this->_idTpo           = $idTpo;
        
        $this->_lstFechas       = $lstFechas;
        $this->_deltaMes        = $this->_calculoDeltaDia();
    }
    
    
    public function getDtaValoreProrrateados()
    {
        $this->_prorrateoValores( $this->_lstFechas, 0, 0 );
        return $this->_lstValoresProrrateados;
    }

    /**
     * 
     * Calculo los valores a generar en funcion a las fechas de inicio y fin
     * 
     * @param type $lstFechas   lista de Periodos de fechas a cumplir
     * @param type $lb          Linea Base en funcion al periodo
     * @param type $pos         Posicion
     * 
     * @return type
     */
    private function _prorrateoValores( $lstFechas, $lb, $pos )
    {
        $diferenciaDias = $this->_totalNumeroDias( $lstFechas[$pos]["fchInicio"], $lstFechas[$pos]["fchFin"] );
        $nuevoVM = ( $this->_deltaMes * $diferenciaDias ) + $lb;

        if( $pos < count( $lstFechas ) ){
            $dtaProrrateo = array();
            
            $dtaProrrateo["delta"]      = $this->_deltaMes;
            $dtaProrrateo["dias"]       = $diferenciaDias;            
            $dtaProrrateo["fchInicio"]  = $lstFechas[$pos]["fchInicio"];
            $dtaProrrateo["fchFin"]     = $lstFechas[$pos]["fchFin"];
            $dtaProrrateo["lb"]         = $this->_generarLineaBase( $lb, $dtaProrrateo["fchInicio"], $dtaProrrateo["fchFin"] );

            $dtaProrrateo["meta"]       = ( $this->_idTpo == 1 )? round( $nuevoVM )
                                                                : round( $nuevoVM, 2 );

            $this->_lstValoresProrrateados[] = $dtaProrrateo;

            $pos = $pos + 1;
            $this->_prorrateoValores( $lstFechas, $nuevoVM, $pos );
        }else{
            return;
        }
    }
    
    /**
     * 
     * Calculo el numero total de meses entre la fecha de Inicio y Fin en un 
     * determinado periodo
     * 
     * @return int
     */
    private function _totalNumeroDias( $fInicio, $fFin )
    {
        $fchInicio = new DateTime( $fInicio );
        $fchFin = new DateTime( $fFin );

        $objFecha = $fchInicio->diff( $fchFin );
        
        return $objFecha->days + 1;
    }
    
    
    private function _calculoDeltaDia()
    {
        return $this->_valor / $this->_totalNumeroDias( $this->_fchInicio, $this->_fchFin );
    }

    /**
     * 
     * Genero Objeto Linea base
     * 
     * @param float $valor      Valor de Linea Base
     * @param date $fchInicio   Fecha de Inicio
     * @param date $fchFin      Fecha de Fin
     * 
     * @return object
     */
    private function _generarLineaBase( $valor, $fchInicio, $fchFin )
    {
        $objLB = new PlanLineaBase( 1, 1, '', $valor, $fchInicio, $fchFin, 2 );
        
        return $objLB->__toString();
    }
}