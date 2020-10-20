<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class ContratosTableResponsableContrato extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__ctr_funcionario_responsable', 'intId_ctrFR', $db );
    }

    /**
     * 
     * Actualizo a cero "0", la vigencia de "una" Unidad de Gestion en "una" variable
     * 
     * @param type $idContrato      Identificador Indicador - Entidad
     * @param type $idResponsable   Identificador de la Unidad de Gestion
     * 
     */
    private function _updVigenciaUndGestion( $idContrato, $idUndGestion )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->update( '#__ctr_funcionario_responsable' );
            $query->set( 'intVigencia_ctrFR = 0' );
            $query->set( 'dteFechaFin_ctrFR = "' . date( "Y-m-d H:i:s" ) . '"' );
            $query->where( 'intIdContrato_ctr = ' . $idContrato );
            $query->where( 'intVigencia_ctrFR = 1' );
            $query->where( 'intId_ugf = ' . $idUndGestion );

            $db->setQuery( (string) $query );
            $db->query();

            return ( $db->getAffectedRows() >= 0 ) ? true : false;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * @param int       $idContrato     Identificador del Contrato.
     * @param int       $idResponsable  Identificador del responsable.
     * @param String    $fchIniciRes    Fecha de Inicio de gestion.
     * @return type
     */
    private function _updFechaIncio( $idContrato, $idResponsable, $fchIniciRes )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );
            
            $query->update( '#__ctr_funcionario_responsable' );
            $query->set( 'dteFechaInicio_ctrFR = "' . $fchIniciRes . '"' );
            $query->where( 'intIdContrato_ctr = ' . $idContrato );
            $query->where( 'intId_ugf = ' . $idResponsable );
            $query->set( 'intVigencia_ctrFR = 1' );

            $db->setQuery( (string) $query );
            $db->query();

            return ( $db->getAffectedRows() >= 0 ) ? true : false;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * @param   int $idContrato     Identificador del Contrato.
     * @param   int $idResponsable  Identificador del responsable.
     * @throws Exception
     */
    private function _saveNewResponsableContrato( $idContrato, $idResponsable, $fchIniciRes )
    {
        $dtaUndGestionResponsable["intId_ctrFR"] = 0;
        $dtaUndGestionResponsable["intIdContrato_ctr"] = $idContrato;
        $dtaUndGestionResponsable["intId_ugf"] = $idResponsable;
        $dtaUndGestionResponsable["dteFechaInicio_ctrFR"] = $fchIniciRes;
        $dtaUndGestionResponsable["intVigencia_ctrFR"] = 1;

        if( !$this->save( $dtaUndGestionResponsable ) ) {
            throw new Exception( JText::_( 'COM_CONTRATOS_REGISTRO_UNIDADGESTION_CONTRATO' ) );
        }
    }

    /**
     * 
     * Gestiona informacion de un funcionario responsable de un determinado
     * Indicador - Entidad
     * 
     * @param type $idContrato      Identificador de Indicador - Entidad
     * @param type $idResponsable   Identificador de Unidad de Gestion
     * 
     */
    public function saveResponsableContrato( $idContrato, $idResponsable, $fchIniciRes )
    {
        $lastRespo = $this->getResponsableContrato( $idContrato );
        if( $lastRespo ) {
            if( $idResponsable != $lastRespo->undGestionFuncionario ) {
                $this->_updVigenciaUndGestion( $idContrato, $lastRespo->undGestionFuncionario );
                $this->_saveNewResponsableContrato( $idContrato, $idResponsable, $fchIniciRes );
            } else {
                $this->_updFechaIncio( $idContrato, $lastRespo->undGestionFuncionario, $fchIniciRes );
            }
        } else {
            $this->_saveNewResponsableContrato( $idContrato, $idResponsable, $fchIniciRes );
        }
        return $this->intId_ctrUGR;
    }

    /**
     * Retorna los datos del responsable de un contrato.
     * @param type $idContrato
     */
    public function getResponsableContrato( $idContrato )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( '
                res.intId_ctrFR             AS  idContratoResponsable,
	 	res.intId_ugf               AS  undGestionFuncionario,
                ugf.intCodigo_ug            AS  idUnidadGestion,
                ugf.intCodigo_fnc           AS  idFuncionario,
	 	res.dteFechaInicio_ctrFR    AS  fechaInicio,
	 	res.dteFechaFin_ctrFR       AS  fechaFin,
	 	res.intVigencia_ctrFR       AS  vigencia,
	 	res.published               AS  published
                ' );
            $query->from( '#__ctr_funcionario_responsable AS res' );
            $query->join( 'inner', '#__gen_ug_funcionario AS ugf ON ugf.intId_ugf=res.intId_ugf' );
            $query->where( 'res.intIdContrato_ctr = ' . $idContrato );
            $query->where( 'res.intVigencia_ctrFR = 1' );
            $db->setQuery( $query );
            $db->query();
            $retval = ($db->loadObject()) ? $db->loadObject() : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_contratos.table.tiposcontratista.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}