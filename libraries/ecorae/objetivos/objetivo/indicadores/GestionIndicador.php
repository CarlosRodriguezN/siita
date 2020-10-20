<?php

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'indicador.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'lineabase.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'variable.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'indicadorvariable.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'indicadordimension.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'varUGResponsable.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'varfuncionarioresponsable.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'seguimiento.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'indVariableIndicador.php';

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos' . DS . 'objetivo' . DS . 'indicadores' . DS . 'CambiosVariable.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'planobjetivo.php';

class GestionIndicador
{

    private $_dtaIndicador;
    private $_origen;
    private $_idIndicador;
    private $_idEntidad;
    private $_isNew;

    /**
     * 
     * Contructor de la clase GestionIndicador
     * 
     * @param Objeto $indicador     Datos del indicador a gestionar
     * 
     */
    public function __construct( $indicador, $banCategoriaInd, $idEntidad, $origen )
    {
        $this->_dtaIndicador                = clone $indicador;
        $this->_dtaIndicador->idCategoria   = $banCategoriaInd;

        $this->_idEntidad   = $idEntidad;
        $this->_origen      = $origen;        
        $this->_idIndicador = $this->_registroIndicador();

        $this->_isNew = ( ( int ) $this->_dtaIndicador->idIndEntidad == 0 ) 
                            ? TRUE 
                            : FALSE;
    }

    /**
     * 
     * Gestiono informacion general de un indicador
     * 
     * @return int  Identificador del Indicador Gestionado
     * 
     */
    private function _registroIndicador()
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            //  Instacio la tabla Identificador
            $tbIndicador = new jTableIndicador( $db );
            $idRegIndicador = $tbIndicador->registroDtaIndicador( $this->_dtaIndicador );

            $db->transactionCommit();

            //  Accedo al metodo Registro Datos Indicador
            return $idRegIndicador;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     * 
     * Gestiona Informacion de un Indicador 
     * sin importar la categoria de este ( fijo, dinamico ) 
     * 
     * @return type
     * 
     */
    public function gestionIndicador()
    {
        //  Gestiono informacion general de los hijos de determinado indicador
        $this->_gestionInfGralIndicador( $this->_dtaIndicador->idIndEntidad );
        
        //  Gestiono el Registro de Lineas Base Asociadas a un indicador
        if( count( ( array ) $this->_dtaIndicador->lstLineaBase ) ){
            $this->_registroLineaBase(  $this->_idIndicador, 
                                        $this->_dtaIndicador->lstLineaBase );
        }else{
            $this->_lineaBaseDefault( $this->_idIndicador );
        }

        //  Gestion de informacion del Grupo al que pertenece el Indicador
        if( is_numeric( $this->_dtaIndicador->idGpoDimension ) && is_numeric( $this->_dtaIndicador->idGpoDecision ) ){
            $db = JFactory::getDBO();

            //  Gestiono informacion de Grupo del Indicador
            $tbGrupo = new jTableGrupoIndicador( $db );
            $tbGrupo->registroGrupoIndicador(   $this->_idIndicador, 
                                                $this->_dtaIndicador->idGpoDimension, 
                                                $this->_dtaIndicador->idGpoDecision );
        }

        //  Realizo el proceso de registro de variables
        if( count( ( array ) $this->_dtaIndicador->lstVariables ) ){
            //  Registro las variables asociadas a un indicador
            $this->_registroVariablesIndicador( $this->_dtaIndicador, 
                                                $this->_idIndicador );
        } else{
            //  En caso que el indicador no tenga variables asociadas, 
            //  por default registro al mismo indicador como variable
            $this->_regIndComoVariable( $this->_dtaIndicador, 
                                        $this->_idIndicador );
        }

        //  Verifico el numero de Dimensiones asociadas al indicador
        if( count( ( array ) $this->_dtaIndicador->lstDimensiones ) ){
            
            $this->_relacionIndicadorDimension( $this->_idIndicador,
                                                $this->_dtaIndicador->lstDimensiones );

            //  Asigno la relacion entre las dimensiones e Indicadores Padre con sus hijos asociados
            $this->_hijosIndicadorDimension(    $this->_dtaIndicador->idIndEntidad, 
                                                $this->_dtaIndicador->lstDimensiones );
        } else{
            //  Realizo el proceso de eliminacion de las relaciones Indicador - Enfoques - Dimension
            $this->_eliminoIndicadorDimension( $this->_idIndicador );
        }

        return $this->_idIndicador;
    }

    
    
