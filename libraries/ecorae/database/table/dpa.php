<?php

/**
 * @package     Joomla.Platform
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined( 'JPATH_PLATFORM' ) or die;

jimport( 'joomla.database.tablenested' );
require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'models' . DS . 'tipFigProject.php';
require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'models' . DS . 'fotosProject.php';

/**
 * Category table
 *
 * @package     Joomla.Platform
 * @subpackage  Table
 * @since       11.1
 */
class JTableDPAActiva extends JTable
{

    /**
     * Constructor
     *
     * @param   JDatabase  &$db  A database connector object
     *
     * @since   11.1
     */
    public function __construct( &$db )
    {
        parent::__construct( '#__ut_dpa', 'id', $db );

        $this->access = ( int ) JFactory::getConfig()->get( 'access' );
    }

    function getTiposUniTerritoriales()
    {
        try{
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );

            $query->select( '    ut.intId_tut        AS id, 
                                ut.strNombre_tut    AS nombre' );
            $query->from( '#__ut_tipoUndTerritorial AS ut' );
            $query->where( 'ut.inpVigencia_tut = 1' );
            $query->where( 'ut.intID_tut NOT IN ( 3,4,5 )' );
            $db->setQuery( $query );
            $result = ($db->loadObjectList()) ? $db->loadObjectList() : false;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return ( array ) $result;
    }

    /**
     * 
     * Retorna una lista de unidades territoriales de acuerdo al tipo de Unidad Territorial, 
     * por ejemplo: tipo de Unidad Territorial REGION: Costa, Sierra, ....
     * 
     * @param type $tipoUnidadTerritorial   Identificador del tipo de Unidad Territorial ( Zona, Region, Circuito, Distrito )
     * 
     * @return type
     */
    function getUnidadesTerritoriales( $tipoUnidadTerritorial )
    {
        try{
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );

            $query->select( "   ut.intID_ut AS id, 
                                CASE ut.strNombre_ut    WHEN ' C' THEN CONCAT(  '(', ut.strCodDPA_ut,  ') ', 'COSTA' )
                                                        WHEN ' I' THEN CONCAT(  '(', ut.strCodDPA_ut,  ') ', 'INSULAR' )
                                                        WHEN ' O' THEN CONCAT(  '(', ut.strCodDPA_ut,  ') ', 'ORIENTE' )
                                                        WHEN ' S' THEN CONCAT(  '(', ut.strCodDPA_ut,  ') ', 'SIERRA' )
                                                        ELSE CONCAT(  '(', ut.strCodDPA_ut,  ') ', ut.strNombre_ut )
                                END AS nombre" );
            $query->from( '#__ut_undTerritorial AS ut' );
            $query->innerJoin( ' #__ut_dpa AS d ON ut.inpID_dpa = d.inpID_dpa' );
            $query->where( " ut.intID_tut ='" . $tipoUnidadTerritorial . "' AND d.inpVigencia_dpa = 1 " );
            $db->setQuery( $query );
            $result = ($db->loadObjectList()) ? $db->loadObjectList() : false;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }

        return ( array ) $result;
    }

    /**
     * 
     * Retorna una lista de provincias 
     * 
     * @param type $idZona
     * @return type
     */
    function getProvinciasPorZona( $idZona )
    {
        try{
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );

            $query->select( "   DISTINCT ut.intID_ut AS id,
                                ut.strNombre_ut AS nombre,
                                ut.lat AS lat,
                                ut.longi AS longi" );
            $query->from( '#__ut_undTerritorial AS ut ' );
            $query->innerJoin( ' #__vw_dpa AS d ON ut.intID_ut = d.intIDProvincia_dpa' );
            $query->where( "d.intIDEntidad_dpa = '" . $idZona . "'" );
            $query->order( "ut.strNombre_ut asc" );

            $db->setQuery( $query );
            $result = ($db->loadObjectList()) ? $db->loadObjectList() : false;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return ( array ) $result;
    }

    /**
     * 
     * Retorna una lista de Cantones pertenecientes a una provincia 
     * en un determinada Zona
     * 
     * @param type $idProvincia Identificador de la provincia
     * @param type $idZona      Identificador de la Zona
     * 
     * @return type
     */
    function getCantonesPorProvincia( $idZona, $idProvincia )
    {
        try{
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );

            $query->select( "    DISTINCT ut.intID_ut AS id,
                                ut.strNombre_ut AS nombre,
                                ut.lat AS lat,
                                ut.longi AS longi" );
            $query->from( ' #__ut_undTerritorial AS ut ' );
            $query->innerJoin( ' #__vw_dpa AS d ON ut.intID_ut = d.intIDCanton_dpa ' );
            $query->where( " d.intIDEntidad_dpa = '" . $idZona . "'" );
            $query->where( " d.intIDProvincia_dpa = '" . $idProvincia . "'" );
            $query->where( " ut.intID_tut = '4'" );
            $query->order( " ut.strNombre_ut asc" );

            $db->setQuery( $query );
            $result = ($db->loadObjectList()) ? $db->loadObjectList() : false;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return ( array ) $result;
    }

    /**
     * 
     * Retona una lista de Parroquias pertenecientes a un canton
     * en una determinada Zona
     * 
     * @param type $idZona      Identificador de la Zona
     * @param type $idCanton    Identidicador del canton
     * 
     * @return type
     */
    function getParroquiasPorCanton( $idZona, $idCanton )
    {
        try{
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );

            $query->select( "   DISTINCT ut.intID_ut AS id,
                                ut.strNombre_ut AS nombre,
                                ut.lat AS lat,
                                ut.longi AS longi" );
            $query->from( '#__ut_undTerritorial AS ut ' );
            $query->innerJoin( ' #__vw_dpa AS d ON ut.intID_ut = d.intIDParroquia_dpa' );
            $query->where( " d.intIDEntidad_dpa = '" . $idZona . "'" );
            $query->where( "d.intIDCanton_dpa = '" . $idCanton . "'" );
            $query->where( " ut.intID_tut = '5'" );
            $query->order( "ut.strNombre_ut asc" );

            $db->setQuery( $query );
            $result = ($db->loadObjectList()) ? $db->loadObjectList() : false;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return ( array ) $result;
    }

    function getParroquiasPorCantonYDistrito( $idCanton, $idDistrito )
    {
        try{
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );

            $query->select( " DISTINCT ut.intID_ut AS id,ut.strNombre_ut AS nombre,ut.lat AS lat,ut.longi AS longi" );
            $query->from( '#__ut_undTerritorial AS ut  ' );
            $query->innerJoin( ' #__vw_dpa AS d ON ut.intID_ut = d.intIDParroquia_dpa' );
            $query->where( "d.intIDCanton_dpa = '{$idCanton}'" );
            $query->where( "d.intIDEntidad_dpa = '{$idDistrito}'" );
            $query->order( "ut.strNombre_ut asc" );

            $db->setQuery( $query );
            $result = ($db->loadObjectList()) ? $db->loadObjectList() : false;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return ( array ) $result;
    }

    function getListaHijos( $idPadre )
    {
        try{
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );

            $query->select( "ut.intID_ut AS id,ut.strNombre_ut AS nombre, ut.lat AS lat,ut.longi AS longi" );
            $query->from( '#__ut_undTerritorial AS ut  ' );
            $query->where( "ut.intID_ut in(
					SELECT rdpa.intID_ut FROM dev_siita.tb_ut_relacionDPA AS rdpa
					where rdpa.intIDPadre_ut = '{$idPadre}'   )" );
            $query->order( "ut.strNombre_ut asc" );

            $db->setQuery( $query );
            $result = ($db->loadObjectList()) ? $db->loadObjectList() : false;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return ( array ) $result;
    }

    function getLstCoordProy( $idProyecto )
    {
        $db = JFactory::getDBO();
        $tabla = "#__pfr_coordenadas";
        $query = "SELECT coor.fltLatitud_cord, coor.fltLongitud_cord 
                 FROM" . $db->nameQuote( $tabla ) . " as coor
                WHERE intCodigo_pry = '" . $idProyecto . "';";
        $db->setQuery( $query );
        $puntos = ($db->loadObjectList()) ? $db->loadObjectList() : false;
        return ( $puntos );
    }

    /**
     * 
     * @param int $idUt   id de la unidad territorial
     * @param int $tut  tipo de unidad territorial
     * @param int $idPrograma   id programa del que se desea los proyectos
     * @return type
     */
    function getListaProyectos( $idUt, $tut, $idPrograma )
    {
        try{
            $db = JFactory::getDbo();
            $lstProyectos = false;
            if( $idUt != 0 && $tut != 0 && $idPrograma != 0 ){

                $query = $this->getSqlProyectos( $idUt, $tut, $idPrograma );
                $db->setQuery( $query );

                $result = ( $db->loadObjectList() ) ? $db->loadObjectList() 
                                                    : false;

                if( $result ){
                    $lstProyectos = $this->_proyectsObjectConvert( $result, $idUt, $tut );
                }
            }

            return $lstProyectos;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * @abstract retorna el nombre de la columa que se necesita para filtrar de la vista 
     * @param int $tut identificador de el tipo de unidad territorial
     */
    private function _getColName( $tut )
    {
        $colName = 'intIDEntidad_dpa';
        switch( $tut ){
            case 3:$colName = 'intIDProvincia_dpa';
                break;
            case 4:$colName = 'intIDCanton_dpa';
                break;
            case 5:$colName = 'intIDParroquia_dpa';
                break;
        }
        return $colName;
    }

    /**
     * 
     * Retorna una lista con todos los Proyectos que pertenecen a un determinado Programa
     * 
     * @param type $idUT        Identificador de Unidad Territorial
     * @param type $tut         Tipo de Unidad Terrotorial
     * @param type $idPrograma  Identificador del Programa
     * 
     * @return type
     * 
     */
    function getSqlProyectos( $idUT, $tut, $idPrograma )
    {
        $colName = $this->_getColName( $tut );

        $db = JFactory::getDBO();
        $query = $db->getQuery( true );
        
        $query->select( "   DISTINCT t1.intCodigo_pry AS id,
                            UPPER( t1.strNombre_pry ) AS proyecto, 
                            t1.intCodigo_pry, 
                            t1.strNombre_pry, 
                            t1.dteFechaInicio_stmdoPry, 
                            t1.dteFechaFin_stmdoPry, 
                            t1.inpDuracion_stmdoPry, 
                            t1.dcmMonto_total_stmdoPry, 
                            t1.intTotal_benDirectos_pry,
                            t2.strSimbolo_unimed AS simUnidadMedia" );
        $query->from( "#__pfr_proyecto_frm AS t1" );
        $query->innerJoin( "#__gen_unidad_medida AS t2 ON t2.intCodigo_unimed = t1.intCodigo_unimed" );
        $query->innerJoin( "#__ubicgeo_pry AS t3 ON t1.intCodigo_pry = t3.intCodigo_pry" );
        
        if( $tut > 2 ){
            $query->innerJoin( "#__vw_dpa t4 ON t4.intIDProvincia_dpa = '". $idUT ."' OR t4.intIDCanton_dpa = '". $idUT ."' OR t4.intIDParroquia_dpa = '". $idUT ."'" );
        }else{
            $query->innerJoin( "#__vw_dpa t4 ON t3.intID_ut = t4.intIDProvincia_dpa OR t3.intID_ut = t4.intIDCanton_dpa OR t3.intID_ut = t4.intIDParroquia_dpa" );
        }
        
        $query->where( "t1.intCodigo_prg = '". $idPrograma ."'" );
        $query->order( "2" );
        
        return $query;
    }

    function _proyectsObjectConvert( $proyectos, $idUt, $idtut )
    {
        $proyectosJSON = array();
        try{
            if( $proyectos ){
                foreach( $proyectos as $proyecto ){
                    $proyectoJSON                               = ( object ) null;
                    $proyectoJSON->property                     = ( object ) null;
                    $proyectoJSON->property->name               = $proyecto->proyecto;
                    $proyectoJSON->property->hasCheckbox        = true;
                    $proyectoJSON->property->id                 = 'proy-' . $proyecto->intCodigo_pry;
                    $proyectoJSON->type                         = "proyecto";
                    $proyectoJSON->data                         = ( object ) null;
                    $proyectoJSON->data->responsable            = $proyecto->responsable;
                    $proyectoJSON->data->id                     = $proyecto->intCodigo_pry;
                    $proyectoJSON->data->strNombre_ut           = $proyecto->strNombre_ut;
                    $proyectoJSON->data->inpDuracion_stmdoPry   = $proyecto->inpDuracion_stmdoPry;
                    $proyectoJSON->data->fInicioEst             = $proyecto->dteFechaInicio_stmdoPry;
                    $proyectoJSON->data->fFinEst                = $proyecto->dteFechaFin_stmdoPry;
                    $proyectoJSON->data->totBeneficiarios       = $proyecto->intTotal_benDirectos_pry;
                    $proyectoJSON->data->totMonto               = $proyecto->dcmMonto_total_stmdoPry;
                    $proyectoJSON->data->simUnidadMedia         = $proyecto->simUnidadMedia;

                    $proyectoJSON->data->iconoName              = self::getProyectoIconoName( $proyecto->intCodigo_pry );
                    $proyectoJSON->data->elemento               = ( object ) null;

                    $proyectoJSON->data->elemento->id           = $proyecto->intCodigo_pry;
                    $proyectoJSON->data->elemento->ubUniTe      = self::getCoorUnTerritoriaActual( $proyecto->intCodigo_pry, $idUt, $idtut ); // le asignamos la coordenada de la Unidad territorial
                    $proyectoJSON->data->elemento->cobertura    = self::getCoberturaProyecto( $proyecto->intCodigo_pry ); // le asignamos la coordenada de la Unidad territorial

                    $proyectoJSON->data->elemento->figuras      = self::getFigsProyect( $proyecto->id ); // asignamos los diferentes formas que puede tener un proyecto.

                    $proyectoJSON->data->elemento->fotos        = self::getFotosProyect( $proyecto->id );

                    $proyectosJSON[] = $proyectoJSON;                    
                }

            }
            
            return $proyectosJSON;
            
        } catch( Exception $e ){
            echo $e->getMessage();
        }
    }

    function getCoorUnTerritoriaActual( $idProyecto, $id, $idtut )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );
            $colName = $this->_getColName( $idtut );
            $query->select( "   DISTINCT concat( t2.strNombreProvincia_dpa,', ',t2.strNombreCanton_dpa,', ',t2.strNombreParroquia_dpa ) as strNombre_ut, 
                                t2.lat AS fltLatitud_cord, 
                                t2.longi AS fltLongitud_cord" );
            $query->from( '#__ubicgeo_pry AS t1' );
            $query->innerJoin( "#__vw_dpa AS t2 ON t1.intID_ut = t2.intIDProvincia_dpa
                                OR t1.intID_ut = t2.intIDCanton_dpa
                                OR t1.intID_ut = t2.intIDParroquia_dpa" );
            $query->where( "t1.intCodigo_pry = '". $idProyecto ."' " );
            $query->where("t2.lat IS NOT NULL");
            $query->where("t2.longi IS NOT NULL");
            $query->where("t2.lat >= -90");
            $query->where("t2.lat <= 90");
            $query->where("t2.longi >= -180");
            $query->where("t2.longi <= 180");

            $db->setQuery( $query );

            $puntos = ($db->loadObjectList())   ? $db->loadObjectList() 
                                                : false;

            return ( $puntos );
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    function getProyectoIconoName( $idProyecto )
    {
        $nameIcon = 'default.png';
        $path = JPATH_SITE . DS . 'components' . DS . 'com_proyectos' . DS . 'images' . DS . $idProyecto . DS . 'icon';

        if( file_exists( $path ) ){
            $count = 0;
            $directorio = opendir( $path );

            while( $archivo = readdir( $directorio ) ){
                if( $archivo != "." && $archivo != ".." ){
                    $nameIcon = $archivo;
                }
            }

            closedir( $directorio );
        }

        return $nameIcon;
    }

    /**
     * Retorna los lugares donde tambien se ejecutan el poryecto.
     * @param type $idProyecto
     * @return type
     */
    function getCoberturaProyecto( $idProyecto )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );
            
            $query->select( "   DISTINCT
                                IF( t2.strNombreProvincia_dpa IS NULL, 'ND', t2.strNombreProvincia_dpa ) AS provincia,
                                IF( t2.strNombreCanton_dpa IS NULL, 'ND', t2.strNombreCanton_dpa ) AS canton, 
                                IF( t2.strNombreParroquia_dpa  IS NULL, 'ND', t2.strNombreParroquia_dpa ) AS parroquia" );
            $query->from( '#__vw_dpa  AS t2' );
            $query->innerJoin( "(   SELECT DISTINCT t1.intID_ut 
                                    FROM #__ubicgeo_pry AS t1
                                    WHERE t1.intCodigo_pry = {$idProyecto} ) AS t3 ON
                                            t2.intIDParroquia_dpa = t3.intID_ut OR
                                            t2.intIDCanton_dpa = t3.intID_ut OR
                                            t2.intIDProvincia_dpa = t3.intID_ut" );
            $query->order( "t2.strNombreProvincia_dpa", "t2.strNombreCanton_dpa", "t2.strNombreParroquia_dpa" );
            
            $db->setQuery( $query );
            return($db->loadObjectList())   ? $db->loadObjectList() 
                                            : false;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    function getFigsProyect( $idProyecto )
    {
        try{
            // creamos un objeto de el modelo 
            $model = new tipfigsProjectModel();
            // llamamos al funcioN  a la que le pasamos el id de el proyecto
            return $model->getFigsProject( $idProyecto );
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.modelspa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    function getFotosProyect( $idProyecto )
    {
        try{
            // creamos un objeto de el modelo 
            $model = new fotosProjectModel();
            // llamamos al funcioN  a la que le pasamos el id de el proyecto
            return $model->getFotosProject( $idProyecto );
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.modelspa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    function getBeneficiarios( $idUniTer, $tut )
    {
        try{
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );

            $query->select( "ut.intID_ut AS id,ut.strNombre_ut AS nombre,ut.lat AS lat,ut.longi AS longi" );
            $query->from( '#__ut_undTerritorial AS ut  ' );
            //$query->join('inner','#__ut_undTerritorial AS u ON r.intID_ut = u.intID_ut');
            $query->where( "ut.intID_ut in(
					SELECT rdpa.intID_ut FROM dev_siita.tb_ut_relacionDPA AS rdpa
					WHERE rdpa.intIDPadre_ut = '{$idUniTer}')" );
            $query->order( "ut.strNombre_ut asc" );

            $db->setQuery( $query );
            $result = ($db->loadObjectList()) ? $db->loadObjectList() : false;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return ( array ) $result;
    }

    /**
     *
     * Retorna lista de cantones pertenecientes a una determanda Provincia
     * 
     * @return type 
     */
    public function lstCantones( $idProvincia )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '    DISTINCT dpa.intIDCanton_dpa AS id, 
                                dpa.strNombreCanton_dpa AS nombre' );

            $query->from( '#__vw_dpa dpa' );
            $query->where( 'dpa.intIDProvincia_dpa = ' . $idProvincia );
            $query->order( 'dpa.strNombreCanton_dpa' );

            $db->setQuery( ( string ) $query );

            $db->query();

            $rstCantones = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : false;

            return $rstCantones;
        } catch( Exception $e ){
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
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( '   DISTINCT dpa.intIDParroquia_dpa AS id, 
                                dpa.strNombreParroquia_dpa AS nombre' );
            $query->from( '#__vw_dpa dpa' );
            $query->where( 'dpa.intIDCanton_dpa = ' . $idCanton );
            $query->order( 'dpa.strNombreParroquia_dpa' );

            $db->setQuery( ( string ) $query );

            $db->query();

            $rstParroquias = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : false;

            return $rstParroquias;
        } catch( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_proyectos.tables.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}
