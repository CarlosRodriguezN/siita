<?php

jimport( 'ecorae.planinstitucion.plnopranual' );
jimport( 'ecorae.planinstitucion.papp' );

//  Adjunto clase de Gestion de PPPP
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'entidad' . DS . 'entidad.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'planinstitucion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos' . DS . 'objetivo' . DS . 'objetivo.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos' . DS . 'objetivo' . DS . 'objetivos.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'pppp.php';

//  Adjunto Libreria de Gestion de Planes
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'Plan.php';

class PAPP
{
    private $_idPei;
    private $_tpoEntidad;
    private $_tpoPln;
    private $_lstPPPP;

    /**
     * 
     * Constructor de la clase PPPP
     * 
     * @param type $idPei   Identificador del Plan Estrategico Institucional
     *
     */
    public function __construct( $idPei = 0 )
    {
        $this->_idPei       = $idPei;
        $this->_tpoEntidad  = 1;
        $this->_tpoPln      = 4;
        
        //  Obtengo lista de PPPP de un determinado PEI
        $this->_lstPPPP = ( $idPei != 0 )   ? $this->_getLstPPPP()
                                            : array();
    }
    
     /**
     *  Retorna las fechas de inicio y fin del semestre actual
     * 
     *  @return string
     */
    public function getPappDateActual()
    {
        $fecha = explode("-", date("Y-m-d"));
        $anio = $fecha[0];
        
        if ( date("Y-m-d") >= $anio . "-07-01" ) {
            $rangoPapp["inicio"] =  $anio . "-07-01";
            $rangoPapp["fin"] = $anio . "-12-31";
        } else {
            $rangoPapp["inicio"] =  $anio . "-01-01";
            $rangoPapp["fin"] = $anio . "-06-30";
        }
        
        return $rangoPapp;
    }
    
    
    /**
     * 
     */
    private function _getLstPPPP()
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );
        $lstPlanes = $tbPlan->getLstPPPP( $this->_idPei );

        if ( !empty( $lstPlanes ) ){
            foreach ( $lstPlanes as $key => $plan ) {
                $plan->idRegPln = $key;
                $objetivos = new Objetivos();

                if( $objetivos ) {
                    $plan->lstObjetivos = $objetivos->getLstObjetivos( $plan->intId_pi, $this->_tpoPln );
                }
            }
        }

        return $lstPlanes;
    }
    
    
    /**
     * 
     * Gestiona el registro de una nueva entidad de tipo PPPP tipo '9'
     * 
     * @return int  Identificador de la entidad PPPP
     * 
     */
    private function _registroEntidadPAPP()
    {
        $db = JFactory::getDBO();
        $tblEntidad = new jTableEntidad( $db );

        return $tblEntidad->saveEntidad( $this->_dtaIndicador->idIndEntidad, 10 );
    }
    
    /**
     *  Gestionar la Creacion de PPPP de acuerdo 
     */
    public function gestionarPAPP()
    {
        $dtaPlanPAPP = array();
        
        //  Recorro la lista de planes
        foreach( $this->_lstPPPP as $pppp ){
            //  Creo fechas de inicio y fin de un PPPP de manera semestral
            $dtaFchPAPP = $this->_calculaFechasPAPP( $pppp->dteFechainicio_pi, $pppp->dteFechafin_pi );

            foreach( $dtaFchPAPP as $fchPAPP ){
                $datetime = new DateTime( $fchPAPP["fchInicio"] );
                $dtaPAPP = array();

                $dtaPAPP["idPln"]               = 0;
                $dtaPAPP["intId_tpoPlan"]       = 4;
                $dtaPAPP["intCodigo_ins"]       = 1;
                $dtaPAPP["intIdPadre_pi"]       = $pppp->intId_pi;
                $dtaPAPP["intIdentidad_ent"]    = $pppp->idEntidad;
                $dtaPAPP["strDescripcion_pi"]   = 'PAPP_'. $datetime->format( 'Y' ) .'_'.$fchPAPP["descripcion"];
                $dtaPAPP["dteFechainicio_pi"]   = $fchPAPP["fchInicio"];
                $dtaPAPP["dteFechafin_pi"]      = $fchPAPP["fchFin"];
                $dtaPAPP["lstObjetivos"]        = $pppp->lstObjetivos;
                
                //  Gestiono la creacion de un plan de tipo PAPP
                $dtaPlanPAPP = new Plan( $dtaPAPP, $this->_tpoPln, 4 );
                
                //  Ejecuto Proceso de registro de Planes
                $this->_guardarPlan( $dtaPlanPAPP->__toString() );
            }
        }

        return;
    }
    
    /**
     * 
     * Estimo las fechas a nivel semestral de un determinado PPPP
     * 
     * @param date $fchInicio   Fecha de Inicio
     * @param date $fchFin      Fecha de Fin
     * 
     * @return type
     */
    private function _calculaFechasPAPP( $fchInicio, $fchFin )
    {
        $datetime = new DateTime( $fchInicio );
        
        $fchS1["fchInicio"] = $fchInicio;
        $fchS1["fchFin"] = $datetime->format( 'Y' ).'-06-30';
        $fchS1["descripcion"] = JText::_( 'COM_PEI_SEMESTRE_I' );
        
        $lstFchPAPP[] = $fchS1;
        
        $fchS2["fchInicio"] = $datetime->format( 'Y' ).'-07-01';
        $fchS2["fchFin"] = $fchFin;
        $fchS2["descripcion"] = JText::_( 'COM_PEI_SEMESTRE_II' );
        
        $lstFchPAPP[] = $fchS2;
        
        return $lstFchPAPP;
    }
    
    /**
     * 
     */
    private function _guardarPlan( $dtaPlan )
    {
        //  Registro un nuevo Plan
        $dtaPei = $this->_regDtaPlan( $dtaPlan );
        
        if( $dtaPei ) {
            //  Identificador del Tpo de Entidad PEI
            $idTpoPEI = 1;
            
            foreach( $dtaPlan->lstObjetivos as $objetivo ){
                //  Creo el Objeto Objetivo que gestiona informacion de un objetivo
                $objObjetivo = new Objetivo();

                //  Registro el Plan Objetivo
                $idPlanObjetivo = $objObjetivo->registroPlanObjetivo( $objetivo->idPlnObjetivo, $objetivo->idObjetivo, $dtaPei, $objetivo->idEntidad, $objetivo->idPadreObj );
                $dtaObjetivo["idObj"]           = $objetivo->idObjetivo;
                $dtaObjetivo["idEntidad"]       = $objetivo->idEntidad;
                $dtaObjetivo["idPlnObjetivo"]   = $idPlanObjetivo;

                $objObjetivo->registroObjetivosProrrateados( $objetivo, (object)$dtaObjetivo );
            }

        }else{
            $dtaPei = array();
        }
        
        return;
    }
    
    
    /** 
     * 
     *  Registro Informacion general de un plan
     * 
     *  @param JSon $dtaPlan     Informacion General de un Plan
     */
    private function _regDtaPlan( $dtaPlan )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );

        //  Arma el Objeto Plan de tipo PEI
        $dta["intId_pi"]            = $dtaPlan->intId_pi;
        $dta["intId_tpoPlan"]       = $dtaPlan->intId_tpoPlan;
        $dta["intCodigo_ins"]       = $dtaPlan->intCodigo_ins;
        $dta["intIdentidad_ent"]    = $dtaPlan->intIdentidad_ent;
        $dta["strDescripcion_pi"]   = $dtaPlan->strDescripcion_pi;
        $dta["dteFechainicio_pi"]   = $dtaPlan->dteFechainicio_pi;
        $dta["dteFechafin_pi"]      = $dtaPlan->dteFechafin_pi;
        $dta["intIdPadre_pi"]       = $dtaPlan->intIdPadre_pi;

        return $tbPlan->registroPlan( $dta );
    }
    
    public function __destruct()
    {
        return;
    }
}