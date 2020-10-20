<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * Clase Tabla Categoria
 */
class PlanNacionalTableIndicadorNacional extends JTable
{
    /**
    * Constructor
    *
    * @param object Database connector object
    */
    function __construct(&$db) 
    {
        parent::__construct( '#__pdn_indicador_nacional', 'intCodigo_in', $db );
    }
}