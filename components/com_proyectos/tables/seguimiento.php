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
class ProyectosTableSeguimiento extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__ind_seguimiento', 'intId_seg', $db );
    }

    /**
     * 
     * Gestiono el Registro o Actualizacion del seguimiento 
     * de una variable en un determinado Indicador
     * 
     * @param type $idIndicador     Identificador del Indicador
     * @param type $idVariable      Identificador de la variable
     * @param type $lstSeguimiento  Lista con informacion del seguimiento de una variable
     * 
     * @return boolean  
     * 
     * @throws Exception
     */
    public function registroSeguimiento( $seguimiento )
    {
        //  Seteo los valores correspondientes
        $dtaSeguimiento["intId_seg"] = $seguimiento->idSeguimiento;
        $dtaSeguimiento["intIdVariableIndicador_var"] = $seguimiento->idVariableSeguimiento;
        $dtaSeguimiento["dteFecha_seg"] = $seguimiento->fecha;
        $dtaSeguimiento["dcmValor_seg"] = $seguimiento->valor;
        
        if( $idIndVariable == 0 ){
            $dtaSeguimiento["dteFechaRegistro_seg"]   = date("Y-m-d H:i:s");
        }
        
        $dtaSeguimiento["dteFechaModificacion_seg"]= date("Y-m-d H:i:s");

        //  Gestiono el registro de planficacion
        if( !$this->save( $dtaSeguimiento ) ) {
            throw new Exception( JText::_( 'COM_PROYECTOS_REGISTRO_VARIABLEINDICADOR' ) );
        }
        
        return $this->intId_seg;
    }

    /**
     * 
     * Lista de Seguimiento de Variables relacionadas a un indicador 
     * en un determinado proyecto
     * 
     * @param type $idProyecto  Identificador del proyecto
     * @param type $idIndicador Identificador del Indicador
     * @param type $idVariable  Identificador de la variable
     * 
     * @return type
     */
    public function lstSeguimientoVariables( $idProyecto, $idIndicador, $idVariable )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intId_seg AS idSeguimiento, 
                                t1.intTpoSeguimiento AS tpoSeguimiento, 
                                t1.dteFecha_seg AS fecha, 
                                t1.dcmValor_seg AS valor' );
            $query->from( '#__ind_seguimiento t1' );
            $query->join( 'INNER', '#__ind_indicador t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
            $query->where( 't2.intCodigo_pry =' . $idProyecto );
            $query->where( 't1.intCodigo_ind =' . $idIndicador );
            $query->where( 't1.intCodigo_var =' . $idVariable );

            $db->setQuery( (string) $query );
            $db->query();

            $rstLstVariables = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : false;

            return $rstLstVariables;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}