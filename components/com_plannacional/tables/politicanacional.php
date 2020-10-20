<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla POLITICANACIONAL ( #__POLITICANACIONAL )
 * 
 */
class plannacionalTablePoliticanacional extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__pnd_politica_nacional', 'intCodigo_pln', $db );
    }
 
}