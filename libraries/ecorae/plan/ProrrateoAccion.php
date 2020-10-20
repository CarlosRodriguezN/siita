<?php

class ProrrateoAccion
{
    private $_accion;
    private $_fchPlnInicio;
    private $_fchPlnFin;

    private $_tpoPlan;
    private $_lstFechas;
    
    private $_deltaDia;
    
    private $_lstProrrateoAccion = array();

    /**
     * 
     * Constructor de la clase Prorrateo Accion
     * 
     * @param object $accion        Datos de la accion a prorratear
     * @param date $fchPlnInicio    Fecha de inicio del plan al que esta asociado a un determinado Plan
     * @param date $fchPlnFin       Fecha de fin del plan al que esta asociado a un determinado Plan
     * @param int $idTpoPlan        Identificador del tipo de plan ( PEI, PPPP, PAPP, .... )
     * 
     */
    public function __construct( $accion, $fchPlnInicio, $fchPlnFin, $idTpoPlan )
    {
        $this->_accion      = clone $accion;
        $this->_fchPlnInicio= $fchPlnInicio;
        $this->_fchPlnFin   = $fchPlnFin;
        $this->_tpoPlan     = $idTpoPlan;

        //  Calculo Delta Mes
        $this->_deltaDia = $this->_calculoDeltaDia();

        //  Calculo las fechas del indicador
        $this->_lstFechas = ( $this->_tpoPlan != 4 )? $this->_lstFchAcciones() 
                                                    : array();

    }
    
    
    /**
     * 
     * Retorno informacion de prorrateo del indicador
     * 
     * @return Object   Datos de informacion de UN SOLO indicador prorrateado
     * 
     */
    public function prorrateoAccion()
    {
        $dtaAccionProrrateada = false;

        switch ( $this->_tpoPlan ){            
            //  Prorrateo el Plan POA
            case '2':
                $dtaProrrateo["fchInicio"]      = $this->_accion->fechaInicioAccion;
                $dtaProrrateo["fchInicioStamp"] = $this->_accion->fechaInicioAccion;
                $dtaProrrateo["fchFin"]         = $this->_accion->fechaFinAccion;
                $dtaProrrateo["fchFinStamp"]    = $this->_accion->fechaFinAccion;
                $dtaProrrateo["base"]           = 0;
                $dtaProrrateo["monto"]          = $this->_accion->presupuestoAccion;

                $this->_lstProrrateoAccion[] = $dtaProrrateo;
            break;
            
            //  Prorrateo el Plan PPPP
            case '3':
                //  Prorrateo al accion de tipo PPPP
                $this->_prorrateoAccion( $this->_lstFechas, 0, 0, 0 );
            break;
        
            //  Prorrateo el Plan PAPP
            case '4':
                $dtaAccionProrrateada = $this->_prorrateoAccionPAPP( 0 );                
            break;
        }

        if( $this->_tpoPlan != 4 ){
            //  Recorro Lista de Indicador prorrateado
            foreach( $this->_lstProrrateoAccion as $accion ){
                //  Verifico si los datos prorrateados del indicador se encuentran 
                //  dentro de los periodos del Programados
                if( $this->_cumpleRango( $accion ) ){
                    $dtaAccionProrrateada = $accion;
                }
            }
        }

        return $dtaAccionProrrateada;
    }
    
