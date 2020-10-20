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
class ProgramaTableResponsablePrograma extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__prg_funcionario_responsable', 'intId_prgFR', $db );
    }

    /**
     * 
     * Actualizo a cero "0", la vigencia de "una" Unidad de Gestion en "una" variable
     * 
     * @param type $idPrograma      Identificador Indicador - Entidad
     * @param type $idResponsable   Identificador de la Unidad de Gestion
     * 
     */
    private function _updVigenciaUndGestion( $idPrograma, $idResponsable )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->update( '#__prg_funcionario_responsable' );
            $query->set( 'intVigencia_prgFR = 0' );
            $query->set( 'dteFechaFin_prgFR = "' . date( "Y-m-d H:i:s" ) . '"' );
            $query->where( 'intCodigo_prg = ' . $idPrograma );
            $query->where( 'intId_ugf = ' . $idResponsable );
            $query->where( 'intVigencia_prgFR = 1' );
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
     * @param int       $idPrograma     Identificador del Programa.
     * @param int       $idUGf          Identificador de la unidad de gestion del funcionario
     * @param String    $fchIniciRes    Fecha de Inicio de gestion.
     * @return type
     */
    private function _updFechaIncio( $idPrograma, $idUGf, $fchIniciRes )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );
            
            $query->update( '#__prg_funcionario_responsable' );
            $query->set( 'dteFechaInicio_prgFR = "' . $fchIniciRes . '"' );
            $query->where( 'intCodigo_prg = ' . $idPrograma );
            $query->where( 'intId_ugf = ' . $idUGf );
            $query->set( 'intVigencia_prgFR = 1' );

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
     * @param   int $idPrograma     Identificador del Programa.
     * @param   int $idResponsable  Identificador del responsable.
     * @throws Exception
     */
    private function _saveNewResponsablePrograma( $idPrograma, $idResponsable, $fchIniciRes )
    {
        $dtaUndGestionResponsable["intId_prgFR"]            = 0;
        $dtaUndGestionResponsable["intCodigo_prg"]          = $idPrograma;
        $dtaUndGestionResponsable["intId_ugf"]              = $idResponsable;
        $dtaUndGestionResponsable["dteFechaInicio_prgFR"]   = $fchIniciRes;
        $dtaUndGestionResponsable["intVigencia_prgFR"]      = 1;

        if( !$this->save( $dtaUndGestionResponsable ) ) {
            throw new Exception( JText::_( 'COM_CONTRATOS_REGISTRO_UNIDADGESTION_CONTRATO' ) );
        }
    }

    /**
     * 
     * Gestiona informacion de un funcionario responsable de un determinado
     * Indicador - Entidad
     * 
     * @param type $idPrograma      Identificador de Indicador - Entidad
     * @param type $idResponsable   Identificador de Unidad de Gestion
     * 
     */
    public function saveResponsablePrograma( $idPrograma, $idResponsable, $fchIniciRes )
    {
        $lastRespo = $this->getResponsablePrograma( $idPrograma );
        if( $lastRespo ) {
            if( $idResponsable != $lastRespo->undGestionFuncionario ) {
                $this->_updVigenciaUndGestion( $idPrograma, $lastRespo->undGestionFuncionario );
                $this->_saveNewResponsablePrograma( $idPrograma, $idResponsable, $fchIniciRes );
            } else {
                $this->_updFechaIncio( $idPrograma, $lastRespo->undGestionFuncionario, $fchIniciRes );
            }
        } else {
            $this->_saveNewResponsablePrograma( $idPrograma, $idResponsable, $fchIniciRes );
        }
        return $this->intId_prgUGR;
    }

    /**
     * Retorna los datos del responsable de un contrato.
     * @param type $idPrograma
     */
    public function getResponsablePrograma( $idPrograma )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->select( '
                res.intId_prgFR             AS  idProgramaResponsable,
	 	res.intId_ugf               AS  undGestionFuncionario,
                ugf.intCodigo_ug            AS  idUnidadGestion,
                ugf.intCodigo_fnc           AS  idFuncionario,
	 	res.dteFechaInicio_prgFR    AS  fechaInicio,
	 	res.dteFechaFin_prgFR       AS  fechaFin,
	 	res.intVigencia_prgFR       AS  vigencia,
	 	res.published               AS  published
                ' );
            $query->from( '#__prg_funcionario_responsable AS res' );
            $query->join( 'inner', '#__gen_ug_funcionario AS ugf ON ugf.intId_ugf=res.intId_ugf' );
            $query->where( 'res.intCodigo_prg = ' . $idPrograma );
            $query->where( 'res.intVigencia_prgFR = 1' );
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