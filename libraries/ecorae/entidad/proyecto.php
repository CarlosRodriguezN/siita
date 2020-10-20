<?php

//  Adjunto Tablas asociadas 
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'proyecto.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'indicador.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos' . DS . 'objetivo' . DS . 'indicadores' . DS . 'indicadores.php';

class Proyecto
{
    public function __construct()
    {}
    
    /**
     * Obtengo una lista de proyectos de una determinada unidad de gestión responsable
     * 
     * @param int      $idEntidadUG    Identificador de la unidad de gestión
     * @return type
     */
    public function getLstProyectosUG( $idEntidadUG )
    {
        $db = JFactory::getDBO();
        $tblPry = new JTableProyecto( $db );
        $result = $tblPry->getLstProyectosUG( $idEntidadUG );
        return $result;
    }
    
    /**
     * Obtengo una lista de proyectos relacionados a un funcionario
     * 
     * @param int      $idFuncionario    Identificador del funcionario
     * @return type
     */
    public function getLstProyectosFnc( $idFuncionario )
    {
        $db = JFactory::getDBO();
        $tblPry = new JTableProyecto( $db );
        $result = $tblPry->getLstProyectosFnc( $idFuncionario );
        return $result;
    }
    
    /**
     * Obtengo una lista de proyectos asociados a un determinado programa
     * 
     * @param int      $idPrograma    Identificador del Programa
     * @return type
     */
    public function getLstProyectosPrg( $idPrograma )
    {
        $db = JFactory::getDBO();
        $tblPry = new JTableProyecto( $db );
        $result = $tblPry->getLstProyectosPrg( $idPrograma );

        return $result;
    }

    public function getReporteProyecto( $idProyecto )
    {
        return $this->_getDtaGeneralProyecto( $idProyecto );
    }
    
    private function _getDtaGeneralProyecto( $idProyecto )
    {
        $db = JFactory::getDBO();
        $tbProyecto = new JTableProyecto( $db );

        return $tbProyecto->getDtaProyecto( $idProyecto );
    }
    
