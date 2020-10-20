<?php

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos' . DS . 'objetivo' . DS . 'indicadores' . DS . 'indicadores.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'EstimacionValores.php';

class PlanificacionOperativa
{
    private $_nombreObjetivo;
    private $_idUGResponsable;
    private $_idUGFuncionario;
    private $_idFuncionario;
    
    private $_fchInicio;
    private $_fchFin;
    private $_valor;
    private $_lstFechas;
    
    private $_lstIndPorMonto;
    private $_lstIndPorciento;

    public function __construct()
    {
        return;
    }
    
    /**
     * 
     * Seteo Informacion de la clase
     * 
     * @param String    $nombreObjetivo
     * @param int       $idUGResponsable
     * @param int       $idUGFuncionario
     * @param int       $idFuncionario
     * @param date      $fchInicio
     * @param date      $fchFin
     * @param real      $valor
     */
    public function setDtaPlanOperativo( $nombreObjetivo, $idUGResponsable, $idUGFuncionario, $idFuncionario, $fchInicio, $fchFin, $valor )
    {
        $this->_nombreObjetivo  = $nombreObjetivo;
        $this->_idUGResponsable = $idUGResponsable;

        $this->_idUGFuncionario = $idUGFuncionario;
        $this->_idFuncionario   = $idFuncionario;

        $this->_fchInicio       = $fchInicio;
        $this->_fchFin          = $fchFin;
        $this->_valor           = $valor;

        $this->_lstFechas       = $this->_getLstFechas();
        
        $this->_lstIndPorMonto  = $this->_getLstIndPorMonto();
        $this->_lstIndPorciento = $this->_getLstIndPorciento();
    }
    
    /**
     * 
     * Genera Planes Operativos
     * 
     * @return Object
     * 
     */
    public function generarPlanesOperativos()
    {
        $lstPlanesOperativos = array();
        
        if( count( $this->_lstFechas ) ){
            foreach( $this->_lstFechas as $fecha ){
                
                $objPlan = new stdClass();
                $anio = new DateTime( $fecha["fchInicio"] );
                
                $objPlan->idTpoPlan     = 2;
                $objPlan->descripcionPln= 'POA-'. $this->_nombreObjetivo .'-'. $anio->format( 'Y' );
                $objPlan->idEntidad     = $this->_idUGResponsable;
                $objPlan->fchInicio     = $fecha["fchInicio"];
                $objPlan->fchFin        = $fecha["fchFin"];
                $objPlan->vigentePln    = 1;

                $objPlan->lstObjetivos  = $this->_getObjetivos( $fecha["fchInicio"], $fecha["fchFin"] );
                
                $lstPlanesOperativos[] = $objPlan;
            }
        }

        return $lstPlanesOperativos;
    }
    
    
    
    
    private function _calculoNumeroAnios()
    {
        $fchInicio  = new DateTime( $this->_fchInicio );
        $fchFin     = new DateTime( $this->_fchFin );

        $f1 = new DateTime( $fchInicio->format( 'Y' ).'-01-01' );
        $f2 = new DateTime( $fchFin->format( 'Y' ).'-01-01' );

        $diffAnios  = $f1->diff( $f2 );

        $numAnios   = ( (int)$diffAnios->m == 0 && (int)$diffAnios->days < 365 )? (int)$diffAnios->y 
                                                                                : (int)$diffAnios->y + 1;
        
        return ( $numAnios == 0 )   ? 1
                                    : $numAnios;
    }
    
    /**
     * 
     * Genero una lista de fechas en funcion a las fechas de inicio y fin
     * 
     * @param type $lstFechas   lista de fechas prorrateadas
     * 
     */
    private function _getLstFechas()
    {
        $lstFechas = array();
        $numAnios = $this->_calculoNumeroAnios();
        
        for( $x = 0; $x < $numAnios; $x++ ){
            switch( true ){
                case ( $x == 0 ):
                    $datetime1 = new DateTime( $this->_fchInicio );
                    $dtaFecha["fchInicio"]  = $this->_fchInicio;

                    if( $numAnios > 1 ){
                        $dtaFecha["fchFin"]     = $datetime1->format( 'Y' ).'-12-31';
                    }else{
                        $dtaFecha["fchFin"]     = $this->_fchFin;
                    }
                break;

                case ( $x == $numAnios - 1 ):
                    $datetime2 = new DateTime( $this->_fchFin );
                    $dtaFecha["fchInicio"]  = $datetime2->format( 'Y' ).'-01-01';
                    $dtaFecha["fchFin"]     = $this->_fchFin;
                break;

                default:
                    $datetime1 = new DateTime( $this->_fchInicio );
                    $dtaFecha["fchInicio"]  = ( (int)$datetime1->format( 'Y' ) + $x ).'-01-01';
                    $dtaFecha["fchFin"]     = ( (int)$datetime1->format( 'Y' ) + $x ).'-12-31';
                break;
            }

            $lstFechas[] = $dtaFecha;
        }

        return $lstFechas;
    }
    
    
    