    /**
     * 
     * Actualizo informacion general de los hijos un determinado indicador
     * 
     * @return type
     */
    private function _gestionInfGralIndicador( $idIndEntidad )
    {
        $lstPlnObjetivos = $this->_getLstPlnObjInd( $idIndEntidad );

        if( count( $lstPlnObjetivos ) ){
            foreach( $lstPlnObjetivos as $plan ){
                
                $dtaIndicador = new stdClass();
                $dtaIndicador->idIndicador          = $plan->idIndicador;
                $dtaIndicador->intCodigo_ind        = $this->_dtaIndicador->idIndicador;
                $dtaIndicador->intCodigoTipo_ind    = $this->_dtaIndicador->idTpoIndicador;
                $dtaIndicador->inpCodigo_claseind   = $this->_dtaIndicador->idClaseIndicador;
                $dtaIndicador->intCodigo_unimed     = $this->_dtaIndicador->idUndMedida;
                $dtaIndicador->inpCodigo_unianl     = $this->_dtaIndicador->idUndAnalisis;
                $dtaIndicador->strNombre_ind        = $this->_dtaIndicador->nombreIndicador;
                $dtaIndicador->strDescripcion_ind   = $this->_dtaIndicador->descripcion;
                $dtaIndicador->strFormula_ind       = $this->_dtaIndicador->formula;
                $dtaIndicador->inpCategoria_ind     = $this->_dtaIndicador->idCategoria;

                $dtaIndicador->strMetodologiaCalculo_ind      = $this->_dtaIndicador->metodologia;
                $dtaIndicador->strLimitacionesTecnicas_ind    = $this->_dtaIndicador->limitaciones;
                $dtaIndicador->strInterpretacionIndicador_str = $this->_dtaIndicador->interpretacion;
                $dtaIndicador->strDisponibilidadIndicador_ind = $this->_dtaIndicador->disponibilidad;                

                //  Instacio la tabla Identificador
                $db = JFactory::getDBO();
                $tbIndicador = new jTableIndicador( $db );
                $idRegIndicador = $tbIndicador->updDtaGralIndicador( $dtaIndicador );
                
                $this->_gestionInfGralIndicador( $plan->idIndEntidad );
            }
        }
        
        return $idRegIndicador;
    }

    /**
     * 
     * Gestiono el registro de Lineas Base de un determinado indicador
     * 
     * @param int       $idIndicador     Identificador del Indicador
     * @param Object    $dtaLineaBase    Datos de linea Base
     * 
     * @return type
     * 
     */
    private function _registroLineaBase( $idIndicador, $dtaLineaBase )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            //  Instancio la tabla Identificador
            $tbLineaBase = new jTableLineaBase( $db );

            //  Registro de lineas base generadas al momento de prorratear un plan
            $tbLineaBase->registroLB( $idIndicador, $dtaLineaBase, $this->_dtaIndicador->idTpoPln );
            
