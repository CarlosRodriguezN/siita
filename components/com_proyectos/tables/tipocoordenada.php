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

class ProyectosTableTipoCoordenada extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__pfr_tipo_coordenadas', 'idtipo_cord', $db );
    }
}