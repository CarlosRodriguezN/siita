<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class jTableIndVariableIndicador extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__ind_indVariable_indicador', 'id_ivi', $db );
    }

    /**
     * 
     * Registro informacion de la relacion entre un Indicador y su correspondiente indicadorVariable
     * 
     * @param int $idIndVariable    Identificador Indicador Variable
     * @param int $idIndicador      Identificador de Indicador
     * 
     * @return int
     * 
     */
    public function registroIndVariable_Indicador( $idIndVariable, $idIndicador )
    {    
        $dtaIVI["intId_iv"]     = $idIndVariable;
        $dtaIVI["intCodigo_ind"]= $idIndicador;
        
        if( !$this->save( $dtaIVI ) ) {
            echo $this->getError(); 
            exit;
        }

        return $this->id_ivi;
    }
    
    
    
    
    private function _deleteIndVariableIndicador( $idIndVariable, $idIndicador )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->delete( '#__ind_indVariable_indicador' );
            $query->where( 'intId_iv = '. $idIndVariable );
            $query->where( 'intCodigo_ind = '. $idIndicador );

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