<?php


jimport( 'ecorae.libraries.ecorae.database.table.planinstitucion' );
jimport( 'ecorae.libraries.ecorae.database.table.objetivo' );
jimport( 'ecorae.libraries.ecorae.database.table.planobjetivo' );
jimport( 'ecorae.libraries.ecorae.database.table.unidadgestion' );
jimport( 'ecorae.libraries.ecorae.database.indicador.undgestionresponsable' );

class PlanOperativo
{
    
    private $_idEntidad;
    private $_dtaPlan;
    
    /**
     * 
     * Construnctor de la clase Plan - Operativo
     * 
     * @param type $dtaPlan     Datos del Plan a Gestionar
     * @param type $idEntidad   Identificador - Entidad del Programa - Proyecto - Contrato
     * 
     */
    public function __construct( $dtaPlan, $idEntidad )
    {
        $this->_idEntidad   = $idEntidad;
        $this->_dtaPlan     = $dtaPlan;        
    }

    
    /**
     * 
     * Gestiona el registro planes operativos - proyectos
     * 
     * @return type
     */
    public function gestionDtaPlan()
    {
        //  Registro del Plan
        $idPlan = $this->_registrarPlan();

        //  Registro Objetivo
        $idObjetivo = $this->_registroObjetivo();

        //  Registro Relacion Plan Objetivo
        $dtaPlanObjetivo = $this->_registrarPlanObjetivo( $idPlan, $idObjetivo );

        //  Registro Acciones asociados a los objetivos de un Plan
        $this->_registroAlineacionObjetivo( $dtaPlanObjetivo );

        //  Registro Acciones asociados a los objetivos de un Plan
        $this->_registroAccionesObjetivo( $dtaPlanObjetivo );

        //  Registro Indicadores asociados a los objetivos de un Plan
        $this->_registroIndicadoresObjetivo( $dtaPlanObjetivo["idEntPO"] );

        return $dtaPlanObjetivo;
    }
    
