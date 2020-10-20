<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla MarcoLogico ( #__prf_etapa )
 * 
 */

class ProyectosTableMarcoLogico extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__pfr_objeto_ml', 'intIdObjeto_oml', $db );
    }
    
    
    public function datosMarcoLogico( $idProyecto )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intIdObjeto_oml          AS idObjML, 
                                t1.intIdTipoObjetoML_toml   AS idTpoML, 
                                t1.intIdPadre_oml           AS idPadre, 
                                t1.strNombre_oml            AS nombre, 
                                t1.strDescripcion_oml       AS descripcion' );

            $query->from( '#__pfr_objeto_ml t1' );            
            $query->where( 't1.intCodigo_pry = '. $idProyecto );
            $query->order( 't1.intIdObjeto_oml' );
            
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
     * Gestiona el registro de un marco logico
     * 
     * @param type $dtaMarcoLogico  Datos de Marco Logico a registrar
     * 
     * @return boolean
     * @throws Exception
     */
    public function registrarMarcoLogico( $dtaMarcoLogico )
    {
        if( !$this->save( $dtaMarcoLogico ) ){
            throw new Exception( 'Error al Registrar Marco Logico de un Proyecto' );
        }

        return $this->intIdObjeto_oml;
    }
    
    
    /**
     * 
     * Gestiono la eliminacion de un marco logico
     * 
     * @param type $idMLogico   Identificacion de un marco logico
     * @return boolean
     * @throws Exception
     */
    public function eliminarMarcoLogico( $idMLogico )
    {
        if( !$this->delete( $idMLogico ) ){
            throw new Exception( 'Error al Eliminar Marco Logico de un Proyecto' );
        }

        return true;
    }
    
    /**
     * 
     * Elimino una Actividad relacionada a un marcoLogico
     * 
     * @param type $idML    Identificador de marcoLogico
     */
    public function deleteActividad( $idML )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->delete( '#__pfr_objeto_ml' );
            $query->where( 'intIdPadre_oml = '. $idML );
            
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