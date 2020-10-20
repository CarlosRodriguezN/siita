<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla Funcionario Responsable ( tb_ind_funcionario_responsable )
 * 
 */
class jTablePlanificacionIndicador extends JTable
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
     * Gestiono Informacion de planificacion de un determinado Indicador
     * 
     * @param type $idIndEntidad        Identificador Indicador Entidad
     * @param type $dtaPlanificacion    Datos de Planificacion del Indicador
     * 
     * @return type
     * 
     * @throws Exception
     */
    public function registroPlanificacion( $dtaPlanificacion )
    {
        if( !$this->save( $dtaPlanificacion ) ) {
            echo $this->getError();
            exit;
        }
        
        return $this->intId_plan;
    }
    
    
    
    public function getLstPlanificacion( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intId_plan AS idPln, 
                                t1.dteFecha_plan AS fecha, 
                                t1.dcmValor_plan AS valor' );
            $query->from( '#__ind_planificacion t1' );
            $query->where( 't1.intIdIndEntidad = '. $idIndEntidad );
            $query->order( 't1.dteFecha_plan' );
            
            $db->setQuery( (string)$query );
            $db->query();

            $dtaPlanificacion = ( $db->getNumRows() > 0 )   ? $db->loadObjectList()
                                                            : array();

            return $dtaPlanificacion;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    
    
    public function deletePlanificacion( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->delete( '#__ind_planificacion' );
            $query->where( 'intIdIndEntidad = '. $idIndEntidad );
            
            $db->setQuery( (string)$query );
            $db->query();

            return $db->getNumRows();
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    
    public function __destruct()
    {
        return;
    }
    
}