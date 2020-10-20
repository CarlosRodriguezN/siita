<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de Indicadores
 * 
 */

class jTableVariableIndPlantilla extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ptlla_variables', 'intIdVariable_pv', $db );
    }
    
    
    public function registroDtaIndVariablePtlla( $dtaIndicador )
    {
        if( !$this->save( $dtaIndicador ) ){
            echo $this->getError(); 
            exit;
        }

        return $this->intIdVariable_pv;
    }
    
    
    
    public function deleteVariablePtlla( $idVariable )
    {
        if( $this->delete( $idVariable ) ){
            $this->_deleteRelIndVariable( $idVariable );
        }else{
            echo $this->getError(); 
            exit;
        }
        
        return;
    }
    
    
    
    public function _deleteRelIndVariable( $idVariable )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->delete( '#__ptlla_indicador_variables' );
            $query->where( 'intIdVariable_pv = '. $idVariable );
                        
            $db->setQuery( (string)$query );
            $db->query();
            
            return $db->getAffectedRows();

        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }


    
    public function __destruct()
    {
        return;
    }

}