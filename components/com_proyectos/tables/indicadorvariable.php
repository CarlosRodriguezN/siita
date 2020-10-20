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
class ProyectosTableIndicadorVariable extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__ind_indicador_variables', 'intId_iv', $db );
    }

    /**
     * 
     * Gestiona la relacion Indicador Variable
     * 
     * @param type $variable        Informacion de una variable
     * @param type $idIndicador     Identificador del Indicador
     * @param type $idVariable      Identificador de la variable
     * 
     * @return type
     * 
     * @throws Exception
     * 
     */
    public function registroIndicadorVariable( $variable, $idIndicador, $idVariable )
    {
        if( $variable->published == 1 ){
            $dtaIndVariable["intId_iv"] = $variable->idIndVariable;
            $dtaIndVariable["intCodigo_ind"] = $idIndicador;
            $dtaIndVariable["intIdVariable_var"] = $idVariable;

            if( $variable->idIndVariable == 0 ) {
                $dtaIndVariable["dteFechaRegistro_iv"] = date( "Y-m-d H:i:s" );
            }

            $dtaIndVariable["dteFechaModificacion_iv"] = date( "Y-m-d H:i:s" );

            if( !$this->save( $dtaIndVariable ) ) {
                throw new Exception( JText::_( 'COM_PROYECTOS_REGISTRO_INDICADOR_VARIABLE' ) );
            }
        }else{
            $this->delete( $variable->idIndVariable );
        }

        return $this->intId_iv;
    }

    
    
    /**
     * 
     * Retorne una lista de Variables asociadas a un determinado Indicador
     * 
     * @param int $idIndicador     Identificador de Indicador
     * 
     * @return type
     * 
     */
    public function getLstIndVariables( $idIndicador )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intId_iv                 AS idIndVariable, 
                                t1.intCodigo_ind            AS idIndicador, 
                                t1.intIdVariable_var        AS idVariable,
                                t2.strNombre_var            AS nombre, 
                                t2.strDescripcion_var       AS descripcion, 
                                t3.intId_tum                AS idTpoUM,
                                t2.intCodigo_unimed         AS idUndMedida, 
                                t3.strDescripcion_unimed    AS undMedida,
                                t2.inpCodigo_unianl         AS idUndAnalisis,
                                t4.strDescripcion_unianl    AS undAnalisis,
                                1                           AS published' );
            $query->from( '#__ind_indicador_variables t1' );
            $query->join( 'INNER', '#__gen_variables t2 ON t1.intIdVariable_var = t2.intIdVariable_var' );
            $query->join( 'INNER', '#__gen_unidad_medida t3 ON t2.intCodigo_unimed = t3.intCodigo_unimed' );
            $query->join( 'INNER', '#__gen_unidad_analisis t4 ON t4.inpCodigo_unianl = t2.inpCodigo_unianl' );
            $query->where( 't1.intCodigo_ind = '. $idIndicador );
            
            $db->setQuery( (string)$query );
            $db->query();

            $lstIndVariables = ( $db->getNumRows() > 0 )? $db->loadObjectList()
                                                        : false;

            return $lstIndVariables;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
}