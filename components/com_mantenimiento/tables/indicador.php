<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

/**
 * 
 * Clase que gestiona informacion de la tabla Plantilla de indicadores ( #__ptlla_indicador )
 * 
 */
class MantenimientoTableIndicador extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__ptlla_indicador', 'intId_pi', $db );
    }

}