    /**
     * 
     * Genera una lista de fechas inicio y fin que corresponden
     * al horizonte de un indicador
     * 
     * @return Array
     * 
     */
    private function _lstFchAcciones()
    {
        $fchInicio  = new DateTime( $this->_accion->fechaInicioAccion );
        $fchFin     = new DateTime( $this->_accion->fechaFinAccion );
        $diffAnios  = $fchInicio->diff( $fchFin );

        $numAnios   = ( (int)$diffAnios->m == 0 )   ? (int)$diffAnios->y 
                                                    : (int)$diffAnios->y + 1;

        for( $x = 0; $x < $numAnios; $x++ ){
            switch( true ){
                case ( $x == 0 ):
                    $datetime1 = new DateTime( $this->_accion->fechaInicioAccion );
                    $dtaFecha["fchInicio"] = $this->_accion->fechaInicioAccion;
                    $dtaFecha["fchFin"] = $datetime1->format( 'Y' ).'-12-31';
                break;

                case ( $x == $numAnios - 1 ):
                    $datetime2 = new DateTime( $this->_accion->fechaFinAccion );
                    $dtaFecha["fchInicio"] = $datetime2->format( 'Y' ).'-01-01';
                    $dtaFecha["fchFin"] = $this->_accion->fechaFinAccion;
                break;

                default:
                    $datetime1 = new DateTime( $this->_accion->fechaInicioAccion );
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
     * Retorna el numero de meses de diferencia que existen entre dos determinadas fechas
     * 
     * @param type $fInicio     Fecha de Inicio
     * @param type $fFin        Fecha de Fin
     * 
     * @return type
     * 
     */
    private function _diferenciaDias( $fInicio, $fFin )
    {
        $fchInicio = new DateTime( $fInicio );
        $fchFin = new DateTime( $fFin );

        $objFecha = $fchInicio->diff( $fchFin );
        
        return $objFecha->days + 1;
    }
    
    /**
     * 
     * Retorna el calculo del Valor delta aplicando la formula
     * 
     * deltaMes = Monto ( presupuesto ) / ( Numero Total de Dias )
     * 
     * @return int  valor redondeado
     * 
     */
    private function _calculoDeltaDia()
    {
        return $this->_accion->presupuestoAccion / $this->_diferenciaDias( $this->_accion->fechaInicioAccion, $this->_accion->fechaFinAccion );
        
    }
    
    /**
     * 
     * Funcion recursiva que calcula el monto prorrateado de accion
     * 
     * @param type $lstFechas   Lista de Fechas
     * @param type $base        Valor Monto (presupuesto) base
     * @param type $vm          Valor Monto
     * @param type $pos         Posicion de la lista de fechas
     * 
     * @return type
     */
    private function _prorrateoAccion( $lstFechas, $base, $vm, $pos )
    {
        $diferenciaDias = $this->_diferenciaDias( $lstFechas[$pos]["fchInicio"], $lstFechas[$pos]["fchFin"] );
        $nuevaBase = ( $this->_deltaDia * $diferenciaDias ) + $base;

        if( $pos < count( $lstFechas ) ){

            $dtaProrrateo = array();
            
            $dtaProrrateo["fchInicio"]      = $lstFechas[$pos]["fchInicio"];
            $dtaProrrateo["fchInicioStamp"] = strtotime( $lstFechas[$pos]["fchInicio"] );
            $dtaProrrateo["fchFin"]         = $lstFechas[$pos]["fchFin"];
            $dtaProrrateo["fchFinStamp"]    = strtotime( $lstFechas[$pos]["fchFin"] );
            $dtaProrrateo["base"]           = $base;
            $dtaProrrateo["monto"]          = round( $nuevaBase );

            $this->_lstProrrateoAccion[] = $dtaProrrateo;
            
            $pos = $pos + 1;
            $this->_prorrateoAccion( $lstFechas, $nuevaBase, $vm, $pos );
        }else{
            return;
        }
    }

    
    /**
     * 
     * Funcion recursiva que calcula el monto prorrateado de accion
     * 
     * @param type $lstFechas   Lista de Fechas
     * @param type $base        Valor Monto (presupuesto) base
     * @param type $vm          Valor Monto
     * @param type $pos         Posicion de la lista de fechas
     * 
     * @return type
     */
    private function _prorrateoAccionPAPP( $base )
    {
        $diferenciaDias = $this->_diferenciaDias( $this->_fchPlnInicio, $this->_fchPlnFin );
        $nuevaBase = ( $this->_deltaDia * $diferenciaDias ) + $base;

        $dtaProrrateo = array();

        $dtaProrrateo["fchInicio"]      = $this->_fchPlnInicio;
        $dtaProrrateo["fchInicioStamp"] = strtotime( $this->_fchPlnInicio );
        $dtaProrrateo["fchFin"]         = $this->_fchPlnFin;
        $dtaProrrateo["fchFinStamp"]    = strtotime( $this->_fchPlnFin );
        $dtaProrrateo["base"]           = $base;
        $dtaProrrateo["monto"]          = round( $nuevaBase );

        return $dtaProrrateo;
    }
    
    
    
    
    /**
     * 
     * Verifica si las fechas de una accion prorrateada esta dentro 
     * del rango de fechas correspondientes al Plan
     * 
     * @param objeto $accion  Datos de la accion prorrateada
     * 
     */
    private function _cumpleRango( $accion )
    {
        $ban = false;
        
        if( $accion["fchInicio"] >= $this->_fchPlnInicio && $accion["fchFin"] <= $this->_fchPlnFin ){
            $ban = true;
        }
        
        return $ban;
    }

}