    private function _getLstIndPorMonto()
    {
        $ev = new EstimacionValores( $this->_valor, $this->_fchInicio, $this->_fchFin, $this->_lstFechas );
        return $ev->getDtaValoreProrrateados();
    }
    
    
    private function _getLstIndPorciento()
    {
        $ev = new EstimacionValores( 100, $this->_fchInicio, $this->_fchFin, $this->_lstFechas, 1 );
        return $ev->getDtaValoreProrrateados();
    }
    
    /**
     * 
     * Retorno Informacion de un determinado Indicador - Plantilla, con la finalidad
     * de adjuntar informacion adicional 
     * 
     * @param int $idPtllaIndicador    Identificador del Indicador - Plantilla
     * 
     * @return object
     * 
     */
    private function _getIndMontoAnual( $idPtllaIndicador, $fchInicio, $fchFin )
    {
        $tbIndicador = new Indicadores();
        $dtaIndIndicador = $tbIndicador->getDtaIndEcoPtlla( $idPtllaIndicador );
        $dtaIndicador = new stdClass();
        
        if( $dtaIndIndicador ){            
            if( count( $this->_lstIndPorMonto ) ){
                foreach( $this->_lstIndPorMonto as $monto ){
                    if( $fchInicio == $monto["fchInicio"] && $fchFin == $monto["fchFin"] ){
                        $dtaIndicador = $this->_setDtaPllaIndicador( $dtaIndIndicador, $monto );
                    }
                }
            }
        }
        
        return $dtaIndIndicador;
    }
    
    
    /**
     * 
     * Retorno Informacion de un determinado Indicador - Plantilla, con la finalidad
     * de adjuntar informacion adicional 
     * 
     * @param int $idPtllaIndicador    Identificador del Indicador - Plantilla
     * 
     * @return object
     * 
     */
    private function _getIndPorcientoAvance( $idPtllaIndicador, $fchInicio, $fchFin )
    {
        $tbIndicador = new Indicadores();
        $dtaIndIndicador = $tbIndicador->getDtaIndEcoPtlla( $idPtllaIndicador );
        $dtaIndicador = new stdClass();

        if( $dtaIndIndicador ){
            
            if( count( $this->_lstIndPorciento ) ){
                foreach( $this->_lstIndPorciento as $porciento ){
                    if( $porciento["fchInicio"] == $fchInicio && $porciento["fchFin"] == $fchFin ){                        
                        $dtaIndicador = $this->_setDtaPllaIndicador( $dtaIndIndicador, $porciento );
                    }
                }
            }

        }
        
        return $dtaIndicador;
    }

    
    /**
     * 
     * Agrego informacion complementaria a un indicador
     * 
     * @param object $dtaIndIndicador     Datos del Indicador
     * @param object $dtaComplementaria   Informacion complementaria para ser agrgada al indicador
     * 
     * @return object
     * 
     */
    private function _setDtaPllaIndicador( $dtaIndIndicador, $dtaComplementaria )
    {
        $dtaIndIndicador->idTpoPln          = 2;
        $dtaIndIndicador->fchHorzMimimo     = $dtaComplementaria["fchInicio"];
        $dtaIndIndicador->fchHorzMaximo     = $dtaComplementaria["fchFin"];
        $dtaIndIndicador->umbral            = $dtaComplementaria["meta"];
        $dtaIndIndicador->idUGResponsable   = $this->_idUGResponsable;
        $dtaIndIndicador->idResponsableUG   = $this->_idUGFuncionario;
        $dtaIndIndicador->idResponsable     = $this->_idFuncionario;
        $dtaIndIndicador->lstVariables      = $this->_getLstPllaVariables( $dtaIndIndicador->idIndPlantilla );
        $dtaIndIndicador->lstPlanificacion  = $this->_getPlanificacion( $dtaIndIndicador, 2 );

        return $dtaIndIndicador;
    }

    
    
    
    private function _getLstPllaVariables( $idIndPlantilla )
    {
        //  Instacio la tabla Indicador Variable
        $db = JFactory::getDBO();
        $tbIndVariable = new jTableIndicadorVariable( $db );

        return $tbIndVariable->getVariablesPlla( $idIndPlantilla );
    }
    
    
    
