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

class ProyectosTableVariable extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__gen_variables', 'intIdVariable_var', $db );
    }
    
    /**
     * 
     * Verifico la existencia de una variable asociada a un determinado indicador - entidad
     * 
     * @param type $idIndEntidad    Identificador Indicador - Entidad
     * 
     * @return type
     * 
     */
    public function existeVariable( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( 'COUNT( t1.intIdVariableIndicador_var ) AS cantidad' );
            $query->from( '#__ind_variables_indicador t1' );
            $query->where( 't1.intIdIndEntidad = '. $idIndEntidad );
            
            $db->setQuery( (string)$query );
            $db->query();

            $existe = ( (int)$db->loadObject()->cantidad > 0 )  ? true
                                                                : false;

            return $existe;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    

    /**
     * 
     * Gestiono el registro de una variable
     * 
     * @param type $variable    datos de la variable a registrar
     * 
     * @return type
     * @throws Exception
     */
    public function registroVariable( $variable )
    {
        if( $variable->published == 1 ){
            $dtaVariable["intIdVariable_var"]   = $variable->idVariable;
            $dtaVariable["strNombre_var"]       = $variable->nombre;
            $dtaVariable["strDescripcion_var"]  = $variable->descripcion;
            $dtaVariable["intCodigo_unimed"]    = $variable->idUndMedida;
            $dtaVariable["inpCodigo_unianl"]    = $variable->idUndAnalisis;
            
            if( $variable->idVariable == 0 ){
                $dtaVariable["dteFechaRegistro_var"]  = date("Y-m-d H:i:s");
            }else{
                $dtaVariable["dteFechaModificacion_var"]  = date("Y-m-d H:i:s");
            }
            
            if( !$this->save( $dtaVariable ) ){
                throw new Exception( JText::_( 'COM_PROYECTOS_REGISTRO_VARIABLE' ) );
            }
            
            $idVariable = $this->intIdVariable_var;
        }else{
            $this->delete( $variable->idVariable );
            
            $idVariable = $variable->idVariable;
        }
        
        return $idVariable;
    }
    
    /**
     * 
     * Gestiono Informacion de Variables Filtradas por Unidad de Medida
     * 
     * @param type $idVarUM     Unidad de Medida
     * @return type
     */
    public function getVariablesPorUndMedida( $idUM )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intIdVariable_var AS id, 
                                t1.strNombre_var AS nombre ' );
            $query->from( '#__gen_variables t1 ' );
            $query->where( ' t1.intCodigo_unimed = '. $idUM .' ' );
            $query->order( ' t1.strDescripcion_var' );
            
            $db->setQuery( (string)$query );
            $db->query();

            if( $db->getNumRows() > 0 ){
                $rst = $db->loadObjectList();
                $rst[] = (object)array( 'id' => -1, 'nombre' => JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_OTRAVARIABLE_TITLE' ) );
                $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_OTRAVARIABLE' ) );
            }else{
                $rst = (object)array( '0' => JText::_( 'COM_PROYECTOS_SIN_REGISTROS' ) );
            }
            
            return $rst;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
}