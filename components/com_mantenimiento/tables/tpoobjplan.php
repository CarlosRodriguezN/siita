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
class mantenimientoTableTpoObjPlan extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__pei_tipo_objetivo', 'intId_tpoObj', $db );
    }
    
    /**
     * Guarda un registro en de linea base y retorna su Id
     * 
     * @param type $dtaTOP       Data de la linea base
     * @return type
     */
    function registroTipoObjPlan( $dtaTOP )
    {
        if( !$this->save( $dtaTOP ) ){
            echo 'error';
            exit;
        }
        
        return $this->intId_tpoObj;
    }
    
    
}