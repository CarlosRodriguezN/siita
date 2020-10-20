<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */

class ProyectosTablePlanificacion extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_planificacion', 'intId_plan', $db );
    }
    
    
    /**
     * 
     * Retorno informacion de planificacion de un determinado indicador
     * 
     * @param type $idIndicador Identificador del Indicador
     * @return type
     * 
     */
    public function getLstPlanificacion( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intId_plan AS idPlanificacion,
                                t1.dteFecha_plan AS fecha, 
                                t1.dcmValor_plan AS valor' );
            $query->from( '#__ind_planificacion t1' );
            $query->where( 't1.intIdIndEntidad ='. $idIndEntidad );

            $db->setQuery( (string)$query );
            $db->query();

            $rstLstPlanificacion = ( $db->getNumRows() > 0 )? $db->loadObjectList() 
                                                            : false;

            return $rstLstPlanificacion;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    
    /**
     * 
     * Gestiona el Registro de Planficacion de un determinado indicador
     * 
     * @param type $idIndEntidad        Identificador de la relacion Entidad - Indicador
     * @param type $dtaPlanificacion    Datos de Planificacion a registrar
     * 
     * @return type
     * 
     * @throws Exception
     */
    public function registrarPlanificacion( $idIndEntidad, $dtaPlanificacion )
    {
        $dtaPlanIndicador["intId_plan"]     = $dtaPlanificacion->idPlanificacion;
        $dtaPlanIndicador["intIdIndEntidad"]= $idIndEntidad;
        $dtaPlanIndicador["dteFecha_plan"]  = $dtaPlanificacion->fecha;
        $dtaPlanIndicador["dcmValor_plan"]  = $dtaPlanificacion->valor;

        if( $dtaPlanificacion->idPlanificacion == 0 ){
            $dtaPlanIndicador["dteFechaRegistro_plan"]   = date("Y-m-d H:i:s");
        }
        
        $dtaPlanIndicador["dteFechaModificacion_plan"]= date("Y-m-d H:i:s");
        
        if( !$this->save( $dtaPlanIndicador ) ){
            throw new Exception( JText::_( 'COM_PROYECTOS_REGISTRO_PLANIFICACION_INDICADORES' ) );
        }
        
        return $this->intId_plan;
    }
    
    
    
}