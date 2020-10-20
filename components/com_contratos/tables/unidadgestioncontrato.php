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
class contratosTableUnidadGestionContrato extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__ctr_ug_responsable', 'intId_ctrUGR', $db );
    }

    /**
     * 
     * Actualizo a cero "0", la vigencia de "una" Unidad de Gestion en "una" variable
     * 
     * @param type $idContrato    Identificador Indicador - Entidad
     * @param type $idUndGestion    Identificador de la Unidad de Gestion
     * 
     */
    private function _updVigenciaUndGestion( $idContrato, $idLastUndGestion )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->update( '#__ctr_ug_responsable' );
            $query->set( 'intVigencia_ctrUGR = 0' );
            $query->set( 'dteFechaFin_ctrUGR = "' . date( "Y-m-d H:i:s" ) . '"' );

            $query->where( 'intIdContrato_ctr = ' . $idContrato );
            $query->where( 'intCodigo_ug = ' . $idLastUndGestion );
            $query->where( 'intVigencia_ctrUGR = 1' );

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
     * Actualiza la fecha de unicio de la unidad de gestion de un contrato.
     * @param int $idContrato       Identificador del contrato.
     * @param int $idUndGestion     Identificador de la unidad de Gestión.
     * @param int $fchIniciUGR      Fecha de Inicio de la Unidad de Gestión.
     * @return type
     */
    private function _updFechaInicio( $idContrato, $idUndGestion, $fchIniciUGR )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->update( '#__ctr_ug_responsable' );
            $query->set( 'dteFechaInicio_ctrUGR = "' . $fchIniciUGR . '"' );

            $query->where( 'intIdContrato_ctr = ' . $idContrato );
            $query->where( 'intCodigo_ug = ' . $idUndGestion );
            $query->where( 'intVigencia_ctrUGR = 1' );
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
     *  Alamacena una nueva unidad de Gestion.
     * @param int $idContrato       Identificador del CONTRATO
     * @param int $idUndGestion     Identificador de la UNIDAD de GESTION.
     * @throws Exception
     */
    private function _saveNewUnidadGestion( $idContrato, $idUndGestion, $fchIniciUGR )
    {
        $dtaUndGestionResponsable["intId_ctrUGR"] = 0;
        $dtaUndGestionResponsable["intCodigo_ug"] = (int) $idUndGestion;
        $dtaUndGestionResponsable["intIdContrato_ctr"] = $idContrato;
        $dtaUndGestionResponsable["dteFechaInicio_ctrUGR"] = $fchIniciUGR;
        $dtaUndGestionResponsable["intVigencia_ctrUGR"] = 1;

        if( !$this->save( $dtaUndGestionResponsable ) ) {
            throw new Exception( JText::_( 'COM_CONTRATOS_REGISTRO_UNIDADGESTION_CONTRATO' ) );
        }
    }

    /**
     * 
     * Gestiona informacion de un funcionario responsable de un determinado
     * Indicador - Entidad
     * 
     * @param type $idContrato   Identificador de Indicador - Entidad
     * @param type $idUndGestion    Identificador de Unidad de Gestion
     * 
     */
    public function saveUnidadGestionContrato( $idContrato, $idUndGestion, $fchIniciUGR )
    {
        //var_dump('c='.$idContrato.'--u='.$idUndGestion);exit();
        $lastUGR = $this->getUnidadGestionContrato( $idContrato );
        if( $lastUGR ) {
            if( $idUndGestion != $lastUGR->idUnidadGestion ) {

                $this->_updVigenciaUndGestion( $idContrato, $lastUGR->idUnidadGestion );
                $this->_saveNewUnidadGestion( $idContrato, $idUndGestion, $fchIniciUGR );
            } else {
                $this->_updFechaInicio( $idContrato, $lastUGR->idUnidadGestion, $fchIniciUGR );
            }
        } else {
            $this->_saveNewUnidadGestion( $idContrato, $idUndGestion, $fchIniciUGR );
        }
        return $this->intId_ctrUGR;
    }

    /**
     * 
     * @param type $idContrato
     * @return type
     */
    public function getUnidadGestionContrato( $idContrato )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( '
                ugr.intId_ctrUGR            AS idCrtUGR,
                ugr.intCodigo_ug            AS idUnidadGestion,	
                ugr.dteFechaInicio_ctrUGR   AS fechaInicio,
                ugr.dteFechaFin_ctrUGR      AS fechaFin,
                ugr.intVigencia_ctrUGR      AS vigencia
                ' );
            $query->from( '#__ctr_ug_responsable AS ugr' );
            $query->where( 'ugr.intIdContrato_ctr = ' . $idContrato );
            $query->where( 'ugr.intVigencia_ctrUGR = 1' );
            //echo $query->__toString();exit();
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