<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla categoria ( #__categoria )
 * 
 */
class mantenimientoTableDPA extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ut_dpa', 'inpID_dpa', $db);
    }
    
    /**
     *
     *  Registro una Unidad Terrorial
     *  
     *  @param type $intID_tut       Identificador de Tipo de Unidad Territoral ( Zona, Region, Provincia, Canton, Parroquia .... )
     *  @param type $inpID_dpa       Identificador de la DPA Registrada ( 2011 ) 
     *  @param type $strCodDPA_ut    Cadena que Identifica el tipo de DPA
     *  @param type $strNombre_ut    Descripcion de Zona, Provincia, etc.
     * 
     *  @return type 
     * 
     */
    function setUnidadTerritorial($intID_tut, $inpID_dpa, $strCodDPA_ut, $strNombre_ut) {
        try {
            $db = & JFactory::getDbo();
            $db->setQuery( true );
            $query = "  INSERT INTO " . $db->nameQuote('#__ut_undTerritorial') . " 
                        ( intID_tut, inpID_dpa, strCodDPA_ut, strNombre_ut ) 
                        VALUES ( '{$intID_tut}', '{$inpID_dpa}', '{$strCodDPA_ut}', '{$strNombre_ut}' )";

            $db->setQuery($query);
            $db->query();

            // Retorna el ID con el que fue insertado el registro
            return $db->insertid();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     *  
     *  Registro informacion de relacion de registros propios de DPA
     *  
     *  @param type $intID_ut       Identificador de Unidad Territorial previamente 
     *                              registrado en la tabla Unidad Territorial
     * 
     *  @param type $intIDPadre_ut   Identificador del Registro Padre
     * 
     *  @return type 
     */
    function setRelacionDPA( $intID_ut, $intIDPadre_ut )
    {
        try {
            $db = &JFactory::getDbo();
            $query = "  INSERT INTO " . $db->nameQuote('#__ut_relacionDPA') . " 
                        ( intID_ut, intIDPadre_ut ) 
                        VALUES ( '{$intID_ut}', '{$intIDPadre_ut}' )";
                        
            $db->setQuery($query);
            $db->query();

            // Retorna el ID con el que fue insertado el registro
            return $db->insertid();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     *  Registro en la tabla unidad territorial las zonas 
     */
    public function loadZonas()
    {
        try {
            $db = &JFactory::getDbo();
            $query = "  SELECT  DISTINCT ZONA as COD_ZONA,
                                CONCAT( 'ZONA DE PLANIFICACION ', ZONA ) AS ZONA
                        FROM 
                            " . $db->nameQuote('#__dpa_2011') . "
                        WHERE
                            ZONA <> ''
                        ORDER BY 
                            COD_ZONA;";
            
            $db->setQuery($query);
            $zonas = $db->loadObjectList();
            
            foreach ($zonas as $zona) {
                $strCodDPA_ut = 'Z' . $zona->COD_ZONA;
                
                //  Ingreso el registro en la tabla Unidad Territorial
                $intID_ut = $this->setUnidadTerritorial( '1', '1', $strCodDPA_ut, $zona->ZONA );
                
                //  Ingreso la relacion Unidad Territorial
                $this->setRelacionDPA( $intID_ut, '0' );
            }
        } catch( Exception $e ) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance( 'com_mantenimiento.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    /**
     *  
     *  Obtengo las Provincias de una determinada Zona
     *  
     *  @param type $zona   Zona a filtrar
     *  
     *  @return type
     *   
     */
    function getProvinciasPorZona( $zona )
    {
        try {
            $db = & JFactory::getDbo();
                        
            //  Selecciono las provincias de pertenecientes a una determinada zona
            $query = "  SELECT	DISTINCT COD_PROVINCIA, 
                                PROVINCIA
                        FROM 
                            " . $db->nameQuote('#__dpa_2011') . "
                        WHERE 
                            ZONA = '{$zona{1}}';";
                            
            $db->setQuery( $query );
            $provincias = $db->loadObjectList();

            return $provincias;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    /**
     *  Cargo las provincias de una determinada Zona 
     */
    public function loadZonasProvincias()
    {
        try {
            $db = & JFactory::getDbo();
            
            //  Selecciono los registros de tipo zona
            $query = "  SELECT  intID_ut, 
                                strCodDPA_ut, 
                                strNombre_ut
                        FROM 
                            " . $db->nameQuote('#__ut_undTerritorial') . "
                        WHERE 
                            intID_tut = 1 
                        AND strCodDPA_ut NOT IN ( 'Z8', 'Z9' ) ;";
            
            $db->setQuery( $query );
            $zonas = $db->loadObjectList();

            foreach( $zonas as $zona ){
                //  Obtengo las Provincias de una determinada "Zona"
                $provincias = $this->getProvinciasPorZona( $zona->strCodDPA_ut );

                foreach( $provincias as $provincia ) {
                    $strCodDPA_ut = str_pad( $provincia->COD_PROVINCIA, 2, "0", STR_PAD_LEFT );
                    $intID_tut = 3; // Provincia 
                    $inpID_dpa = 1; // 2011;
                    //  Cargo las provincias de unas determinadas zonas
                    $intID_ut = $this->setUnidadTerritorial( $intID_tut, $inpID_dpa, $strCodDPA_ut, $provincia->PROVINCIA );
                    
                    //  Cargo la relacion zona provincia
                    $this->setRelacionDPA( $intID_ut, $zona->intID_ut );
                }
            }
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    
    
    /**
     * 
     * Retorno los cantones pertenecientes a una determinadad Zona especial
     * 
     * @param type $zona    Zona a filtrar
     * 
     * @return type
     */    
    function getCantonesPorZona( $zona )
    {
        try {
            $db = & JFactory::getDbo();
                        
						
            //  Selecciono los cantones pertenecientes a una determinada zona
            $query = "  SELECT  t1.intID_ut, 
                                t2.idDpa, 
                                t1.strNombre_ut
                        FROM " . $db->nameQuote('#__ut_undTerritorial') . " t1
                        JOIN (	SELECT	DISTINCT CONCAT( COD_PROVINCIA, COD_CANTON ) AS idDpa, 
                                                        CANTON
                                        FROM " . $db->nameQuote('#__dpa_2011') . " ta
                                        WHERE ta.ZONA = '". $zona{1} ."'
                                ) t2 ON
                        t1.strCodDPA_ut = t2.idDpa";

            $db->setQuery( $query );
            $cantones = $db->loadObjectList();

            return $cantones;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    /**
     * Cargo la relacion Zonas Cantones para zonas especiales como Zona 8 Guayas y Zona 9 Pichincha
     */
    public function loadZonasCantones()
    {
        try {
            $db = & JFactory::getDbo();
            
            //  Selecciono los registros de tipo zona
            $query = "  SELECT  intID_ut, 
                                strCodDPA_ut, 
                                strNombre_ut
                        FROM 
                            " . $db->nameQuote('#__ut_undTerritorial') . "
                        WHERE 
                            intID_tut = 1 
                        AND strCodDPA_ut IN ( 'Z8', 'Z9' ) ;";
            
            $db->setQuery( $query );
            $zonas = $db->loadObjectList();

            foreach( $zonas as $zona ){
                //  Obtengo las Provincias de una determinada "Zona"
                $cantones = $this->getCantonesPorZona( $zona->strCodDPA_ut );
                foreach( $cantones as $canton ) {
                    //  Cargo la relacion zona provincia
                    $this->setRelacionDPA( $canton->intID_ut, $zona->intID_ut );
                }
            }
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Obtengo los cantones pertenecientes a una determinada Provincia
     * 
     *  @param type $cod_provincia  Codigo de provincia
     * 
     *  @return type 
     */
    function getCantones( $cod_provincia )
    {
        try {
            $db = & JFactory::getDbo();
            $query = "  SELECT  DISTINCT COD_CANTON, 
                                CANTON
                        FROM 
                            " . $db->nameQuote('#__dpa_2011') . "
                        WHERE 
                            CAST( COD_PROVINCIA AS UNSIGNED ) = CAST( '{$cod_provincia}' AS UNSIGNED );";
            
            $db->setQuery( $query );
            $cantones = $db->loadObjectList();
            
            return $cantones;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Cargo los Cantones pertenecientes a una determinada Provincia 
     */
    function loadProvinciasCantones() 
    {
        try {
            $db = & JFactory::getDbo();
            
            //  Selecciono las provincias registradas en la tabla Unidad Territorial ( tb_ut_undTerritorial )
            $query = "  SELECT intID_ut, strCodDPA_ut, strNombre_ut
                        FROM 
                            " . $db->nameQuote('#__ut_undTerritorial') . "
                        WHERE 
                            intID_tut = '3'
                        ORDER BY 
                            CAST( strCodDPA_ut AS UNSIGNED );";
            
            $db->setQuery( $query );
            $provincias = $db->loadObjectList();
            
            foreach( $provincias as $provincia ){
                //  Selecciono los cantones de una determinada Provincia
                $cantones = $this->getCantones( $provincia->strCodDPA_ut );

                foreach( $cantones as $canton ){
                    $strCodDPA_ut = $provincia->strCodDPA_ut . str_pad( $canton->COD_CANTON, 2, "0", STR_PAD_LEFT );
                    $intID_tut = 4; // Cantón 
                    $inpID_dpa = 1; // 2011;
                    
                    //  Registro los cantones en la tabla unidad territorial
                    $intID_ut = $this->setUnidadTerritorial( $intID_tut, $inpID_dpa, $strCodDPA_ut, $canton->CANTON );
                    
                    //  Registro la relacion Provincia - Canton
                    $this->setRelacionDPA( $intID_ut, $provincia->intID_ut );
                }
            }
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    /**
     *  Retorno las parroquias de un determinado canton
     *  @param type $cod_canton Codigo del canton a filtrar
     * 
     *  @return type 
     */
    function getParroquias( $cod_canton )
    {
        try {
            $codProvincia = rtrim( substr($cod_canton, 0, 2) );
            $codCanton = rtrim( substr($cod_canton, 2, strlen( $cod_canton ) ) );
            
            $db = & JFactory::getDbo();

            $query = "  SELECT	DISTINCT COD_PARROQUIA, 
                                PARROQUIA
                        FROM
                            " . $db->nameQuote('#__dpa_2011') . "
                        WHERE COD_PROVINCIA = '{$codProvincia}'
                        AND COD_CANTON = '{$codCanton}'";

            $db->setQuery($query);
            $parroquias = $db->loadObjectList();

            return $parroquias;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Carga de Cantones de parroquias determinadas 
     */
    function loadCantonesParroquias() 
    {
        try {
            $db = & JFactory::getDbo();
            $query = "  SELECT intID_ut, strCodDPA_ut, strNombre_ut
                        FROM 
                            " . $db->nameQuote('#__ut_undTerritorial') . "
                        WHERE 
                            intID_tut = '4'
                        ORDER BY 
                            CAST( strCodDPA_ut AS UNSIGNED )";
            
            $db->setQuery($query);
            $cantones = $db->loadObjectList();

            foreach( $cantones as $canton ){
                $parroquias = $this->getParroquias( $canton->strCodDPA_ut );

                foreach ($parroquias as $parroquia) {
                    $strCodDPA_ut = $canton->strCodDPA_ut . str_pad($parroquia->COD_PARROQUIA, 2, "0", STR_PAD_LEFT);
                    $intID_tut = 5; // Parroquia 
                    $inpID_dpa = 1; // 2008;
                    
                    //  Registro las Parroquias en la tabla unidad Terrorial
                    $intID_ut = $this->setUnidadTerritorial($intID_tut, $inpID_dpa, $strCodDPA_ut, $parroquia->PARROQUIA);
                    
                    //  Registro la relacion Canton Parroquia
                    $this->setRelacionDPA($intID_ut, $canton->intID_ut);
                }
            }
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    
    /**
     *  Cargo las Distritos registradas en el archivo DPA 
     */
    public function loadDistritos()
    {
        try {
            $db = & JFactory::getDbo();
            $query = "  SELECT DISTINCT COD_DISTRITO
                        FROM 
                            ". $db->nameQuote( '#__dpa_2011' ) ."
                        WHERE 
                            COD_DISTRITO <> ''
                        ORDER BY 
                            COD_DISTRITO";
            
            $db->setQuery( $query );
            $distritos = $db->loadObjectList();

            foreach( $distritos as $distrito ) {
                $intID_tut = 6; // Distrito
                $inpID_dpa = 1; // 2008;
                
                //  Registro el distrito en la tabla Unidad Territorial
                $intID_ut = $this->setUnidadTerritorial( $intID_tut, $inpID_dpa, $distrito->COD_DISTRITO, $distrito->COD_DISTRITO );
                
                //  Ingreso la relacion Unidad Territorial
                $this->setRelacionDPA( $intID_ut, '0' );
            }
        } catch( Exception $e ) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance( 'com_mantenimiento.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    /**
     * 
     *  Retorna los cantones pertenecientes a un determinado Distrito
     * 
     *  @param type $distrito   Distrito a filtrar 
     *  @return type 
     */
    public function getCantonesDistritos( $distrito )
    {
        try {
            $db = & JFactory::getDbo();
            $query = "  SELECT DISTINCT ta.intID_ut, ta.strCodDPA_ut, ta.strNombre_ut
                        FROM 
                            ". $db->nameQuote('#__ut_undTerritorial') ." ta
                        JOIN (
                                SELECT 
                                    DISTINCT CONCAT( COD_PROVINCIA, COD_CANTON ) AS DPA
                                FROM 
                                    ". $db->nameQuote('#__dpa_2011') ."
                                WHERE 
                                    COD_DISTRITO = '{$distrito}'
                            ) tb ON
                        ta.strCodDPA_ut = tb.DPA";

            $db->setQuery($query);
            $cantones = $db->loadObjectList();

            return $cantones;
        } catch( Exception $e ){
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    
    
    public function getParroquiasDistritos( $distrito )
    {
        try {
            $db = & JFactory::getDbo();
            $query = "  SELECT DISTINCT ta.intID_ut, ta.strCodDPA_ut, ta.strNombre_ut
                        FROM 
                            ". $db->nameQuote('#__ut_undTerritorial') ." ta
                        JOIN (
                                SELECT 
                                    DISTINCT CONCAT( COD_PROVINCIA, COD_CANTON, COD_PARROQUIA ) AS DPA
                                FROM 
                                    ". $db->nameQuote('#__dpa_2011') ."
                                WHERE 
                                    COD_DISTRITO = '{$distrito}'
                            ) tb ON
                        ta.strCodDPA_ut = tb.DPA";

            $db->setQuery($query);
            $cantones = $db->loadObjectList();

            return $cantones;
        } catch( Exception $e ){
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    
    /**
     * 
     *  Cargo la relacion Distrito Canton
     * 
     *  En este proceso no se carga informacion en la tabla Unidad Territorial tb_ut_undTerritorial
     * 
     *  Ya que se registra la relacion existente en los cantones ya registrados y los distritos registrados 
     *  en el proceso "loadDistritos"
     * 
     */
    function loadDistritosCantones() 
    {
        try {
            $db = & JFactory::getDbo();
            $query = "  SELECT intID_ut, strCodDPA_ut
                        FROM 
                            " . $db->nameQuote('#__ut_undTerritorial') . "
                        WHERE 
                            intID_tut = 6
                        ORDER BY 
                            strCodDPA_ut;";
            
            $db->setQuery($query);
            $distritos = $db->loadObjectList();

            foreach( $distritos as $distrito ){
                //  Obtengo los cantones pertenecientes a un determinado distrito
                $cantones = $this->getCantonesDistritos( $distrito->strCodDPA_ut );

                foreach( $cantones as $canton ) {
                    //  Registro la relacion Cantones - Distritos
                    $this->setRelacionDPA( $canton->intID_ut, $distrito->intID_ut );
                }
            }
        } catch( Exception $e ) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    
    function loadDistritosParroquias()
    {
        try {
            $db = & JFactory::getDbo();
            $query = "  SELECT intID_ut, strCodDPA_ut
                        FROM 
                            " . $db->nameQuote('#__ut_undTerritorial') . "
                        WHERE 
                            intID_tut = 6
                        ORDER BY 
                            strCodDPA_ut;";
            
            $db->setQuery($query);
            $distritos = $db->loadObjectList();

            foreach( $distritos as $distrito ){
                //  Obtengo los cantones pertenecientes a un determinado distrito
                $parroquias = $this->getParroquiasDistritos( $distrito->strCodDPA_ut );

                foreach( $parroquias as $parroquia ) {
                    //  Registro la relacion Cantones - Distritos
                    $this->setRelacionDPA( $parroquia->intID_ut, $distrito->intID_ut );
                }
            }
        } catch( Exception $e ) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    //
    //  Calculo el nuevo Identificador de entidad
    //
    private function _getIdEntidad()
    {
        try {
            $db = & JFactory::getDbo();
            
            //  Obtengo el Ultimo Identificador
            $query = "  SELECT MAX( t1.intidentidad_ent ) as idEntidad
                        FROM 
                            " . $db->nameQuote('#__gen_entidad') . " t1;";
            
            $db->setQuery( $query );
            
            //  Calculo el nuevo Identificador de Entidad
            $idEntidad = $db->loadObject();

            return (int)$idEntidad->idEntidad;
        } catch( Exception $e ) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    public function udpEntidadProyectos()
    {
        try {
            //  Obtengo el ultimo Id Entidad
            $idEntidad = $this->_getIdEntidad();
            $db = &JFactory::getDbo();
            
            //  Obtengo los proyectos
            $query = "  SELECT  t1.intcodigo_pry as idProyecto, 
                                t1.intIdEntidad_ent as idEntidad
                        FROM 
                            " . $db->nameQuote('#__pfr_proyecto_frm') . " t1;";
            
            $db->setQuery( $query );
            
            //  Calculo el nuevo Identificador de Entidad
            $proyectos = $db->loadObjectList();

            foreach( $proyectos as $proyecto ){
                $idEntidad = $idEntidad + 1;
                
                //  Inserto registro en entidad
                $sqlAdd = " INSERT INTO ". $db->nameQuote('#__gen_entidad') ." 
                            (   intidentidad_ent,
                                intidtipoentidad_te )
                            VALUES
                            (
                                '". $idEntidad ."', 2
                            );";
                
                $db->setQuery( $sqlAdd );
                $db->query();
                
                //  Actualizo informacion de entidad en cada proyecto
                $sqlUpd = " UPDATE " . $db->nameQuote( '#__pfr_proyecto_frm' ) . "
                            SET intIdEntidad_ent = '". $idEntidad ."'
                            WHERE intcodigo_pry = '". $proyecto->idProyecto ."'";

                $db->setQuery( $sqlUpd );
                $db->query();
            }
        } catch( Exception $e ) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    
    /**
     *  Cargo las Zonas registradas en el archivo DPA 
     */
    public function loadRegiones()
    {
        try {
            $db = & JFactory::getDbo();
            $query = "  SELECT DISTINCT REGION
                        FROM 
                            " . $db->nameQuote('#__dpa_2011') . "
                        WHERE
                            REGION <> ''
                        ORDER BY 
                            REGION;";

            $db->setQuery($query);
            $regiones = $db->loadObjectList();
            
            foreach( $regiones as $region ){
                $strCodDPA_ut = 'R' . $region->REGION;
                
                //  Ingreso el registro en la tabla Unidad Territorial
                $intID_ut = $this->setUnidadTerritorial( '2', '1', $strCodDPA_ut, $region->REGION );
                
                //  Ingreso la relacion Unidad Territorial
                $this->setRelacionDPA( $intID_ut, '0' );
            }
        } catch( Exception $e ) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance( 'com_mantenimiento.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
    
    /**
     * 
     *  Retorna los Provincias que pertenecen a un determinada Region
     * 
     *  @param type $region   region a filtrar 
     *  @return type 
     */
    public function getProvinciasRegion( $region )
    {
        try {
            $db = & JFactory::getDbo();
            $query = "  SELECT  DISTINCT ta.intID_ut, 
                                ta.strCodDPA_ut, 
                                ta.strNombre_ut
                        FROM 
                            " . $db->nameQuote('#__ut_undTerritorial') . " ta
                        JOIN (
                                SELECT 
                                        COD_PROVINCIA AS DPA
                                FROM 
                                        " . $db->nameQuote('#__dpa_2011') . "
                                WHERE 
                                        REGION = '{$region}'
                            ) tb ON
                        ta.strCodDPA_ut = tb.DPA 
                        ORDER BY ta.strCodDPA_ut";
                            
            $db->setQuery($query);
            $provincias = $db->loadObjectList();
            
            return $provincias;
        } catch( Exception $e ){
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * 
     *  Cargo la relacion Region Provincia
     * 
     *  En este proceso no se carga informacion en la tabla Unidad Territorial tb_ut_undTerritorial
     * 
     *  Ya que se registra la relacion existente en los cantones ya registrados y los distritos registrados 
     *  en el proceso "loadDistritos"
     * 
     */
    function loadRegionProvincia() 
    {
        try {
            $db = & JFactory::getDbo();
            $query = "  SELECT  intID_ut, 
                                strCodDPA_ut
                        FROM 
                            " . $db->nameQuote('#__ut_undTerritorial') . "
                        WHERE 
                            intID_tut = 2
                        ORDER BY 
                            strCodDPA_ut;";
            
            $db->setQuery($query);
            $regiones = $db->loadObjectList();

            foreach( $regiones as $region ){
                //  Obtengo los cantones pertenecientes a un determinado distrito
                $provincias = $this->getProvinciasRegion( trim( $region->strCodDPA_ut, 'R' ) );

                foreach( $provincias as $provincia ) {
                    //  Registro la relacion Region - Provincias
                    $this->setRelacionDPA( $provincia->intID_ut, $region->intID_ut );
                }
            }
        } catch( Exception $e ) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    /**
     *  Cargo las Distritos registradas en el archivo DPA 
     */
    public function loadCircuitos()
    {
        try {
            $db = &JFactory::getDbo();
            $db->setQuery( true );
            
            $query = "  SELECT DISTINCT COD_CIRCUITO
                        FROM 
                            ". $db->nameQuote( '#__dpa_2011' ) ."
                        WHERE 
                            COD_CIRCUITO <> 'ND'
                        ORDER BY 
                            COD_CIRCUITO";
            
            $db->setQuery( $query );
            
            $circuitos = $db->loadObjectList();
            
            foreach( $circuitos as $circuito ) {
                $intID_tut = 7; // Circuito
                $inpID_dpa = 1; // 2008;
                
                //  Ingreso el registro en la tabla Unidad Territorial
                $intID_ut = $this->setUnidadTerritorial( $intID_tut, $inpID_dpa, $circuito->COD_CIRCUITO, $circuito->COD_CIRCUITO );
                
                //  Ingreso la relacion Unidad Territorial
                $this->setRelacionDPA( $intID_ut, '0' );
            }
        } catch( Exception $e ) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance( 'com_mantenimiento.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    
    
    /**
     * 
     *  Retorna los cantones pertenecientes a un determinado Distrito
     * 
     *  @param type $distrito   Distrito a filtrar 
     *  @return type 
     */
    public function getParroquiasCircuitos( $circuito )
    {
        try {
            $db = & JFactory::getDbo();
            $query = "  SELECT  ta.intID_ut, 
                                ta.strCodDPA_ut, 
                                ta.strNombre_ut
                        FROM 
                            " . $db->nameQuote('#__ut_undTerritorial') . " ta
                        JOIN (
                                SELECT 
                                    CONCAT( COD_PROVINCIA, COD_CANTON, COD_PARROQUIA ) AS DPA
                                FROM 
                                    " . $db->nameQuote('#__dpa_2011') . "
                                WHERE 
                                    COD_CIRCUITO = '{$circuito}'
                            ) tb ON
                        ta.strCodDPA_ut = tb.DPA";

            $db->setQuery($query);
            $parroquias = $db->loadObjectList();

            return $parroquias;
        } catch( Exception $e ){
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    public function loadCircuitosParroquias() 
    {
        try {
            $db = & JFactory::getDbo();
            $query = "  SELECT  intID_ut, 
                                strCodDPA_ut
                        FROM 
                            " . $db->nameQuote('#__ut_undTerritorial') . "
                        WHERE 
                            intID_tut = 7
                        ORDER BY 
                            strCodDPA_ut;";
            
            $db->setQuery($query);
            $circuitos = $db->loadObjectList();

            foreach( $circuitos as $circuito ){
                //  Obtengo los cantones pertenecientes a un determinado distrito
                $parroquias = $this->getParroquiasCircuitos( $circuito->strCodDPA_ut );

                foreach( $parroquias as $parroquia ) {
                    //  Registro la relacion Parrquias - Circuitos
                    $this->setRelacionDPA( $parroquia->intID_ut, $circuito->intID_ut );
                }
            }
        } catch( Exception $e ) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    
    
    
    /**
     * 
     *  Retorna los cantones pertenecientes a un determinado Circuito
     * 
     *  @param type $circuito   Circuito a filtrar 
     *  @return type 
     */
    public function getCantonesCircuitos( $circuito )
    {
        try {
            $db = & JFactory::getDbo();
            $query = "  SELECT  ta.intID_ut, 
                                ta.strCodDPA_ut, 
                                ta.strNombre_ut
                        FROM 
                            " . $db->nameQuote('#__ut_undTerritorial') . " ta
                        JOIN (
                                SELECT 
                                    CONCAT( COD_PROVINCIA, COD_CANTON ) AS DPA
                                FROM 
                                    " . $db->nameQuote('#__dpa_2011') . "
                                WHERE 
                                    COD_CIRCUITO = '{$circuito}'
                            ) tb ON
                        ta.strCodDPA_ut = tb.DPA";

            $db->setQuery($query);
            $parroquias = $db->loadObjectList();

            return $parroquias;
        } catch( Exception $e ){
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    public function loadCircuitosCantones() 
    {
        try {
            $db = & JFactory::getDbo();
            $query = "  SELECT  intID_ut, 
                                strCodDPA_ut
                        FROM 
                            " . $db->nameQuote('#__ut_undTerritorial') . "
                        WHERE 
                            intID_tut = 7
                        ORDER BY 
                            strCodDPA_ut;";
            
            $db->setQuery($query);
            $circuitos = $db->loadObjectList();

            foreach( $circuitos as $circuito ){
                //  Obtengo los cantones pertenecientes a un determinado distrito
                $cantones = $this->getCantonesCircuitos( $circuito->strCodDPA_ut );

                foreach( $cantones as $canton ) {
                    //  Registro la relacion Parrquias - Circuitos
                    $this->setRelacionDPA( $canton->intID_ut, $circuito->intID_ut );
                }
            }
        } catch( Exception $e ) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_mantenimiento.tables.dpa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
}