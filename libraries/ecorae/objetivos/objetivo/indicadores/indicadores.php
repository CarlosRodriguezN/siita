<?php

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'indicador.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'indicadorut.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'lineabase.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'rango.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'variable.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'indicadordimension.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'indicadorvariable.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'programacion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'planificacionindicador.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'funcionario.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'alineacion' . DS . 'alineacionexterna.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanLineaBase.php';

class Indicadores
{
    private $_db;
    
    public function __construct()
    {
        $this->_db = JFactory::getDBO();
    }
    
    /**
     * 
     * Retorno Informacion de Indicadores asociados a una determinada Entidad
     * 
     * @return type
     * 
     */
    public function getLstIndicadores( $idEntidad = NULL )
    {
        //  Instacio la tabla Identificador
        $tbIndicador = new jTableIndicador( $this->_db );
        
        return $tbIndicador->getDataIndicadores( $idEntidad );
    }
    
    /**
     *  Recupera el Indicador dado el idIndEntidad ( Identificador indicador entidad )
     * @param type $idIndEntidad    Identificador Indicador Entidad
     * @return type
     */
    public function getIndicadorByIndEnt( $idIndEntidad )
    {
        $tbIndicador = new jTableIndicador( $this->_db );
        return $tbIndicador->getDataIndByEntidad( $idIndEntidad );
    }

    /**
     * 
     * Retorno lista de Unidades Territoriales asociadas a un indicador
     * 
     * @param type $idIndEntidad    Identificador Indicador - Entidad
     * @return type
     * 
     */
    public function getLstIndUndTerritorial( $idIndEntidad )
    {
        //  Instacio la tabla Identificador
        $tbUndTerritorial = new jTableIndicadorUT( $this->_db );
        
        return $tbUndTerritorial->getUndTerritorialIndicador( $idIndEntidad );
    }
    
    
    public function getAgendas( $idIndicador )
    {
        //  Instacio la tabla Identificador
        $tbAlineacion = new jTableAlineacioExterna( $this->_db );
        
        return $tbAlineacion->getAlineacionIndicador( $idIndicador );
    }
    
    
    
    /**
     * 
     * Retorna una lista de lineas base asociadas a un determinado Indicador
     * 
     * @param type $idIndicador     Identificador del Indicador
     * 
     * @return type
     * 
     */
    public function getLstLineasBase( $idIndicador )
    {
        //  Instacio la tabla Linea Base
        $tbLineaBase = new jTableLineaBase( $this->_db );
        
        return $tbLineaBase->getLstLineasBase( $idIndicador );
    }
    
    /**
     * 
     * Retorna una lista de Rangos Asociados a un Indicador
     * 
     * @param type $idIndEntidad    Identificador Indicador Entidad
     * 
     * @return type
     * 
     */
    public function getLstRangos( $idIndEntidad )
    {
        //  Instacio la tabla Linea Base
        $tbRango = new jTableRango( $this->_db );
        
        return $tbRango->getLstRangos( $idIndEntidad );
    }
    
    
    /**
     * 
     * Retorna una lista de Variables Asociadas a un determinado Indicador
     * 
     * @param int $idIndicador      Identificador del Indicador
     * @param int $idEntFnc         Identificador de la entidad del funcionario
     * 
     * @return type
     * 
     */
    public function getLstVariables( $idIndicador, $idEntFnc = null, $fchInicio = null, $fchFin = null )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Indicador Variable
        $tbIndVariable = new jTableIndicadorVariable( $db );
        $lstElementosFmla = array();
        
        //  Retorno las variables asociadas a un indicador
        $lstElementos = ( is_null( $idEntFnc ) )? $tbIndVariable->getElementosIndicador( $idIndicador, $fchInicio, $fchFin )
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

                $dtaSeguimiento = $this->_getDtaSeguimiento( $idIndicador, $elemento->idIndVariable, $fchInicio, $fchFin );

                //  Obtengo informacion de seguimiento de estos elementos
                $dtaVariable->lstSeguimiento = ( is_null( $dtaSeguimiento ) )   ? array()
                                                                                : $dtaSeguimiento;

