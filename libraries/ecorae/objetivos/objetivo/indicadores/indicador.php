<?php

jimport( 'ecorae.planinstitucion.plnopranual' );
jimport( 'ecorae.planinstitucion.papp' );

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos'. DS .'objetivo'. DS .'indicadores'. DS .'GestionIndicador.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos'. DS .'objetivo'. DS .'indicadores'. DS .'IndicadorEntidad.php';



class Indicador
{
    private $_origen;
    private $_tpoPlan;
    
    /**
     * 
     * Constructor de la de clase indicador
     * 
     * @param int $origen        Identifica el origen de la peticion de creacion de objeto
     *                              0: Gestion Operativa ( programas, proyectos, contratos,... )
     *                              1: Gestion Estrategica ( PPPP - PAPP - POA's )
     * 
     */
    public function __construct( $origen = 0, $tpoPlan = 1 )
    {
        $this->_origen  = $origen;
        $this->_tpoPlan = $tpoPlan;
    }
    
    /**
     * 
     * Gestiona el Registro de "UN" Indicador
     * 
     * @param int       $idEntidad          Identificador de la entidad "OBJETIVO" a la que esta asociado este indicador
     * @param Objeto    $indicador          Datos del Indicador
     * @param int       $banCategoriaInd    Categoria del Indicador
     *                                          1: Fijo             ( Economico, Financiero, Beneficiarios Directo, Beneficiarios Indirectos )
     *                                          2: Dinamico         ( GAP, Enfoque de Igualdad, Enfoque Ecorae, Otros Indicadores )
     *                                          3: Otros Indicadores
     *                                          4: Contextos
     * 
     * @param int $dtaPadre                 Identificador del Indicador Padre
     * 
     * @return type
     * 
     */
    public function registroIndicador( $idEntidad, $indicador, $banCategoriaInd = 0, $dataObj = NULL )
    {
        $idIndEntidad = 0;

        if( is_object( $indicador ) ){
            //  Gestiono informacion del INDICADOR
            $idIndicador = $this->_gestionIndicador(    $indicador, 
                                                        $banCategoriaInd, 
                                                        $idEntidad );

            //  Gestiono informacion del INDICADOR - ENTIDAD
            $idIndEntidad = $this->_gestionIndicadorEntidad(    $indicador, 
                                                                $idIndicador, 
                                                                $idEntidad, 
                                                                $dataObj );
        }
        
        return $idIndEntidad;
    }
    
    /**
     * 
     * Gestiona Informacion de un Indicador 
     * sin importar la categoria de este ( fijo, dinamico ) 
     * 
     * @param Objeto $indicador         Datos del indicador a gestionar
     * @param int $banCategoriaInd      Categoria del indicador 1: Economico, 2: Financiero, etc
     * @param int $idEntidad            Identificador de la entidad a la esta asociado este indicador
     * 
     * @return type
     * 
     */
    private function _gestionIndicador( $indicador, $banCategoriaInd, $idEntidad )
    {
        if( $indicador->published == 0 ){
            $this->eliminacionIndicador( $indicador->idIndicador );
        }else{
            $gi = new GestionIndicador( $indicador, $banCategoriaInd, $idEntidad, $this->_origen );

            //  Ejecuto procesos de gestion del INDICADOR
            $idIndicador = $gi->gestionIndicador();
        }
        
        return $idIndicador;
    }
    
    
    public function eliminacionIndicador( $idIndicador )
    {
        //  Elimino Linea Base - Indicador
        $this->_deleteLineaBase( $idIndicador );
        
        //  Elimino Dimension - Indicador
        $this->_deleteIndicadorDimension( $idIndicador );
        
        //  Elimino el seguiemiento de las variables asociadas a un indicador
        $this->_deleteSeguimientoVariables( $idIndicador );
        
        //  Gestiono la Eliminacion de los funcionarios responsables de una variable
        $this->_deleteVariableFuncionarioResponsable( $idIndicador );
        
        //  Gestiono la Eliminacion de los funcionarios responsables de una variable
        $this->_deleteVariableUGResponsable( $idIndicador );

        //  Elimino Indicador - Variable
        $this->_deleteIndicadorVariable( $idIndicador );

        return $idIndicador;
    }
    
    
    /**
     * 
     * Gestiona la eliminacion de Lineas Base
     * 
     * @param int $idIndicador  Identificador del Indicador
     * @return type
     */
    private function _deleteLineaBase( $idIndicador )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Identificador - variable
        $tbLineaBase = new jTableLineaBase( $db );

