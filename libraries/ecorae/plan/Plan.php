<?php

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanObjetivo.php';

class Plan
{
    private $_idPln;
    private $_intId_tpoPlan;
    private $_intCodigo_ins;
    private $_intIdPadre_pi;
    private $_intIdentidad_ent;
    private $_strDescripcion_pi;
    private $_dteFechainicio_pi;
    private $_dteFechafin_pi;
    private $_tpoObjetivo;

    private $_objetivos = array();
    
    /**
     * 
     * Gestiona la creacion de un PLAN ( PPPP, PAPP, POA - UG, POA - F ), de acuerdo 
     * al tipo de plan que se esta ejecutando
     * 
     * @param objeto $dtaPlan       Informacion general de un plan
     * @param int $tpoPln           Tipo de Plan, PPPP, PAPP, POA, etc.
     * @param int $tpoObjetivo      Tipo de Plan, PPPP, PAPP, POA, etc.
     * 
     * 
     */
    public function __construct( $dtaPlan, $tpoPln, $tpoObjetivo )
    {
        $this->_idPln               = $dtaPlan["idPln"];
        $this->_intId_tpoPlan       = $dtaPlan["intId_tpoPlan"];
        $this->_intCodigo_ins       = $dtaPlan["intCodigo_ins"];
        $this->_intIdPadre_pi       = $dtaPlan["intIdPadre_pi"];
        $this->_intIdentidad_ent    = $dtaPlan["intIdentidad_ent"];
        $this->_strDescripcion_pi   = $dtaPlan["strDescripcion_pi"];
        $this->_dteFechainicio_pi   = $dtaPlan["dteFechainicio_pi"];
        $this->_dteFechafin_pi      = $dtaPlan["dteFechafin_pi"];
        $this->_tpoObjetivo         = $tpoObjetivo;

        if( count( $dtaPlan["lstObjetivos"] ) ){ 
            $this->_creaObjetivos( $dtaPlan["lstObjetivos"] );
        }
    }
    
    /**
     * 
     * Se crean los objetivos en funcion al tipo de plan, con esto se homologan 
     * fechas de inicio y fin, de cada indicador fechas de inicio y fin, umbrales 
     * 
     * @param type $lstObjetivos    Informacion de los Objetivo
     * @return type
     */
    private function _creaObjetivos( $lstObjetivos )
    {
        foreach( $lstObjetivos as $objetivo ){
            $objObjetivo = new PlanObjetivo(    $objetivo, 
                                                $this->_dteFechainicio_pi, 
                                                $this->_dteFechafin_pi, 
                                                $this->_intId_tpoPlan, 
                                                $this->_tpoObjetivo );

            $this->_objetivos[] = $objObjetivo;

            if( $this->_intId_tpoPlan == 2 && count( (array)$objetivo->lstAcciones ) ){
                $this->_convierteAccionesEnObjetivos( $objetivo->lstAcciones );
            }
        }
        
        return;
    }
    
    /**
     * 
     * Se crea un Objetivo en funcion de a la informacion de un accion, 
     * este procedimiento se realiza al momento de crear un POA por UG y POA 
     * por funcionario
     * 
     * @param type $lstAcciones     Lista de Acciones asociadas a un Objetivo
     * 
     * @return type
     * 
     */
    private function _convierteAccionesEnObjetivos( $lstAcciones )
    {
        foreach( $lstAcciones as $accion ){
            //  Seteo Solamente la descripcion del Objetivo ya que estamos creando de una Accion un Objetivo
            $dtaObjAccion["idObjetivo"]     = 0;
            $dtaObjAccion["idTpoObj"]       = 1;
            $dtaObjAccion["descObjetivo"]   = $accion->descripcionAccion;

            $objAccionObjetivo = new PlanObjetivo(  (object)$dtaObjAccion, 
                                                    $this->_dteFechainicio_pi, 
                                                    $this->_dteFechafin_pi, 
                                                    2, 
                                                    2 );
            
            $this->_objetivos[] = $objAccionObjetivo;
        }
        
        return;
    }
    
    public function __toString()
    {
        $dtaObjetivo = array();
        
        $dtaPlan["idPln"]               = $this->_idPln;
        $dtaPlan["intId_tpoPlan"]       = $this->_intId_tpoPlan;
        $dtaPlan["intCodigo_ins"]       = $this->_intCodigo_ins;
        $dtaPlan["intIdPadre_pi"]       = $this->_intIdPadre_pi;
        $dtaPlan["intIdentidad_ent"]    = $this->_intIdentidad_ent;
        $dtaPlan["strDescripcion_pi"]   = $this->_strDescripcion_pi;
        $dtaPlan["dteFechainicio_pi"]   = $this->_dteFechainicio_pi;
        $dtaPlan["dteFechafin_pi"]      = $this->_dteFechafin_pi;
        
        foreach( $this->_objetivos as $objetivo ){
            $dtaObjetivo[] = $objetivo->__toString();
        }
        
        $dtaPlan["lstObjetivos"] = $dtaObjetivo;
        
        return (object)$dtaPlan;
    }
    
    
    
    /**
     * 
     * Prorrateo indicadores asociados a un determinado Indicador
     * 
     * @return type
     * 
     */
    public function dtaPlanPPPP()
    {
        foreach( $this->_objetivos as $objetivo ){
            //  Prorrateo de Indicadores asociados a un determinado Objetivo
            $objetivo->prorrateoIndicadores( $this->_dteFechainicio_pi, $this->_dteFechafin_pi );
        }
        
        return;
    }
}