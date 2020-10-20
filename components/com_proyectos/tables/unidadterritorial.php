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
class ProyectosTableUnidadTerritorial extends JTable
{

    private $_dataUndTerritorial;
    private $_idProyecto;

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
     *
     * Retorna lista de cantones pertenecientes a una determanda Provincia
     * 
     * @return type 
     */
    public function lstCantones( $idProvincia )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '    DISTINCT dpa.intIDCanton_dpa AS id, 
                                dpa.strNombreCanton_dpa AS nombre' );

            $query->from( '#__vw_dpa dpa' );
            $query->where( 'dpa.intIDProvincia_dpa = ' . $idProvincia );
            $query->order( 'dpa.strNombreCanton_dpa' );

            $db->setQuery( (string) $query );

            $db->query();

            $rstCantones = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : false;

            return $rstCantones;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *
     * Retorna lista de cantones pertenecientes a una determanda Provincia
     * 
     * @return type 
     */
    public function lstParroquias( $idCanton )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   DISTINCT dpa.intIDParroquia_dpa AS id, 
                                dpa.strNombreParroquia_dpa AS nombre' );
            $query->from( '#__vw_dpa dpa' );
            $query->where( 'dpa.intIDCanton_dpa = ' . $idCanton );
            $query->order( 'dpa.strNombreParroquia_dpa' );

            $db->setQuery( (string) $query );

            $db->query();

            $rstParroquias = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : false;

            return $rstParroquias;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

///////////////////////////////////////////
//  PARA PROYECTOS
///////////////////////////////////////////    
    
