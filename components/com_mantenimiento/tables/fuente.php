<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla categoria ( #__categoria )
 * 
 */
class MantenimientoTableFuente extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_fuente', 'intCodigo_fuente', $db );
    }

    public function registrarFuente( $data )
    {
         if( !$this->save( $data ) ){
            echo 'error - guardar fuente';
            exit;
        }
        return $this->intCodigo_fuente;
    }
    
    /**
     *  Retorna la data de una fuente en espesifico
     * @param type $id      Id de la fuente
     * @return type
     */
    public function getFuente( $id )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select("f.intCodigo_fuente      AS idFuente,
                            f.intCodigo_ug          AS idUG,
                            f.strObservacion_fuente AS descFuente,
                            f.published");
            $query->from("#__ind_fuente f");
            $query->where("f.intCodigo_fuente = {$id}");
            $db->setQuery($query);

            $result = ( $db->query() ) ? $db->loadObject() : array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.fuente.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}