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

class ProgramaTableIndicadorDimension extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_dimension_indicador', 'intId_dimInd', $db );
    }
    
    /**
     * 
     * Creo la relacion Indicador Dimension
     * 
     * @param array $dtaIndicadorDimension   Datos a registrar como son Id del Indicador y Dimension
     * @return int
     * 
     * @throws Exception
     */
    function crearRelacionIndDimension( $dtaIndicadorDimension )
    {
        if( !$this->save( $dtaIndicadorDimension ) ){
            throw new Exception( JText::_( 'COM_PROYECTOS_REGISTRO_INDICADORES' ) );
            exit;
        }
        
        return $this->intId_dimInd;
    }
}