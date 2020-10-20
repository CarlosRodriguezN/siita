<?php

jimport( 'ecorae.objetivos.objetivo.objetivos' );

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanAccion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanActividad.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanAlineacion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'PlanIndicador.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'ProrrateoIndicador.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'plan' . DS . 'ProrrateoAccion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'entidad' . DS . 'entidad.php';

class PlanObjetivo
{
    private $_idObjetivo;
    private $_idEntidad;
    private $_idPlnObjetivo;
    private $_idPrioridadObj;
    private $_idTpoObj;
    private $_idPadreObj;
    private $_descObjetivo;
    private $_descTpoObj;
    private $_nmbPrioridadObj;
    private $_published;
    
    private $_acciones = array();
    private $_indicadores = array();
    private $_alineaciones = array();
    
    /**
     * 
     * Constructor del Plan Objetivo
     * 
     * @param objeto $dtaObjetivo   Datos del Objetivo, indicadores, acciones, etc.
     * @param date $fchInicioPln    Fecha de Inicio del Plan
     * @param date $fchFinPln       Fecha de Fin del Plan
     * @param int $tpoPln           Tipo de Plan
     * 
     */
    public function __construct( $dtaObjetivo, $fchInicioPln, $fchFinPln, $tpoPln, $tpoObjetivo )
    {        
        $this->_idObjetivo      = $this->_registrarObjetivo( $dtaObjetivo );
        $this->_idEntidad       = $this->_crearIdEntidadObjetivo();

        $this->_idPlnObjetivo   = 0;
        $this->_idPrioridadObj  = $dtaObjetivo->idPrioridadObj;
        $this->_idTpoObj        = $tpoObjetivo;
        
        $this->_idPadreObj      = $dtaObjetivo->idEntidad;

        $this->_descObjetivo    = $dtaObjetivo->descObjetivo;
        $this->_descTpoObj      = $dtaObjetivo->descTpoObj;
        $this->_nmbPrioridadObj = $dtaObjetivo->nmbPrioridadObj;
        $this->_indicadores     = array();
        $this->_acciones        = array();
        $this->_published       = $dtaObjetivo->published;

        //  Seteo Indicadores
        if( count( (array)$dtaObjetivo->lstIndicadores ) ){
            foreach( $dtaObjetivo->lstIndicadores as $indicador ){                
                $dtaIndicador = (object)$indicador;

                if( !is_null( $dtaIndicador->nombreIndicador ) && strlen( $dtaIndicador->nombreIndicador ) > 0 ){
                    $objIndicador = new PlanIndicador( $dtaIndicador, $fchInicioPln, $fchFinPln, $tpoPln );
                    $objIndicador->prorrateoIndicador();

                    $this->_indicadores[] = $objIndicador;
                }

            }
        }

        //  Seteo Informacion de Acciones
        if( count( (array)$dtaObjetivo->lstAcciones ) ){
            foreach( $dtaObjetivo->lstAcciones as $accion ){

                if( !is_null( $accion->descripcionAccion ) ){
                    $objAccion = new PlanAccion( $accion, $fchInicioPln, $fchFinPln, $tpoPln );

                    if( $objAccion->__toString() != null ){
                        $this->_acciones[] = $objAccion->__toString();
                    }
                }

            }
        }
        
        return;
    }
    
