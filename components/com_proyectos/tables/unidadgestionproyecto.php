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
class ProyectosTableUnidadGestionProyecto extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__pry_ug_responsable', 'intId_pryUGR', $db );
    }

    /**
     * 
     * Actualizo a cero "0", la vigencia de "una" Unidad de Gestion en "una" variable
     * 
     * @param type $idProyecto    Identificador Indicador - Entidad
     * @param type $idUndGestion    Identificador de la Unidad de Gestion
     * 
     */
    private function _updVigenciaUndGestion( $idProyecto, $idLastUndGestion )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->update( '#__pry_ug_responsable' );
            $query->set( 'intVigencia_pryUGR = 0' );
            $query->set( 'dteFechaFin_pryUGR = "' . date( "Y-m-d H:i:s" ) . '"' );

            $query->where( 'intCodigo_pry = ' . $idProyecto );
            $query->where( 'intCodigo_ug = ' . $idLastUndGestion );
            $query->where( 'intVigencia_pryUGR = 1' );

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
     * @param int $idProyecto       Identificador del contrato.
     * @param int $idUndGestion     Identificador de la unidad de Gestión.
     * @param int $fchIniciUGR      Fecha de Inicio de la Unidad de Gestión.
     * @return type
     */
    private function _updFechaInicio( $idProyecto, $idUndGestion, $fchIniciUGR )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->update( '#__pry_ug_responsable' );
            $query->set( 'dteFechaInicio_pryUGR = "' . $fchIniciUGR . '"' );
            $query->where( 'intCodigo_pry = ' . $idProyecto );
            $query->where( 'intCodigo_ug = ' . $idUndGestion );
            $query->where( 'intVigencia_pryUGR = 1' );

            $db->setQuery( (string) $query );
            $db->query();

            return ( $db->getAffectedRows() >= 0 )  ? true 
                                                    : false;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *  Alamacena una nueva unidad de Gestion.
     * @param int $idProyecto       Identificador del CONTRATO
     * @param int $idUndGestion     Identificador de la UNIDAD de GESTION.
     * @throws Exception
     */
    private function _saveNewUnidadGestion( $idReg, $idProyecto, $idUndGestion, $fchIniciUGR )
    {
        $dtaUndGestionResponsable["intId_pryUGR"]           = $idReg;
        $dtaUndGestionResponsable["intCodigo_ug"]           = (int) $idUndGestion;
        $dtaUndGestionResponsable["intCodigo_pry"]          = $idProyecto;
        $dtaUndGestionResponsable["dteFechaInicio_pryUGR"]  = $fchIniciUGR;
        $dtaUndGestionResponsable["intVigencia_pryUGR"]     = 1;

        if( !$this->save( $dtaUndGestionResponsable ) ) {
            throw new Exception( JText::_( 'COM_CONTRATOS_REGISTRO_UNIDADGESTION_CONTRATO' ) );
        }
    }

    /**
     * 
     * Gestiona informacion de un funcionario responsable de un determinado
     * Indicador - Entidad
     * 
     * @param type $idProyecto   Identificador de Indicador - Entidad
     * @param type $idUndGestion    Identificador de Unidad de Gestion
     * 
     */
    public function saveUnidadGestionProyecto( $idProyecto, $idUndGestion, $fchIniciUGR )
    {
        $lastUGR = $this->getUnidadGestionProyecto( $idProyecto );
        $idReg = ( $lastUGR == false )  ? 0 
                                        : $lastUGR->idCrtUGR;
                
        if( $lastUGR && ( $idUndGestion != $lastUGR->idUnidadGestion ) ) {
            $idReg = 0;
            $this->_updVigenciaUndGestion( $idProyecto, $lastUGR->idUnidadGestion );
        }

        $this->_saveNewUnidadGestion( $idReg, $idProyecto, $idUndGestion, $fchIniciUGR );

        return $this->intId_pryUGR;
    }

    /**
     * 
     * @param type $idProyecto
     */
    public function getUnidadGestionProyecto( $idProyecto )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( '   ugr.intId_pryUGR            AS idCrtUGR,
                                ugr.intCodigo_ug            AS idUnidadGestion,	
                                ugr.dteFechaInicio_pryUGR   AS fechaInicio,
                                ugr.dteFechaFin_pryUGR      AS fechaFin,
                                ugr.intVigencia_pryUGR      AS vigencia' );
            $query->from( '#__pry_ug_responsable AS ugr' );
            $query->where( 'ugr.intCodigo_pry = ' . $idProyecto );
            $query->where( 'ugr.intVigencia_pryUGR = 1' );

            $db->setQuery( $query );
            $db->query();

            $retval = ($db->loadObject())   ? $db->loadObject() 
                                            : false;
            
            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_contratos.table.tiposcontratista.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}