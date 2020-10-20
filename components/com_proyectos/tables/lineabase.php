<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de Indicadores
 * 
 */

class ProyectosTableLineaBase extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_linea_base', 'intCodigo_lbind', $db );
    }
    
    /**
     * 
     * Retorno una lista de instituciones
     * 
     * @return type
     */
    public function getLstInstituciones()
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   intCodigo_ins AS key, 
                                strNombre_ins AS value' );
            $query->from( '#__gen_institucion' );
            $query->where( 'published = 1' );

            $db->setQuery( (string)$query );
            $db->query();

            $rst = ( $db->getNumRows() > 0 )? $db->loadObjectList()
                                            : false;

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
     * Gestion de Lineas Base
     * 
     * @param type $idFuente
     * @return type
     * 
     */
    public function getLineasBase( $idFuente )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intCodigo_lbind AS id, 
                                UPPER( t1.strDescripcion_lbind ) AS nombre, 
                                t1.dcmValor_lbind AS valor' );
            $query->from( '#__ind_linea_base t1' );
            $query->where( 't1.intCodigo_fuente = '. $idFuente );
            $query->where( 'published = 1' );
            $query->order( 'strDescripcion_lbind' );

            $db->setQuery( (string)$query );
            $db->query();

            if( $db->getNumRows() > 0 ){
                $rst = $db->loadObjectList();
                $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_LINEABASE_TITLE' ) );
            }else{
                $rst = array();
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
     * Gestiono el registro de lineas base de un deteminado indicador
     * 
     * @param type $idIndicador     Identificador del indicador
     * @param type $dtaLineaBase    Datos de la linea base a registrar
     * 
     */
    public function registroLineaBase( $idIndicador, $dtaLineaBase )
    {
        //  Borro las lineas base de un determinado indicador
        if( (int)$this->_delLineasBase( $idIndicador ) >= 0 )
        {
            //  Registro las nuevas lineas base pertenecientes a un determinado indicador
            $this->_registroLineasBase( $idIndicador, $dtaLineaBase );
        }
    }
    
    /**
     * 
     * Elimino las lineas base de un determinado indicador
     * 
     * @param type $idIndicador     Identificador de un determinado indicador
     * 
     * @return type
     * 
     */
    private function _delLineasBase( $idIndicador )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->delete( '#__ind_linea_base_indicador ' );
            $query->where( ' intCodigo_ind = '. $idIndicador );
            
            $db->setQuery( (string)$query );
            $db->query();

            $ban = ( $db->getAffectedRows() >= 0 )  ? TRUE 
                                                    : FALSE;

            return $ban;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Gestion de Registro de Lineas Base asociadas a un determinado indicador
     * 
     * @param type $idIndicador     Identificador del Indicador
     * @param type $dtaLineaBase    Datos de Linea Base
     * @return type
     */
    private function _registroLineasBase( $idIndicador, $dtaLineaBase )
    {
        try {
            $db = JFactory::getDBO();
            
            $sql = 'INSERT INTO #__ind_linea_base_indicador( intCodigo_lbind, intCodigo_ind ) VALUES';
            
            foreach( $dtaLineaBase as $lineaBase ){
                $sql .= '( '. $lineaBase->idLineaBase .', '. $idIndicador .' ),';
            }
            
            $db->setQuery( rtrim( $sql, ',' ) );
            $db->query();
            
            return $db->getAffectedRows();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Retorna una lista de lineas base relacionadas a un determinado indicador
     * 
     * @param type $idIndicador     Identificador de Indicador
     * 
     * @return type
     * 
     */
    public function getLstLineasBase( $idIndicador )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intCodigo_lbind AS idLineaBase,
                                t2.strDescripcion_lbind AS nombre,
                                t2.dcmValor_lbind AS valor,
                                t3.intCodigo_fuente AS idFuente,
                                t3.strObservacion_fuente AS fuente' );
            $query->from( '#__ind_linea_base_indicador t1' );
            $query->join( 'INNER', '#__ind_linea_base t2 ON t1.intCodigo_lbind = t2.intCodigo_lbind' );
            $query->join( 'INNER', '#__ind_fuente t3 ON t2.intCodigo_fuente = t3.intCodigo_fuente' );
            $query->where( 't1.intCodigo_ind = '. $idIndicador );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $lstLB = ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList()
                                                    : false;
            
            return $lstLB;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
}