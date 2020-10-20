<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */

class ProyectosTableCanastaProyecto extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__cp_propuesta', 'intIdPropuesta_cp', $db );
    }
    
    /**
     * 
     * Asigno los proyectos registrados en la canasta de proyectos a un determinado proyecto
     * 
     * @param type $idProyecto      Identificador del proyecto
     * @param type $lstPropuestas   Lista de propuestas de proyecto
     * 
     */
    public function asignarPropuesta( $idProyecto, $lstPropuestas )
    {
        try {
            
            if( $this->_deletePropuestas( $idProyecto ) ){
                $db = JFactory::getDBO();
                $query = '  INSERT INTO #__cp_propuesta_proyecto( intIdPropuesta_cp, intCodigo_pry ) 
                            VALUES'; 

                foreach( $lstPropuestas as $propuesta ){
                    $query .= '( '. $propuesta .','. $idProyecto .' ),';
                }
                
                $db->setQuery( rtrim( $query, ',' ) );
                $db->query();

                return $db->getAffectedRows();
            }else{
                return true;
            }
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
            
            return false;
        }
    }
    
    /**
     * 
     * Elimino el registro de  las propuestas anteriores
     * 
     * @param type $idProyecto  Identificador del proyecto
     * 
     * @return boolean
     * 
     */
    private function _deletePropuestas( $idProyecto )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            
            $query->delete( '#__cp_propuesta_proyecto' );
            $query->where( 'intCodigo_pry = '. $idProyecto );
            
            $db->setQuery( $query );
            $db->query();
            
            $retval = ( $db->getAffectedRows() >= 0 )   ? true 
                                                        : false;
            
            return $retval;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
            
            return false;
        }
    }
    

    /**
     * 
     * Retorna informacion en formato JSon de una lista de proyectos
     * 
     * La informacion esta agrupada por nombre de proyecto, 
     * la suma total de cada proyecto, y suma de la cantidad de beneficiarios 
     * de cada una de las propuesta de cada proyecto
     * 
     * @param type $lstPropuestas   Lista con identificadores de cada propuesta de proyecto
     * 
     * @return boolean
     * 
     */
    public function dtaGeneralPropuesta( $lstPropuestas )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   GROUP_CONCAT( t1.strNombre_cp ) AS nombrePropuesta, 
                                SUM( t1.dcmMonto_cp ) AS monto,
                                SUM( t1.intNumeroBeneficiarios ) AS numBeneficiarios' );
            $query->from( '#__cp_propuesta as t1' );
            $query->where( 't1.intIdPropuesta_cp IN ( '. implode( $lstPropuestas, ',' ) .' )' );
            
            $db->setQuery( (string)$query );
            $db->query();

            if( $db->getNumRows() > 0 ){
                
                $dtaPry = $db->loadObject();
                
                //  En caso que sea un nuevo proyecto registro una nueva entidad, 
                //  y el identificador de esta nueva entidad
                $dtaProyecto['intCodigo_pry']           = 0;
                $dtaProyecto['inpCodigo_cb']            = 0;
                $dtaProyecto['idPrograma']              = 0;
                $dtaProyecto['idSubPrograma']           = 0;
                $dtaProyecto['idTpoSubPrograma']        = 0;
                $dtaProyecto['inpCodigo_subsec']        = 0;
                $dtaProyecto['strNombre_pry']           = $dtaPry->nombrePropuesta; 
                $dtaProyecto['strDescripcion_pry']      = $dtaPry->nombrePropuesta; 
                $dtaProyecto['strCodigoInterno_pry']    = ''; 
                $dtaProyecto['dteFechaInicio_stmdoPry'] = ''; 
                $dtaProyecto['dteFechaFin_stmdoPry']    = ''; 
                $dtaProyecto['inpDuracion_stmdoPry']    = 0;
                $dtaProyecto['intcodigo_unimed']        = 0;
                $dtaProyecto['strcup_pry']              = ''; 
                $dtaProyecto['strCargoResponsable_pry'] = ''; 
                $dtaProyecto['dcmMonto_total_stmdoPry'] = floatval( $dtaPry->monto );
            }
            
            return json_encode( (object)$dtaProyecto );
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
            
            return false;
        }
    }
    
    /**
     * 
     * Retorno una lista de alineaciones al PNBV de una lista de propuestas
     * 
     * @param type $lstPropuestas
     */
    public function dtaPNBV( $lstPropuestas )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   DISTINCT idCodigo_mn as idMetaNacional' );
            $query->from( '#__cp_propuesta AS t1' );
            $query->join( 'INNER', '#__cp_propuesta_pnbv AS t2 ON t1.intIdPropuesta_cp = t2.intIdPropuesta_cp' );
            $query->where( 't1.intIdPropuesta_cp IN ( '. implode( $lstPropuestas, ',' ) .' )' );
            
            $db->setQuery( (string)$query );
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? json_encode( $db->loadObjectList() )
                                                : json_encode( array() );
            
            return $retval;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
            
            return false;
        }

        return;
    }
    
    /**
     * 
     * Obtengo informacion de unidad terrotorial de una canasta de proyectos
     *  
     * @param type $lstPropuestas   Lista de Propuestas de proyecto
     * @return boolean
     * 
     */
    public function dtaUndTerritorial( $lstPropuestas )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   DISTINCT intID_ut as idUndTerritorial' );
            $query->from( '#__cp_ubicgeo AS t1' );
            $query->join( 'INNER', '#__cp_propuesta AS t2 ON t1.intIdPropuesta_cp = t2.intIdPropuesta_cp' );
            $query->where( 't1.intIdPropuesta_cp IN ( '. implode( $lstPropuestas, ',' ) .' )' );
                        
            $db->setQuery( (string)$query );
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? json_encode( $db->loadObjectList() )
                                                : FALSE;

            return $retval;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
            
            return false;
        }
    }
    
    /**
     * 
     * Retorna las coordenadas de una lista de propuestas
     * 
     * @param type $lstPropuestas   Lista de propuestas de proyecto
     * @return boolean
     *  
     */
    public function dtaUndCoordenadas( $lstPropuestas )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intId_tg, 
                                t1.strDescripcionGrafico_gcp,
                                t1.intId_gcp,
                                t4.fltLatitud_cord,
                                t4.fltLongitud_cord' );
            $query->from( '#__cp_grafico_propuesta t1' );
            $query->join( 'INNER', '#__gen_tipo_grafico t3 ON t1.intId_tg = t3.intId_tg' );
            $query->join( 'INNER', '#__cp_coordenadas_grafico t4 ON t1.intId_gcp = t4.intId_gcp' );
            $query->where( 't1.intIdPropuesta_cp IN ( '. implode( $lstPropuestas, ',' ) .' )' );

            $db->setQuery( (string)$query );
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? json_encode( $db->loadObjectList() )
                                                : array();

            return $retval;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
            
            return false;
        }
    }

}