    /**
     * 
     * Genera la propuesta de Planificacion de un determinado indicador
     * 
     * @param type $dtaIndicador    Datos de Indicador ha planificar
     * @param type $tpoPln          Tipo de Planificacion, 2 - POA
     * @return type
     */
    private function _getPlanificacion( $dtaIndicador, $tpoPln )
    {
        $objProgramacion = new PlanProgramacion( $dtaIndicador, $tpoPln );

        return $objProgramacion->generarProgramacion();
    }
    
    /**
     * 
     * Genera un objetivo con informacion de indicadores para un determinado 
     * periodo de tiempo Planificado
     * 
     * @param date $fchInicio   Fecha de Inicio
     * @param date $fchFin      Fecha de Fin
     * 
     * @return object
     * 
     */
    private function _getObjetivos( $fchInicio, $fchFin )
    {
        $objObjetivo = new stdClass();

        $objObjetivo->descObjetivo      = $this->_nombreObjetivo;
        $objObjetivo->lstIndicadores    = array();
        
        //  Adjunto Indicador Monto Anual
        $objObjetivo->lstIndicadores[] = $this->_getIndMontoAnual( 14, $fchInicio, $fchFin );

        //  Ajunto Indicador Porciento de Avance
        $objObjetivo->lstIndicadores[] = $this->_getIndPorcientoAvance( 15, $fchInicio, $fchFin );
        
        return $objObjetivo;
    }
    
    /**
     * 
     * 
     * 
     * @param int $idEntidad    Identificador de la Entidad
     * 
     */
    public function getDtaPlanesOperativos( $idEntidad )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        
        $lstPlanes = $tbPlan->getPlanesPorEntidad( $idEntidad );
        
        if( count( $lstPlanes ) ){
            foreach( $lstPlanes as $plan ){
                $plan->lstObjetivos  = $this->_getDtaObjetivosPorPlan( $plan->idPlan );
            }
        }

        //  echo json_encode( $lstPlanes ); exit;
        
