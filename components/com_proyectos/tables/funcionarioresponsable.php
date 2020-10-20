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

class ProyectosTableFuncionarioResponsable extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_funcionario_responsable', 'intId_fgr', $db );
    }
    
    
    /**
     * 
     * Retorno el identificador del funcionario responsable de un determinado indicador
     * 
     * @param type $idIndEntidad
     * 
     */
    private function _getFuncionarioIndicador( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 't1.intId_ugf AS idFuncionario' );
            $query->from( '#__ind_funcionario_responsable t1 ' );
            $query->where( 't1.intIdIndEntidad = '. $idIndEntidad );
            
            $db->setQuery( (string)$query );
            $db->query();

            $dtaIndicadores = ( $db->getNumRows() > 0 ) ? $db->loadObject()->idFuncionario
                                                        : FALSE;
            return $dtaIndicadores;

        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Actualizo a cero "0", la vigencia de "un" funcionario en "un" indicador
     * 
     * @param type $idIndEntidad    Identificador Indicador - Entidad
     * @param type $idFuncionario   Identificador del Funcionario
     */
    private function _updVigenciaFuncionario( $idIndEntidad, $idFuncionario )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->update( '#__ind_funcionario_responsable' );
            $query->set( 'intVigencia_fgr = 0' );
            $query->set( 'dteFechaFin_fgr = '. date( "Y-m-d H:i:s" ) );
            $query->where( 'intIdIndEntidad = '. $idIndEntidad );
            $query->where( 'intCodigo_fnc = '. $idFuncionario );

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
     * @param type $idFuncionario       Identificador Funcinario
     * 
     */
    public function registrarFuncionarioResponsable( $idRegIndEntidad, $idFuncionario )
    {
        if( $idFuncionario != $this->_getFuncionarioIndicador( $idRegIndEntidad ) )
        {
            if( $this->_updVigenciaFuncionario( $idRegIndEntidad, $idFuncionario ) ){
                
                $dtaIndVariable["intId_fgr"]            = 0;
                $dtaIndVariable["intId_ugf"]            = $idFuncionario;
                $dtaIndVariable["intIdIndEntidad"]      = $idRegIndEntidad;
                $dtaIndVariable["dteFechaInicio_fgr"]   = date( "Y-m-d H:i:s" );
                $dtaIndVariable["intVigencia_fgr"]      = 1;

                if( !$this->save( $dtaIndVariable ) ) {
                    throw new Exception( JText::_( 'COM_PROYECTOS_REGISTRO_FUNCIONARIO_INDICADOR' ) );
                }
            }
        }
        
        return $this->intId_fgr;
    }

}