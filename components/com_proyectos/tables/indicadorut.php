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

class ProyectosTableIndicadorUT extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_indicador_uterritorial', 'intID_ut', $db );
    }
    
    /**
     * 
     * Registro Unidades Territoriales que estan asociadas a un determinado indicador
     * 
     * @param type $idIndEntidad            Identificador de la entidad
     * @param type $lstUndTerritoriales     Lista de Unidades Territoriales asociadas a un indicador
     */
    public function registroIndicadorUndTerritorial( $idIndEntidad, $lstUndTerritoriales )
    {
        try {
            $retval = true;
            
            if( $this->_delRegUndTerritorial( $idIndEntidad ) ){
                $db = JFactory::getDBO();
                $query = $db->getQuery( true );
                $sql = "INSERT INTO tb_ind_indicador_uterritorial (intID_ut, intIdIndEntidad) VALUES ";

                foreach( $lstUndTerritoriales as $undTerritorial ){
                    $sql .= "( ". $undTerritorial->idUndTerritorial .", ". $idIndEntidad ." ),";
                }

                $db->setQuery( rtrim( $sql, ',' ) );
                $db->query();

                $retval = ( $db->getAffectedRows() > 0 )? TRUE
                                                        : FALSE;
            }
            
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Elimino los registros de unidades territoriales asociadas a un determinado indicador
     * 
     * @param type $idIndEntidad    Identificador del Indicador entidad
     * @return type
     */
    private function _delRegUndTerritorial( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );
            
            //  Elimino el registro de unidades territoriales anteriores
            $query->delete( '#__ind_indicador_uterritorial' );
            $query->where( 'intIdIndEntidad = '. $idIndEntidad );
            
            $db->setQuery( $query );
            $db->query();
            
            $retval = ( $db->getAffectedRows() >= 0 )   ? TRUE
                                                        : FALSE;
            
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}