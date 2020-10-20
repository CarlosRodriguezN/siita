<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class jTableAccFncResponsable extends JTable
{

    /**
     *  Constructor
     *
     * @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__pln_funcionario_responsable', 'intId_plnFR', $db );
    }

    /**
     *  Rejistra un funcionario responsable de una acción
     * @param type      $objAcc     Objeto accion 
     * @return type
     */
    public function registroFunRes( $accion )
    {
        $accFR["intId_plnFR"]           = 0;
        $accFR["intId_plnAccion"]       = $accion->idAccion;
        $accFR["intId_ugf"]             = $accion->idFunResp;
        $accFR["dteFechaInicio_plnFR"]  = $accion->fechaInicioFR;
        $accFR["intVigencia_plnFR"]     = 1;
        $accFR["published"]             = 1;

        if( !$this->save( $accFR ) ) {
            echo $this->getError();
            exit;
        }

        return $this->intId_plnFR;
    }

    /**
     *  Actualiza el responsable titular/vigente de una acción
     * @param type $idAccion  Id de la relación entre acción y funcionario responsable
     * @return type
     */
    public function updAvalibleFunRes( $idAccion )
    {
        $db = & JFactory::getDBO();
        try {
            $db->transactionStart();
            
            $query = $db->getQuery( TRUE );
            
            $query->update( "#__pln_funcionario_responsable" );
            $query->set( 'dteFechaFin_plnFR ="' . DATE( 'Y-m-d' ) . '"' );
            $query->set( 'intVigencia_plnFR = 0' );
            $query->where( "intId_plnAccion=" . $idAccion );
            
            $db->setQuery( $query );

            $result = ($db->query())? true 
                                    : false;
            
            $db->transactionCommit();
            
            return $result;
        } catch(Exception $e) {
            // catch any database errors.
            $db->transactionRollback();

            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );

            JErrorPage::render( $e );
        }
    }

    /**
     *  Actualiza la informacion de resposavilidad de un funcionario sobre una accion
     * @param type $idAccionFR          Id de la relación entre acción y funcionario responsable
     * @param type $fecha               Fecha de la responsabilidad del funcionario
     * @return type
     */
    public function updDateFunRes( $idAccionFR, $fecha )
    {
        $db = & JFactory::getDBO();
        
        try {
            $db->transactionStart();
            
            $query = $db->getQuery( TRUE );
            $query->update( "#__pln_funcionario_responsable" );
            $query->set( 'dteFechaInicio_plnFR="' . $fecha . '"' );
            $query->where( "intId_plnFR=" . $idAccionFR );
            $db->setQuery( $query );

            $result = ($db->query())? true 
                                    : false;

            $db->transactionCommit();
            
            return $result;
        } catch(Exception $e) {
            // catch any database errors.
            $db->transactionRollback();
            
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_pei.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            
            JErrorPage::render($e);
        }
    }

    /**
     *  Devuelve el Id actual de un funcionario responsable de una acción
     * @param type $idAccFR     Id de la ralacion entre funcionario y acción
     * @return type
     */
    public function getActualFR( $idAccFR )
    {
        $db = & JFactory::getDBO();
        $query = $db->getQuery( TRUE );

        $query->select( "intId_ugf              AS idFunc,
                        dteFechaInicio_plnFR    AS fechaInicio" );
        $query->from( "#__pln_funcionario_responsable" );
        $query->where( "intId_plnFR=" . $idAccFR );
        $db->setQuery( $query );

        $result = ($db->query())? $db->loadObject() 
                                : array();

        return $result;            
    }

}