    private function _existeUndTerritorial( $idUndTerritorial )
    {
        try {
            $db = &JFactory::getDbo();

            $sql = "SELECT COUNT(*) AS cantidad 
                    FROM " . $db->nameQuote( '#__ubicgeo_pry' ) . "
                    WHERE intCodigo_pry = '" . $this->_idProyecto . "'
                    AND intID_ut = '" . $idUndTerritorial . "'";

            $db->setQuery( $sql );
            $db->query();

            $retval = ( $db->getAffectedRows() > 0 ) ? (int) $db->loadObject()->cantidad : FALSE;

            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    private function _insertUndTerritorial( $idUndTerritorial )
    {
        try {
            $db = &JFactory::getDbo();
            $sql = "INSERT INTO #__ubicgeo_pry (intCodigo_pry, 
                                                intID_ut )";

            $sql .= "VALUE ('" . $this->_idProyecto . "', 
                                '" . $idUndTerritorial . "' )";

            $db->setQuery($sql);
            $db->query();
            $retval = ( $db->getAffectedRows() > 0 ) ? TRUE : FALSE;

            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    private function _updUndTerritorial( $idUndTerritorial )
    {
        try {
            $db = &JFactory::getDbo();

            $sql = "UPDATE " . $db->nameQuote( '#__ubicgeo_pry' ) . " 
                    SET intID_ut = '" . $idUndTerritorial . "'
                    WHERE intCodigo_pry = '" . $this->_idProyecto . "'";

            $db->setQuery( $sql );
            $db->query();

            $retval = ( $db->getAffectedRows() > 0 ) ? TRUE : FALSE;

            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    private function _delUndTerritorial( $idUndTerritorial )
    {
        try {
            $db = &JFactory::getDbo();

            $sql = "DELETE FROM" . $db->nameQuote( '#__ubicgeo_pry' ) . " 
                    WHERE intCodigo_pry = '" . $this->_idProyecto . "' 
                    AND intID_ut = '" . $idUndTerritorial . "'";

            $db->setQuery( $sql );
            $db->query();

            $retval = ( $db->getAffectedRows() > 0 ) ? TRUE : FALSE;

            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Registro Informacion de Unidad Territorial
     * 
     * @return type
     */
    private function _setDataUnidadTerritorial()
    {
        try {
            $db = &JFactory::getDbo();
            $db->getQuery( true );

            foreach( $this->_dataUndTerritorial as $key => $value ) {
                
                //  Valido Contenido de la data
                switch( true ) {
                    //  Si no existe idParroquia Verifico por idParroquia
                    case( $value->idProvincia != 0 && $value->idCanton != 0 && $value->idParroquia != 0 ):

                        if( $this->_existeUndTerritorial( $value->idParroquia ) == TRUE ) {

                            if( $value->published == 1 ) {
                                $this->_updUndTerritorial( $value->idParroquia );
                            } else {
                                $this->_delUndTerritorial( $value->idParroquia );
                            }
                        } else {
                            $this->_insertUndTerritorial( $value->idParroquia );
                        }

                        break;

                    //  Si no existe idParroquia Verifico por idCanton
                    case( $value->idProvincia != 0 && $value->idCanton != 0 && $value->idParroquia == 0 && $value->published == 1 ):

                        if( $this->_existeUndTerritorial( $value->idCanton ) == TRUE ) {

                            if( $value->published == 1 ) {
                                $this->_updUndTerritorial( $value->idCanton );
                            } else {
                                $this->_delUndTerritorial( $value->idCanton );
                            }
                        } else {
                            $this->_insertUndTerritorial( $value->idCanton );
                        }

                        break;

                    //  Si no existe idParroquia Verifico por idProvincia
                    case( $value->idProvincia != 0 && $value->idCanton == 0 && $value->idParroquia == 0 && $value->published == 1 ):
                        if( $this->_existeUndTerritorial( $value->idProvincia ) == TRUE ) {
                            if( $value->published == 1 ) {
                                $this->_updUndTerritorial( $value->idProvincia );
                            } else {
                                $this->_delUndTerritorial( $value->idProvincia );
                            }
                        } else {
                            $this->_insertUndTerritorial( $value->idProvincia );
                        }

                        break;
                }
            }
            return true;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Registro una unidad territorial de un determinado proyecto
     * 
     * @param type $idProyecto              Identificador de un proyecto
     * @param type $dataUnidadTerritorial   Datos de la unidad territorial a registrar
     * 
     * @return type
     * 
     */
    public function registrarUnidadTerritorial( $idProyecto, $dataUnidadTerritorial )
    {
        $this->_idProyecto = $idProyecto;
        $this->_dataUndTerritorial = json_decode( $dataUnidadTerritorial );
        if( count( (array) $this->_dataUndTerritorial ) > 0 ) {
            //  Elimino el unidades territoriales
            $this->_delUnidadesTerritoriales();

            return $this->_setDataUnidadTerritorial();
        }

        return true;
    }




///////////////////////////////////////////
//  PARA CANASTA DE PROYECTOS - PROYECTOS
///////////////////////////////////////////   

    /**
     * 
     * Registro Unidades territoriales pertenecientes a un grupo de propuestas 
     * de proyectos en la tabla perteneciente a un determinada proyecto
     * 
     * @param type $idProyecto              Identificador de Proyecto
     * @param type $dataUnidadTerritorial   Lista de Unidades territoriales
     * 
     * @return boolean
     */
    public function registroUndTerritorialCanasta( $idProyecto, $dataUnidadTerritorial )
    {
        $this->_idProyecto = $idProyecto;
        $this->_dataUndTerritorial = json_decode( $dataUnidadTerritorial );

        if( count( (array) $this->_dataUndTerritorial ) > 0 ) {
            //  Elimino el unidades territoriales
            $this->_delUnidadesTerritoriales();
            foreach( $this->_dataUndTerritorial as $undTerritorial ) {
            // $this->_insertUndTerritorial( $undTerritorial );
            }
        }

        return true;
    }

    /**
     * 
     * Elimino todas las unidades territoriales registradas
     * 
     * @return type
     */
    private function _delUnidadesTerritoriales()
    {
        try {
            $db = &JFactory::getDbo();

            $sql = "DELETE FROM" . $db->nameQuote( '#__ubicgeo_pry' ) . " 
                    WHERE intCodigo_pry = '" . $this->_idProyecto . "'";

            $db->setQuery( $sql );
            $db->query();

            $retval = ( $db->getAffectedRows() > 0 ) ? TRUE : FALSE;

            return $retval;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *
     * Retorna una lista de Unidades Territoriales pertenecientes 
     * a un determinado proyecto en Formato DPA
     * 
     * @param type $idUnidadTerritorial Identificador de la Unidad Territorial
     * 
     * @return type 
     */
    public function getLstUnidadTerritorial( $idProyecto )
    {
        try {
            $db = JFactory::getDBO();

            $sql = "SELECT  t1.intCodigo_pry AS idProyecto, 
                            t1.intID_ut AS idUndTerritorial,
                            t2.intID_tut AS idTpoUndTerritorial
                    FROM " . $db->nameQuote( "#__ubicgeo_pry" ) . " t1
                    JOIN " . $db->nameQuote( "#__ut_undTerritorial" ) . " t2 ON
                    t1.intID_ut = t2.intID_ut
                    WHERE t1.intCodigo_pry = '" . $idProyecto . "'";

            $db->setQuery( (string) $sql );
            $db->query();

            if( $db->getNumRows() > 0 ) {
                foreach( $db->loadObjectList() as $ut ) {
                    $lstUndTerritorial[] = $this->_getDtaUndTerritorial( $ut );
                }
            }

            return $lstUndTerritorial;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    private function _getDtaUndTerritorial( $dtaUT )
    {
        try {
            $db = JFactory::getDBO();

            switch( $dtaUT->idTpoUndTerritorial ) {
                case '3': $sql = "  SELECT  DISTINCT intIDProvincia_dpa AS idProvincia,
                                            strNombreProvincia_dpa AS provincia,
                                            0 AS idCanton, 
                                            0 AS canton, 
                                            0 AS idParroquia, 
                                            0 AS parroquia
                                    FROM tb_vw_dpa 
                                    WHERE intIDProvincia_dpa = " . $dtaUT->idUndTerritorial;

                    break;

                case '4': $sql = "  SELECT  DISTINCT intIDProvincia_dpa AS idProvincia,
                                            strNombreProvincia_dpa AS provincia,
                                            intIDCanton_dpa AS idCanton,
                                            strNombreCanton_dpa AS canton,
                                            0 AS idParroquia, 
                                            0 AS parroquia
                                    FROM tb_vw_dpa 
                                    WHERE intIDCanton_dpa = " . $dtaUT->idUndTerritorial;
                    break;

                case '5': $sql = "  SELECT  DISTINCT intIDProvincia_dpa AS idProvincia,
                                            strNombreProvincia_dpa AS provincia,
                                            intIDCanton_dpa AS idCanton,
                                            strNombreCanton_dpa AS canton,
                                            intIDParroquia_dpa AS idParroquia, 
                                            strNombreParroquia_dpa AS parroquia
                                    FROM tb_vw_dpa 
                                    WHERE intIDParroquia_dpa = " . $dtaUT->idUndTerritorial;
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
     * Retona informacion de provincia, canton, parroquia de una determinada DPA
     * 
     * @param type $idDPA   identificador de la unidad territorial
     * @return type
     */
    public function dtaDPA( $idDPA )
    {
        try {
            $db = JFactory::getDBO();

            $sql = "SELECT  intID_ut, 
                            intID_tut, 
                            strCodDPA_ut
                    FROM " . $db->nameQuote( "#__ut_undTerritorial" ) . " t1
                    WHERE intID_ut = " . $idDPA;

            $db->setQuery( (string) $sql );
            $db->query();

            if( $db->getNumRows() > 0 ) {
                $dtaUndTerritorial = $db->loadObject();

                switch( $dtaUndTerritorial->intID_tut ) {
                    case '3':
                        $lstUndTerritorial = $this->_getLstProvincia( $dtaUndTerritorial->intID_ut );
                        break;

                    case '4':
                        $lstUndTerritorial = $this->_getLstCanton( $dtaUndTerritorial->intID_ut );
                        break;

                    case '5':
                        $lstUndTerritorial = $this->_getLstParroquia( $dtaUndTerritorial->intID_ut );
                        break;
                }
            }

            return $lstUndTerritorial;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Retorna informacion especifica de una provincia
     *  
     * @param type $idProvincia Identificador de la provincia
     * @return type
     */
    private function _getLstProvincia( $idProvincia )
    {
        try {
            $db = JFactory::getDBO();

            $sql = "SELECT  DISTINCT intIDProvincia_dpa AS idProvincia, 
                            0 AS idCanton, 
                            0 AS idParroquia
                    FROM tb_vw_dpa 
                    WHERE intIDProvincia_dpa = " . $idProvincia;

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

    /**
     * 
     * Retorna Provincia a la que pertenece un determinado canton
     * 
     * @param type $idCanton    Identificador de canton
     * 
     * @return type
     */
    private function _getLstCanton( $idCanton )
    {
        try {
            $db = JFactory::getDBO();

            $sql = "SELECT  DISTINCT intIDProvincia_dpa AS idProvincia, 
                            intIDCanton_dpa AS idCanton, 
                            0 AS idParroquia
                    FROM tb_vw_dpa 
                    WHERE intIDCanton_dpa = " . $idCanton;

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

    /**
     * 
     * Retorna Provincia, Canton a la que pertenece determinada Parroquia
     * 
     * @param type $idParroquia Identificador de parroquia
     * 
     * @return type
     * 
     */
    private function _getLstParroquia( $idParroquia )
    {
        try {
            $db = JFactory::getDBO();

            $sql = "SELECT  DISTINCT intIDProvincia_dpa AS idProvincia, 
                            intIDCanton_dpa AS idCanton, 
                            intIDParroquia_dpa AS idParroquia
                    FROM tb_vw_dpa 
                    WHERE intIDParroquia_dpa = " . $idParroquia;

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

    
    /**
     * Recupera las coordenadas de una DPA
     * @param type      $idUbcTerr      Unidad territorial.
     * @return Arrat                    Lista de coordenadas
     */
    public function dtaDPAContratos( $idUbcTerr )
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

}