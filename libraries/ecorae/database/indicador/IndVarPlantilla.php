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

class jTableIndVarPlantilla extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ptlla_indicador_variables', 'intId_piv', $db );
    }
    
    
    public function registroRelacionIndVarPtlla( $dtaIndVarPtlla )
    {
        if( !$this->save( $dtaIndVarPtlla ) ){
            echo $this->getError(); 
            exit;
        }

        return $this->intIdVariable_pv;
    }
    
    public function __destruct()
    {
        return;
    }

}