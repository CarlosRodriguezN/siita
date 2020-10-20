<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.tablenested' );

/**
 * 
 * Clase que gestiona informacion de Indicadores
 * 
 */
class jTableGrupoIndicador extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__ind_grupo', 'intId_gpo', $db );
    }

    public function getDtaGpoIndicador( $idIndicador )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   GROUP_CONCAT( IF( ta.idGpoPadre = 1, ta.idGpo, NULL ) )  AS idGpoDimension,
                                GROUP_CONCAT( IF( ta.idGpoPadre = 2, ta.idGpo, NULL ) )  AS idGpoDecision' );
            $query->from( '( SELECT   t1.intCodigo_ind AS idIndicador,
                                    t1.intId_gpo AS idGpo, 
                                    t2.intId_gpo_padre AS idGpoPadre
                            FROM tb_ind_grupo_indicador t1
                            JOIN tb_ind_grupo t2 ON t1.intId_gpo = t2.intId_gpo
                            WHERE t1.intCodigo_ind = ' . $idIndicador . ' ) ta' );

            $db->setQuery( ( string ) $query );
            $db->query();

            $ban = ( $db->getAffectedRows() >= 0 ) ? $db->loadObject() : FALSE;

            return $ban;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Gestiono el registro de lineas base de un deteminado indicador
     * 
     * @param type $idIndicador     Identificador del indicador
     * @param type $dtaLineaBase    Datos de la linea base a registrar
     * 
     */
    public function registroGrupoIndicador( $idIndicador, $idGpoDimension, $idGpoDecision )
    {
        if( !empty( $idGpoDimension ) && !empty( $idGpoDecision ) ){
            //  Elimino los grupos pertenecientes a un determinado indicador
            if( ( int ) $this->_delGrupoIndicador( $idIndicador ) >= 0 ){
                //  Registro los nuevos grupos pertenecientes a un determinado indicador
                $this->_registroGrupoIndicador( $idIndicador, $idGpoDimension, $idGpoDecision );
            }
        }

        return;
    }

    /**
     * 
     * Elimino las lineas base de un determinado indicador
     * 
     * @param type $idIndicador     Identificador de un determinado indicador
     * 
     * @return type
     * 
     */
    private function _delGrupoIndicador( $idIndicador )
    {
        $db = JFactory::getDBO();
        try{
            $db->transactionStart();
            $query = $db->getQuery( true );

            $query->delete( '#__ind_grupo_indicador ' );
            $query->where( ' intCodigo_ind = ' . $idIndicador );

            $db->setQuery( ( string ) $query );
            $db->query();

            $ban = ( $db->getAffectedRows() >= 0 )  ? TRUE 
                                                    : FALSE;

            $db->transactionCommit();
            return $ban;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            
            JErrorPage::render($e);
        }
    }

    /**
     * 
     * Gestion de Registro de Lineas Base asociadas a un determinado indicador
     * 
     * @param type $idIndicador     Identificador del Indicador
     * @param type $dtaLineaBase    Datos de Linea Base
     * @return type
     */
    private function _registroGrupoIndicador( $idIndicador, $idGpoDimension, $idGpoDecision )
    {
        $db = JFactory::getDBO();
        try{
            $db->transactionStart();
            $sql = 'INSERT INTO #__ind_grupo_indicador ( intId_gpo , intCodigo_ind ) VALUES';

            $sql .= '   ( ' . $idGpoDimension . ', ' . $idIndicador . ' ), 
                        ( ' . $idGpoDecision . ', ' . $idIndicador . ' )';

            $db->setQuery( rtrim( $sql, ',' ) );
            $db->query();

            $db->transactionCommit();
            return $db->getAffectedRows();
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            
            JErrorPage::render($e);
        }
    }

}
