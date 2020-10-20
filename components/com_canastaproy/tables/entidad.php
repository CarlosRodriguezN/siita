<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla beneficiario ( #__gen_benefificario )
 * 
 */
class CanastaproyTableEntidad extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__gen_entidad', 'intIdentidad_ent', $db );
    }
    
    public function registrarEntidadPropuesta( $dtaEntidad )
    {
        if( !$this->save( $dtaEntidad ) ){
            echo 'error'; exit;
        }
        
        return $this->intIdentidad_ent;
    }

}