<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla tipo de indicador ( #__ind_tipo_indicador )
 * 
 */
class mantenimientoTableTpoIndicador extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_tipo_indicador', 'intCodigoTipo_ind', $db );
    }
    
    /**
     * Guarda un registro de tipo de indicador y retorna su Id
     * 
     * @param type $dtaTpoInd       Data del tipo de indicador
     * @return type
     */
    function registroTpoIndicador( $dtaTpoInd )
    {
        if( !$this->save( $dtaTpoInd ) ){
            echo 'error';
            exit;
        }
        
        return $this->intCodigo_lbind;
    }
    
    
}