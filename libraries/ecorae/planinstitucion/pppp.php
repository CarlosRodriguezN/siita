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

class PPPP
{
    private $_idPei;
    private $_tpoEntidad;
    private $_tpoPln;

    /**
     * 
     * Constructor de la clase PPPP
     * 
     * @param type $idPei   Identificador del Plan Estrategico Institucional
     *
     */
    public function __construct( $idPei )
    {
        $this->_idPei = $idPei;
        $this->_tpoEntidad = 1;
        $this->_tpoPln = 3;
    }
    
    /**
     * 
     * Obtengo informacion de Objetivos, Acciones e Indicadores 
     * asociados a un determinado PEI
     * 
     */
    private function _lstObjetivosPEI()
    {
        $objetivos = new Objetivos();
        $lstObjetivos = $objetivos->getLstObjetivos( $this->_idPei, $this->_tpoEntidad );
        
        return $lstObjetivos;
    }

    
    /**
     * 
     * Retorna Informacion Completa de un PEI, 
     * 
     * @return Objeto
     * 
     */
    private function _getDtaPei()
    {
        $dtaPei = null;

        $db = JFactory::getDBO();
        $tbPlnInstitucion = new jTablePlanInstitucion( $db );

        //  Obtengo la informacion de un PEI
        $dtaPei = $tbPlnInstitucion->getDtaPei( $this->_idPei );

        if( is_object( $dtaPei ) ){
            //  Obtengo informacion de Objetivos con sus respectiva alineacion, 
            //  acciones e indicadores
            $dtaPei->lstObjetivos =  $this->_lstObjetivosPEI();
        }

        return $dtaPei;
    }

    /**
     * 
     * Genero una lista de fechas en las cuales vamos a generar los planes
     * 
     * @param type $lstFechasValor   lista de fechas y valores Prorrateados por fechas
     * 
     */
    private function _getLstFechasPPPP( $dtaFchInicio, $dtaFchFin )
    {
        $fchInicio  = new DateTime( $dtaFchInicio );
        $fchFin     = new DateTime( $dtaFchFin );
        $diffAnios  = $fchInicio->diff( $fchFin );
        
        $lstFechasValor = array();
        
        $numAnios   = ( (int)$diffAnios->m == 0 )   ? (int)$diffAnios->y 
                                                    : (int)$diffAnios->y + 1;

        for( $x = 0; $x < $numAnios; $x++ ){
            switch( true ){
                case ( $x == 0 ):
                    $datetime1 = new DateTime( $dtaFchInicio );
                    $dtaFecha["fchInicio"] = $dtaFchInicio;
                    $dtaFecha["fchFin"] = $datetime1->format( 'Y' ).'-12-31';
                break;

                case ( $x == $numAnios - 1 ):
                    $datetime2 = new DateTime( $dtaFchFin );
                    $dtaFecha["fchInicio"] = $datetime2->format( 'Y' ).'-01-01';
                    $dtaFecha["fchFin"] = $dtaFchFin;
                break;

                default:
                    $datetime1 = new DateTime( $dtaFchInicio );
                    $dtaFecha["fchInicio"] = ( (int)$datetime1->format( 'Y' ) + $x ).'-01-01';
                    $dtaFecha["fchFin"] = ( (int)$datetime1->format( 'Y' ) + $x ).'-12-31';
                break;
            }

            $lstFechasValor[] = $dtaFecha;
        }

        return $lstFechasValor;
    }
    
    /**
     *  Gestionar la Creacion de PPPP de acuerdo 
     */
    public function gestionarPPPP()
    {
        $dtaPlanPPPP = array();
        
        //  Obtengo informacion especifica del PEI Vigente
        $dtaPei = $this->_getDtaPei();

        //  Genero las Fechas de Inicio y Fin de cada PPPP
        $lstFechas = $this->_getLstFechasPPPP( $dtaPei->fchInicio, $dtaPei->fchFin );

        foreach( $lstFechas as $fecha ){
            $datetime = new DateTime( $fecha["fchInicio"] );
            $dtaPPPP = array();

            $dtaPPPP["idPln"]               = $dtaPei->idPln;
            $dtaPPPP["intId_tpoPlan"]       = 3;
            $dtaPPPP["intCodigo_ins"]       = $dtaPei->idInstitucion;
            $dtaPPPP["intIdPadre_pi"]       = $dtaPei->idPln;
            $dtaPPPP["intIdentidad_ent"]    = $dtaPei->idEntidad;
            $dtaPPPP["strDescripcion_pi"]   = 'PPPP_'. $datetime->format( 'Y' );
            $dtaPPPP["dteFechainicio_pi"]   = $fecha["fchInicio"];
            $dtaPPPP["dteFechafin_pi"]      = $fecha["fchFin"];
            $dtaPPPP["lstObjetivos"]        = $dtaPei->lstObjetivos;

            //  Gestiono la creacion de un plan de tipo PPPP
            $dtaPlanPPPP = new Plan( $dtaPPPP, $this->_tpoPln, 3 );
            
            //  Ejecuto procesos de registro del nuevo Plan
            $this->_guardarPlan( $dtaPlanPPPP->__toString() );
        }

        return;
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
                $this->_registroPlanObjetivo( $objetivo, $dtaPei );
            }
        }else{
            $dtaPei = array();
        }
    }
    
    /**
     * 
     * Registro Informacion Plan de Objetivo
     * 
     * @param Object $objetivo      Datos del Objetivo a Registrar
     * @param int $dtaPei           Identificador del Plan al que pertenece
     * 
     * @return type
     * 
     */
    private function _registroPlanObjetivo( $objetivo, $dtaPei )
    {
        //  Creo el Objeto Objetivo que gestiona informacion de un objetivo
        $objObjetivo = new Objetivo();

        //  Registro el Plan Objetivo
        $idPlanObjetivo = $objObjetivo->registroPlanObjetivo(   $objetivo->idPlnObjetivo, 
                                                                $objetivo->idObjetivo, 
                                                                $dtaPei, 
                                                                $objetivo->idEntidad, 
                                                                $objetivo->idPadreObj );

        $dtaObjetivo["idObj"]           = $objetivo->idObjetivo;
        $dtaObjetivo["idEntidad"]       = $objetivo->idEntidad;
        $dtaObjetivo["idPlnObjetivo"]   = $idPlanObjetivo;
        
        return $objObjetivo->registroObjetivosProrrateados( $objetivo, (object)$dtaObjetivo, $this->_tpoEntidad );
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
        $dta["blnVigente_pi"]       = 1;
        
        return $tbPlan->registroPlan( $dta );
    }
}