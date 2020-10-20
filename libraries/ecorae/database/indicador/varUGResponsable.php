<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.tablenested');
 
/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__ind_variable_undGestion_responsable )
 * 
 */
class jTableVarUGResponsable extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct(    '#__ind_variable_undGestion_responsable', 
                                'intId_vugr', 
                                $db );
    }
    
    /**
     * 
     * Actualizo a cero "0", la vigencia de "una" Unidad de Gestion en "una" variable
     * 
     * @param type $idVUGR          Identificador Variable Unidad de Gestion Responsable
     * @param type $idUndGestion    Identificador de la Unidad de Gestion
     * 
     */
    private function _updVigenciaUndGestion(    $idVUGR, 
                                                $idUndGestion )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->update( '#__ind_variable_undGestion_responsable' );
            $query->set( 'intVigencia_vugr = 0' );
            $query->set( 'dteFechaFin_vugr = "'. date( "Y-m-d H:i:s" ) . '"' );
            $query->where( 'intId_vugr = '. $idVUGR );
            $query->where( 'intCodigo_ug = '. $idUndGestion );
            
            $db->setQuery( (string)$query );
            $db->query();

            return ( $db->getAffectedRows() >= 0 )  ? true
                                                    : false;

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
     * @param int       $idIndVariable      Identificador de Indicador - Entidad
     * @param object    $dtaVariable        datos de la variable a registrar
     * 
     */
    public function registrarUndGestionResponsable( $idIndVariable, $dtaVariable )
    {
        if( (int) $dtaVariable->idUGResponsable != (int) $dtaVariable->oldIdUGResponsable ){
            $this->_updVigenciaUndGestion( (int)$dtaVariable->idVUGR, 
                                            $dtaVariable->oldIdUGResponsable );

            $dtaUndGestionResponsable["intId_iv"]           = $idIndVariable;
            $dtaUndGestionResponsable["intCodigo_ug"]       = $dtaVariable->idUGResponsable;
            $dtaUndGestionResponsable["dteFechaInicio_vugr"]= date( 'Y-m-d' );
            $dtaUndGestionResponsable["intVigencia_vugr"]   = 1;

            if( !$this->save( $dtaUndGestionResponsable ) ){
                throw new Exception( JText::_( 'COM_PROYECTOS_REGISTRO_UNIDADGESTION_INDICADOR' ) );
                exit;
            }
        }

        return $this->intId_vugr;
    }
    
    
    /**
     * 
     * Gestiona la eliminacion las unidades de gestion responsables de las 
     * variables de un determinado indicador
     * 
     * @param int $idIndicador  Identificador del Indicador
     * 
     * @return int
     */
    public function deleteUGResponsable( $idIndicador )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->delete( '#__ind_variable_undGestion_responsable' );
            $query->where( 'intId_iv IN (   SELECT t2.intId_iv 
                                            FROM tb_ind_indicador_variables t2 
                                            WHERE t2.intCodigo_ind = '. $idIndicador .' )' );

            $db->setQuery( (string)$query );
            $db->query();

            return $db->getNumRows();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    public function __destruct()
    {
        return;
    }
    
}