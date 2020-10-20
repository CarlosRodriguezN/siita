<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__poa_plan_institucion )
 * 
 */
class IndicadoresTableIndicador extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__ind_indicador', 'intCodigo_ind', $db );
    }

}
