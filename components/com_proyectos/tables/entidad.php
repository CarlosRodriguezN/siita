<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla Entidad ( #__gen_entidad )
 * 
 */

class ProyectosTableEntidad extends JTable
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
    
    /**
     * 
     * Registra una entidad
     * 
     * @return int Identificador de una nueva entidad
     * 
     */
    public function registrarEntidad( $dtaEntidad )
    {
        try {
            if( !$this->save( $dtaEntidad ) ){
                return JError::raiseWarning( 500, $this->getError() );
            }

            return $this->intIdentidad_ent;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}