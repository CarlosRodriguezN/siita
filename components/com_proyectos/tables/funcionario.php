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

class ProyectosTableFuncionario extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__gen_funcionario', 'intCodigo_fnc', $db );
    }
    
    /**
     * 
     * Retorna una lista de funcionarios
     * 
     * @param type $idUndGestion
     */
    public function getLstFuncionarios( $idUndGestion )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   intId_ugf AS id, 
                                CONCAT( t2.strApellido_fnc, " ", t2.strNombre_fnc ) AS nombre' );
            $query->from( '#__gen_ug_funcionario t1' );
            $query->join( '', '#__gen_funcionario t2 ON t1.intCodigo_fnc = t2.intCodigo_fnc' );
            $query->where( 't1.intCodigo_ug = '. $idUndGestion );

            $db->setQuery( (string)$query );
            $db->query();

            $rstFuncionarios = ( $db->getNumRows() > 0 )? $db->loadObjectList()
                                                        : FALSE;

            return $rstFuncionarios;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
}