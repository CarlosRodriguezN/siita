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
class ProgramaTableUnidadGestionPrograma extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__prg_ug_responsable', 'intId_prgUGR', $db );
    }

    /**
     * 
     * Actualizo a cero "0", la vigencia de "una" Unidad de Gestion en "una" variable
     * 
     * @param type $idPrograma    Identificador Indicador - Entidad
     * @param type $idUndGestion    Identificador de la Unidad de Gestion
     * 
     */
    private function _updVigenciaUndGestion( $idPrograma, $idLastUndGestion )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->update( '#__prg_ug_responsable' );
            $query->set( 'intVigencia_prgUGR = 0' );
            $query->set( 'dteFechaFin_prgUGR = "' . date( "Y-m-d H:i:s" ) . '"' );

            $query->where( 'intCodigo_prg = ' . $idPrograma );
            $query->where( 'intCodigo_ug = ' . $idLastUndGestion );
            $query->where( 'intVigencia_prgUGR = 1' );

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
     * @param int $idPrograma       Identificador del contrato.
     * @param int $idUndGestion     Identificador de la unidad de Gestión.
     * @param int $fchIniciUGR      Fecha de Inicio de la Unidad de Gestión.
     * @return type
     */
    private function _updFechaInicio( $idPrograma, $idUndGestion, $fchIniciUGR )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->update( '#__prg_ug_responsable' );
            $query->set( 'dteFechaInicio_prgUGR = "' . $fchIniciUGR . '"' );

            $query->where( 'intCodigo_prg = ' . $idPrograma );
            $query->where( 'intCodigo_ug = ' . $idUndGestion );
            $query->where( 'intVigencia_prgUGR = 1' );
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
     * @param int $idPrograma       Identificador del CONTRATO
     * @param int $idUndGestion     Identificador de la UNIDAD de GESTION.
     * @throws Exception
     */
    private function _saveNewUnidadGestion( $idPrograma, $idUndGestion, $fchIniciUGR )
    {
        $dtaUndGestionResponsable["intId_prgUGR"]           = 0;
        $dtaUndGestionResponsable["intCodigo_ug"]           = (int) $idUndGestion;
        $dtaUndGestionResponsable["intCodigo_prg"]          = (int) $idPrograma;
        $dtaUndGestionResponsable["dteFechaInicio_prgUGR"]  = $fchIniciUGR;
        $dtaUndGestionResponsable["intVigencia_prgUGR"]     = 1;

        if( !$this->save( $dtaUndGestionResponsable ) ) {
            throw new Exception( JText::_( 'COM_CONTRATOS_REGISTRO_UNIDADGESTION_CONTRATO' ) );
        }
        return $this->intId_prgUGR;
    }

    /**
     * 
     * Gestiona informacion de un funcionario responsable de un determinado
     * Indicador - Entidad
     * 
     * @param type $idPrograma   Identificador de Indicador - Entidad
     * @param type $idUndGestion    Identificador de Unidad de Gestion
     * 
     */
    public function saveUnidadGestionPrograma( $idPrograma, $idUndGestion, $fchIniciUGR )
    {
        $lastUGR = $this->getUnidadGestionPrograma( $idPrograma );
        if( $lastUGR ) {
            if( $idUndGestion != $lastUGR->idUnidadGestion ) {
                $this->_updVigenciaUndGestion( $idPrograma, $lastUGR->idUnidadGestion );
                $this->_saveNewUnidadGestion( $idPrograma, $idUndGestion, $fchIniciUGR );
            } else {
                $this->_updFechaInicio( $idPrograma, $lastUGR->idUnidadGestion, $fchIniciUGR );
            }
        } else {
            $this->_saveNewUnidadGestion( $idPrograma, $idUndGestion, $fchIniciUGR );
        }
        return $this->intId_prgUGR;
    }

    /**
     * 
     * @param type $idPrograma
     */
    public function getUnidadGestionPrograma( $idPrograma )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( '
                ugr.intId_prgUGR            AS idPrgUGR,
                ugr.intCodigo_ug            AS idUnidadGestion,	
                ugr.dteFechaInicio_prgUGR   AS fechaInicio,
                ugr.dteFechaFin_prgUGR      AS fechaFin,
                ugr.intVigencia_prgUGR      AS vigencia
                ' );
            $query->from( '#__prg_ug_responsable AS ugr' );
            $query->where( 'ugr.intCodigo_prg = ' . $idPrograma );
            $query->where( 'ugr.intVigencia_prgUGR = 1 LIMIT 1' );
            $db->setQuery( $query );
            $db->query();
            $retval = ($db->getAffectedRows() > 0) ? $db->loadObject() : false;
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_contratos.table.tiposcontratista.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}