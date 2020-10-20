<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla Imagenes
 * 
 */

class ContratosTableObjetivo extends JTable
{
    /**
    *   Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db )
    {
        parent::__construct( '#__crt_objetivo_contrato', 'intID_objcrt', $db );
    }
    
    /**
     * 
     * Retorno una lista de objetivos de un determinado proyecto
     * 
     * @param type $idEntidad  Identificador del Proyecto
     * @return type
     */
    public function getIndicadoresObjetivos( $idEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t2.intId_objInd_crt         AS idOIP,
                                obj.intID_objcrt            AS idObjetivo,
                                t2.intIdIndEntidad          AS idIndEntidad,
                                obj.strDescripcion_objpry   AS descObjetivo' );
            $query->from( '#__crt_objetivo_contrato as obj' );
            $query->join( 'INNER', '#__crt_crt_objetivos_indicador t2 ON obj.intID_objcrt = t2.intID_objcrt' );
            $query->where( 't2.intIdentidad_ent = '. $idEntidad );
            $query->order( 'obj.intcodigotipo_tpoobj' );
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
     * Gestiono el Registro de un Objetivo de un determinado Proyecto
     * 
     * @param array $objetivo   datos del objetivo de un proyecto a registrar 
     * 
     * @return boolean
     * 
     * @throws Exception
     * 
     */
    public function registroObjetivoContrato( $objetivo )
    {
        if( !$this->save( $objetivo ) ){
            throw new Exception( 'Error al Registrar Objetivo de un Proyecto' );
        }

        return $this->intID_objcrt;
    }
    
    /**
     * 
     * Elimina un determinado objetivo de un proyecto
     * 
     * @param type $idObjetivo  Identificador del Objetivo a eliminar
     * 
     * @return boolean
     * @throws Exception
     */
    public function eliminarObjetivoProyecto( $idObjetivo )
    {
        if( !$this->delete( $idObjetivo ) ){
            throw new Exception( 'Error al eliminar el Objetivo'. $idObjetivo );
        }
        
        return true;
    }
    
    
    /**
     * 
     * Obtengo Lista de Objetivos que pertenecen a una determinada entidad
     * 
     * @param type $idEntidad   Identificador entidad ( proyecto )
     * @return type
     */
    public function getObjetivosContrato($idEntidad){
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

           
            $query->select('   t1.intId_objInd_crt          AS idOIP,
                               t2.intID_objcrt              AS idObjetivo,
                               t2.strDescripcion_objcrt     AS descObjetivo');
            $query->from('#__crt_crt_objetivos_indicador AS t1');
            $query->join('INNER', '#__crt_objetivo_contrato AS t2 ON t1.intID_objcrt = t2.intID_objcrt');
            $query->where( 't1.intIdEnt_ent=' . $idEntidad );
            $query->where('t1.intIdIndEntidad = 0');
            $db->setQuery( (string)$query );
            $db->query();

            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList()
                                                : array();

            return $result;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
}