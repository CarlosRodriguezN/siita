<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla Tipo Gestion ( #__gen_tipo_gestion )
 * 
 */
class MantenimientoTableTipoGestion extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__gen_tipo_gestion', 'intIdTipoGestion_tpg', $db );
    }

}