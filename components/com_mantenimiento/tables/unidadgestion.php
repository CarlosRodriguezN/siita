<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

/**
 * 
 * Clase que gestiona informacion de la tabla categoria ( #__categoria )
 * 
 */
class MantenimientoTableUnidadGestion extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gen_unidad_gestion', 'intCodigo_ug', $db );
    }

    public function getLstUnidadesGestion() {
        
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intCodigo_ug AS idUG,
                            strNombre_ug AS nombreUG");
            $query->from( "#__gen_unidad_gestion" );
            $query->where("published = 1" );
            
            $db->setQuery($query);
            $db->query();
            $result = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_funcionarios.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Ejecuta la sentencia a las base de datos para retornar la lista de 
     *  opciones adicionales de la unidad de gestion
     * @param type $idUG
     * @return type
     */
    public function getOpAdd( $idUG )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("strOpAdd_ug");
            $query->from( "#__gen_unidad_gestion" );
            $query->where("intCodigo_ug = {$idUG}" );
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 && $db->loadResult() != null ) ? $db->loadResult() : json_encode(array());
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_funcionarios.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function getDataUG( $id )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intCodigo_ug, intCodigo_ug, tb_intCodigo_ug, intCodigo_ins, strNombre_ug, strAlias_ug");
            $query->from( "#__gen_unidad_gestion" );
            $query->where("intCodigo_ug = {$id}" );
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 && $db->loadResult() != null ) ? $db->loadObject() : array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_funcionarios.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
}