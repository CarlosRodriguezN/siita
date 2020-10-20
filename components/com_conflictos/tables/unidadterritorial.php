<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

/**
 * 
 * Clase que gestiona informacion de la tabla Atribtuo ( #__ctr_atributo )
 * 
 */
class ConflictosTableUnidadTerritorial extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__gc_tema_uterritorial', 'intId_tma', $db );
    }

    /**
     * 
     * @param type $idTema
     * @param type $unidadTerritorial
     */
    public function saveUnidadTerritorial( $idTema, $unidadTerritorial )
    {
        if( $unidadTerritorial->published == 1 ) {
            
            $ubicacionGeografica = new stdClass();

            $ubicacionGeografica->intId_tma = $idTema;
            if( $unidadTerritorial->idProvincia != 0 ) {
                $ubicacionGeografica->intID_ut = $unidadTerritorial->idProvincia;
                if( $unidadTerritorial->idCanton != 0 ) {
                    $ubicacionGeografica->intID_ut = $unidadTerritorial->idCanton;
                    if( $unidadTerritorial->idParroquia != 0 ) {
                        $ubicacionGeografica->intID_ut = $unidadTerritorial->idParroquia;
                    }
                }
            }
            $success = $this->_db->insertObject( "#__gc_tema_uterritorial", $ubicacionGeografica );
            return $success;
        }
    }

    /**
     * 
     * @param type $idTema
     */
    public function deleteUnidadesTerritoriales( $idTema )
    {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery( true );
            $query->delete( $db->nameQuote( '#__gc_tema_uterritorial' ) );
            $query->where( $db->nameQuote( 'intId_tma' ) . '=' . $db->quote( $idTema ) );

            $db->setQuery( $query );
            $db->query();
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_contratos.table.tiposcontratista.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
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

            $sql = "SELECT  intId_tma, 
                            intID_tut, 
                            strCodDPA_ut
                    FROM " . $db->nameQuote( "#__ut_undTerritorial" ) . " t1
                    WHERE intId_tma = " . $idUbcTerr;

            $db->setQuery( (string) $sql );
            $db->query();


            if( $db->getNumRows() > 0 ) {
                $dtaUT = $db->loadObject();

                $dtaUT->coordenada = $this->_getDataDPA( $dtaUT );
            }

            return $dtaUT;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.tables.log.php' );
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
                            WHERE intIDProvincia_dpa = " . $dtaDPA->intId_tma;
                    break;

                case '4':
                    $sql = "SELECT  DISTINCT    lat                         AS lat, 
                                                longi                       AS lng,
                                                strNombreProvincia_dpa      AS provincia,
                                                strNombreCanton_dpa         AS canton,
                                                strNombreParroquia_dpa      AS parroquia
                            FROM tb_vw_dpa 
                            WHERE intIDCanton_dpa = " . $dtaDPA->intId_tma;
                    break;

                case '5':
                    $sql = "SELECT  DISTINCT    lat                         AS lat, 
                                                longi                       AS lng,
                                                strNombreProvincia_dpa      AS provincia,
                                                strNombreCanton_dpa         AS canton,
                                                strNombreParroquia_dpa      AS parroquia
                            FROM tb_vw_dpa 
                            WHERE intIDParroquia_dpa = " . $dtaDPA->intId_tma;
                    break;
            }

            $db->setQuery( (string) $sql );
            $db->query();

            $undTerritorial = ( $db->loadObject() ) ? $db->loadObject() : FALSE;

            return $undTerritorial;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_conflictos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}