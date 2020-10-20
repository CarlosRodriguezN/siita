<?php

/**
 * @package     Joomla.Platform
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined( 'JPATH_PLATFORM' ) or die;
//require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'models' . DS . 'mapaDPA.php';
//require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'dpa.php';

jimport( 'joomla.database.tablenested' );

/**
 * Category table 
 *
 * @package     Joomla.Platform
 * @subpackage  Table
 * @since       11.1
 */
class JTableUnidadGestion extends JTable
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
        parent::__construct( '#__gen_unidad_gestion', 'intCodigo_ug', $db );

        $this->access = (int) JFactory::getConfig()->get( 'access' );
    }

    function getUnidadesGestion( $rucInstitucion )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            
            $query->select( '   ug.intCodigo_ug AS id, 
                                upper( ug.strNombre_ug ) AS nombre' );
            $query->from( '#__gen_unidad_gestion AS ug' );
            $query->join( 'inner', '#__gen_institucion AS i ON i.intCodigo_ins = ug.intCodigo_ins' );
            $query->where( "i.strRuc_ins = '{$rucInstitucion}'" );
            $query->where( 'ug.published = 1' );
            
            $db->setQuery( (string) $query );
            $db->query();

            $unidadesGestion = ( $db->getNumRows() > 0 )? $db->loadObjectList()
                                                        : array();

            return $unidadesGestion;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.pei.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        
    }
    
    function getEntidadUG( $idUG )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );

            $query->select( 't1.intIdentidad_ent    AS idEntidadUG,
                             t1.strNombre_ug        AS nombreUG,
                             t1.strAlias_ug         AS aliasUG,
                             t1.intCodigo_ug        AS intCodigo_ug,
                             t1.tb_intCodigo_ug     AS tb_intCodigo_ug,
                             t1.intCodigo_ins       AS intCodigo_ins,
                             t1.intTpoUG_ug         AS intTpoUG_ug' );
            $query->from( '#__gen_unidad_gestion t1' );
            $query->where( "t1.intCodigo_ug = ". $idUG );
            $db->setQuery( (string) $query );
            $db->query();

            $dtaUGestion = ( $db->getNumRows() > 0 )? $db->loadObject() 
                                                    : false;

            return $dtaUGestion;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.pei.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        
    }
    
    function getAnioPoaUG( $idEntInd )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->select( "DATE_FORMAT( ugr.dteFechaInicio_ugr, '%Y' )    AS anio,
                            ugr.dteFechaInicio_ugr AS fecha" );
            $query->from( '#__ind_ug_responsable ugr' );
            $query->innerJoin( '#__ind_indicador_entidad ie ON ie.intIdIndEntidad = ugr.intIdIndEntidad' );
            $query->where( "ie.intIdIndEntidad = {$idEntInd}" );
            $query->where( "ugr.inpVigencia_ugr = 1 ORDER BY fecha DESC LIMIT 1" );
            
            $db->setQuery( (string) $query );
            $db->query();

            $result = ($db->getAffectedRows() > 0) ? $db->loadObject() : false;
            
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.pei.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $result;
    }
    
    function existPoaUG( $idEntUG, $anio )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->select( 'intId_pi AS idPoa' );
            $query->from( '#__pln_plan_institucion' );
            $query->where( "intIdentidad_ent = {$idEntUG}");
            $query->where( "blnVigente_pi = {$anio}");
            $query->where( 'published = 1 LIMIT 1');
            
            $db->setQuery( (string) $query );
            $db->query();
            
            $result = ($db->loadObject() > 0) ? $db->loadResult() : false;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.pei.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $result;
    }
    
    function getEntUGRInd( $idUGR )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->select( 'intIdentidad_ent   AS idEntidadUG,
                            strNombre_ug        AS nombreUG,
                            strAlias_ug         AS aliasUG' );
            $query->from( '#__gen_unidad_gestion ug' );
            $query->where( "ug.intCodigo_ug = {$idUGR}" );
            $db->setQuery( (string) $query );
            $db->query();

            $idUndGestion = ($db->getAffectedRows() > 0) ? $db->loadObject() : false ;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.pei.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $idUndGestion;
    }
    
    function getLstPoas( $idEntUG )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->select( 'intId_pi AS idPoa' );
            $query->from( '#__pln_plan_institucion ug' );
            $query->where( "intIdentidad_ent = {$idEntUG}" );
            $query->where( "intId_tpoPlan = 2" );       //  2 id de tipo de plan POA
            $db->setQuery( (string) $query );
            $db->query();

            $idUndGestion = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : false ;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.pei.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $idUndGestion;
    }
    
    /**
     * Recupera la UNIDAD DE gESTION
     * @param type $idUnidadGestion
     * @return type
     */
    public function getUnidadGestion($idUnidadGestion){
       try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->select( 'intIdentidad_ent    AS idEntidadUG,
                             strNombre_ug        AS nombreUG,
                             strAlias_ug         AS aliasUG,
                             intCodigo_ug	 AS intCodigo_ug,
                             tb_intCodigo_ug	 AS tb_intCodigo_ug,
                             intCodigo_ins	 AS intCodigo_ins,
                             intTpoUG_ug         AS intTpoUG_ug'
                    );
            $query->from( '#__gen_unidad_gestion' );
            $query->where( "intCodigo_ug = {$idUnidadGestion}" );
            $db->setQuery( (string) $query );
            $db->query();

            $idUndGestion = ($db->getAffectedRows() > 0) ? $db->loadObject() : false;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.pei.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $idUndGestion;
    }
    /**
     * Recupera la UNIDAD DE gESTION
     * @param type $idEntidad
     * @return type
     */
    public function getUnidadGestionByEntidad($idEntidad){
       try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select(''
                    . 'ug.intCodigo_ug,'
                    . 'ug.intIdentidad_ent,'
                    . 'ug.intIdentidad_ent AS idEntidad,'
                    . 'ug.tb_intCodigo_ug,'
                    . 'ug.intCodigo_ins,'
                    . 'ug.strNombre_ug,'
                    . 'ug.strAlias_ug,'
                    . 'ug.intTpoUG_ug,'
                    . 'ug.published,'
                    . 'ug.checked_out,'
                    . 'ug.checked_out_time,'
                    . 'en.strUrlTableU_ent'
                    . '');
            $query->from('#__gen_unidad_gestion ug');
            $query->where("ug.intIdentidad_ent = {$idEntidad}");

            $query->join(' inner', '#__gen_entidad AS en ON  ug.intIdentidad_ent = en.intIdentidad_ent');

            $query->where( "ug.intCodigo_ug <> 0" );       //  2 id de tipo de plan POA
            
            $db->setQuery( (string) $query );
            $db->query();

            $idUndGestion = ($db->getAffectedRows() > 0) ? $db->loadObject() : false ;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.pei.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $idUndGestion;
    }
    /**
     * Recupera las unidades de gestion hijas de una unidad de gestion
     * @param type $idUndGesPadre
     * @return type
     */
    public function getHijosUnidadGestion($idUndGesPadre){
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->select( '*' );
            $query->from( '#__gen_unidad_gestion ug' );
            $query->where( "tb_intCodigo_ug = {$idUndGesPadre}" );
            $query->where( "intCodigo_ug <> 0" );       //  2 id de tipo de plan POA
            $db->setQuery( (string) $query );
            $db->query();
            $idUndGestion = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : array() ;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.pei.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $idUndGestion;
        
    }
    
    
    
    /**
     * 
     * Retorna una lista de unidades de Gestion Vigentes
     * 
     * @return type
     * 
     */
    public function getLstUndGestion()
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );

            $query->select(''
                    . 't1.intCodigo_ug     AS idUndGestion,'
                    . 't1.intIdentidad_ent AS idEntidad,'
                    . 't1.tb_intCodigo_ug  AS idPadre,'
                    . 't1.strNombre_ug     AS nombre,'
                    . 't1.strAlias_ug      AS alias,'
                    . 't1.intTpoUG_ug      AS tpoUndGes,'
                    . 't1.published        AS published');
            $query->from( '#__gen_unidad_gestion t1' );            
            $query->where( 't1.published = 1' );
            $query->order( 't1.strNombre_ug' );

            $db->setQuery( (string) $query );
            $db->query();

            $lstUndGestion = ( $db->getNumRows() > 0 )  ? $db->loadObjectList()
                                                        : array();

            return $lstUndGestion;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.pei.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    public function getPlnVigenteUG($idUG) 
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->select( 't1.intId_pi    AS idPlan,'
                    . 'dteFechainicio_pi    AS dtaInicioPln,'
                    . 'blnVigente_pi        AS vigentePln' );
            $query->from( '#__pln_plan_institucion t1' );
            $query->innerJoin( '#__gen_unidad_gestion t2 ON t1.intIdentidad_ent = t2.intIdentidad_ent');
            $query->where( "t2.intCodigo_ug = {$idUG}" );
            $query->where( "t1.blnVigente_pi = 1" );
            $query->where( "t1.published = 1" );
            $db->setQuery( (string) $query );
            $db->query();
            $plan = ($db->getAffectedRows() > 0) ? $db->loadObject() : array() ;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.pei.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $plan;
    }
    
}