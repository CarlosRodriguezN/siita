<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla Programa ( #__prf_etapa )
 * 
 */

class ProyectosTablePrograma extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__pfr_programa', 'intCodigo_prg', $db );
    }
    
    
    public function lstProgramas()
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intCodigo_prg AS id, 
                                UPPER( t1.strNombre_prg ) AS nombre' );
            $query->from( '#__pfr_programa t1' );
            $query->where( 't1.published = 1' );
            $query->order( 't1.strNombre_prg' );

            $db->setQuery( (string)$query );
            $db->query();
            
            if( $db->getNumRows() > 0 ){
                $rst = $db->loadObjectList();
                $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_PROGRAMAS_TITLE' ) );
            }else{
                $rst = (object)array( '0' => JText::_( 'COM_PROYECTOS_SIN_REGISTROS' ) );
            }
            
            return $rst;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * 
     * Retorna una lista de SubProgramas
     * 
     * @param type $idPrograma  identificador de un programa
     * @return type
     * 
     */
    public function lstSubProgramas( $idPrograma )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intId_SubPrograma AS id, 
                                UPPER( t1.strDescripcion_sprg ) AS nombre ' );
            $query->from( '#__pfr_sub_programa t1' );
            $query->where( 't1.intCodigo_prg = '. $idPrograma );

            $db->setQuery( (string)$query );
            $db->query();
            
            if( $db->getNumRows() > 0 ){
                $rst = $db->loadObjectList();
                $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_SUBPROGRAMAS_TITLE' ) );
            }else{
                $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_PROYECTOS_SIN_REGISTROS' ) );
            }

            return $rst;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * Gestiona el retorno de una lista de tipos de subProgramas asociados 
     * a un determinado SubPrograma
     * 
     * @param type $idSubPrograma   Identificador de SubPrograma
     * 
     * @return type
     * 
     */
    public function lstTiposSubProgramas( $idSubPrograma )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intId_tsprg AS id, 
                                UPPER( t1.strDescripcion_tsprg ) AS nombre' );
            $query->from( '#__pfr_tipo_sub_programa t1' );
            $query->where( 't1.intId_SubPrograma = '. $idSubPrograma );

            $db->setQuery( (string)$query );
            $db->query();

            if( $db->getNumRows() > 0 ){
                $rst = $db->loadObjectList();
                $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_TPOSUBPROGRAMAS_TITLE' ) );
            }else{
                $rst = (object)array( '0' => JText::_( 'COM_PROYECTOS_SIN_REGISTROS' ) );
            }
            
            return $rst;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    
    
    
}