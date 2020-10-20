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

class ProyectosTableMeta extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db )
    {
        parent::__construct( '#__pgp_meta_proyecto', 'intcodgio_metapry', $db );
    }
    
    
    /**
     * 
     *  Gestiono el registro de los datos generales de un proyecto
     * 
     *  @param array $dtaProyecto Datos Generales de un proyecto
     * 
     *  @return boolean
     * 
     */
    public function registroMetaProyecto( $dtaMetaPry )
    {
        if( !$this->save( $dtaMetaPry ) ){
            throw new Exception( 'Error al Registrar la Meta de un Proyecto' );
            return false;
        }

        return $this->intcodgio_metapry;
    }
    
}