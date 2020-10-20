<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

//  Import Joomla JUser Library
jimport( 'joomla.user.user' );

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class ProgramaTableUnidadTerritorial extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__ut_undTerritorial', 'intID_ut', $db );
    }

    /**
     * Recupera las coordenadas de una DPA
     * @param type      $idUbcTerr      Unidad territorial.
     * @return Arrat                    Lista de coordenadas
     */
    public function dtaDPA( $idUbcTerr )
    {
        try {
            $db = JFactory::getDBO();

            $sql = "SELECT  intID_ut, 
                            intID_tut, 
                            strCodDPA_ut
                    FROM " . $db->nameQuote( "#__ut_undTerritorial" ) . " t1
                    WHERE intID_ut = " . $idUbcTerr;

            $db->setQuery( (string) $sql );
            $db->query();


            if( $db->getNumRows() > 0 ) {
                $dtaUT = $db->loadObject();

                $dtaUT->coordenada = $this->_getDataDPA( $dtaUT );
            }

            return $dtaUT;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * Recupera las coordenadas de una DPA
     * @param object   $dtaDPA  Objeto DPA
     * @return object           Objeto coodenadas 
     */
    private function _getDataDPA( $dtaDPA )
    {
        try {
            $db = JFactory::getDBO();

            switch( $dtaDPA->intID_tut ) {
                case '3':
                    $sql = "SELECT  DISTINCT    lat                         AS lat, 
                                                longi                       AS lng,
                                                strNombreProvincia_dpa      AS provincia,
                                                strNombreCanton_dpa         AS canton,
                                                strNombreParroquia_dpa      AS parroquia
                            FROM tb_vw_dpa 
                            WHERE intIDProvincia_dpa = " . $dtaDPA->intID_ut;
                    break;

                case '4':
                    $sql = "SELECT  DISTINCT    lat                         AS lat, 
                                                longi                       AS lng,
                                                strNombreProvincia_dpa      AS provincia,
                                                strNombreCanton_dpa         AS canton,
                                                strNombreParroquia_dpa      AS parroquia
                            FROM tb_vw_dpa 
                            WHERE intIDCanton_dpa = " . $dtaDPA->intID_ut;
                    break;

                case '5':
                    $sql = "SELECT  DISTINCT    lat                         AS lat, 
                                                longi                       AS lng,
                                                strNombreProvincia_dpa      AS provincia,
                                                strNombreCanton_dpa         AS canton,
                                                strNombreParroquia_dpa      AS parroquia
                            FROM tb_vw_dpa 
                            WHERE intIDParroquia_dpa = " . $dtaDPA->intID_ut;
                    break;
            }

            $db->setQuery( (string) $sql );
            $db->query();

            $undTerritorial = ( $db->getNumRows() > 0 ) ? $db->loadObject() : FALSE;

            return $undTerritorial;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorna informacion de Unidades Territoriales
     * 
     * @param type $indEntidad  Identificador de Entidad
     * @return type
     */
    public function getUndTerritorialIndicador( $indEntidad )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   t1.intID_ut AS idUndTerritorial, 
                                t2.intID_tut AS tpoUndTerritorial' );
            $query->from( '#__ind_indicador_uterritorial t1' );
            $query->join( 'INNER', '#__ut_undTerritorial t2 ON t1.intID_ut = t2.intID_ut' );
            $query->where( 't1.intIdIndEntidad = ' . $indEntidad );

            $db->setQuery( (string) $query );
            $db->query();

            if( $db->getNumRows() > 0 ) {
                $undsTerritoriales = $db->loadObjectList();

                foreach( $undsTerritoriales as $key => $undTerritorial ) {
                    $infoUT = $this->_getInfoUT( $undTerritorial );
                    $infoUT->idRegUT = $key;
                    $dtaUndTerritorial[] = $infoUT;
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

            $lstUndTerritorial = ( $db->getNumRows() > 0 ) ? $db->loadObject() : FALSE;

            return $lstUndTerritorial;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
}