            $db->transactionCommit();
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }

        return;
    }

    
    private function _lineaBaseDefault( $idIndicador )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            //  Instancio la tabla LineaBase
            $tbLineaBase = new jTableLineaBase( $db );

            $objLB = new stdClass();
            $objLB->idLineaBase = 1;

            $dtaLineaBase[] = $objLB;

            //  Registro las lineas base asociadas a un determinado indicador
            $tbLineaBase->regLBIndicador( $idIndicador, $dtaLineaBase );

            $db->transactionCommit();
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }

        return;
    }
    
    
    /**
     * 
     * Gestino el proceso de registro de Variables que forman parte de la 
     * formula de un indicador
     * 
     * @param Object $indicador     Datos del indicador
     * @param int $idIndicador      Identificador del Indicador
     * 
     */
    private function _registroVariablesIndicador( $indicador, $idIndicador )
    {
        //  Gestiono el Registro de Variablas Asociadas a un Indicador
        foreach( $indicador->lstVariables as $variable ){

            if( $variable->published != 0 ){
                
                //  
                //  Gestiona el registro de una nueva variable asociada 
                //  a un determinado indicador si el tipo de elemento es:
                //      1:  Variable
                //      2:  Indicador, en caso que asociemos un "Indicador" como 
                //          variable de la formula de calculo
                //
                if( $variable->idTpoElemento == 1 ){
                    $idVariable = $this->_registroVariable( $variable );

                    //  Gestiono la relacion Indicador Variable
                    $idIndVariable = $this->_relacionIndicadorVariable( $variable, $idIndicador, $idVariable );

                    //  Registro - Unidad de Gestion Responsable de la variable
                    if( is_numeric( $variable->idUGResponsable ) && (int) $variable->idUGResponsable > 0 ){
                        $this->_ugResponsableVar( $idIndVariable, $variable );
                    }

                    //  Registro - Funcionario Responsable de la variable                        
                    if( is_numeric( $variable->idFunResponsable ) && (int) $variable->idFunResponsable > 0 ){
                        $this->_funcionarioResponsableVar( $idIndVariable, $variable );
                    }

                    //  Registro datos de Seguimiento de una variable que forma parte de un indicador 
                    if( count( (array) $variable->lstSeguimiento ) ){
                        $this->_seguimientoVariable( $indicador->idIndEntidad, $variable->idIndVariable, $variable->lstSeguimiento );
                    }
                    
                } else{                    
                    //  Gestiono la relacion Indicador - Variable
                    $idIndVariable = $this->_relacionIndicadorVariable( $variable, $idIndicador, $variable->idElemento );
                    $ivi = $this->_relacionIndVariable_Indicador( $idIndVariable, $idIndicador );
                }
            } else{
                //  Elimino la relacion Indicador Variable
                $idIndVariable = $this->_relacionIndicadorVariable( $variable );
                
                //  Elimino valores de seguimiento de Variables
                $this->_eliminoSeguimientoIndicadorVariable( $variable->idIndVariable );
            }          

            if( ( $indicador->idIndEntidad == 0 && $indicador->idIndicador == 0 ) || $variable->idElemento == 0 ){
                $ivi = $this->_relacionIndVariable_Indicador( $idIndVariable, $idIndicador );
            }
        }
   
        //  replico cambios a los indicadores hijo de un determinado indicador
        $this->_replicoIndicadorVariable( $indicador->idIndEntidad, $idIndVariable );

        return $idIndVariable;
    }
    
    private function _replicoIndicadorVariable( $idIndEntidad, $idIndVariable )
    {
        $lstIndicadores = $this->_getLstIndicadores( $idIndEntidad );

        foreach( $lstIndicadores as $ind ){
            $ivi = $this->_relacionIndVariable_Indicador( $idIndVariable, $ind->idIndicador );
            $this->_replicoIndicadorVariable( $ind->idIndEntidad, $idIndVariable );
        }
        
        return $ivi;
    }
    
    
    private function _getLstIndicadores( $idIndEntidad )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Indicador - Entidad
        $tbIE = new jTableIndicadorEntidad( $db );

        return $tbIE->getLstIndEntidadHijos( $idIndEntidad );
    }
    
    

    /**
     * 
     * Registro la informacion de un indicador como variable
     * 
     * @param objeto $indicador   Data del indicador a registrar como variable
     * 
     * @return int
     * 
     */
    private function _regIndComoVariable( $indicador, $idIndicador )
    {
        $dtaVariable["idVariable"]      = 0;
        $dtaVariable["nombre"]          = $indicador->nombreIndicador;
        $dtaVariable["descripcion"]     = $indicador->descripcion;
        $dtaVariable["idUndMedida"]     = $indicador->idUndMedida;
        $dtaVariable["idUndAnalisis"]   = $indicador->idUndAnalisis;
        $dtaVariable["idUGResponsable"] = $indicador->idUGResponsable;
        $dtaVariable["idFunResponsable"]= $indicador->idResponsable;        
        $dtaVariable["published"]       = 1;
        
        //  Gestiono la nueva variable
        $idVariable = $this->_registroVariable( (object)$dtaVariable );

        //  Gestiono la relacion Indicador - Variable
        $idIV = $this->_relacionIndicadorVariable( (object)$dtaVariable, $idIndicador, $idVariable );
        
        //  Gestiono relacion IndicadorVariable - Indicador
        $ivi = $this->_relacionIndVariable_Indicador( $idIV, $idIndicador );

        //  Registro - Unidad de Gestion Responsable de la variable
        if( is_numeric( $indicador->idUGResponsable ) && (int)$indicador->idUGResponsable > 0 ){
            $this->_ugResponsableVar( $idIV, (object)$dtaVariable );
        }
        
        //  Registro - Funcionario Responsable de la variable                        
        if( is_numeric( $indicador->idResponsable ) && (int) $indicador->idResponsable > 0 ){
            $this->_funcionarioResponsableVar( $idIV, (object)$dtaVariable );
        }

        return $ivi;
    }

    
    
    private function _relacionIndicadorDimension( $idIndicador, $lstDimensiones )
    {
        //  Gestiono la relacion indicador - dimension
        $this->_gestionRelacionIndDimension(    $idIndicador, 
                                                $lstDimensiones );
        
        return;
    }
    
    
    
    /**
     * 
     * Creo la relacion Identificador - Dimensión
     * 
     * @param type $idIndicador     Identificador del Indicador
     * @param type $idDimension     Identificador de la Dimensión
     * 
     */
    private function _hijosIndicadorDimension( $idIndEntPadre, $lstDimensiones )
    {
        $lstPlnObjetivos = $this->_getLstPlnObjInd( $idIndEntPadre );
        
        if( count( $lstPlnObjetivos ) ){
            foreach( $lstPlnObjetivos as $plan ){
                
                //  Elimino las dimensiones asociadas a un indicador
                $this->_deleteIndicadorDimension( $plan->idIndicador );
                
                //  Gestiono la relacion indicador - dimension
                $this->_gestionRelacionIndDimension(    $plan->idIndicador, 
                                                        $lstDimensiones, 
                                                        1 );

                $this->_hijosIndicadorDimension(    $plan->idIndEntidad, 
                                                    $lstDimensiones );
            }
        }
        
        return;
    }
    

    private function _getLstPlnObjInd( $idIndEntidad )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanObjetivo( $db );

        return $tbPlan->getPlnObjIndicadores( $idIndEntidad );
    }
    
    
    
    private function _gestionRelacionIndDimension( $idIndicador, $lstDimensiones, $ban = 0 )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();
            
            foreach( $lstDimensiones as $dtaDimension ){
                //  Instacio la tabla Identificador - Variable
                $tbIndDimension = new jTableIndicadorDimension( $db );
                $rst = $tbIndDimension->registrarRelacionIndDimension(  $idIndicador, 
                                                                        $dtaDimension, 
                                                                        $ban );
            }

            $db->transactionCommit();

            return $rst;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }
    
    

    /**
     * 
     * Realizo el proceso de eliminacion de la relacion Indicador Dimension
     * 
     * @param type $idIndicador     Identificador del Indicador
     * 
     */
    private function _eliminoIndicadorDimension( $idIndicador )
    {
        //  Elimino dimensiones asociadas al indicador
        $this->_deleteIndicadorDimension( $idIndicador );

        $dtaIndDimension["idDimIndicador"] = 0;
        $dtaIndDimension["idDimension"] = $indicador->idDimension;
        $dtaIndDimension["published"] = 1;

        //  Asigno la relacion Indicador - Dimension
        return $this->_hijosIndicadorDimension( $idIndicador, ( object ) $dtaIndDimension );
    }

    /**
     * 
     * Gestiono el registro de una variable
     * 
     * @return type
     */
    private function _registroVariable( $variable )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            //  Instacio la tabla Identificador
            $tbVariable = new jTableVariable( $db );
            $idIndicador = $tbVariable->getIdVariable( $variable );

            $db->transactionCommit();

            return $idIndicador;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     *
     * Gestiono la Relacion Indicador Variable
     *  
     * @param type $variable        Informacion de la Variable
     * @param type $idIndicador     Identificador del Indicador
     * @param type $idVariable      Identificador de la Variable
     * 
     * @return type
     * 
     */
    private function _relacionIndicadorVariable( $variable, $idIndicador = 0, $idVariable = 0 )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            //  Instacio la tabla Identificador - variable
            $tbIndicadorVariable = new jTableIndicadorVariable( $db );
            $rst = $tbIndicadorVariable->registroIndicadorVariable( $variable, $idIndicador, $idVariable );

            $db->transactionCommit();

            return $rst;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }
    
    
    
    private function _eliminoSeguimientoIndicadorVariable( $idIndVariable )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            //  Instacio la tabla Identificador - variable
            $tbSgVar = new jTableSeguimiento( $db );
            $rst = $tbSgVar->deleteSeguimientoVariable( $idIndVariable );

            $db->transactionCommit();

            return $rst;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
        
    }
    
    
    
    private function _relacionIndVariable_Indicador( $idIndVariable, $idIndicador )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            //  Instacio la tabla Identificador - variable
            $tbIVI = new jTableIndVariableIndicador( $db );
            $rst = $tbIVI->registroIndVariable_Indicador( $idIndVariable, $idIndicador );

            $db->transactionCommit();

            return $rst;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
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
     * Gestiono el registro de la Unidad de Gestion Responsable de una variable
     * 
     * @param int       $idIndVariable      Identificador del Indicador Variable
     * @param object    $dtaVariable        Identificador de la Unidad de Gestion
     * 
     * @return type
     * 
     */
    private function _ugResponsableVar( $idIndVariable, $dtaVariable )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            //  Instacio la tabla Funcionario
            $tbFRV = new jTableVarUGResponsable( $db );
            $rst = $tbFRV->registrarUndGestionResponsable( $idIndVariable, $dtaVariable );

            $db->transactionCommit();
            return $rst;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     * 
     * Gestiono el registro de un funcionario responsable de una variable
     * 
     * @param int   $idIndVariable      Identificador del Indicador Variable
     * @param int   $idFuncionario      Identificador del Funcionario
     * 
     * @return type
     */
    private function _funcionarioResponsableVar( $idIndVariable, $dtaVariable )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            //  Instancio la tabla Funcionario
            $tbFRV = new jTableVarFuncionarioResponsable( $db );
            $rst = $tbFRV->registrarFunResVariable( $idIndVariable, $dtaVariable );

            $db->transactionCommit();

            return $rst;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     * 
     * Gestiono los procesos de cambio
     * 
     * @return type
     * 
     */
    public function gcIndicador()
    {
        if( count( $this->_dtaIndicador->lstVariables ) ){
            foreach( $this->_dtaIndicador->lstVariables as $variable ){
                //  Valido si existe cambio en la Unidad de Gestion Responsable de una variable
                if( isset( $variable->oldIdUGResponsable ) && $variable->idUGResponsable != $variable->oldIdUGResponsable ){
                    $cv = new CambiosVariable( $variable, $this->_idEntidad );

                    //  Proceso de actualizacion de Unidades de Gestion de Responsables de una variable
                    $cv->updUGResponsableVar( $this->_idEntidad );
                }

                //  Valido si existe cambio en el funcionario responsable de una variable
                if( isset( $variable->oldIdFunResponsable ) && $variable->idFunResponsable != $variable->oldIdFunResponsable ){
                    $cv = new CambiosVariable( $variable, $this->_idEntidad );

                    //  Proceso de actualizacion de Funcionarios de Responsables de una variable
                    $cv->updFunResponsable( $this->_idEntidad );
                }
            }
        }

        return;
    }

    /**
     * 
     * Gestiona el Informacion de Registro de seguimiento de variables
     * 
     * @param type $idIndEntidad        Identificador del Indicador-Entidad
     * @param type $idIndVariable       Identificador del Indicador-Variable
     * @param type $lstSeguimiento      Datos de seguimiento
     * 
     * @return type
     * 
     */
    private function _seguimientoVariable( $idIndEntidad, $idIndVariable, $lstSeguimiento )
    {
        $db = JFactory::getDBO();
        try{
            $db->transactionStart();
            foreach( $lstSeguimiento as $seguimiento ){
                $tbSeg = new jTableSeguimiento( $db );

                //  Ejecuto el proceso de seguimiento de informacion
                $tbSeg->gestionSeguimiento( $idIndEntidad, $idIndVariable, $seguimiento );
            }
            
            $db->transactionCommit();
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }


        return;
    }

    /**
     * 
     * Destructor de la clase GestionIndicador
     * 
     * @return type
     */
    public function __destruct()
    {
        return;
    }

}