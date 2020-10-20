<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

require_once JPATH_BASE . DS . 'administrator' . DS .'components' . DS . 'com_funcionarios' . DS . 'helpers' . DS . 'funcionarios.php';

/**
 * 
 * Clase que gestiona informacion de la tabla categoria ( #__categoria )
 * 
 */
class FuncionariosTableUnidadGestion extends JTable
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
            $lstGruposUsr = JFactory::getUser()->groups; 
            $canDo = FuncionariosHelper::getActions();
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intCodigo_ug AS idUG,
                            strNombre_ug AS nombreUG");
            $query->from( "#__gen_unidad_gestion" );
            $query->where( "(published = 1 OR ( published = 0 AND intCodigo_ug = 0 ))" );
            
            if ( !$canDo->get('core.create') && !$canDo->get('core.edit') && !$canDo->get('core.delete')  ) {
                $query->where( '(intIdGrupo_ug IN ( '. implode( ", ", $lstGruposUsr ) .' ))' );
            }
            $db->setQuery($query);
            
//            var_dump($query->__toString()); exit;
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
     *  Retorna la lista de cargos relacionados a una unidad de gestion
     * @param type $idUG
     * @return type
     */
    public function getCargosUG( $idUG )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("intId_ugc As id,"
                    . "strDescripcion_cargo As nombre");
            $query->from( "#__gen_ug_cargo" );
            $query->where("intCodigo_ug = {$idUG}" );
            $query->where("published = 1" );
            $db->setQuery($query);
            $db->query();
            
            $result = ( $db->getAffectedRows() > 0 && $db->loadResult() != null ) ? $db->loadObjectList() : array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_funcionarios.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
}