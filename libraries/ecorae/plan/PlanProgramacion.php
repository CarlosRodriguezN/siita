<?php

class PlanProgramacion
{
    private $_dtaIndicador;

    private $_valorLB;
    private $_deltaIncremento;
    private $_periodoIncremento;
    private $_lstFechasFinIntervalo = array();
    private $_lstProgramacion       = array();    


    public function __construct( $dtaIndicador, $tpoPln )
    {
        $this->_dtaIndicador    = clone $dtaIndicador;

        //  Calculo el incremento
        $this->_deltaIncremento         = $this->_calculoDeltaIncremento();
        $this->_lstFechasFinIntervalo   = $this->_getLstFechasIntervalo();
        $this->_valorLB                 = $this->_getValorLB();

        switch( $tpoPln ){
            //  Programacion POA - Trimestral
            case '2':
            case '6':
                $this->_periodoIncremento   = 3;
            break;
            
            //  Programacion PPPP - Anual
            case '3': 
                $this->_periodoIncremento   = 12;
            break;
            
            //  Programacion PAPP - Semestral
            case '4': 
                $this->_periodoIncremento   = 6;
            break;
        }
    }

    
    /**
     * 
     * Genera la lista de programacion
     * 
     * @return type
     */
    public function generarProgramacion()
    {
        $this->_calculoProgramacion( $this->_dtaIndicador->fchHorzMimimo, $this->_dtaIndicador->fchHorzMaximo, $this->_valorLB );
        return $this->_lstProgramacion;
    }

    /**
     * 
     * Calculo el incremento inicial
     * 
     * @return type
     * 
     */
    private function _calculoDeltaIncremento()
    {
        $fchInicio  = new DateTime( $this->_dtaIndicador->fchHorzMimimo );
        $fchFin     = new DateTime( $this->_dtaIndicador->fchHorzMaximo );

        $objFecha   = $fchInicio->diff( $fchFin );

        $dias = $objFecha->days + 1;

        $valLB = $this->_getValorLB();
        
        if( $valLB == 0 ){
            $meta = $this->_dtaIndicador->umbral;
        } else{
            $meta = $this->_dtaIndicador->umbral - $valLB;
        }
        
        $rst = $meta / $dias;

        return $rst;
    }
    
    /**
     * 
     * Retorna una lista con intervalos
     * 
     * @return array;
     */
    private function _getLstFechasIntervalo()
    {
        $lstFchIntervalo = array();

        $anioFchInicio = new DateTime( $this->_dtaIndicador->fchHorzMimimo );
        $anio = $anioFchInicio->format( 'Y' );

        for( $m = 0; $m < 12; $m++ ){
            $ultimoDia = $anio. '-' . ( $m + 1 ). '-' .cal_days_in_month( CAL_GREGORIAN, ( $m + 1 ), $anio );
            $lstFchIntervalo[( $m + 1 )] = new DateTime( $ultimoDia );
        }
        
        return $lstFchIntervalo;
    }

    /**
     * 
     * Retorno el valor de la Linea Base
     * 
     * @return type
     */
    private function _getValorLB()
    {
        $valor = 0;
        
        if( is_array( $this->_dtaIndicador->lstLineaBase ) ){
            foreach( $this->_dtaIndicador->lstLineaBase as $lb ){
                $valor = $lb->valor;
            }
        }else{
            $dtaValor = $this->_dtaIndicador->lstLineaBase->valor;

            $valor = is_null( $dtaValor )   ? 0 
                                            : round( $dtaValor, 2 );
        }

        return $valor;
    }

    /**
     * 
     * Calculo la programacion de acuerdo a un determinado tipo de alineacion
     * 
     * @param date $dtFchInicio     Fecha de Inicio de la programacion
     * @param float $valorInicial   Valor inicial de la programacion
     * 
     */
    private function _calculoProgramacion( $fInicio, $fFin, $valorInicial )
    {
        $dtFchInicio        = new DateTime( $fInicio );
        $dtFchFin           = new DateTime( $fFin );        
        $dtFchFinIntervalo  = $this->_calcularFechaFinIntervalo( $dtFchInicio, $dtFchFin );

        $lst = array();
        $lst["id"]          = 0;
        $lst["fchInicio"]   = $dtFchInicio->format('Y-m-d');
        $lst["fecha"]       = $dtFchFinIntervalo->format('Y-m-d');

        $objFecha   = $dtFchInicio->diff( $dtFchFinIntervalo );
        
        $numDias = $objFecha->days + 1;
        $valor = $valorInicial + ( $numDias * $this->_deltaIncremento );
        $lst["valor"]   = round( $valor, 2 );

        $dtFchFinIntervalo->add( new DateInterval( 'P1D' ) );
        $this->_lstProgramacion[] = $lst;

        if( $dtFchFin > $dtFchFinIntervalo ){
            $this->_calculoProgramacion( $dtFchFinIntervalo->format('Y-m-d'), $fFin, round( $valor, 2 ) );
        }
    }
    
    /**
     * 
     * Calcula la fecha de fin del Intervalo en funcion al periodo de incremento
     * 
     * @param date $dtFchInicio     Fecha de Inicio del periodo
     * @param date $dtFchFin        Fecha de Fin del Periodo
     * 
     * @return date     Fecha de fin de intervalo
     * 
     */
    private function _calcularFechaFinIntervalo( $dtFchInicio, $dtFchFin )
    {
        $anio = $dtFchInicio->format( 'Y' );
        $mes =  $dtFchInicio->format( 'm' ) - 1;
        
        $indice = ( floor( $mes / $this->_periodoIncremento ) + 1 ) * $this->_periodoIncremento;
        $mes =  $dtFchFin->format( 'm' );
        
        $dtFechaFinIntervalo = $this->_lstFechasFinIntervalo[$indice];
        
        if( $dtFchFin < $dtFechaFinIntervalo ){
            $dtFechaFinIntervalo = $dtFchFin;
        }

        return $dtFechaFinIntervalo;
    }
}