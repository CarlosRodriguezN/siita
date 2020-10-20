<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport('joomla.database.tablenested');

/**
 * 
 *  Clase que gestiona informacion de la tabla de proyectos ( #__gen_estado_entidad )
 * 
 */
class JTableEstadoEntidad extends JTable {

    /**
     * Constructor
     * @param   JDatabase  &$db  A database connector object
     * @since   11.1
     */
    public function __construct( &$db )
    {
        parent::__construct( '#__gen_estado_entidad', 'intIdEstado_ee', $db );
    }
    
    /**
     * 
     * Gestiona el Registro de informacion del estado de una determinada entidad
     * 
     * @param array $dtaEstadoEntidad    Data del estado entidad a gestionar
     * 
     * @return int
     */
    public function registrarEstadoEntidad( $dtaEstadoEntidad )
    {
        if( !$this->save( $dtaEstadoEntidad ) ) {
            echo $this->getError();
            exit;
        }

        return $this->intIdEstado_ee;
    }
}