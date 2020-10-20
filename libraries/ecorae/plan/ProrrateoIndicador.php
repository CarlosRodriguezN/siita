<?php

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanLineaBase.php';

/**
 * 
 * Clase que permite el calculo de fechas y valores meta en funcion de un 
 * determinado Plan ( PPPP, PAPP, POA )
 * 
 * PPPP - Se CREA de manera Anual
 * PAPP - Se CREA de manera Semestral
 * POA  - Se CREA de manera Anual
 * 
 */
class ProrrateoIndicador
{
    private $_dtaIndicador;
    private $_fchPlnInicio;
    private $_fchPlnFin;
    private $_tpoPlan;
    private $_lstFechas;
    
    private $_periodoIncremento;
    private $_factorDivision;
    private $_numIteraciones;

    //  Calculo el incremento
    private $_incremento;
    
    private $_deltaMes;
    private $_valorMinLB;
    private $_lstIndProrrateados = array();
    
    private $_inicio;

    /**
     * 
     * Constructor de la clase ProrrateoIndicador
     * 
     * @param type $dtaIndicador    Datos del Indicador
     * @param type $fchInicio       Fecha de Inicio al que el indicador esta siendo prorrateado
     * @param type $fchFin          Fecha de Fin al que el indicador esta siendo prorrateado
     * @param type $tpoPlan         Tipo de Plan, p.e.: PEI, PPPP, PAPP, POA_ug, POA_funcionario
     * 
     */
    public function __construct( $dtaIndicador, $fchInicio, $fchFin, $tpoPlan = 2 )
    {
        $this->_dtaIndicador    = (object)$dtaIndicador;
        $this->_fchPlnInicio    = new DateTime( $fchInicio );
        $this->_fchPlnFin       = new DateTime( $fchFin );
        $this->_tpoPlan         = $tpoPlan;
        
        $this->_valorMinLB      = $this->_valorMinimoLineaBase();
        
        $this->_factorDivision  = 1;
        $this->_inicio = 0;
        
        switch ( $this->_tpoPlan ){
            // PEI
            case '1': 
                //  Calculo Delta Mes
                $this->_deltaMes = $this->_calculoDeltaMes();

                //  Calculo las fechas del indicador
                $this->_lstFechas = $this->_lstFchIndicador();
            break;
            
            //  POA
            case '2': 
                $this->_periodoIncremento   = 2;
                $this->_factorDivision      = 90;
                $this->_numIteraciones      = 4;

                //  Calculo el incremento
                $this->_incremento  = $this->_calculoIncremento();
            break;
            
            // PPPP
            case '3': 
                //  Calculo Delta Mes
                $this->_deltaMes = $this->_calculoDeltaMes();

                //  Calculo las fechas del indicador
                $this->_lstFechas = $this->_lstFchIndicador();
            break;
        }
    }
    
    /**
     * 
     * Retorna Informacion general del Objeto Indicador
     * 
     * @return type
     * 
     */
    public function __toString()
    {
        $dtaProrrateo["dtaIndicador"]   = $this->_dtaIndicador;
        $dtaProrrateo["fchPlnInicio"]   = $this->_fchPlnInicio;
        $dtaProrrateo["fchPlnFin"]      = $this->_fchPlnFin;
        $dtaProrrateo["valorMinLB"]     = $this->_valorMinLB;
        $dtaProrrateo["deltaMes"]       = $this->_deltaMes;
        
        return $dtaProrrateo;
    }
    
    /**
     * 
     * Calculo el incremento inicial
     * 
     * @return type
     * 
     */
    private function _calculoIncremento()
    {
        $fchInicio = new DateTime( $this->_dtaIndicador->fchHorzMimimo );
        $fchFin = new DateTime( $this->_dtaIndicador->fchHorzMaximo );
        $objFecha = $fchInicio->diff( $fchFin );
        
        $dias = ( $objFecha->days > 0 ) ? $objFecha->days
                                        : 1;

        return ( $this->_dtaIndicador->umbral - $this->_valorMinLB->valor ) / ( $dias / $this->_factorDivision );
    }

    
    /**
     * 
     * Retorna el menor valor de un conjunto de valores registrados 
     * como lineas base.
     * 
     * En caso que no exista ningun valor registrado retorna cero "0"
     * 
     * @return type
     * 
     */
    private function _valorMinimoLineaBase()
    {
        $objLBMin = new stdClass();
        $objLBMin->valor = 0;
        
        foreach( $this->_dtaIndicador->lstLineaBase as $key => $lb ){
            if( $key == 0 ){
                $valor = $lb->valor;
                $objLBMin = $lb;
            }elseif( $lb->valor < $valor ){
                $objLBMin = $lb;   
            }
        }
        
        return $objLBMin;
    }

