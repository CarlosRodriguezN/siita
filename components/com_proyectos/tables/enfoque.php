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

class ProyectosTableEnfoque extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__gen_enfoque', 'intId_dim', $db );
    }
    
    
    public function getLstDimension( $idEnfoque )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('t1.intId_dim AS id, 
                            t1.strDescripcion_dim AS nombre');
            $query->from( '#__gen_dimension t1' );
            $query->where( 't1.intId_enfoque = '. $idEnfoque );
            $query->order( 't1.strDescripcion_dim' );
            
            $db->setQuery( $query );
            $db->query();

            $lstDimensiones = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() 
                                                        : FALSE;

            return $lstDimensiones;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}