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

class jTableIndicadorUT extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__ind_indicador_uterritorial', 'intID_ut', $db );
    }
    
    /**
     * 
     * Registro Unidades Territoriales que estan asociadas a un determinado indicador
     * 
     * @param type $idIndEntidad            Identificador de la entidad
     * @param type $lstUndTerritoriales     Lista de Unidades Territoriales asociadas a un indicador
     */
    public function registroIndicadorUndTerritorial( $idIndEntidad, $lstUndTerritoriales )
    {
        try {
            $retval = true;
            
            if( !empty( $lstUndTerritoriales ) ){
                if( $this->_delRegUndTerritorial( $idIndEntidad ) ){
                    $db = JFactory::getDBO();
                    $query = $db->getQuery( true );
                    $sql = "INSERT INTO tb_ind_indicador_uterritorial (intID_ut, intIdIndEntidad) VALUES ";

                    foreach( $lstUndTerritoriales as $undTerritorial ){
                        $sql .= "( ". $undTerritorial->idUndTerritorial .", ". $idIndEntidad ." ),";
                    }

                    $db->setQuery( rtrim( $sql, ',' ) );
                    $db->query();

                    $retval = ( $db->getAffectedRows() > 0 )? TRUE
                                                            : FALSE;
                }
            }

            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Elimino los registros de unidades territoriales asociadas a un determinado indicador
     * 
     * @param type $idIndEntidad    Identificador del Indicador entidad
     * @return type
     */
    private function _delRegUndTerritorial( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );
            
            //  Elimino el registro de unidades territoriales anteriores
            $query->delete( '#__ind_indicador_uterritorial' );
            $query->where( 'intIdIndEntidad = '. $idIndEntidad );
            
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

    /**
     * 
     * Retorna informacion de Unidades Territoriales
     * 
     * @param type $idIndEntidad  Identificador de Entidad
     * @return type
     */
    public function getUndTerritorialIndicador( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intID_ut AS idUndTerritorial, 
                                t2.intID_tut AS tpoUndTerritorial' );
            $query->from( '#__ind_indicador_uterritorial t1' );
            $query->join( 'INNER', '#__ut_undTerritorial t2 ON t1.intID_ut = t2.intID_ut' );
            $query->where( 't1.intIdIndEntidad = ' . $idIndEntidad );
            
            $db->setQuery( (string) $query );
            $db->query();

            $dtaUndTerritorial = array();
            if( $db->getNumRows() > 0 ) {
                $undsTerritoriales = $db->loadObjectList();

                foreach( $undsTerritoriales as $key => $undTerritorial ) {
                    if( (int)$undTerritorial->idUndTerritorial != 0 && (int)$undTerritorial->tpoUndTerritorial != 0 ){
                        $infoUT = $this->_getInfoUT( $undTerritorial );
                        $infoUT->idRegUT = $key;
                        
                        $dtaUndTerritorial[] = $infoUT;
                    }

                }
            }
            
            return $dtaUndTerritorial;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorna informacion de una determinada unidad territorial
     * 
     * @param type $undTerritorial
     * @return type
     */
    private function _getInfoUT( $undTerritorial )
    {
        try {
            $db = JFactory::getDBO();

            switch( $undTerritorial->tpoUndTerritorial ) {
                case 3:
                    $sql = "SELECT  DISTINCT intIDProvincia_dpa AS idUndTerritorial,
                                    strNombreProvincia_dpa AS provincia, 
                                    0 AS canton, 
                                    0 AS parroquia
                    FROM tb_vw_dpa 
                    WHERE intIDProvincia_dpa = " . $undTerritorial->idUndTerritorial;
                    break;

                case 4:
                    $sql = "SELECT  DISTINCT intIDCanton_dpa AS idUndTerritorial,
                                    strNombreProvincia_dpa AS provincia, 
                                    strNombreCanton_dpa AS canton, 
                                    0 AS parroquia
                    FROM tb_vw_dpa 
                    WHERE intIDCanton_dpa = " . $undTerritorial->idUndTerritorial;
                    break;

                case 5:
                    $sql = "SELECT  DISTINCT intIDParroquia_dpa AS idUndTerritorial,
                                    strNombreProvincia_dpa AS provincia, 
                                    strNombreCanton_dpa AS canton, 
                                    strNombreParroquia_dpa AS parroquia
                    FROM tb_vw_dpa 
                    WHERE intIDParroquia_dpa = " . $undTerritorial->idUndTerritorial;
                    break;
            }

            $db->setQuery( (string) $sql );
            $db->query();

            $lstUndTerritorial = ( $db->getNumRows() > 0 )  ? $db->loadObject() 
                                                            : FALSE;

            return $lstUndTerritorial;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    /**
     * 
     * Gestiono la eliminacion de las Unidades Territoriales asociadas a un indicador
     * 
     * @param int $idIndEntidad    Identificador indicador Entidad
     * 
     * @return int
     */
    public function deleteUnidadTerritorial( $idIndEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->delete( '#__ind_indicador_uterritorial' );
            $query->where( 'intIdIndEntidad = '. $idIndEntidad );
            
            $db->setQuery( (string)$query );
            $db->query();

            return $db->getNumRows();
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    public function __destruct()
    {
        return;
    }
    
}