    /**
     * 
     * Registro Informacion de un Plan de tipo POA - OP para proyectos, programas, contratos
     * 
     * @return int  Identificador de registro del Plan
     * 
     */
    private function _registrarPlan()
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );

        //  Arma el Objeto Plan de tipo PEI
        $dta["intId_pi"]            = ( isset( $this->_dtaPlan->idPlan ) )  ? $this->_dtaPlan->idPlan
                                                                            : 0;

        $dta["intId_tpoPlan"]       = 5;
        $dta["intCodigo_ins"]       = 1;
        $dta["intIdentidad_ent"]    = $this->_idEntidad;
        $dta["strDescripcion_pi"]   = $this->_dtaPlan->descripcionPln;
        $dta["dteFechainicio_pi"]   = $this->_dtaPlan->fchInicio;
        $dta["dteFechafin_pi"]      = $this->_dtaPlan->fchFin;
        $dta["intIdPadre_pi"]       = $this->_getIdPlanPOA_UG();

        $dta["blnVigente_pi"]       = ( isset( $this->_dtaPlan->vigentePln ) )  ? $this->_dtaPlan->vigentePln
                                                                                : 1;
        
        return $tbPlan->registroPlan( $dta );
    }

    
    
    /**
     * 
     * Retorna el Identificador del plan de una determinada unidad de gestion, 
     * en un determinado periodo de tiempo 
     * 
     * @param int $idUGResponsable     Identificador del Unidad de Gestion Responsable
     *  
     * @return int  Retona el Identificador de la unidad de gestion responsable, 
     *              caso contrario FALSE
     */
    private function _existePlanUG()
    {
        //  Instacio la tabla Unidad de Gestion
        $db = JFactory::getDBO();
        $tbUGR = new jTableUndGestionResponsable( $db );

        return $tbUGR->_existePlanUG( $this->_dtaPlan->idUGResponsable, $this->_dtaPlan );
    }
    
    
    /**
     * 
     * Obtengo los datos de una unidad de gestion responsable
     * 
     * @return type
     * 
     */
    private function _getDtaUG()
    {
        $db = JFactory::getDBO();
        $tbUG = new JTableUnidadGestion( $db );

        return $tbUG->getEntidadUG( $this->_dtaPlan->idUGResponsable );
    }
    
    
    /**
     * 
     * Verifico la existencia del POA de una determinada Unidad de Gestion, 
     * en caso que no exista lo creamos en funciona a la informacion de unidad de gestion
     * 
     * @param int $idUGR        Identificador de la Unidad de Gestion - Responsable
     * @param object $plan      Informacion del periodo de tiempo a verificar
     * 
     * @return int  Identificador del plan de tipo POA - UG, de la unidad de gestion responsable
     * 
     */
    private function _getIdPlanPOA_UG()
    {
        $idPlnUG = $this->_existePlanUG();

        if( $idPlnUG == FALSE ){
            
            $idPlnUG = 0;
            
            //  Obtengo los datos de la unidad de Gestion
            //  $dtaUG = $this->_getDtaUG();

            //  Creo en Plan POA - UG, en funcion a los datos de la UG Responsable
            // $idPlnUG = $this->_crearPlanUG( $dtaUG );
        }

        return $idPlnUG;
    }
    
    
    
    /**
     * 
     * Crea el Plan de tipo POA-UG, de una determinada unidad de gestion
     * 
     * @param type $dtaUG
     * @return type
     */
    private function _crearPlanUG( $dtaUG )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
         
        $datePOA = new DateTime( $this->_dtaPlan->fchInicio );

        $dtaPoa["idPln"]            = 0;
        $dtaPoa["intId_tpoPlan"]    = 2;
        $dtaPoa["intCodigo_ins"]    = 1;
        $dtaPoa["intIdPadre_pi"]    = $this->_getIdPlanPPPP();
        $dtaPoa["intIdentidad_ent"] = $dtaUG->idEntidadUG;
        $dtaPoa["strDescripcion_pi"]= 'POA_' . $dtaUG->nombreUG . '_' . $datePOA->format( 'Y' );
        $dtaPoa["dteFechainicio_pi"]= $this->_dtaPlan->fchInicio;
        $dtaPoa["dteFechafin_pi"]   = $this->_dtaPlan->fchFin;
        
        return $tbPlan->registroPlan( $dtaPoa );

    }
    
    
    
    private function _getIdPlanPPPP()
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        
        return $tbPlan->getIdPPPP( $this->_dtaPlan );
    }
    
    
    
    private function _registroObjetivo()
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $dtaObjetivo = $this->_dtaPlan->planObjetivo;

            $tblObjetivo = new jTableObjetivo( $db );

            $objetivoPln["intId_ob"]            = isset( $dtaObjetivo->idObjetivo ) ? $dtaObjetivo->idObjetivo 
                                                                                    : 0;

            $objetivoPln["strDescripcion_ob"]   = $dtaObjetivo->nombre;
            $objetivoPln["dteFecharegistro_ob"] = date( "Y-m-d H:i:s" );
            $objetivoPln["published"]           = 1;
            
            if( $objetivoPln["intId_ob"] != 0 ){
                $objetivoPln["dteFechamodificacion_ob"] = date( "Y-m-d H:i:s" );
            }
            
            $idRegObjetivo = $tblObjetivo->registroObjPei( $objetivoPln );            
            $db->transactionCommit();

            return $idRegObjetivo;
        }catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }
    
    
    
    /**
     *  Gestiona el registro de una Entidad de tipo Objetivo
     *  4 es el ID de Tipo Entidad "Objetivo"
     */
    private function _registroEntidad()
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $tblEntidad = new jTableEntidad( $db );
            $idEntidad = $tblEntidad->saveEntidad( 0, 4 );

            $db->transactionCommit();

            return $idEntidad;   //  saveEntidad retorna un nuevo ID de entidad
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }
    
    
    
    /**
     * 
     * Registro informacion con la relacion Plan - Objetivo
     * 
     * @param int $idPlan  Identificador del Plan
     * @param int $idObj   Identificador del Objetivo
     * 
     * @return 
     */
    private function _registrarPlanObjetivo( $idPlan, $idObj )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();
            
            $dtaObjetivo = $this->_dtaPlan->planObjetivo;
            $tblPlnObj = new jTablePlanObjetivo( $db );

            $dtaPlnObj["intId_pln_objetivo"] = ( $dtaObjetivo->idPlnObjetivo )  ? $dtaObjetivo->idPlnObjetivo 
                                                                                : 0;

            $dtaPlnObj["intId_pi"]          = $idPlan;
            $dtaPlnObj["intId_ob"]          = $idObj;
            $dtaPlnObj["intIdentidad_ent"]  = ( isset( $dtaObjetivo->idEntidadObjetivo ) )  ? $dtaObjetivo->idEntidadObjetivo
                                                                                            : $this->_registroEntidad();

            $rstPlnObj["idPlnObjetivo"] = $tblPlnObj->registroPlnObj( $dtaPlnObj );
            $rstPlnObj["idEntPO"]       = $dtaPlnObj["intIdentidad_ent"];

            $db->transactionCommit();

            return $rstPlnObj;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }
    
    /**
     * 
     * Gestiono el Registro de indicadores Operativos asociados a los Objetivos 
     * 
     * @param type $idEntidadPlanObjetivo   Identificador Entidad del Plan Objetivo
     * 
     * @return type
     * 
     */
    private function _registroIndicadoresObjetivo( $idEntidadPlanObjetivo )
    {
        $lstIndicadores = $this->_dtaPlan->planObjetivo->lstIndicadores;
        
        if( count( ( array ) $lstIndicadores ) ){
            //  Registro indicadores asociados a un objetivo OEI
            foreach( $lstIndicadores as $indicador ){
                //  Gestion de Indicadores generados a partir del prorrateo de un indicador
                $objIndicador = new Indicador();
                $objIndicador->registroIndicador( $idEntidadPlanObjetivo, $indicador, 0 );
            }
        }
        
        return;
    }

    /**
     * 
     * Registro de Acciones asociadas a un objetivo 
     * 
     * @param type $dataObj     Datos de registro de un objetivo
     * @return type
     * 
     */
    private function _registroAccionesObjetivo( $dataObj )
    {
        $lstAcciones = $this->_dtaPlan->planObjetivo->lstAcciones;
        
        if( count( $lstAcciones ) ){
            $oAcciones = new Acciones();
            $rst = $oAcciones->saveAcciones( $lstAcciones, (object)$dataObj );
        }

        return $rst;
    }


    /**
     * 
     * Registro Alineacion 
     * 
     * @param type $dtaPlanObjetivo
     */
    private function _registroAlineacionObjetivo( $dataObj )
    {
        $lstAlineaciones = $this->_dtaPlan->planObjetivo->lstAlineaciones;

        if( count( $lstAlineaciones ) ){
            $oAlineacion = new AlineacionOperativa();
            $oAlineacion->saveAlineacionesOperativas( $lstAlineaciones, $dataObj["idEntPO"], 2 );            
        }

        return;
    }

}