    /**
     * 
     * Retorna el numero de meses de diferencia que existen entre dos determinadas fechas
     * 
     * @param type $fInicio     Fecha de Inicio
     * @param type $fFin        Fecha de Fin
     * 
     * @return type
     * 
     */
    private function _diferenciaMeses( $fInicio, $fFin )
    {
        $fchInicio = new DateTime( $fInicio );
        $fchFin = new DateTime( $fFin );
        $objFecha = $fchInicio->diff( $fchFin );
        
        $df = ( $objFecha->d > 15 ) ? 1 
                                    : 0;

        return ( $objFecha->y * 12 ) + $objFecha->m + $df;
    }
    
    /**
     * 
     * Retorna el calculo del Valor delta mes aplicando la formula
     * 
     * deltaMes = ( valorMeta - LB ) / ( Numero Total de Meses )
     * 
     * @return int  valor redondeado
     * 
     */
    private function _calculoDeltaMes()
    {
        $valorMeta = $this->_dtaIndicador->umbral;
        $rst = $this->_diferenciaMeses( $this->_dtaIndicador->fchHorzMimimo, $this->_dtaIndicador->fchHorzMaximo );

        $totalMeses = ( $rst > 0 )  ? $rst 
                                    : 1;
        
        return ( $valorMeta - $this->_valorMinLB->valor ) / $totalMeses;
    }
    
    /**
     * 
     * Genera una lista de fechas inicio y fin que corresponden
     * al horizonte de un indicador
     * 
     * @return Array
     * 
     */
    private function _lstFchIndicador()
    {
        $fchInicio  = new DateTime( $this->_dtaIndicador->fchHorzMimimo );
        $fchFin     = new DateTime( $this->_dtaIndicador->fchHorzMaximo );
        $diffAnios  = $fchInicio->diff( $fchFin );
        
        $numAnios   = ( (int)$diffAnios->m == 0 )   ? (int)$diffAnios->y 
                                                    : (int)$diffAnios->y + 1;

        for( $x = 0; $x < $numAnios; $x++ ){
            switch( true ){
                case ( $x == 0 ):
                    $datetime1 = new DateTime( $this->_dtaIndicador->fchHorzMimimo );
                    $dtaFecha["fchInicio"] = $this->_dtaIndicador->fchHorzMimimo;
                    $dtaFecha["fchFin"] = $datetime1->format( 'Y' ).'-12-31';
                break;

                case ( $x == $numAnios - 1 ):
                    $datetime2 = new DateTime( $this->_dtaIndicador->fchHorzMaximo );
                    $dtaFecha["fchInicio"] = $datetime2->format( 'Y' ).'-01-01';
                    $dtaFecha["fchFin"] = $this->_dtaIndicador->fchHorzMaximo;
                break;

                default:
                    $datetime1 = new DateTime( $this->_dtaIndicador->fchHorzMimimo );
                    $dtaFecha["fchInicio"] = ( (int)$datetime1->format( 'Y' ) + $x ).'-01-01';
                    $dtaFecha["fchFin"] = ( (int)$datetime1->format( 'Y' ) + $x ).'-12-31';
                break;
            }

            $lstFechas[] = $dtaFecha;
        }

        return $lstFechas;
    }
    
    /**
     * 
     * Funcion recursiva que calcula las fechas a partir
     * 
     * @param type $lstFechas
     * @param type $lb
     * @param type $vm
     * @param type $pos
     * 
     */
    private function _prorrateoPPPP( $lstFechas, $lb, $vm, $pos )
    {
        $diferenciaMes = $this->_diferenciaMeses( $lstFechas[$pos]["fchInicio"], $lstFechas[$pos]["fchFin"] );
        $nuevoVM = ( $this->_deltaMes * $diferenciaMes ) + $lb;

        if( $pos < count( $lstFechas ) ){

            $dtaProrrateo = array();
            $dtaProrrateo["fchInicio"]  = $lstFechas[$pos]["fchInicio"];
            $dtaProrrateo["fchFin"]     = $lstFechas[$pos]["fchFin"];
            $dtaProrrateo["lb"]         = $lb;
            $dtaProrrateo["meta"]       = round( $nuevoVM );

            $this->_lstIndProrrateados[] = $dtaProrrateo;

            $pos = $pos + 1;
            $this->_prorrateoPPPP( $lstFechas, $nuevoVM, $vm, $pos );
        }else{
            return;
        }
    }
    
    /**
     * 
     * Verifica si las fechas de un indicador prorrateado esta dentro 
     * del rango de fechas correspondientes al Plan
     * 
     * @param objeto $indProrrateado  Datos del indicador prorrateado
     * 
     */
    private function _cumpleRango( $indProrrateado )
    {
        $fchInicioProrrateado   = new DateTime( $indProrrateado["fchInicio"] );
        $fchFinProrrateado      = new DateTime( $indProrrateado["fchFin"] );
        $ban = false;

        if( $fchInicioProrrateado >= $this->_fchPlnInicio && $fchFinProrrateado <= $this->_fchPlnFin ){
            $ban = true;
        }
        
        return $ban;
    }

