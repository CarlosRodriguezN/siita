<?php

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'planinstitucion.php';

/**
 * Clase que realiza el ajuste de fechas a los Indicadores y acciones de 
 * un determinado Plan
 * 
 */
class AjusteFechaPlan
{
    private $_idPlan;
    private $_nuevaFchInicio;
    private $_nuevaFchFin;
    private $_lstPlanes;
    
    /**
     * 
     * Constructor de la clase Ajuste de Fechas de Planes
     * 
     * @param type $idPlan      Identificador del Plan
     * @param type $fchInicio   Nueva Fecha de Inicio
     * @param type $fchFin      Nueva Fecha de Fin
     * 
     */
    public function __construct( $idPlan, $fchInicio, $fchFin )
    {
        $this->_idPlan = $idPlan;
        $this->_nuevaFchInicio = new DateTime( $fchInicio );
        $this->_nuevaFchFin = new Datetime( $fchFin );

        $this->_getLstPlanesHijo( $this->_idPlan );
    }

    /**
     *  Retorno una lista de planes hijos de un determinado Plan
     */
    private function _getLstPlanes( $idPlan )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTablePlanInstitucion( $db );

        return $tbPlan->getLstPlanes( $idPlan );
    }
    
    
    /**
     * 
     * Funcion recursiva que gestiona informacion de planes hijo de un 
     * determinado plan
     * 
     * @param type $idPlan      Identificador de un plan
     * @return type
     * 
     */
    private function _getLstPlanesHijo( $idPlan )
    {
        $lstHijos = $this->_getLstPlanes( $idPlan );
        
        if( count( $lstHijos ) > 0 ){
            foreach( $lstHijos as $hijo ){
                $this->_lstPlanes[] = $hijo;
                $this->_getLstPlanesHijo( $hijo->idPlan );
            }
        }else{
            return;
        }
    }
    
    /**
     * 
     * verifica que la informacion de un determinado se encuentre dentro de los 
     * rangos de las nuevas fechas
     * 
     * @param type $dtaPlan     Datos del plan a verificar
     */
    private function _validaFecha( $dtaPlan )
    {
        $ban = FALSE;

        $fchPlnInicio = new DateTime( $dtaPlan->fchInicio );        
        $fchPlnFin = new DateTime( $dtaPlan->fchFin );
        
        if( ( $this->_nuevaFchFin < $fchPlnInicio ) || ( $fchPlnFin > $this->_nuevaFchFin ) ){
            $ban = TRUE;
        }
        
        return $ban;
    }
    
    /**
     * 
     * Actualizo las fechas del indicador
     * 
     * @param type $plan    Datos del plan a actualizar
     * 
     */
    private function _updFechaPlanIndicador( $plan )
    {
        $db = JFactory::getDBO();
        $tbPlan = new jTableIndicadorEntidad( $db );
        
        $dtaIndicadorEntidad["intIdIndEntidad"]             = $plan->idIndEntidad;
        $dtaIndicadorEntidad["dteHorizonteFchInicio_indEnt"]= $this->_nuevaFchInicio;
        $dtaIndicadorEntidad["dteHorizonteFchFin_indEnt"]   = $this->_nuevaFchFin;

        return $tbPlan->registrarIndicadorEntidad( $dtaIndicadorEntidad );
    }
    
    /**
     * 
     * Actualizo a Vigencia de un indicador Entidad
     * 
     * @param type $plan
     * @return type
     */
    private function _updVigenciaPlanIndicador( $plan )
    {
        try {
            $db = &JFactory::getDBO();
            $query = $db->getQuery(TRUE);

            $query->update( '#__ind_indicador_entidad' );
            $query->set( 'intVigencia_indEnt = 0' );
            $query->where( 'intIdIndEntidad = '. $plan->idIndEntidad );
            
            $db->setQuery($query);
            $db->query();

            $retval = ( $db->getAffectedRows() )? TRUE
                                                : FALSE;

            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_poa.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * 
     *  Gestiona la actualizacion de fechas de los planes asociados a un 
     *  determinado plan
     * 
     */
    public function ajusteFechas()
    {
        if( count( $this->_lstPlanes ) > 0 ){
            //  Recorro la lista de planes
            foreach( $this->_lstPlanes as $plan ){
                //  Verifica si la entidad de un determinado plan esta dentro los rangos de las nuevas fechas
                if( $this->_validaFecha( $plan ) ){
                    if( $plan->idTpoPlan == 1 ){
                        //  Actualiza las fechas entidad_indicador a sus nuevas fechas
                        $this->_updFechaPlanIndicador( $plan );
                        
                        //  Actualiza las fechas de las acciones 
                        $this->_updaFechaAccion( $plan );
                    }
                }else{
                    //  Actualiza la vigencia de una entidad indicador a NO ACTIVO, 
                    //  ya que no se encuentra dentro de los rangos de las nuevas fechas
                    $this->_updVigenciaPlanIndicador( $plan );
                }
            }    
        }
        
        return;
    }
}