        return $tbLineaBase->deleteLineaBase( $idIndicador );
    }
    
    /**
     * 
     * Elimina las dimensiones asociados a un indicador
     * 
     * @param type $idIndicador     Identificador del Indicador
     * @return type
     */
    private function _deleteIndicadorDimension( $idIndicador )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Identificador - variable
        $tbIndDimension = new jTableIndicadorDimension( $db );

        return $tbIndDimension->deleteIndDimension( $idIndicador );
    }
    
    /**
     * 
     * Gestiona la eliminacion de los funcionarios responsables de una variable
     * 
     * @param int $idIndicador  Identificador del Indicador
     * 
     * @return int
     * 
     */
    private function _deleteVariableFuncionarioResponsable( $idIndicador )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Identificador - variable
        $tbVarFR = new jTableVarFuncionarioResponsable( $db );

        return $tbVarFR->deleteFuncionarioResponsable( $idIndicador );
    }

    /**
     * 
     * Gestiona la eliminacion de las Unidades de Gestion Responsable de una variable
     * 
     * @param int $idIndicador      Identificador del Indicador
     * 
     * @return int
     */
    private function _deleteVariableUGResponsable( $idIndicador )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Identificador - variable
        $tbVarUGR = new jTableVarUGResponsable( $db );

        return $tbVarUGR->deleteUGResponsable( $idIndicador );
    }

    /**
     * 
     * Gestiona la eliminacion del Indicador Variable
     * 
     * @param int $idIndicador  Identificador del Indicador
     * 
     * @return int
     * 
     */
    private function _deleteIndicadorVariable( $idIndicador )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Indicador - variable
        $tbIndVariable = new jTableIndicadorVariable( $db );

        return $tbIndVariable->deleteIndicadorVariable( $idIndicador );
    }
    
    /**
     * 
     * Gestiona la eliminacion del seguimiento de variables
     * 
     * @param int $idIndicador  Identificador del Indicador
     */
    private function _deleteSeguimientoVariables( $idIndicador )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Identificador - variable
        $tbSeguimiento = new jTableSeguimiento( $db );

        return $tbSeguimiento->deleteSeguimiento( $idIndicador );
    }
    
    
    
    /**
     * 
     * Gestiono Informacion del Indicador - Entidad
     * 
     * @param Object $indicador     Data del Indicador a Gestionar
     * @param int $idIndicador      Identificador del indicador
     * @param int $idEntidad        Identificador de la entidad del OBJETIVO
     * 
     * @return type
     */
    private function _gestionIndicadorEntidad( $indicador, $idIndicador, $idEntidad, $dataObj )
    {
        //  Instancio Clase de Gestion de Indicador - Entidad
        $ie = new IndicadorEntidad( $indicador, 
                                    $idIndicador, 
                                    $idEntidad, 
                                    $this->_origen, 
                                    $this->_tpoPlan, 
                                    0,
                                    $dataObj );

        if( $indicador->published == 0 ){
            $idIndEntidad = $ie->eliminacionIndicadorEntidad();
        }else{
            //  Ejecuto el proceso de gestion de informacion de "UN" Indicador - Entidad
            $idIndEntidad = $ie->gestionIndicadorEntidad();
        }

        return $idIndEntidad;
    }
    
    public function __destruct()
    {
        return;
    }
}