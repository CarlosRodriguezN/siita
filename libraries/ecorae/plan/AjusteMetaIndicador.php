<?php

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'indicadorentidad.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanIndicador.php';

class AjusteMetaIndicador
{
    private $_indicador;
    private $_lstIndHijos;
    
    /**
     * 
     * Constructor de la clase AjusteMetaIndicador
     * 
     * @param object $indicador   Datos de un indicador
     * 
     */
    public function __construct( $indicador )
    {        
        $this->_indicador   = clone (object)$indicador;
        
        //  Obtengo Indicadores hijo asociados a un determinado indicador
        $this->_getLstIndicadoresHijo( $this->_indicador->idIndEntidad );
    }

    /**
     * 
     * Retorna una lista de indicadores entidad asociados a un 
     * determinado indicador entidad
     * 
     * @return type
     * 
     */
    private function _lstIndHijos( $idIndEntidad )
    {
        $db = JFactory::getDBO();
        $tbIndEntidad = new jTableIndicadorEntidad( $db );
        
        return $tbIndEntidad->lstIndEntidad( $idIndEntidad );
    }
    
    /**
     * 
     * Funcion recursiva que gestiona informacion de planes hijo de un 
     * determinado plan
     * 
     * @param type $idIndEntidad      Identificador de un plan
     * 
     * @return type
     * 
     */
    private function _getLstIndicadoresHijo( $idIndEntidad )
    {
        $lstHijos = $this->_lstIndHijos( $idIndEntidad );
        
        if( count( $lstHijos ) ){
            foreach( $lstHijos as $hijo ){
                $this->_lstIndHijos[] = $hijo;
                $this->_getLstIndicadoresHijo( $hijo->idIndEntidad );
            }
        }else{
            return;
        }
    }
    
    /**
     * 
     * Ajuste indicador meta de un indicador
     * 
     * @return type
     */
    public function ajusteMeta()
    {
        if( count( $this->_lstIndHijos ) ){            
            foreach( $this->_lstIndHijos as $indicador ){
                $objIndicador = new ProrrateoIndicador( $this->_indicador, $this->_indicador->fchHorzMimimo, $this->_indicador->fchHorzMaximo, $indicador->tpoPlan );
                $dtaProrrateo = $objIndicador->prorrateoIndicador();
                
                //  GESTIONO EL REGISTRO DE INDICADORES A LA BBDD;
                $db = JFactory::getDBO();
                $tbIndEntidad = new jTableIndicadorEntidad( $db );

                $dtaIndEntidad["intIdIndEntidad"]               = $indicador->idIndEntidad;
                $dtaIndEntidad["dteHorizonteFchInicio_indEnt"]  = $dtaProrrateo["fchInicio"];
                $dtaIndEntidad["dteHorizonteFchFin_indEnt"]     = $dtaProrrateo["fchFin"];
                $dtaIndEntidad["dcmValor_ind"]                  = $dtaProrrateo["meta"];

                //  Registro cambios del indicador
                $tbIndEntidad->registrarIndicadorEntidad( $dtaIndEntidad );
            }
        }

        return;
    }

}