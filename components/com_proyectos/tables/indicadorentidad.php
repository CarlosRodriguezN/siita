<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de registro de indicador ( #__prf_etapa )
 * 
 */

class ProyectosTableIndicadorEntidad extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_indicador_entidad', 'intIdIndEntidad', $db );
    }
    
    /**
     * 
     * Registro informacion de Indicador Entidad
     * 
     * @param type $dtaIndicadorEntidad     Datos de registro de un indicador Entidad
     * 
     * @return type
     * @throws Exception
     */
    public function registrarIndicadorEntidad( $dtaIndicadorEntidad )
    {
        if( !$this->save( $dtaIndicadorEntidad ) ){
            throw new Exception( JText::_( 'COM_PROYECTOS_REGISTRO_INDICADORES' ) );
            exit;
        }

        return $this->intIdIndEntidad;
    }
    
}