    /**
     * 
     * Retorno informacion de prorrateo del indicador
     * 
     * @return Object   Datos de informacion de UN SOLO indicador prorrateado
     * 
     */
    public function prorrateoIndicador()
    {
        $dtaIndProrrateo = false;
        
        switch ( $this->_tpoPlan ){
            //  Prorrateo el Plan PEI
            case '1': 
                $this->_prorrateoPPPP( $this->_lstFechas, $this->_valorMinLB->valor, 0, 0 );
            break;
            
            //  Prorrateo el Plan POA - UG / POA - Funcionario
            case '2':
            case '6':
                $dtaProrrateo["fchInicio"]  = $this->_fchPlnInicio->format( 'Y-m-d' );
                $dtaProrrateo["fchFin"]     = $this->_fchPlnFin->format( 'Y-m-d' );
                $dtaProrrateo["lb"]         = $this->_valorMinLB->valor;
                $dtaProrrateo["meta"]       = round( $this->_dtaIndicador->umbral );

                $this->_lstIndProrrateados[] = $dtaProrrateo;
            break;
            
            //  Prorrateo el Plan PPPP
            case '3':
                $this->_prorrateoPPPP( $this->_lstFechas, $this->_valorMinLB->valor, 0, 0 );
            break;
        
            //  Prorrateo el Plan PAPP
            case '4':
                $dtaProrrateo["fchInicio"]  = $this->_fchPlnInicio->format( 'Y-m-d' );
                $dtaProrrateo["fchFin"]     = $this->_fchPlnFin->format( 'Y-m-d' );
                $dtaProrrateo["lb"]         = $this->_valorMinLB->valor;
                $dtaProrrateo["meta"]       = $this->_calculaMeta();

                $this->_lstIndProrrateados[] = $dtaProrrateo;
            break;
        }

        //  Recorro Lista de Indicador prorrateado
        foreach( $this->_lstIndProrrateados as $indProrrateado ){
            //  Verifico si los datos prorrateados del indicador se encuentran 
            //  dentro de los periodos del Programados
            if( $this->_cumpleRango( $indProrrateado ) ){
                $indProrrateo = $indProrrateado;

                $indProrrateo["lbProrrateada"] = $this->_generarLB( $indProrrateado );

                $dtaIndProrrateo[] = (object)$indProrrateo;
            }
        }

        return $dtaIndProrrateo;
    }
    
    
    
    private function _calculaMeta()
    {
        $rst = 0;
        
        if( (int)$this->_fchPlnInicio->format( 'm' ) > 6 ){
            $rst = $this->_dtaIndicador->umbral;
        }else {

           $valorMinLB =  ( is_null( $this->_valorMinLB ) ) ? 0 
                                                            : $this->_valorMinLB->valor;
           
           $rst = round( $valorMinLB + ( ( $this->_dtaIndicador->umbral - $valorMinLB ) / 2 ) );
        }
        
        return $rst;
    }
    
    
    
    /**
     * 
     * Genera la nueva linea base
     * 
     * @param array $dtaIndProrrateado
     * 
     */
    private function _generarLB( $dtaIndProrrateado )
    {
        //  Creo una nueva linea base y lo asigno al indicador
        $objLB = new PlanLineaBase( 1, 
                                    1, 
                                    $this->_valorMinLB->nombre,
                                    $dtaIndProrrateado["lb"], 
                                    $dtaIndProrrateado["fchInicio"], 
                                    $dtaIndProrrateado["fchFin"] );

        //  Asigno la nueva linea Base al indicador
        $rst = array();
        $rst[] = $objLB->__toString();

        return $rst;
    }
    
    /**
     * 
     * Calculo la Programacion de un indicador en funcion frecuencia de monitoreo
     * 
     * @param type $fchInicio   Fecha de Inicio
     * @param type $valor       Valor Inicial
     * @param type $frc         Frecuencia de monitoreo ( por default mes )
     * @param type $num         Numero de dias, meses en funcion de frecuencia de monitoreo
     * 
     * @return type
     * 
     */
    private function _calculoProgramacion( $fchInicio, $valor, $num, $frc = 'M' )
    {
        $fecha = new DateTime( $fchInicio );

        $intervalo = ( $num > 0 )   ? new DateInterval('P1M')
                                    : new DateInterval('P0M');

        $fecha->add( $intervalo );

        if( $num < $this->_numIteraciones ){
            $lst = array();
            $lst["id"]      = 0;
            $lst["fecha"]   = $fecha->format('Y-m-d');
            $lst["valor"]   = $valor + $this->_delta;
            
            $this->_lstIndProrrateados[] = $lst;

            $num = $num + 1;
            $this->_calculoProgramacion( $fecha->format('Y-m-d'), $lst["valor"], $num, $frc );
        }else{
            return;
        }
    }
}