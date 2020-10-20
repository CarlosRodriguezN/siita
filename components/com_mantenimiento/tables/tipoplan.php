<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla beneficiario ( #__gen_benefificario )
 * 
 */
class mantenimientoTableTipoPlan extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__pei_tipo_plan_ins', 'intId_tpoPlan', $db );
    }
    
    /**
     * Guarda un registro en de linea base y retorna su Id
     * 
     * @param type $dtaLB       Data de la linea base
     * @return type
     */
    function registroTipoPlan( $dtaLB )
    {
        if( !$this->save( $dtaLB ) ){
            echo 'error';
            exit;
        }
        
        return $this->intId_tpoPlan;
    }
    
    
}