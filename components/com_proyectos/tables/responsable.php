<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__fun_responsable_pry )
 * 
 */

class ProyectosTableResponsable extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_responsable', 'indId_ri', $db );
    }
    
    
    /**
     * 
     * Gestino el registro de Responsables de gestion de un indicador
     * 
     * @param type $intIdIndEntidad
     * @param type $indicador
     * @return type
     * @throws Exception
     */
    public function registrarResponsable( $intIdIndEntidad, $indicador )
    {
        $dtaResponsable["indId_ri"]         = 0;
        $dtaResponsable["intIdIndEntidad"]  = $intIdIndEntidad;
        $dtaResponsable["intCodigo_ug"]     = $indicador->idUndGestion;
        $dtaResponsable["intCodigo_fnc"]    = $indicador->idResponsable;
        $dtaResponsable["dteFechaInicio_ri"]= date("Y-m-d H:i:s");
        
        if( !$this->save( $dtaResponsable ) ){
            throw new Exception( JText::_( 'COM_PROYECTOS_REGISTRO_ATRIBUTOS_INDICADOR' ) );
        }
        
        return $this->indId_ri;
    }
    
}