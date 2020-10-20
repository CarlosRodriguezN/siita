<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.tablenested');
 
/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */

class jTableIndicadorDimension extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_dimension_indicador', 'intId_dimInd', $db );
    }

    /**
     * 
     * Gestiona el retorno de una lista de dimensiones asociadas a un determinado indicador
     * 
     * @param type $idIndicador     Identificador del Indicador
     * @return type
     * 
     */
    public function getLstDimensiones( $idIndicador )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intId_dimInd AS idDimIndicador,
                                t2.intId_enfoque AS idEnfoque,
                                t3.strNombre_enfoque AS enfoque,
                                t1.intId_dim AS idDimension,
                                t2.strDescripcion_dim AS dimension, 
                                1 AS published' );
            $query->from( '#__ind_dimension_indicador t1' );
            $query->join( 'INNER', '#__gen_dimension t2 ON t1.intId_dim = t2.intId_dim ' );
            $query->join( 'INNER', '#__gen_enfoque t3 ON t2.intId_enfoque = t3.intId_enfoque ' );
            $query->where( ' t1.intCodigo_ind = '. $idIndicador );

            $db->setQuery( (string)$query );
            $db->query();

            $dtaDimension = array();
            if( $db->getNumRows() > 0 ) {
                $dimensiones = $db->loadObjectList();

                foreach( $dimensiones as $key => $dimension ) {
                    $infoDim = $dimension;
                    $infoDim->idRegDimension = $key;
                    $dtaDimension[] = $infoDim;
                }
            }

            return $dtaDimension;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * 
     * Retorna las dimensiones asociadas a un Indicador de tipo plantilla
     * 
     * @param type $idIndPlantilla     Identificador del Indicador de tipo la plantilla
     * 
     */
    public function getLstDimIndPlantilla( $idIndPlantilla )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   0                       AS idDimIndicador,
                                t2.intId_enfoque        AS idEnfoque,
                                t3.strNombre_enfoque	AS enfoque,
                                t1.intId_dim            AS idDimension,
                                t2.strDescripcion_dim	AS dimension, 
                                1                       AS published' );
            $query->from( '#__ptlla_dimension_indicador t1' );
            $query->join( 'INNER', '#__gen_dimension t2 ON t1.intId_dim = t2.intId_dim ' );
            $query->join( 'INNER', '#__gen_enfoque t3 ON t2.intId_enfoque = t3.intId_enfoque ' );
            $query->where( ' t1.intId_pi = '. $idIndPlantilla );
            
            $db->setQuery( (string)$query );
            $db->query();

            $dtaDimension = ( $db->getNumRows() > 0 )   ? $db->loadObjectList()
                                                        : array();

            return $dtaDimension;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * 
     * Creo la relacion Indicador Dimension
     * 
     * @param array $dtaIndicadorDimension   Datos a registrar como son Id del Indicador y Dimension
     * @return int
     * 
     * @throws Exception
     */
    public function registrarRelacionIndDimension( $idIndicador, $dtaDimension, $ban )
    {
        if( $dtaDimension->published == 1 ){
            $dtaIndicadorDimension["intId_dimInd"]  = ( $ban == 0 ) 
                                                            ? $dtaDimension->idDimIndicador 
                                                            : 0;

            $dtaIndicadorDimension["intCodigo_ind"] = $idIndicador;
            $dtaIndicadorDimension["intId_dim"]     = $dtaDimension->idDimension;

            if( !$this->save( $dtaIndicadorDimension ) ){
                echo $this->getError();
                exit;
            }

        }else{
            $this->delete( $dtaDimension->idDimIndicador );
        }
        
        return $this->intId_dimInd;
    }
    
    /**
     * 
     * Elimino Dimensiones Asociadas a un indicador
     * 
     * @param type $idIndicador     Identificador del Indicador
     * 
     * @return type
     * 
     */
    public function deleteIndDimension( $idIndicador )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            //  Elimino dimensiones asociadas a un indicador
            $query->delete( '#__ind_dimension_indicador ' );
            $query->where( ' intCodigo_ind = '. $idIndicador );

            $db->setQuery( $query );
            $db->query();

            $retval = ( $db->getAffectedRows() >= 0 )   ? TRUE
                                                        : FALSE;

            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
}