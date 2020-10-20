<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.tablenested');
 
/**
 * 
 * Clase que gestiona informacion de registro de indicador ( #__prf_etapa )
 * 
 */

class jTableIndicadorEntidad extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_indicador_entidad', 'intIdIndEntidad', $db );
    }
    
    /**
     * 
     * Registro informacion de Indicador Entidad
     * 
     * @param type $dtaIndicadorEntidad     Datos de registro de un indicador Entidad
     * 
     * @return type
     * @throws Exception
     */
    public function registrarIndicadorEntidad( $dtaIndicadorEntidad )
    {
        if( !$this->save( $dtaIndicadorEntidad ) ){
            echo $this->getError(); 
            exit;
        }

        return $this->intIdIndEntidad;
    }
    
    /**
     * 
     * Retorna informacion especifica de un indicador
     * 
     * @param type $idIndEntidad    Identificador del indicador entidad
     * @return type
     */
    public function getDtaIndicadorEntidad( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intCodigo_ind                AS idIndicador,
                                t1.dcmValor_ind                 AS umbral, 
                                t1.dteHorizonteFchInicio_indEnt AS fchHorzMimimo, 
                                t1.dteHorizonteFchFin_indEnt    AS fchHorzMaximo,
                                t2.intCodigo_ug                 AS idUGResponsable,
                                t3.intId_ugf                    AS idFuncionario' );
            $query->from( '#__ind_indicador_entidad t1' );
            $query->join( 'INNER', '#__ind_ug_responsable t2 ON t1.intIdIndEntidad = t2.intIdIndEntidad AND t2.inpVigencia_ugr = 1' );
            $query->join( 'INNER', '#__ind_funcionario_responsable t3 ON t1.intIdIndEntidad = t3.intIdIndEntidad AND t3.intVigencia_fgr = 1' );
            $query->where( 't1.intIdIndEntidad = '. $idIndEntidad );
            
            $db->setQuery( (string)$query );
            $db->query();

            $dtaIndEntidad = ( $db->getNumRows() )  ? $db->loadObject() 
                                                    : FALSE;

            return $dtaIndEntidad;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorna una lista de Indicadores Entidad
     * 
     * @param type $idIndEntidad    Identificador Indicador Entidad
     * @return type
     * 
     */
    public function lstIndEntidad( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intIdIndEntidad              AS idIndEntidad,
                                t1.dcmValor_ind                 AS meta, 
                                t1.dteHorizonteFchInicio_indEnt AS fchInicio, 
                                t1.dteHorizonteFchFin_indEnt    AS fchFin,
                                t2.intIdentidad_ent             AS idEntidad,
                                t3.intId_tpoPlan                AS tpoPlan' );
            $query->from( 'tb_ind_indicador_entidad t1' );
            $query->join( 'INNER', 'tb_pln_plan_objetivo t2 ON t1.intIdentidad_ent = t2.intIdentidad_ent' );
            $query->join( 'INNER', 'tb_pln_plan_institucion t3 ON t2.intId_pi = t3.intId_pi' );
            $query->where( 't1.intIdIndEntidad = '. $idIndEntidad, 'OR' );
            $query->where( 't1.intIdIEPadre_indEnt = '. $idIndEntidad );
            
            $db->setQuery( (string)$query );
            $db->query();

            $dtaIndEntidad = ( $db->getNumRows() > 0 )  ? $db->loadObjectList() 
                                                        : array();

            return $dtaIndEntidad;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    
    /**
     * 
     * Retorna una lista de indicadores Hijos de un determinado Indicador Padre
     * 
     * @param type $idIndEntidadPadre   Identificador del indicador Padre
     * 
     * @return type
     * 
     */
    public function getLstIndEntidadHijos( $idIndEntidadPadre )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intIdIndEntidad	AS idIndEntidad, 
                                t1.intCodigo_ind	AS idIndicador' );
            $query->from( ' #__ind_indicador_entidad t1' );
            $query->join( 'INNER', '#__pln_plan_objetivo t2 ON t1.intIdentidad_ent = t2.intIdentidad_ent' );
            $query->join( 'INNER', '#__pln_plan_institucion t3 ON t2.intId_pi = t3.intId_pi' );
            $query->where( 't1.intIdIEPadre_indEnt = '. $idIndEntidadPadre );
            $query->where( 'YEAR( t3.dteFechainicio_pi ) >= YEAR( NOW() )' );
            
            $db->setQuery( (string)$query );
            $db->query();

            $dtaIndEntidad = ( $db->getNumRows() > 0 )  ? $db->loadObjectList() 
                                                        : array();

            return $dtaIndEntidad;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    
    /**
     * 
     * Elimino una lista de indicadores entidad
     * 
     * @param type $lstIndicadores  Lista de Indicadores a eliminar
     * @return type
     */
    private function _delIndicadorEntidad( $lstIndicadores )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            
            $query->delete( '#__ind_indicador_entidad' );
            $query->where( ' intIdIndEntidad IN ( '. implode( ',', $lstIndicadores ) .' )' );
            
            $db->setQuery( (string)$query );
            $db->query();

            $ban = ( $db->getAffectedRows() >= 0 )  ? TRUE 
                                                    : FALSE;

            return $ban;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    /**
     * 
     * Elimino una lista de indicadores Entidad
     * 
     * @param type $lstIndicadores  Lista de Indicadores
     *  
     * @return type
     * 
     */
    public function delLstIndicadorEntidad( $lstIndicadores )
    {
        $lstIndEntidad = array();
        
        foreach( $lstIndicadores as $ind ){
            $lstIndEntidad[] = $ind->idIndEntidad;
        }
        
        return $this->_delIndicadorEntidad( $lstIndEntidad );
    }
 
    /**
     * 
     * Retorna URL de acceso 
     * 
     * @param type $idIndicador
     * @return type
     */
    public function getNombreIndicadorTableu( $idIndicador )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( 't1.strAccesoTableu_indEnt AS nombreTableau' );
            $query->from( ' #__ind_indicador_entidad t1' );
            $query->where( ' t1.intCodigo_ind = '. $idIndicador );

            $db->setQuery( (string)$query );
            $db->query();

            $nombreTableau = ( $db->getNumRows() > 0 )  ? $db->loadObject()->nombreTableau 
                                                        : '';

            return $nombreTableau;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Gestino la eliminacion de un indicador - entidad
     * 
     * @param int $idIndEntidad    Identificador del Indicador Entidad
     * 
     * @return int
     * 
     */
    public function deleteIndEntidad( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->delete( '#__ind_indicador_entidad' );
            $query->where( 'intIdIndEntidad IN ( '. $idIndEntidad .' )' );

            $db->setQuery( (string)$query );
            $db->query();

            return $db->getNumRows();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
 
    /**
     * 
     * Retorno una lista de indicadores asociados a una determinada Entidad
     * 
     * @param int $idEntidad    Identificador de la Entidad
     * 
     * @return Array
     */
    public function _getLstIndicadores( $idEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intIdIndEntidad AS idIndEntidad, 
                                t1.intCodigo_ind AS idIndicador' );
            $query->from( '#__ind_indicador_entidad t1' );
            $query->where( 't1.intIdentidad_ent = '. $idEntidad );
            
            $db->setQuery( (string)$query );
            $db->query();

            $dtaIndEntidad = ( $db->getNumRows() > 0 )  ? $db->loadObjectList() 
                                                        : array();

            return $dtaIndEntidad;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    public function verificarSeguimiento( $idEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   COUNT(*) AS cantidad' );
            $query->from( '#__ind_indicador_entidad t1' );
            $query->join( 'INNER', '#__ind_indicador_variables t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
            $query->join( 'INNER', '#__ind_seguimiento t3 ON t2.intId_iv = t3.intId_iv' );
            $query->where( 't1.intIdentidad_ent ='. $idEntidad );
            
            $db->setQuery( (string)$query );
            $db->query();

            return $db->loadObject()->cantidad;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Cambio la vigencia de un determinado indicador
     * 
     * @param int $idIndEntidad     Identificador del Indicador Entidad
     * 
     * @return type
     * 
     */
    public function updVigenciaIndEntidad( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->update( '#__ind_indicador_entidad' );
            $query->set( 'intVigencia_indEnt = 0' );
            $query->where( 'intIdIndEntidad = '. $idIndEntidad );

            $db->setQuery( (string)$query );
            $db->query();

            return $db->getNumRows();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    
    /**
     * 
     * Actualizo la entidad a la que esta asociado un determinado Indicador Entidad
     * 
     * @param type $idIndEntidad    Identificador del Indicador Entidad
     * @param type $idEntidad       Identificador de la entidad
     * 
     * @return type
     * 
     */
    public function updEntidadIndicador( $idIndEntidad, $idEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->update( '#__ind_indicador_entidad' );
            $query->set( 'intIdentidad_ent = '. $idEntidad );
            $query->where( 'intIdIndEntidad = '. $idIndEntidad );

            $db->setQuery( (string)$query );
            $db->query();

            return $db->getNumRows();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    public function __destruct()
    {
        return;
    }
}