        return $lstPlanes;
    }
    
    
    
    private function _getDtaObjetivosPorPlan( $idPlan )
    {
        $db = JFactory::getDBO();
        $tbPlanObj = new jTablePlanObjetivo( $db );
        $objetivo = $tbPlanObj->getDtaObjetivo( $idPlan );
        
        if( !empty( $objetivo ) ){
            $objetivo->lstIndicadores   = $this->_getIndicadoresPorObjetivo( $objetivo->idEntidadObjetivo );
            $objetivo->lstAcciones      = $this->_getAccionesPorObjetivo( $objetivo->idPlnObjetivo );
            $objetivo->lstAlineacion    = $this->_getAlineacionPorObjetivo( $objetivo->idEntidadObjetivo );
        }
        
        return $objetivo;
    }
    
    private function _getIndicadoresPorObjetivo( $idEntidad )
    {
        $db = JFactory::getDBO();
        $tbPlanObj = new jTableIndicador( $db );

        $lstIndicadores = $tbPlanObj->getDataIndicadores( $idEntidad );
        
        foreach( $lstIndicadores as $indicador ){
            $indicador->lstUndTerritorial   = $this->_getLstIndUndTerritorial( $indicador->idIndEntidad );
            $indicador->lstLineaBase        = $this->_getLstLineasBase( $indicador->idIndicador );
            $indicador->lstRangos           = $this->_getLstRangos( $indicador->idIndEntidad );
            $indicador->lstVariables        = $this->_getLstVariables( $indicador->idIndicador );
            $indicador->lstDimensiones      = $this->_getLstDimensiones( $indicador->idIndicador );
            $indicador->lstPlanificacion    = $this->_getLstPlanificacion( $indicador->idIndEntidad );
        }
        
        return $lstIndicadores;
    }
    
    
    private function _getLstIndUndTerritorial( $idIndEntidad )
    {
        //  Instacio la tabla Identificador
        $db = JFactory::getDBO();
        $tbUndTerritorial = new jTableIndicadorUT( $db );
        
        return $tbUndTerritorial->getUndTerritorialIndicador( $idIndEntidad );
    }
    

    private function _getLstLineasBase( $idIndicador )
    {
        //  Instacio la tabla Linea Base
        $db = JFactory::getDBO();
        $tbLineaBase = new jTableLineaBase( $db );
        
        return $tbLineaBase->getLstLineasBase( $idIndicador );
    }
    
    
    private function _getLstRangos( $idIndEntidad )
    {
        //  Instacio la tabla Linea Base
        $db = JFactory::getDBO();
        $tbRango = new jTableRango( $db );

        return $tbRango->getLstRangos( $idIndEntidad );
    }
    
    
    private function _getLstVariables( $idIndicador, $idEntFnc = null )
    {
        //  Instacio la tabla Indicador Variable
        $db = JFactory::getDBO();
        $tbIndVariable = new jTableIndicadorVariable( $db );
        $lstElementosFmla = array();
        
        //  Retorno las variables asociadas a un indicador
        $lstElementos = ( is_null( $idEntFnc ) )? $tbIndVariable->getElementosIndicador( $idIndicador )
                                                : $tbIndVariable->getElementosIndicadorPorFuncionario( $idIndicador, $idEntFnc );

        if( count( $lstElementos ) ){
            foreach( $lstElementos as $elemento ){
                
                switch( true ){
                    case ( $elemento->idTpoElemento == 1 ): 
                        $dtaVariable = $tbIndVariable->getElementoVariable( $idIndicador, $elemento->idElemento );
                    break;
                
                    case ( $elemento->idTpoElemento == 2 ): 
                        $dtaVariable = $tbIndVariable->getElementoIndicador( $idIndicador, $elemento->idElemento );
                    break;
                }
                
                $dtaSeguimiento = $this->_getDtaSeguimiento( $idIndicador, $elemento->idIndVariable );

                //  Obtengo informacion de seguimiento de estos elementos
                $dtaVariable->lstSeguimiento = ( is_null( $dtaSeguimiento ) )   ? array()
                                                                                : $dtaSeguimiento;

                $lstElementosFmla[] = $dtaVariable;
            }
        }
        
        return $lstElementosFmla;
    }
    
    
    private function _getLstDimensiones( $idIndicador )
    {
        //  Instacio la tabla Linea Base
        $db = JFactory::getDBO();
        $tbIndDimension = new jTableIndicadorDimension( $db );
        
        return $tbIndDimension->getLstDimensiones( $idIndicador );
    }
    
    private function _getLstPlanificacion( $idIndEntidad )
    {
        //  Instacio la tabla Planificacion Indicador
        $db = JFactory::getDBO();
        $tbPlanificacion = new jTablePlanificacionIndicador( $db );
        
        return $tbPlanificacion->getLstPlanificacion( $idIndEntidad );
    }
    
    
    /**
     * 
     * Retorno Informacion de Seguimiento de una Variable o indicador
     * 
     * @param int $idIndicador      Identificador del Indicador
     * 
     * @return Object
     * 
     */
    private function _getDtaSeguimiento( $idIndicador, $idIndVariable )
    {
        //  Instacio la tabla Linea Base
        $db = JFactory::getDBO();
        $tbIndicador = new jTableIndicador( $db );

        return $tbIndicador->getDtaSeguimientoIndicador( $idIndicador, $idIndVariable );
    }
    
    
    private function _getAccionesPorObjetivo( $idPlnObjetivo )
    {
        //  Instacio la tabla Linea Base
        $db = JFactory::getDBO();
        $tbIndicador = new jTablePlnAccion( $db );
        
        return $tbIndicador->getPlanAccion( $idPlnObjetivo );
    }
    
    
    private function _getAlineacionPorObjetivo( $idEntidadObjetivo )
    {
        $db = JFactory::getDBO();
        $tbAO = new jTableAlineacionOperativa( $db );
        
        return $tbAO->getAlineacionPlnOperativo( $idEntidadObjetivo );
        
    }
    
}