    /**
     * 
     * Muestro el contenido del objeto PlanObjetivo en forma de array
     * 
     * @return Array
     */
    public function __toString()
    {
        $lstAcciones    = array();
        $lstIndicadores = array();
        $lstAlineacion  = array();

        $dtaObjetivo["idObjetivo"]      = $this->_idObjetivo;
        $dtaObjetivo["idEntidad"]       = $this->_idEntidad;
        $dtaObjetivo["idPlnObjetivo"]   = $this->_idPlnObjetivo;
        $dtaObjetivo["idPrioridadObj"]  = $this->_idPrioridadObj;
        $dtaObjetivo["idTpoObj"]        = $this->_idTpoObj;
        $dtaObjetivo["idPadreObj"]      = $this->_idPadreObj;
        $dtaObjetivo["descObjetivo"]    = $this->_descObjetivo;
        $dtaObjetivo["descTpoObj"]      = $this->_descTpoObj;
        $dtaObjetivo["nmbPrioridadObj"] = $this->_nmbPrioridadObj;
        $dtaObjetivo["published"]       = $this->_published;
        
        //  Agrego lista de Indicadores prorrateados asociados a un determinado objetivo
        foreach( $this->_indicadores as $indicador ){
            $lstIndicadores[] = $indicador->__toString();
        }        
        $dtaObjetivo["lstIndicadores"] = $lstIndicadores;
        
        //  Agrego lista de Acciones prorrateadas asociados a un determinado objetivo
        foreach( $this->_acciones as $accion ){
            $lstAcciones[] = $accion;
        }
        $dtaObjetivo["lstAcciones"] = $lstAcciones;
        
        //  Agrego lista de Acciones prorrateadas asociados a un determinado objetivo
        foreach( $this->_alineaciones as $alineacion ){
            $lstAlineacion[] = $alineacion;
        }
        
        $dtaObjetivo["lstAlineaciones"] = $lstAlineacion;
        
        return (object)$dtaObjetivo;
    }
    
    /**
     * 
     * Retorno el identificador de Entidad del Objetivo a construir
     * 
     * @return type
     */
    private function _crearIdEntidadObjetivo()
    {
        $db = JFactory::getDBO();
        $tblEntidad = new jTableEntidad( $db );

        return $tblEntidad->saveEntidad( 0, 4 );
    }
    
    /**
     * 
     * Registro nuevo Objetivo
     * 
     * @param Object $objetivo      Informacion de un Objetivo
     */
    private function _registrarObjetivo( $objetivo )
    {
        $db = JFactory::getDBO();
        $tbObjetivo = new jTableObjetivo( $db );

        //  Verifico la existencia de un determinado objetivo
        $dtaObjetivo["intId_ob"]            = $this->_existeObjetivo( $objetivo->descObjetivo ); 
        $dtaObjetivo["strDescripcion_ob"]   = $objetivo->descObjetivo; 
        $dtaObjetivo["intId_tpoObj"]        = 1;
        $dtaObjetivo["dteFecharegistro_ob"] = date( 'Y-m-d' ); 

        return $tbObjetivo->registroObjPei( $dtaObjetivo );
    }
    
    
    
    private function _existeObjetivo( $descObjetivo )
    {
        $db = JFactory::getDBO();
        $tbObjetivo = new jTableObjetivo( $db );
        
        return $tbObjetivo->existeObjetivo( $descObjetivo );
    }
    
    
    /**
     * 
     * Prorrateo Informacion de un indicador de acuerdo a una 
     * fecha de Inicio y Fin, fechas asociadas a un determinado PPPP
     * 
     * @param date $fchInicio   Fecha de Inicio
     * @param date $fchFin      Fecha de Fin
     * 
     * @return array    Retorna una lista de indicadores prorrateados
     * 
     */
    public function prorrateoIndicadores( $fchInicio, $fchFin )
    {
        foreach( $this->_indicadores as $indicador ){
            $indicador->prorrateoIndicador( $fchInicio, $fchFin );
        }
        
        return;
    }
    
    
    /**
     * 
     * Gestiona el prorrateo de Acciones 
     * 
     * @param date $fchInicio   Fecha de Inicio de un Plan
     * @param date $fchFin      Fecha de Fin de un Plan
     * 
     * @return type
     * 
     */
    public function prorrateoAcciones( $fchInicio, $fchFin )
    {
        foreach( $this->_acciones as $accion ){
            $accion->prorrateoAccion( $fchInicio, $fchFin );
        }

        return;
    }
}