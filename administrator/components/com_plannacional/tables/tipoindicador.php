<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * Clase Tabla Categoria
 */
class PlanNacionalTableTipoIndicador extends JTable
{
    /**
    * Constructor
    *
    * @param object Database connector object
    */
    function __construct(&$db) 
    {
        parent::__construct('#__gen_tipo_indicador', 'INTCODIGOTIPO_IND', $db);
    }
}