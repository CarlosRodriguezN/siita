<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla Funcionario Responsable ( tb_ind_funcionario_responsable )
 * 
 */

class jTableVarFuncionarioResponsable extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_variable_funcionario_responsable', 'intId_vfr', $db );
    }
    
    
    /**
     * 
     * Retorno el identificador del funcionario responsable de una 
     * determinada variable
     * 
     * @param type $idIndVariable   Identificador Indicador - Variable
     * 
     */
    private function _getFuncionarioVariable( $idIndVariable )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 't1.intId_ugf AS idFuncionario' );
            $query->from( '#__ind_variable_funcionario_responsable t1 ' );
            $query->where( 't1.intId_iv = '. $idIndVariable );

            $db->setQuery( (string)$query );
            $db->query();
            
            $dtaIndicadores = ( $db->getNumRows() > 0 ) ? $db->loadObject()->idFuncionario
                                                        : 0;
            return $dtaIndicadores;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Actualizo a cero "0", la vigencia de "un" funcionario en "una" variable
     * 
     * @param type $idVarFR         Identificador Variable funcionario - responsable
     * @param type $idFuncionario   Identificador del Funcionario
     */
    private function _updVigenciaFuncionario( $idVarFR, $idFuncionario )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->update( '#__ind_variable_funcionario_responsable' );
            $query->set( 'intVigencia_vfr = 0' );
            $query->set( 'dteFechaFin_vfr = "'. date( "Y-m-d H:i:s" ) .'"' );

            $query->where( 'intId_vfr = '. $idVarFR );
            $query->where( 'intId_ugf = '. $idFuncionario );

            $db->setQuery( (string)$query );
            $db->query();

            return ( $db->getAffectedRows() >= 0 )  ? TRUE
                                                    : FALSE;

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
     * @param object    $variable           Informacion de la variable a gestionar
     * 
     */
    public function registrarFunResVariable( $idIndVariable, $variable )
    {
        if( (int)$variable->idFunResponsable != (int)$variable->oldIdFunResponsable ){            
            $this->_updVigenciaFuncionario( $variable->idVFR, $variable->oldIdFunResponsable );
            
            $dtaIndVariable["intId_iv"]             = $idIndVariable;
            $dtaIndVariable["intId_ugf"]            = $variable->idFunResponsable;
            $dtaIndVariable["dteFechaInicio_vfr"]   = date( 'Y-m-d' );
            $dtaIndVariable["intVigencia_vfr"]      = 1;

            if( !$this->save( $dtaIndVariable ) ) {
                throw new Exception( $this->getError() );
            }

        }

        return $this->intId_vfr;
    }
    
    
    /**
     * 
     * Gestiono la eliminacion de Funcionarios responsables asociados a las 
     * variables que pertenecen a un denterminado indicador
     * 
     * @param int $idIndicador  identificador de un Indicador
     * 
     * @return int
     * 
     */
    public function deleteFuncionarioResponsable( $idIndicador )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->delete( '#__ind_variable_funcionario_responsable' );
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