                $lstElementosFmla[] = $dtaVariable;
            }
        }
        
        return $lstElementosFmla;
    }
    
    /**
     * 
     * Retorna una lista de Dimensiones asociadas a un determinado indicador
     * 
     * @param type $idIndicador     Identificador del Indicador
     * @return type
     */
    public function getLstDimensiones( $idIndicador )
    {
        //  Instacio la tabla Linea Base
        $tbIndDimension = new jTableIndicadorDimension( $this->_db );
        
        return $tbIndDimension->getLstDimensiones( $idIndicador );
    }

    /**
     * 
     * Retorna una lista de Indicadores GAP 
     * Agrupados por Masculino - Femenino - Total
     * 
     * @param type $lstGap  Lista de todos Indicadores GAP asociados por un determinado enfoque
     * 
     */
    public function getLstGAP( $lstGap )
    {        
        //  Genero una lista de dimensiones
        foreach( $lstGap as $gap ){
            $lstDimensiones[] = $gap["idDimension"];
        }
        
        //  Filtro dimensiones unicas
        $dimensiones = array_unique( $lstDimensiones );
        
        //  Recorro lista de dimensiones unicas
        foreach( $dimensiones as $dimension ){
            //  Creo un arreglo vacio, con la finalidad de ingresar informacion de  GAP M - F - T
            $lstDimension = array();
            
            foreach( $lstGap as $dtaGap ){
                $dtaGap["umbral"] = (int)$dtaGap["umbral"];

                if( $dtaGap["idDimension"] == $dimension ){
                    switch( $dtaGap["modeloIndicador"] ){
                        case 'bh': 
                            $lstDimension["gapMasculino"] = $dtaGap;
                        break;
                    
                        case 'bm': 
                            $lstDimension["gapFemenino"] = $dtaGap;
                        break;
                    
                        case 'b': 
                            $lstDimension["gapTotal"] = $dtaGap;
                        break;
                    }
                }
            }
            
            $lstGapCompleta[] = $lstDimension;
        }
        
        return $lstGapCompleta;
    }
    
    /**
     * 
     * Retorna una lista de Indicadores de Enfoque de Igualdad 
     * Agrupados por Genero Masculino - Femenino y Total
     * 
     * @param type $lstEI   Lista de Indicadores Enfoque de Igualdad asociados por un determinado Enfoque
     * @return type
     */
    public function getLstEI( $lstEI )
    {
        //  Genero una lista de dimensiones
        foreach( $lstEI as $ei ){
            $lstEIs[] = $ei["idDimension"];
        }

        //  Filtro dimensiones unicas
        $dimensiones = array_unique( $lstEIs );
        
        //  Recorro lista de dimensiones unicas
        foreach( $dimensiones as $dimension ){
            //  Creo un arreglo vacio, con la finalidad de ingresar informacion de  ei M - F - T
            $lstDimension = array();
            
            foreach( $lstEI as $dtaEI ){
                $dtaEI["umbral"] = (int)$dtaEI["umbral"];
                if( $dtaEI["idDimension"] == $dimension ){
                    switch( $dtaEI["modeloIndicador"] ){
                        case 'bh': 
                            $lstDimension["eiMasculino"] = $dtaEI;
                        break;
                    
                        case 'bm': 
                            $lstDimension["eiFemenino"] = $dtaEI;
                        break;
                    
                        case 'b': 
                            $lstDimension["eiTotal"] = $dtaEI;
                        break;
                    }
                }
            }
            
            $lstEICompleta[] = $lstDimension;
        }
        
        return $lstEICompleta;
    }
    
    
    /**
     * 
     * Retorna una lista de Indicadores de Enfoque Ecorae
     * Agrupados por genero Masculino - Femenino y Total
     * 
     * @param type $lstEE   Lista de indicadores de Enfoque Ecorae
     * 
     * @return type
     */
    public function getLstEE( $lstEE )
    {
        //  Genero una lista de dimensiones
        foreach( $lstEE as $ee ){
            $lstEEs[] = $ee["idDimension"];
        }

        //  Filtro dimensiones unicas
        $dimensiones = array_unique( $lstEEs );

        //  Recorro lista de dimensiones unicas
        foreach( $dimensiones as $dimension ){
            //  Creo un arreglo vacio, con la finalidad de ingresar informacion de  ei M - F - T
            $lstDimension = array();
            
            foreach( $lstEE as $dtaEE ){
                $dtaEE["umbral"] = (int)$dtaEE["umbral"];

                if( $dtaEE["idDimension"] == $dimension ){
                    switch( $dtaEE["modeloIndicador"] ){
                        case 'bh': 
                            $lstDimension["eeMasculino"] = $dtaEE;
                        break;
                    
                        case 'bm': 
                            $lstDimension["eeFemenino"] = $dtaEE;
                        break;
                    
                        case 'b': 
                            $lstDimension["eeTotal"] = $dtaEE;
                        break;
                    }
                }
            }
            
            $lstEECompleta[] = $lstDimension;
        }
        
        return $lstEECompleta;
    }
    
    /**
     * 
     * Gestiono el retorno de informacion de otros indicadores
     * 
     * @param int $idEntidad    Identificador de Entidad
     * @param int $tpoPlan      Identificador del tipo de plan
     * @param int $idEntFnc     Identificador de la entidad del Funcionario
     * 
     * @return type
     * 
     */
    public function getLstOtrosIndicadores( $idEntidad, $tpoPlan = 0, $idEntFnc = null, $fchInicio = null, $fchFin = null )
    {
        //  Instacio la tabla Indicador
        $tbIndicador = new jTableIndicador( $this->_db );
        $lstOtrosInd = $tbIndicador->getLstOtrosIndicadores( $idEntidad, $idEntFnc );

        $dtaOtrosIndicadores = array();
                        
        if( count( $lstOtrosInd ) > 0 ){
            foreach( $lstOtrosInd as $indicador ){

                $dtoIndicador["idIndEntidad"]           = $indicador->idIndEntidad;
                $dtoIndicador["idIndicador"]            = $indicador->idIndicador;
                $dtoIndicador["nombreIndicador"]        = $indicador->nombreIndicador;
                $dtoIndicador["modeloIndicador"]        = $indicador->modeloIndicador;

                $dtoIndicador["umbral"]                 = (int) $indicador->umbral;
                $dtoIndicador["oldUmbral"]              = (int) $indicador->umbral;
                
                $dtoIndicador["tendencia"]              = (int) $indicador->tendencia;
                $dtoIndicador["descripcion"]            = $indicador->descripcion;
                $dtoIndicador["idUndAnalisis"]          = (int) $indicador->idUndAnalisis;
                $dtoIndicador["undAnalisis"]            = $indicador->undAnalisis;
                $dtoIndicador["idTpoUndMedida"]         = (int) $indicador->idTpoUndMedida;
                $dtoIndicador["idUndMedida"]            = (int) $indicador->idUndMedida;
                $dtoIndicador["undMedida"]              = $indicador->undMedida;
                $dtoIndicador["idTpoIndicador"]         = $indicador->idTpoIndicador;
                $dtoIndicador["tpoIndicador"]           = $indicador->tpoIndicador;
                
                $dtoIndicador["formula"]                = $indicador->formula;

                $dtoIndicador["fchHorzMimimo"]          = $indicador->fchHorzMimimo;
                $dtoIndicador["oldFchHorzMimimo"]       = $indicador->fchHorzMimimo;

                $dtoIndicador["fchHorzMaximo"]          = $indicador->fchHorzMaximo;
                $dtoIndicador["oldFchHorzMaximo"]       = $indicador->fchHorzMaximo;

                $dtoIndicador["umbMinimo"]              = $indicador->umbMinimo;
                $dtoIndicador["umbMaximo"]              = $indicador->umbMaximo;
                $dtoIndicador["idClaseIndicador"]       = $indicador->idClaseIndicador;
                $dtoIndicador["idFrcMonitoreo"]         = $indicador->idFrcMonitoreo;

                //  Informacion complementaria
                $dtoIndicador["metodologia"]            = $indicador->metodologia;
                $dtoIndicador["limitaciones"]           = $indicador->limitaciones;
                $dtoIndicador["interpretacion"]         = $indicador->interpretacion;
                $dtoIndicador["disponibilidad"]         = $indicador->disponibilidad;
                
                $dtoIndicador["idRegUGR"]               = $indicador->idRegUGR;
                $dtoIndicador["idUGResponsable"]        = $indicador->idUGResponsable;
                $dtoIndicador["oldIdUGResponsable"]     = $indicador->idUGResponsable;
                
                $dtoIndicador["fchInicioUG"]            = $indicador->fchInicioUG;

                $dtoIndicador["idRegFR"]                = $indicador->idRegFR;
                $dtoIndicador["idResponsableUG"]        = $indicador->idResponsableUG;
                $dtoIndicador["oldIdResponsableUG"]     = $indicador->idResponsableUG;
                
                $dtoIndicador["idResponsable"]          = $indicador->idResponsable;
                $dtoIndicador["oldIdResponsable"]       = $indicador->idResponsable;
                $dtoIndicador["fchInicioFuncionario"]   = $indicador->fchInicioFuncionario;
                $dtoIndicador["intIdIEPadre_indEnt"]    = $indicador->idPadreInd;
                $dtoIndicador["idHorizonte"]            = $indicador->idHorizonte;
                $dtoIndicador["senplades"]              = $indicador->senplades;
                
                //  Asigno al indicador informacion del grupo al que pertenece un determinado indicador
                $dtaGpoIndicador = $this->_getDtaGpoIndicador( $indicador->idIndicador );                
                $dtoIndicador["idGpoDimension"]         = $dtaGpoIndicador->idGpoDimension;
                $dtoIndicador["idGpoDecision"]          = $dtaGpoIndicador->idGpoDecision;

                $dtoIndicador["lstUndsTerritoriales"]   = $this->getLstIndUndTerritorial( $indicador->idIndEntidad );
                $dtoIndicador["lstLineaBase"]           = $this->getLstLineasBase( $indicador->idIndicador );
                $dtoIndicador["lstRangos"]              = $this->getLstRangos( $indicador->idIndEntidad );

                $dtoIndicador["lstVariables"]           = $this->getLstVariables(   $indicador->idIndicador, 
                                                                                    $idEntFnc, 
                                                                                    $fchInicio, 
                                                                                    $fchFin );
                
                $dtoIndicador["lstDimensiones"]         = $this->getLstDimensiones( $indicador->idIndicador );
                $dtoIndicador["lstPlanificacion"]       = $this->getLstPlanificacion( $indicador->idIndEntidad );

                $dtoIndicador["published"] = 1;

                $dtaOtrosIndicadores[] = $dtoIndicador;                
            } 
        }

        return $dtaOtrosIndicadores;
    }

    
    public function getLstIndUG( $idEntidad, $tpoPlan, $idEntFnc, $idEntUG, $fchInicio = null, $fchFin = null )
    {
        //  Instacio la tabla Indicador
        $tbIndicador = new jTableIndicador( $this->_db );
        $lstOtrosInd = $tbIndicador->getLstIndUG( $idEntidad, $idEntUG );
        $dtaOtrosIndicadores = array();
                        
        if( count( $lstOtrosInd ) > 0 ){
            foreach( $lstOtrosInd as $indicador ){
                $dtoIndicador["idIndEntidad"]           = $indicador->idIndEntidad;
                $dtoIndicador["idIndicador"]            = $indicador->idIndicador;
                $dtoIndicador["nombreIndicador"]        = $indicador->nombreIndicador;
                $dtoIndicador["modeloIndicador"]        = $indicador->modeloIndicador;

                $dtoIndicador["umbral"]                 = (int) $indicador->umbral;
                $dtoIndicador["oldUmbral"]              = (int) $indicador->umbral;
                
                $dtoIndicador["tendencia"]              = (int) $indicador->tendencia;
                $dtoIndicador["descripcion"]            = $indicador->descripcion;
                $dtoIndicador["idUndAnalisis"]          = (int) $indicador->idUndAnalisis;
                $dtoIndicador["undAnalisis"]            = $indicador->undAnalisis;
                $dtoIndicador["idTpoUndMedida"]         = (int) $indicador->idTpoUndMedida;
                $dtoIndicador["idUndMedida"]            = (int) $indicador->idUndMedida;
                $dtoIndicador["undMedida"]              = $indicador->undMedida;
                $dtoIndicador["idTpoIndicador"]         = $indicador->idTpoIndicador;
                $dtoIndicador["tpoIndicador"]           = $indicador->tpoIndicador;
                $dtoIndicador["formula"]                = $indicador->formula;

                $dtoIndicador["fchHorzMimimo"]          = $indicador->fchHorzMimimo;
                $dtoIndicador["oldFchHorzMimimo"]       = $indicador->fchHorzMimimo;

                $dtoIndicador["fchHorzMaximo"]          = $indicador->fchHorzMaximo;
                $dtoIndicador["oldFchHorzMaximo"]       = $indicador->fchHorzMaximo;

                $dtoIndicador["umbMinimo"]              = $indicador->umbMinimo;
                $dtoIndicador["umbMaximo"]              = $indicador->umbMaximo;
                $dtoIndicador["idClaseIndicador"]       = $indicador->idClaseIndicador;
                $dtoIndicador["idFrcMonitoreo"]         = $indicador->idFrcMonitoreo;

                //  Informacion complementaria
                $dtoIndicador["metodologia"]            = $indicador->metodologia;
                $dtoIndicador["limitaciones"]           = $indicador->limitaciones;
                $dtoIndicador["interpretacion"]         = $indicador->interpretacion;
                $dtoIndicador["disponibilidad"]         = $indicador->disponibilidad;
                
                $dtoIndicador["idRegUGR"]               = $indicador->idRegUGR;
                $dtoIndicador["idUGResponsable"]        = $indicador->idUGResponsable;
                $dtoIndicador["oldIdUGResponsable"]     = $indicador->idUGResponsable;
                
                $dtoIndicador["fchInicioUG"]            = $indicador->fchInicioUG;

                $dtoIndicador["idRegFR"]                = $indicador->idRegFR;
                $dtoIndicador["idResponsableUG"]        = $indicador->idResponsableUG;
                $dtoIndicador["oldIdResponsableUG"]     = $indicador->idResponsableUG;
                
                $dtoIndicador["idResponsable"]          = $indicador->idResponsable;
                $dtoIndicador["oldIdResponsable"]       = $indicador->idResponsable;
                $dtoIndicador["fchInicioFuncionario"]   = $indicador->fchInicioFuncionario;
                //  $dtoIndicador["accesoTableu"]           = htmlentities( $indicador->accesoTableu );

                //  Asigno al indicador informacion del grupo al que pertenece un determinado indicador
                $dtaGpoIndicador = $this->_getDtaGpoIndicador( $indicador->idIndicador );                
                $dtoIndicador["idGpoDimension"]         = $dtaGpoIndicador->idGpoDimension;
                $dtoIndicador["idGpoDecision"]          = $dtaGpoIndicador->idGpoDecision;
                
                $dtoIndicador["lstUndsTerritoriales"]   = $this->getLstIndUndTerritorial( $indicador->idIndEntidad );
                $dtoIndicador["lstLineaBase"]           = $this->getLstLineasBase( $indicador->idIndicador );
                $dtoIndicador["lstRangos"]              = $this->getLstRangos( $indicador->idIndEntidad );
                
                $dtoIndicador["lstVariables"]           = $this->getLstVariables(   $indicador->idIndicador, 
                                                                                    $idEntFnc, 
                                                                                    $fchInicio, 
                                                                                    $fchFin );
                
                $dtoIndicador["lstDimensiones"]         = $this->getLstDimensiones( $indicador->idIndicador );
                $dtoIndicador["lstPlanificacion"]       = $this->getLstPlanificacion( $indicador->idIndEntidad );
                
                $dtoIndicador["published"] = 1;
                
                $dtaOtrosIndicadores[] = $dtoIndicador;
            } 
        }

        return $dtaOtrosIndicadores;
    }
    
    /**
     * 
     * Retorno informacion de un determinado indicador, identificado por su Indicador Entidad
     * 
     * @param int $idIndEntidad     Identificador de Indicador Entidad
     * @param int $tpoPlan          Identificador del tipo de plan
     * 
     * @return type
     * 
     */
    public function getIndEntidad( $idIndEntidad, $tpoPlan = 0 )
    {
        //  Instacio la tabla Indicador
        $tbIndicador = new jTableIndicador( $this->_db );
        $dtaIndEntidad = $tbIndicador->getDataIndByEntidad( $idIndEntidad );
            
        $dtoIndicador["idIndEntidad"]       = $dtaIndEntidad->idIndEntidad;
        $dtoIndicador["idIndicador"]        = $dtaIndEntidad->idIndicador;
        $dtoIndicador["nombreIndicador"]    = $dtaIndEntidad->nombreIndicador;
        $dtoIndicador["modeloIndicador"]    = $dtaIndEntidad->modeloIndicador;
        $dtoIndicador["umbral"]             = (int) $dtaIndEntidad->umbral;
        $dtoIndicador["tendencia"]          = (int) $dtaIndEntidad->tendencia;
        $dtoIndicador["descripcion"]        = $dtaIndEntidad->descripcion;
        $dtoIndicador["idUndAnalisis"]      = (int) $dtaIndEntidad->idUndAnalisis;
        $dtoIndicador["undAnalisis"]        = (int) $dtaIndEntidad->undAnalisis;
        $dtoIndicador["idTpoUndMedida"]     = (int) $dtaIndEntidad->idTpoUndMedida;
        $dtoIndicador["idUndMedida"]        = (int) $dtaIndEntidad->idUndMedida;
        $dtoIndicador["undMedida"]          = (int) $dtaIndEntidad->undMedida;
        $dtoIndicador["idTpoIndicador"]     = $dtaIndEntidad->idTpoIndicador;
        $dtoIndicador["formula"]            = $dtaIndEntidad->formula;
        $dtoIndicador["fchHorzMimimo"]      = $dtaIndEntidad->fchHorzMimimo;
        $dtoIndicador["fchHorzMaximo"]      = $dtaIndEntidad->fchHorzMaximo;
        $dtoIndicador["umbMinimo"]          = $dtaIndEntidad->umbMinimo;
        $dtoIndicador["umbMaximo"]          = $dtaIndEntidad->umbMaximo;
        $dtoIndicador["idClaseIndicador"]   = $dtaIndEntidad->idClaseIndicador;
        $dtoIndicador["idFrcMonitoreo"]     = $dtaIndEntidad->idFrcMonitoreo;

        //  Informacion complementaria
        $dtoIndicador["metodologia"]        = $dtaIndEntidad->metodologia;
        $dtoIndicador["limitaciones"]       = $dtaIndEntidad->limitaciones;
        $dtoIndicador["interpretacion"]     = $dtaIndEntidad->interpretacion;
        $dtoIndicador["disponibilidad"]     = $dtaIndEntidad->disponibilidad;
        
        $dtoIndicador["idRegUGR"]           = $dtaIndEntidad->idRegUGR;
        $dtoIndicador["idUGResponsable"]    = $dtaIndEntidad->idUGResponsable;
        $dtoIndicador["fchInicioUG"]        = $dtaIndEntidad->fchInicioUG;

        $dtoIndicador["idRegFR"]            = $dtaIndEntidad->idRegFR;
        $dtoIndicador["idResponsableUG"]    = $dtaIndEntidad->idResponsableUG;
        $dtoIndicador["idResponsable"]      = $dtaIndEntidad->idResponsable;
        $dtoIndicador["fchInicioFuncionario"]= $dtaIndEntidad->fchInicioFuncionario;

        //  Asigno al indicador informacion del grupo al que pertenece un determinado indicador
        $dtaGpoIndicador = $this->_getDtaGpoIndicador( $dtaIndEntidad->idIndicador );                
        $dtoIndicador["idGpoDimension"] = $dtaGpoIndicador->idGpoDimension;
        $dtoIndicador["idGpoDecision"] = $dtaGpoIndicador->idGpoDecision;

        $dtoIndicador["lstUndsTerritoriales"]   = $this->getLstIndUndTerritorial( $dtaIndEntidad->idIndEntidad );
        $dtoIndicador["lstLineaBase"]           = $this->getLstLineasBase( $dtaIndEntidad->idIndicador );
        $dtoIndicador["lstRangos"]              = $this->getLstRangos( $dtaIndEntidad->idIndEntidad );
        $dtoIndicador["lstVariables"]           = $this->getLstVariables( $dtaIndEntidad->idIndicador );
        $dtoIndicador["lstDimensiones"]         = $this->getLstDimensiones( $dtaIndEntidad->idIndicador );
        $dtoIndicador["lstProgramacion"]        = $this->getlstProgramacion( $dtaIndEntidad->idIndEntidad, $tpoPlan );

        $dtoIndicador["published"] = 1;

        $dtaOtrosIndicadores[] = $dtoIndicador;
        
        return $dtaOtrosIndicadores;
    }
 
    
    /**
     * 
     * Retorna Informacion de indicadores de tipo contexto
     * 
     * @param type $intId_pi    Identificador del PEI, al que estan asociados los Indicadores de Tipo Contexto
     * @return type
     * 
     */
    public function getLstContextos( $idEntidad )
    {
        $lstIndContexto = array();
    
        //  Instacio la tabla Linea Base
        $tbIndicador = new jTableIndicador( $this->_db );
        
        //  Obtengo informacion de los indicadores de tipo Contexto asociados 
        //  a un PEI de acuerdo a su entidad (PEI)
        $lstContextos = $tbIndicador->getLstIndContexto( $idEntidad );

        if( $lstContextos ){
            foreach( $lstContextos as $contexto ){
                $dtaContexto = Array();
                
                $dtaContexto["idIndEntidad"]    = $contexto->idIndEntidad;
                $dtaContexto["idIndicador"]     = $contexto->idIndicador;
                $dtaContexto["nombreIndicador"] = $contexto->nombreIndicador;
                $dtaContexto["modeloIndicador"] = $contexto->modeloIndicador;
                $dtaContexto["umbral"]          = (int) $contexto->umbral;
                $dtaContexto["tendencia"]       = (int) $contexto->tendencia;
                $dtaContexto["descripcion"]     = $contexto->descripcion;
                $dtaContexto["idUndAnalisis"]   = (int) $contexto->idUndAnalisis;
                $dtaContexto["undAnalisis"]     = (int) $contexto->undAnalisis;
                $dtaContexto["idTpoUndMedida"]  = (int) $contexto->idTpoUndMedida;
                $dtaContexto["idUndMedida"]     = (int) $contexto->idUndMedida;
                $dtaContexto["undMedida"]       = (int) $contexto->undMedida;
                $dtaContexto["idTpoIndicador"]  = $contexto->idTpoIndicador;
                $dtaContexto["formula"]         = $contexto->formula;
                $dtaContexto["idMetodoCalculo"] = $contexto->idMetodoCalculo;
                $dtaContexto["fchHorzMimimo"]   = $contexto->fchHorzMimimo;
                $dtaContexto["fchHorzMaximo"]   = $contexto->fchHorzMaximo;
                $dtaContexto["umbMinimo"]       = $contexto->umbMinimo;
                $dtaContexto["umbMaximo"]       = $contexto->umbMaximo;
                $dtaContexto["idClaseIndicador"]= $contexto->idClaseIndicador;
                $dtaContexto["idFrcMonitoreo"]  = $contexto->idFrcMonitoreo;
                $dtaContexto["idUGResponsable"] = $contexto->idUGResponsable;
                $dtaContexto["idResponsableUG"] = $contexto->idResponsableUG;
                $dtaContexto["idResponsable"]   = $contexto->idResponsable;
                $dtaContexto["nombreReporte"]   = $contexto->nombreReporte;

                $dtaContexto["lstRangos"]       = $this->getLstRangos( $contexto->idIndEntidad );
                $dtaContexto["lstVariables"]    = $this->getLstVariables( $contexto->idIndicador );
                $dtaContexto["lstProgramacion"] = $this->getlstProgramacion( $contexto->idIndEntidad, $tpoPlan );
                
                $dtaContexto["published"] = 1;
                
                $lstIndContexto[] = $dtaContexto;
            }
        }
        
        return $lstIndContexto;
    }
    
    /**
     * 
     * @param type $idPlantilla
     */
    public function getDtaPlantillaIndicador( $idPlantilla )
    {
        //  Instacio la tabla Indicador
        $tbIndicador = new jTableIndicador( $this->_db );
        $dtaIndPlantilla = $tbIndicador->getDtaPlantilla( $idPlantilla );
        
        if( count( $dtaIndPlantilla ) > 0 ){
            foreach( $dtaIndPlantilla as $indicador ){
                $dtaIndicador = array();
                        
                $dtaIndicador["idClaseIndicador"]   = $indicador->idClaseIndicador;
                $dtaIndicador["idTpoIndicador"]     = $indicador->idTpoIndicador;
                $dtaIndicador["idTpoUndMedida"]     = $indicador->idTpoUndMedida;
                $dtaIndicador["idUndMedida"]        = $indicador->idUndMedida;
                $dtaIndicador["idUndAnalisis"]      = $indicador->idUndAnalisis;
                $dtaIndicador["nombreIndicador"]    = $indicador->nombreIndicador;
                $dtaIndicador["descripcion"]        = $indicador->descripcion;
                $dtaIndicador["formula"]            = $indicador->formula;

                $dtaIndicador["lstVariables"]       = $this->getLstVarPlantilla( $indicador->idIndPlantilla );
                $dtaIndicador["lstDimensiones"]     = $this->getLstDimensionesPlantilla( $indicador->idIndPlantilla );
            }
        }else{
            $dtaIndicador = array();
        }

        return $dtaIndicador;
    }
    
    
    /**
     * 
     * Retorna Informacion de Indicadores de acuerdo a una determinada Dimension
     * 
     * @param type $idIndPlantilla      Identificador del Indicador de tipo plantilla
     * @param type $idDimension         Identificador de Dimension
     * 
     * @return array
     * 
     */
    public function getDtaPtllaPorDimension( $idDimension, $idCategoria = 1 )
    {
        //  Instacio la tabla Indicador
        $tbIndicador = new jTableIndicador( $this->_db );
        $dtaIndPlantilla = $tbIndicador->dtaPlantillaPorDimension( $idDimension, $idCategoria );
        
        if( count( $dtaIndPlantilla ) > 0 ){
            foreach( $dtaIndPlantilla as $key => $indicador ){
                $dtaIndicador = array();
                
                $dtaIndicador["idRegIndicador"]     = $key;
                $dtaIndicador["idClaseIndicador"]   = $indicador->idClaseIndicador;
                $dtaIndicador["idTpoIndicador"]     = $indicador->idTpoIndicador;
                $dtaIndicador["idTpoUndMedida"]     = $indicador->idTpoUndMedida;
                $dtaIndicador["idUndMedida"]        = $indicador->idUndMedida;
                $dtaIndicador["idUndAnalisis"]      = $indicador->idUndAnalisis;
                $dtaIndicador["modeloIndicador"]    = $indicador->modeloIndicador;
                $dtaIndicador["nombreIndicador"]    = $indicador->nombreIndicador;
                $dtaIndicador["descripcion"]        = $indicador->descripcion;
                $dtaIndicador["formula"]            = $indicador->formula;
                $dtaIndicador["idDimension"]        = $idDimension;

                $dtaIndicador["lstVariables"]       = $this->getLstVarPlantilla( $indicador->idIndPlantilla );
                $dtaIndicador["lstDimensiones"]     = $this->getLstDimensionesPlantilla( $indicador->idIndPlantilla );

                $dtaLstIndicadores[] = (object)$dtaIndicador;
            }
        }else{
            $dtaLstIndicadores = array();
        }
        
        return $dtaLstIndicadores;
    }
    
    /**
     * 
     * Retorna las dimensiones asociadas a un indicador de Tipo plantilla
     * 
     * @param type $idIndPlantilla     Identificador del Indicador de Tipo plantillas
     * 
     * @return type
     * 
     */
    public function getLstDimensionesPlantilla( $idIndPlantilla )
    {
        //  Instacio la tabla Linea Base
        $tbDimPlantilla = new jTableIndicadorDimension( $this->_db );
        return $tbDimPlantilla->getLstDimIndPlantilla( $idIndPlantilla );
    }
    
    /**
     * 
     * Retorna las variables asociadas a un Indicador de Tipo Plantilla
     * 
     * @param type $idIndPlantilla     Identificador de la Plantilla
     * 
     * @return type
     * 
     */
    public function getLstVarPlantilla( $idIndPlantilla )
    {
        //  Instacio la tabla Linea Base
        $tbIndVariable = new jTableIndicadorVariable( $this->_db );
        $variables = $tbIndVariable->getLstIndVarPlantilla( $idIndPlantilla );
        
        foreach( $variables as $variable ){
            $variable->idTpoElemento    = 1;
            $variable->idElemento       = 0;
            $variable->lstSeguimiento   = Array();

            $lstVariables[] = $variable;
        }

        return $lstVariables;
    }
    
    
    /**
     * 
     * Gestiono el retorno de informacion de lineas base asociadas a un determinado Indicador - Entidad
     * 
     * @param type $idIndEntidad    Identificador Indicador - Entidad
     */
    public function getLineaBase( $idIndEntidad )
    {
        $db = JFactory::getDBO();
        
        //  Instacio la tabla Funcionario
        $tbUT = new jTableIndicadorEntidad( $db );
        
        
    }

    /**
     * 
     * Retorna informaicon de Indicadores Asociados una determinada Entidad 
     * y una determinada Unidad de Medida
     * 
     * @param type $idEntidad   Identificador de Entidad
     * @param type $idUM        Identificador Unidad de Medida
     * 
     * @return type
     * 
     */
    public function getDtaIndicador( $idEntidad, $idUM )
    {
        $db = JFactory::getDBO();
        
        //  Instacio la tabla Funcionario
        $tbIndicador = new jTableIndicador( $db );
        $rst = $tbIndicador->lstIndicadores( $idEntidad, $idUM );

        return $rst;
    }
    
    /**
     * 
     * Retorna informacion de los responsables de un Indicador 
     * perteneciente a una determinada Entidad
     * 
     * @param type $idEntidad       Identificador de Entidad
     * @param type $idIndicador     Identificador del Indicador
     * 
     * @return type
     * 
     */
    public function getDtaResponsablesIndicador( $idEntidad, $idIndicador )
    {
        $db = JFactory::getDBO();
        
        //  Instacio la tabla Funcionario
        $tbIndicador = new jTableIndicador( $db );
        $rst = $tbIndicador->getResponsablesIndicador( $idEntidad, $idIndicador );
        
        return $rst;
    }

    /**
     * 
     * Retorno la programacion asociada a un determinado indicador
     * 
     * @param type $idIndEntidad    Identificador del Indicador - Entidad
     * @param type $tpoPlan         Indentificador tipo de plan
     *                                  1: PEI
     *                                  2: POA
     * 
     * @return type
     */
    public function getlstProgramacion( $idIndEntidad, $tpoPlan = 0 ) 
    {
        $db = JFactory::getDBO();
        
        //  Instacio la tabla Funcionario
        $tbProgramacion = new jTableProgramacion( $db );
        $lstPrgInd = $tbProgramacion->getProgramacionIndicador( $idIndEntidad, $tpoPlan );
        
        foreach ($lstPrgInd AS $key => $prgInd) {
            $lstMetasPrg = $this->getMetasProgramacion($prgInd->idPrgInd);
            $prgInd->idReg = $key;
            $prgInd->lstMetasProgramacion = $lstMetasPrg;
        }
        
        return $lstPrgInd;
    }
    
    
    
    public function getLstPlanificacion( $idIndEntidad )
    {
        $db = JFactory::getDBO();
        
        //  Instacio la tabla Planificacion Indicador
        $tbPlanificacion = new jTablePlanificacionIndicador( $db );
        
        return $tbPlanificacion->getLstPlanificacion( $idIndEntidad );
    }
    
    
    
    public function getMetasProgramacion($idPrgInd)
    {
        $db = JFactory::getDBO();
        
        //  Instacio la tabla Funcionario
        $tbDetallePrg = new jTableProgramacionDetalle( $db );
        $lstMetas = $tbDetallePrg->getMetasProgramacion($idPrgInd);
        
        return $lstMetas;
    }
    
    
    /**
     * 
     * Funcionarios asociados a una unidad de Gestion
     * 
     * @param type $idUndGestion    Identificador de Unidad de Gestion
     * 
     * @return type
     */
    public function getResponsablesVariable( $idUndGestion )
    {
        $db = JFactory::getDBO();
        
        //  Instacio la tabla Funcionario
        $tbIndicador = new jTableIndicador( $db );
        $rst = $tbIndicador->getResponsablesVariable( $idUndGestion );
        
        if( count( $rst ) > 0 ){
            $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_RESPONSABLE_TITLE' ) );
        }else{
            $rst[] = (object)array(  'id' => 0, 'nombre' => JText::_( 'COM_INDICADORES_SIN_REGISTROS' ) );
        }
        
        return $rst;
    }
    
    
    /**
     * 
     * Obtengo Informacion de Indicadores Economicos - registrados en la plantilla de indicadores
     * 
     * @param type $idPlantilla     Identificador de la Plantilla
     * @param type $idDimension     Identificador de la Dimension
     * 
     * @return type
     * 
     */
    public function getDtaIndEcoPtlla( $idPlantilla = 0, $idDimension = 0 )
    {
        //  Instacio la tabla Linea Base
        $tbIndicador = new jTableIndicador( $this->_db );
        
        return $tbIndicador->getDtaPlantilla( $idPlantilla, $idDimension );
    }

    /**
     * 
     * Retorna informacion especifica de un indicador entidad
     * 
     * @param type $idIndEntidad    Identificador Indicador entidad
     * 
     * @return type
     * 
     */
    public function getDtaIndEntidad( $idIndEntidad )
    {
        //  Instacio la tabla Linea Base
        $tbIndicador = new jTableIndicador( $this->_db );
        return $tbIndicador->getDataIndByEntidad( $idIndEntidad );
    }
    
    /**
     * 
     * Gestiona el Retorno de informacion de Indicadores asociados a 
     * una determinada entidad
     * 
     * @param type $idEntidad   Identificador del Indicador Entidad
     * 
     * @return type
     * 
     */
    public function dtaIndicadoresEntidad( $idEntidad )
    {
        //  Instacio la tabla Indicador
        $tbIndicador = new jTableIndicador( $this->_db );
        $rst = $tbIndicador->getIndEntidad( $idEntidad );

        if( count( $rst ) > 0 ){
            $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_INDICADORES_FIELD_INDICADORES_ENTIDAD_TITLE' ) );
        }else{
            $rst[] = (object)array(  'id' => 0, 'nombre' => JText::_( 'COM_INDICADORES_SIN_REGISTROS' ) );
        }

        return $rst;
    }
    
    
    
    /**
     * 
     * Retorna una lista de funcionarios asociados a una determinda Unidad de Gestion
     * 
     * @param type $idUndGestion    Identificador de la Unidad de Gestion
     */
    public function lstFuncionariosPorUG( $idUndGestion )
    {
        //  Instacio la tabla Indicador
        $tbFuncionario = new JTableUnidadFuncionario( $this->_db );
        $rst = $tbFuncionario->lstFuncionariosPorUG( $idUndGestion );

        if( count( $rst ) > 0 ){
            $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_INDICADORES_FIELD_LSTINDICADOR_FUNCIONARIO_TITLE' ) );
        }else{
            $rst[] = (object)array(  'id' => 0, 'nombre' => JText::_( 'COM_INDICADORES_SIN_REGISTROS' ) );
        }

        return $rst;
    }
    
 
    
    /**
     * 
     * Retorno informacion del grupo al que pertenece un determinado indicador
     * 
     * @param type $idIndicador     identificador del indicador
     * @return type
     */
    private function _getDtaGpoIndicador( $idIndicador )
    {
        //  Instacio la tabla Linea Base
        $tbGpoInd = new jTableGrupoIndicador( $this->_db );
        return $tbGpoInd->getDtaGpoIndicador( $idIndicador );
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
    private function _getDtaSeguimiento( $idIndicador, $idIndVariable, $fchInicio = null, $fchFin = null )
    {
        //  Instacio la tabla Linea Base
        $db = JFactory::getDBO();
        
        $tbIndicador = new jTableIndicador( $db );

        return $tbIndicador->getDtaSeguimientoIndicador( $idIndicador, $idIndVariable, $fchInicio, $fchFin );
    }
    
    
    
    public function getIndicadores()
    {
        //  Instacio la tabla Linea Base
        $db = JFactory::getDBO();
        $tbHechosIndicador = new jTableIndicador( $db );
    }
    
    
    
    public function cierreIndicador( $idIndEntidad, $idIndicador )
    {
        $ban = false;

        //  Sumatoria de valores ejecutatos por un determinado indicador
        $totalVE = $this->_totalVE( $idIndEntidad );
        
        //  Obtengo el valor de la LB Actual del indicador
        $dtaLB = $this->_getDtaLineaBase( $idIndEntidad );

        if( $totalVE && $dtaLB ){
            //  Sumo el valor ejecutado del indicador mas el valor de la LB
            $valorNLB = $dtaLB->valorLB + $totalVE;
            
            //  Ejecuto proceso de actualizacion de LB
            $this->_updLineaBase( $dtaLB->idIndPadre, $valorNLB, $dtaLB->fchInicio, $dtaLB->fchFin );
        }

        //  Ejecuto proceso de actualizacion de LB
        return $ban;
    }
    
    
    private function _totalVE( $idIndEntidad )
    {
        //  Instacio la tabla Linea Base
        $db = JFactory::getDBO();
        $tbIndicador = new jTableIndicador( $db );
        
        return $tbIndicador->totalVE( $idIndEntidad );
    }
    
    private function _getDtaLineaBase( $idIndEntidad )
    {
        //  Instacio la tabla Linea Base
        $db = JFactory::getDBO();
        $tbLB = new jTableLineaBase( $db );
        
        return $tbLB->getDtaLineaBase( $idIndEntidad );
    }
                        
    
    private function _updLineaBase( $idIndicador, $valorNLB, $fchInicio, $fchFin )
    {        
        //  Creo la nueva Linea Base
        $dtaLineaBase = $this->_crearLineaBase( $valorNLB, $fchInicio, $fchFin );

        //  Instancio la tabla Linea Base
        $db = JFactory::getDBO();

        //  Instancio la tabla linea base
        $tbLineaBase = new jTableLineaBase( $db );

        //  Registro de lineas base generadas al momento de prorratear un plan
        return $tbLineaBase->registroLB( $idIndicador, $dtaLineaBase, 1 );
    }
    
    
    private function _crearLineaBase( $valorNLB, $fchInicio, $fchFin )
    {
        //  Creo una nueva linea base y lo asigno al indicador
        $objLB = new PlanLineaBase( 1, 
                                    1, 
                                    null,
                                    $valorNLB, 
                                    $fchInicio, 
                                    $fchFin );

        //  Asigno la nueva linea Base al indicador
        $nlb = array();
        $nlb[] = $objLB->__toString();

        return $nlb;
    }
    
    
    public function __destruct()
    {
        return;
    }
    
}
