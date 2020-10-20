<?php

class CambiosVariable
{

    //  Datos del Inidador a gestionar
    private $_dtaVariable;

    public function __construct( $dtaVariable )
    {
        $this->_dtaVariable = clone $dtaVariable;
    }

    /**
     * 
     * Retorno una lista de Objetivos hijos de un determinado Objetivo
     * 
     * @param int $idEntidad    Identificador de la entidad que asocia un 
     *                          indicador con un determinado objetivo
     * 
     * @return int
     * 
     */
    private function _getLstPlnObjetivos( $idEntidad )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $tbPlan = new jTablePlanObjetivo( $db );
            $rst = $tbPlan->getPlnObjetivos( $idEntidad );

            $db->transactionCommit();

            return $rst;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    /**
     * 
     * @param type $idEntidad
     */
    public function updUGResponsableVar( $idEntidad )
    {
        $lstPln = $this->_getLstPlnObjetivos( $idEntidad );

        if( count( $lstPln ) ){
            foreach( $lstPln as $plan ){

                //  Obtengo una lista de variables asociados a un indicador
                $lstVpI = $this->_getLstVariablesPorIndicador( $plan->idIndicador );

                foreach( $lstVpI as $vpi ){
                    if( $this->_dtaVariable->idElemento == $vpi->idVariable ){
                        $this->_updUGResponsable( $vpi->idIndVariable );
                    }
                }

                $this->updUGResponsableVar( $plan->idEntidad );
            }
        }

        return;
    }

    private function _getLstVariablesPorIndicador( $idIndicador )
    {
        $db = JFactory::getDBO();

        $tbIV = new jTableIndicadorVariable( $db );
        $rst = $tbIV->getLstVariablesPorIndicador( $idIndicador );

        return $rst;
    }

    private function _updUGResponsable( $idIndVariable )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $tbUGR = new jTableVarUGResponsable( $db );
            $rst = $tbUGR->registrarUndGestionResponsable( $idIndVariable, $this->_dtaVariable );

            $db->transactionCommit();
            return $rst;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

    public function updFunResponsable( $idEntidad )
    {
        $lstPln = $this->_getLstPlnObjetivos( $idEntidad );

        if( count( $lstPln ) ){
            foreach( $lstPln as $plan ){
                //  Obtengo una lista de variables asociados a un indicador
                $lstVpI = $this->_getLstVariablesPorIndicador( $plan->idIndicador );

                foreach( $lstVpI as $vpi ){
                    if( $this->_dtaVariable->idElemento == $vpi->idVariable ){
                        $this->_updFunResponsable( $vpi->idIndVariable );
                    }
                }

                $this->updFunResponsable( $plan->idEntidad );
            }
        }

        return;
    }

    private function _updFunResponsable( $idIndVariable )
    {
        $db = JFactory::getDBO();

        try{
            $db->transactionStart();

            $tbVFR = new jTableVarFuncionarioResponsable( $db );
            $rst = $tbVFR->registrarFunResVariable( $idIndVariable, $this->_dtaVariable );

            $db->transactionCommit();

            return $rst;
        } catch( Exception $e ){
            // catch any database errors.
            $db->transactionRollback();
            JErrorPage::render( $e );
        }
    }

}
