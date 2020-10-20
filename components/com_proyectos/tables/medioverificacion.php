<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__gen_medio_verificacion )
 * 
 */
class ProyectosTableMedioVerificacion extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__gen_medio_verificacion', 'intIdMedVer_mver', $db );
    }
    
    
    
    
    /**
     * 
     * Retorno una lista de medios de verificacion de un determinado Marco Logico
     * 
     * @param type $idObjML Identificador del marco logico
     * 
     * @return type
     * 
     */
    public function dtaMedioVerificacion( $idObjML )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intIdMedVer_mver     AS idMedVerificacion, 
                                t1.strDescripcion_mver  AS descMV,
                                t1.published            AS published
                                ' );
            $query->from('#__gen_medio_verificacion t1');
            $query->where('t1.intIdObjeto_oml = ' . $idObjML);
            $query->where('t1.published = 1');
            $query->order('t1.intIdMedVer_mver');
            
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
    public function registrarMedioVerificacion( $dtaMedVerificacion )
    {
        if( !$this->save( $dtaMedVerificacion ) ){
            throw new Exception( 'Error al Registrar Medio de Verificacion de un MarcoLogico' );
        }

        return true;
    }
    
    
    
    /**
     * 
     * Gestiono la eliminacion de un medio de verificacion 
     * perteneciente a un determinado marco logico
     * 
     * @param type $idML    Identificador de Marco Logico
     * 
     * @return type
     */
    public function eliminarMedVerificacion( $idML )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->delete( '#__gen_medio_verificacion' );
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