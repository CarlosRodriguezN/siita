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

class ProyectosTableSupuesto extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__gen_supuestos', 'intIdSupuesto_spto', $db );
    }

    /**
     * 
     * Retorna informacion de lista de supuestos de un determinado marco logico
     * 
     * @param type $idObjML Identificador de un marco logico
     * @return type
     */
    public function dtaSupuestos( $idObjML )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intIdSupuesto_spto   AS idSupuesto, 
                                t1.published            AS published,
                                t1.strDescripcion_spto  AS descSupuesto' );
            $query->from( '#__gen_supuestos t1' );
            $query->where( 't1.intIdObjeto_oml = '. $idObjML );
            $query->where( 't1.published = 1' );
            $query->order( 't1.intIdSupuesto_spto' );
            $db->setQuery( (string)$query );
            $db->query();

            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList()
                                                : FALSE;

            return $result;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    
    /**
     * 
     * Registro un supuesto de determinado Marco Logico independientemente 
     * del tipo marco logico
     * 
     * @param type $dtaSupuesto     Datos informativos de un marco logico
     * 
     * @return boolean
     * @throws Exception
     * 
     */
    public function registrarSupuesto( $dtaSupuesto )
    {
        if( !$this->save( $dtaSupuesto ) ){
            throw new Exception( 'Error al Registrar Supuesto de un MarcoLogico' );
        }

        return true;
    }
    
    
    
    
    /**
     * 
     * Gestiono la eliminacion de supuesto (s)
     * perteneciente a un determinado marco logico
     * 
     * @param type $idML    Identificador de Marco Logico
     * 
     * @return type
     */
    public function eliminarSupuesto( $idML )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->delete( '#__gen_supuestos' );
            $query->where( 'intIdObjeto_oml = '. $idML );
            
            $db->setQuery( (string)$query );
            $db->query();

            $rst = ( $db->getAffectedRows() >= 0 )  ? TRUE 
                                                    : FALSE;

             return $rst;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    
    
}