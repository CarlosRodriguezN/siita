<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * Clase Tabla Categoria
 */
class PlanNacionalTablePeriodicidad extends JTable
{
    /**
    * Constructor
    *
    * @param object Database connector object
    */
    function __construct(&$db) 
    {
        parent::__construct('#__gen_periodicidad', 'INTCODIGO_PER', $db);
    }
}