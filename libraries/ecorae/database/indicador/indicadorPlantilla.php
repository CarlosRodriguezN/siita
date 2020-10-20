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

class jTableIndicadorPlantilla extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ptlla_indicador', 'intId_pi', $db );
    }
    
    
    public function registroDtaIndicadorPtlla( $dtaIndicador )
    {
        if( !$this->save( $dtaIndicador ) ){
            echo $this->getError(); 
            exit;
        }

        return $this->intId_pi;
    }

    public function __destruct()
    {
        return;
    }

}