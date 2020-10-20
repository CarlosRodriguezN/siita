<?php

// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla categoria ( #__categoria )
 * 
 */
class mantenimientoTableSector extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__gen_sector', 'inpCodigo_sec', $db );
    }

    
//    function store($updateNulls = false) {
//        var_dump( JRequest::get() ); exit;
//    }
    
}