    /**
     * 
     * Seteo Informacion de Indicadores pertenecientes a un proyecto 
     * asociado por su entidad
     * 
     * @return type
     */
    public function getDtaIndicadores( $idEntidad )
    {
        $lstIndicadores = array();
        $dtaIndicadores = $this->_getIndicadores( $idEntidad );

        //  Seteo informacion de Indicadores en el Formulario
        if( count($dtaIndicadores) ){

            foreach( $dtaIndicadores as $indicador ){
                //  Agrego el tipo de indicador - Alias de Unidad de Analisis
                $dtoIndicador["idIndicador"]                                = strtolower( $indicador->idIndicador );
                $dtoIndicador["nombre_del_Indicador"]                       = strtolower( $indicador->nombreIndicador );
                $dtoIndicador["definicion"]                                 = strtolower( $indicador->descripcionIndicador );
                $dtoIndicador["formula_de_calculo"]["formula"]              = strtolower( $indicador->Formula );
                $dtoIndicador["formula_de_calculo"]["lista_de_Variables"]   = $this->_getVariablesIndicador( $indicador->idIndicador );

                $dtoIndicador["valor_meta"]                                 = $indicador->valorMeta;
                $dtoIndicador["tendencia"]                                  = strtolower( $indicador->tendencia );
                
                $dtoIndicador["unidad_de_medida"]["tipo_unidad_medida"]     = strtolower( $indicador->tipoUnidadMedida );
                $dtoIndicador["unidad_de_medida"]["unidad_medida"]          = strtolower( $indicador->unidadMedida );
                
                $dtoIndicador["unidad_de_Analisis"]                         = (int)$indicador->unidadAnalisis;
                $dtoIndicador["fuente_de_datos"]                            = 'ECORAE';
                $dtoIndicador["periodicidad_del_indicador"]                 = strtolower( $indicador->frecuenciaMonitoreo );
                
                $dtoIndicador["disponiblidad_de_datos"]["fecha_de_inicio"]  = $indicador->fechaInicioIndicador;
                $dtoIndicador["disponiblidad_de_datos"]["fecha_de_fin"]     = $indicador->fechaFinIndicador;
                
                $dtoIndicador["nivel_de_desagregacion"]["general"]          = $this->_getEnfoquesIndicador( (int)$indicador->idIndicador );
                $dtoIndicador["nivel_de_desagregacion"]["geografico"]       = $this->_procesarUnidadTerritorial( (int)$indicador->idIndEntidad );
                $dtoIndicador["relacion_instrumentos_de_planificacion"]     = $this->_procesarAgendas( (int)$indicador->idIndicador );

                $dtoIndicador["fecha_creacion"]                             = is_null( $indicador->fechaCreacion ) 
                                                                                    ? '' 
                                                                                    : $indicador->fechaCreacion;
                
                $dtoIndicador["fecha_modificacion"]                         = is_null( $indicador->fechaModificacion ) 
                                                                                    ? '' 
                                                                                    : $indicador->fechaCreacion;

                $dtoIndicador["idCategoriaIndicador"]                       = $indicador->idCategoriaIndicador;

                $lstIndicadores[] = $dtoIndicador;
            }
        }

        return $lstIndicadores;
    }
    
    
    private function _getVariablesIndicador( $idIndicador )
    {
        $listaVariables = array();
        $infoIndicador = new Indicadores();
        $lstVariables = $infoIndicador->getLstVariables( $idIndicador );

        foreach( $lstVariables as $variable ){
            $dtaVariable["nombre"]                  = strtolower( $variable->nombre );
            $dtaVariable["descripcion"]             = strtolower( $variable->descripcion );
            $dtaVariable["unidad_de_Medida"]        = strtolower( $variable->undMedida );
            $dtaVariable["unidad_de_analisis"]      = strtolower( $variable->undAnalisis );
            $dtaVariable["seguimiento_de_variable"] = $this->_procesarValoresSeguimiento( $variable->lstSeguimiento );

            $listaVariables[] = $dtaVariable;
        }

        return $listaVariables;
    }
    
    
    private function _getEnfoquesIndicador( $idIndicador )
    {
        $dtaEnfoques = array();
        $infoIndicador = new Indicadores();
        $lstEnfoques = $infoIndicador->getLstDimensiones( $idIndicador );

        foreach ( $lstEnfoques as $enfoque ){
            $dta["enfoque"]     = strtolower( $enfoque->enfoque );
            $dta["dimension"]   = strtolower( $enfoque->dimension );

            $dtaEnfoques[] = $dta;
        }

        return $dtaEnfoques;
    }
    
    
    private function _procesarUnidadTerritorial( $idIndEntidad )
    {
        $dtaUT = array();
        $infoIndicador = new Indicadores();
        $lstUT = $infoIndicador->getLstIndUndTerritorial( $idIndEntidad );
        
        foreach( $lstUT as $ut ){
            $dta["provincia"]   = strtolower( $ut->provincia );
            $dta["canton"]      = strtolower( $ut->canton );
            $dta["parroquia"]   = strtolower( $ut->parroquia );
            
            $dtaUT[] = $dta;
        }
        
        return $dtaUT;
    }
    
    private function _procesarAgendas( $idIndicador )
    {
        $dtaAgendas     = array();
        $infoIndicador  = new Indicadores();
        $lstAgendas     = $infoIndicador->getAgendas( $idIndicador );

        foreach( $lstAgendas as $agenda ){
            $dta["agenda"]  = strtolower( $agenda->agenda );
            $dta["nivel_1"] = strtolower( $agenda->gerarquia_1 );
            $dta["nivel_2"] = strtolower( $agenda->gerarquia_2 );
            $dta["nivel_3"] = strtolower( $agenda->gerarquia_3 );
            
            $dtaAgendas[] = $dta;
        }

        return $dtaAgendas;
    }
    
    
    
    private function _procesarValoresSeguimiento( $lstSeguimiento )
    {
        $dtaSeguimiento = array();

        foreach( $lstSeguimiento as $seguimiento ){
            $dta = array();
            $dta["fecha"] = $seguimiento->fecha;
            $dta["valor"] = $seguimiento->valor;
            
            $dtaSeguimiento[] = $dta;
        }
        
        return $dtaSeguimiento;
    }
    
    
    private function _getIndicadores( $idEntidad )
    {
        $db = JFactory::getDBO();
        $tbIndicadores = new jTableIndicador( $db );
        return $tbIndicadores->getIndicadorPorEntidad( $idEntidad );
    }
    
    public function __destruct()
    {
        return;
    }
}