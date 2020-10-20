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
class ProyectosTableResponsableProyecto extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__pry_funcionario_responsable', 'intId_pryFR', $db );
    }

    /**
     * 
     * Actualizo a cero "0", la vigencia de "una" Unidad de Gestion en "una" variable
     * 
     * @param type $idProyecto      Identificador Indicador - Entidad
     * @param type $idResponsable   Identificador de la Unidad de Gestion
     * 
     */
    private function _updVigenciaUndGestion( $idProyecto, $idUndGestion )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->update( '#__pry_funcionario_responsable' );
            $query->set( 'intVigencia_pryFR = 0' );
            $query->set( 'dteFechaFin_pryFR = "' . date( "Y-m-d H:i:s" ) . '"' );
            $query->where( 'intCodigo_pry = ' . $idProyecto );
            $query->where( 'intVigencia_pryFR = 1' );
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
     * @param int       $idProyecto     Identificador del Proyecto.
     * @param int       $idResponsable  Identificador del responsable.
     * @param String    $fchIniciRes    Fecha de Inicio de gestion.
     * @return type
     */
    private function _updFechaIncio( $idProyecto, $idResponsable, $fchIniciRes )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );
            
            $query->update( '#__pry_funcionario_responsable' );
            $query->set( 'dteFechaInicio_pryFR = "' . $fchIniciRes . '"' );
            $query->where( 'intCodigo_pry = ' . $idProyecto );
            $query->where( 'intId_ugf = ' . $idResponsable );
            $query->set( 'intVigencia_pryFR = 1' );

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
     * @param   int $idProyecto     Identificador del Proyecto.
     * @param   int $idResponsable  Identificador del responsable.
     * @throws Exception
     */
    private function _saveNewResponsableProyecto( $idReg, $idProyecto, $idResponsable, $fchIniciRes )
    {
        $dtaUndGestionResponsable["intId_pryFR"]            = $idReg;
        $dtaUndGestionResponsable["intCodigo_pry"]          = $idProyecto;
        $dtaUndGestionResponsable["intId_ugf"]              = $idResponsable;
        $dtaUndGestionResponsable["dteFechaInicio_pryFR"]   = $fchIniciRes;
        $dtaUndGestionResponsable["intVigencia_pryFR"]      = 1;

        if( !$this->save( $dtaUndGestionResponsable ) ) {
            throw new Exception( JText::_( 'COM_CONTRATOS_REGISTRO_UNIDADGESTION_CONTRATO' ) );
        }
    }

    /**
     * 
     * Gestiona informacion de un funcionario responsable de un determinado
     * Indicador - Entidad
     * 
     * @param type $idProyecto      Identificador de Indicador - Entidad
     * @param type $idResponsable   Identificador de Unidad de Gestion
     * 
     */
    public function saveResponsableProyecto( $idProyecto, $idResponsable, $fchIniciRes )
    {
        $lastRespo = $this->getResponsableProyecto( $idProyecto );
        $idReg = ( $lastRespo == FALSE )? 0 
                                        : $lastRespo->idProyectoResponsable; 
        
        if( $lastRespo && ( $idResponsable != $lastRespo->undGestionFuncionario ) ) {
            $idReg = 0;
            $this->_updVigenciaUndGestion( $idProyecto, $lastRespo->undGestionFuncionario );
        }
        
        $this->_saveNewResponsableProyecto( $idReg, $idProyecto, $idResponsable, $fchIniciRes );
        
        return $this->intId_pryUGR;
    }

    /**
     * Retorna los datos del responsable de un contrato.
     * @param type $idProyecto
     */
    public function getResponsableProyecto( $idProyecto )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( '   res.intId_pryFR             AS  idProyectoResponsable,
                                res.intId_ugf               AS  undGestionFuncionario,
                                ugf.intCodigo_ug            AS  idUnidadGestion,
                                ugf.intCodigo_fnc           AS  idFuncionario,
                                res.dteFechaInicio_pryFR    AS  fechaInicio,
                                res.dteFechaFin_pryFR       AS  fechaFin,
                                res.intVigencia_pryFR       AS  vigencia,
                                res.published               AS  published' );

            $query->from( '#__pry_funcionario_responsable AS res' );
            $query->join( 'inner', '#__gen_ug_funcionario AS ugf ON ugf.intId_ugf=res.intId_ugf' );
            $query->where( 'res.intCodigo_pry = ' . $idProyecto );
            $query->where( 'res.intVigencia_pryFR = 1' );

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