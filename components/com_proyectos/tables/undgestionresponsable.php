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

class ProyectosTableUndGestionResponsable extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_ug_responsable', 'intId_ugr', $db );
    }
    
    
    /**
     * 
     * Retorno el identificador de la Unidad de Gestion responsable de un 
     * determinado indicador
     * 
     * @param type $idIndEntidad    Identificador del Indicador Entidad
     * 
     */
    private function _getUndGestionIndicador( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 't1.intCodigo_ug AS idUndGestion' );
            $query->from( '#__ind_ug_responsable t1 ' );
            $query->where( 't1.intIdIndEntidad = '. $idIndEntidad );
            
            $db->setQuery( (string)$query );
            $db->query();

            $idUndGestion = ( $db->getNumRows() > 0 ) ? $db->loadObject()->idUndGestion
                                                        : FALSE;
            return $idUndGestion;

        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Actualizo a cero "0", la vigencia de "una" Unidad de Gestion en "un" indicador
     * 
     * @param type $idIndEntidad    Identificador Indicador - Entidad
     * @param type $idUndGestion    Identificador de la Unidad de Gestion
     * 
     */
    private function _updVigenciaUndGestion( $idIndEntidad, $idUndGestion )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->update( '#__ind_ug_responsable' );
            $query->set( 'inpVigencia_ugr = 0' );
            $query->set( 'dteFechaFin_ugr = '. date( "Y-m-d H:i:s" ) );
            $query->where( 'intIdIndEntidad = '. $idIndEntidad );
            $query->where( 'intCodigo_ug = '. $idUndGestion );
            
            $db->setQuery( (string)$query );
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
     * Gestiona informacion de un funcionario responsable de un determinado
     * Indicador - Entidad
     * 
     * @param type $idRegIndEntidad     Identificador de Indicador - Entidad
     * @param type $idUndGestion        Identificador de Unidad de Gestion
     * 
     */
    public function registrarUndGestionResponsable( $idRegIndEntidad, $idUndGestion )
    {
        if( $idUndGestion != $this->_getUndGestionIndicador( $idRegIndEntidad ) ){
            if( $this->_updVigenciaUndGestion( $idRegIndEntidad, $idUndGestion ) ){
                $dtaUndGestionResponsable["intId_ugf"] = 0;
                $dtaUndGestionResponsable["intIdIndEntidad"] = $idRegIndEntidad;
                $dtaUndGestionResponsable["intCodigo_ug"] = $idUndGestion;
                $dtaUndGestionResponsable["dteFechaInicio_ugr"] = date( "Y-m-d H:i:s" );
                $dtaUndGestionResponsable["inpVigencia_ugr"] = 1;

                if( !$this->save( $dtaUndGestionResponsable ) ) {
                    throw new Exception( JText::_( 'COM_PROYECTOS_REGISTRO_UNIDADGESTION_INDICADOR' ) );
                }
                
            }
        }
        
        return $this->intId_fgr;
    }

}