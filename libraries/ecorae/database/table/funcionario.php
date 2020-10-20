<?php

/**
 * @package     Joomla.Platform
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('JPATH_PLATFORM') or die;
//require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'models' . DS . 'mapaDPA.php';
//require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'dpa.php';

jimport('joomla.database.tablenested');

/**
 * Category table 
 *
 * @package     Joomla.Platform
 * @subpackage  Table
 * @since       11.1
 */
class JTableUnidadFuncionario extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabase  &$db  A database connector object
     *
     * @since   11.1
     */
    public function __construct(&$db) {
        parent::__construct('#__gen_funcionario', 'intCodigo_fnc', $db);

        $this->access = (int) JFactory::getConfig()->get('access');
    }
    
    /**
     *  Retorna la lista de funcionarios de una unidad de gestion 
     *  para los field Funcionarios Responsables
     * @param type $idUnidadGestion
     * @return type
     */
    function getLstRespoUniGestion($idUnidadGestion) {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('t1.intId_ugf AS id,
                            UPPER (concat( t2.strApellido_fnc," ",t2.strNombre_fnc )) AS nombre ');
            $query->from('#__gen_ug_funcionario AS t1 ');
            $query->join('inner', 'tb_gen_funcionario AS t2 ON t2.intCodigo_fnc=t1.intCodigo_fnc ');
            $query->where("t1.intCodigo_ug = {$idUnidadGestion} ");
            $query->where('t1.intCodigo_fnc <> 0 ');
            $query->where('t1.published = 1 ');
            
            $db->setQuery((string) $query);
            $db->query();
            $unidadesGestion = ($db->loadObjectList()) ? $db->loadObjectList() : false;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.programas.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
        return $unidadesGestion;
    }
    
    /**
     * Retorna una lista de funcionarios pertenecientes a una determindad 
     * Unidad de Gestion
     * @param type $idUndGestion    Identificador de Unidad de Gestion
     * @return type
     */
    public function lstFuncionariosPorUG( $idUndGestion )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select(''
                    . ' t1.intId_ugf                                            AS id,'
                    . ' CONCAT( t2.strNombre_fnc, " ", t2.strApellido_fnc  )    AS nombre,'
                    . ' t2.intCodigo_fnc                                        AS idFuncionario,'
                    . ' t2.intIdentidad_ent                                     AS idEntidad,'
                    . ' t3.strUrlTableU_ent                                     AS urlTableU'
                    . '');
            $query->from('#__gen_ug_funcionario AS t1');
            $query->innerJoin('#__gen_funcionario AS t2 ON t1.intCodigo_fnc = t2.intCodigo_fnc');
            $query->innerJoin('#__gen_entidad AS t3 ON t3.intIdentidad_ent = t2.intIdentidad_ent');
            $query->where(' t1.intCodigo_ug = ' . $idUndGestion);
            $query->where(' t1.published = 1');
            $query->where(' t2.intCodigo_fnc <> 0');
            
            $db->setQuery((string) $query);
            $db->query();

            $lstFuncionarios = ( $db->loadObjectList() ) ? $db->loadObjectList() : array();
            return $lstFuncionarios;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.programas.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     * Retorna una lista de funcionarios pertenecientes a una determindad 
     * Unidad de Gestion
     * @param type $idUndGestion    Identificador de Unidad de Gestion
     * @return type
     */
    public function lstFuncionariosByUG( $idUndGestion )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select("ugf.intId_ugf                   AS idUgFnci, 
                            ugf.intCodigo_ug                AS idUg, 
                            ug.strNombre_ug                 AS nombreUg, 
                            ugf.inpCodigo_cargo             AS idCargoFnci, 
                            cf.strDescripcion_cargo         AS descCargoFnci, 
                            ugf.intCodigo_fnc               AS idFuncionario, 
                            CONCAT(fun.strNombre_fnc, ' ', fun.strApellido_fnc ) AS nombreFnci, 
                            ugf.dteFechaInicio_ugf          AS fechaInicio, 
                            ugf.dteFechaFin_ugf             AS fechaFin,
                            ugf.dteFechaModificacion_ugf    AS fechaUpd,
                            ugf.published                   AS published");
            $query->from("#__gen_ug_funcionario ugf");
            $query->innerJoin("#__gen_unidad_gestion ug ON ug.intCodigo_ug = ugf.intCodigo_ug");
            $query->innerJoin("#__gen_funcionario fun ON fun.intCodigo_fnc = ugf.intCodigo_fnc");
            $query->innerJoin("#__gen_ug_cargo cf ON cf.intId_ugc = ugf.intId_ugc");
            $query->where("ugf.intCodigo_ug = " . $idUndGestion);
            $query->where("ugf.published = 1");
            $query->where("ugf.intCodigo_fnc <> 0");
            
            $db->setQuery((string) $query);
            $db->query();

            $lstFuncionarios = ( $db->loadObjectList() ) ? $db->loadObjectList() : array();
            return $lstFuncionarios;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.programas.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     *  Retorna la entidad de un funcionario
     * @param type $id
     * @return type
     */
    public function getEntidadFuncionario( $id )
    {
         try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select( '   t1.intIdentidad_ent AS idEntidadFun,
                                t1.intIdUser_fnc    AS idUsuarioFnc,
                                t1.strApellido_fnc  AS apellidoFnc,
                                t1.strNombre_fnc    AS nombreFun,
                                t1.strCI_fnc        AS ciFun' );
            $query->from( '#__gen_funcionario t1' );
            $query->where( 't1.intCodigo_fnc = '. $id );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $dtaFnc = ( $db->getAffectedRows() > 0  )? $db->loadObject() : array();
            return $dtaFnc;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.funcionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna el objeto sin funcionario de una unidad de gestion
     * @param type $idUG
     * @return type
     */
    public function getSinFunionarioUG( $idUG )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->select( 'intId_ugf    AS idFncUG,'
                        . 'intCodigo_ug   AS idUG,'
                        . 'intCodigo_fnc  AS idFnc' );
            $query->from( '#__gen_ug_funcionario' );
            $query->where( "intCodigo_ug = {$idUG}" );
            $query->where( "intCodigo_fnc = 0" );
            $db->setQuery( (string) $query );
            $db->query();
            $sinFnc = ($db->getAffectedRows() > 0) ? $db->loadObject() : array() ;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.funcionario.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $sinFnc;
    }
    
    /////////////    GESTION DE CAMBIO DE FUNCIONARIOS RESPONSABLES  ///////////
    /////////////////////       PROGRAMAS       //////////////////////////
    
    /**
     *  Retorna la lista de Programas de un funcionario responsabla
     * @param type $idFncUG
     * @return type
     */
    public function getProgramasByFR( $idFncUG )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->select( 'intId_prgFR        AS idPrgFR,'
                        . 'intId_ugf            AS idFncUG,'
                        . 'intCodigo_prg        AS idPrg,'
                        . 'dteFechaInicio_prgFR AS fechaInicio,'
                        . 'intVigencia_prgFR    AS vigencia');
            $query->from( '#__prg_funcionario_responsable' );
            $query->where( "intId_ugf = {$idFncUG}" );
            $query->where( "intVigencia_prgFR = 1" );
            $query->where( "published = 1" );
            $db->setQuery( (string) $query );
            $db->query();
            $sinFnc = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : array() ;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.funcionario.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $sinFnc;
    }
    
    /**
     *  Regiastra un funcionario responsable de un Programa
     * @param type $idPrg               Id de Programa
     * @param type $idFncUG             Id de funcionario responsable id-funcionario-unidadgestion
     * @param type $fechaInicio         fecha de inicio de gestion
     * @return type
     */
    public function registrarFncRspPrograma( $idPrg, $idFncUG, $fechaInicio )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->insert( "#__prg_funcionario_responsable" );
            $query->columns( "intId_prgFR, intCodigo_prg, intId_ugf, dteFechaInicio_prgFR, intVigencia_prgFR, published" );
            $query->values( "0, {$idPrg}, {$idFncUG}, '{$fechaInicio}', 1, 1" );
            $db->setQuery( (string) $query );
            $result = ($db->query()) ? true : false ;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.funcionario.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $result;
    }
    
    /**
     *  Actualiza la vigencia de un funcionario respondable de un Programa
     * @param type $idPrgFR
     * @param type $fechaFin
     * @param type $vigencia
     * @return type
     */
    public function actualizarFinFncRspPrograma( $idPrgFR, $fechaFin, $vigencia )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->update( '#__prg_funcionario_responsable' );
            $query->set( "dteFechaFin_prgFR = '{$fechaFin}', intVigencia_prgFR = {$vigencia}" );
            $query->where( "intId_prgFR = {$idPrgFR}" );
            $db->setQuery( (string) $query );
            $result = ( $db->query() ) ? true : false ;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.funcionario.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $result;
    }
    
    /////////////////////       PROYECTOS       //////////////////////////
    
    /**
     *  Retorna la lista de proyectos de un funcionario responsabla
     * @param type $idFncUG
     * @return type
     */
    public function getProyectosByFR( $idFncUG )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->select( 'intId_pryFR        AS idPryFR,'
                        . 'intId_ugf            AS idFncUG,'
                        . 'intCodigo_pry        AS idPry,'
                        . 'dteFechaInicio_pryFR AS fechaInicio,'
                        . 'intVigencia_pryFR    AS vigencia');
            $query->from( '#__pry_funcionario_responsable' );
            $query->where( "intId_ugf = {$idFncUG}" );
            $query->where( "intVigencia_pryFR = 1" );
            $query->where( "published = 1" );
            $db->setQuery( (string) $query );
            $db->query();
            $sinFnc = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : array() ;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.funcionario.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $sinFnc;
    }
    
    /**
     *  Regiastra un funcionario responsable de un Proyecto
     * @param type $idPry               Id de Proyecto
     * @param type $idFncUG             Id de funcionario responsable id-funcionario-unidadgestion
     * @param type $fechaInicio         fecha de inicio de gestion
     * @return type
     */
    public function registrarFncRspPry( $idPry, $idFncUG, $fechaInicio )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->insert( "#__pry_funcionario_responsable" );
            $query->columns( "intId_pryFR, intCodigo_pry, intId_ugf, dteFechaInicio_pryFR, intVigencia_pryFR, published" );
            $query->values( "0, {$idPry}, {$idFncUG}, '{$fechaInicio}', 1, 1" );
            $db->setQuery( (string) $query );
            $result = ($db->query()) ? true : false ;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.funcionario.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $result;
    }
    
    /**
     *   Actualiza la vigencia de un funcionario responsable de un Proyecto
     * @param type $idPryFR
     * @param type $fechaFin
     * @param type $vigencia
     * @return type
     */
    public function actualizarFinFncRspPry( $idPryFR, $fechaFin, $vigencia )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->update( '#__pry_funcionario_responsable' );
            $query->set( "dteFechaFin_pryFR = '{$fechaFin}', intVigencia_pryFR = {$vigencia}" );
            $query->where( "intId_pryFR = {$idPryFR}" );
            $db->setQuery( (string) $query );
            $result = ( $db->query() ) ? true : false ;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.funcionario.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $result;
    }
    
    /////////////////////   CONTRATOS Y CONVENIOS     //////////////////////////
    
    /**
     *  Retorna la lista de contratos o convenios de un funcionario responsabla
     * @param type $idFncUG             id de funcionario responsable id-funcionario-unidadgestion
     * @param type $tipo                tipo 1: Contrato 2: Convenio
     * @return type
     */
    public function getCtrsCnvsByFR( $idFncUG, $tipo = null )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->select( 't1.intId_ctrFR        AS idCtrFR,'
                        . 't1.intId_ugf            AS idFncUG,'
                        . 't1.intIdContrato_ctr    AS idCtr,'
                        . 't1.dteFechaInicio_ctrFR	AS fechaInicio,'
                        . 't1.intVigencia_ctrFR    AS vigencia');
            $query->from( '#__ctr_funcionario_responsable t1' );
            $query->innerJoin( "#__ctr_contrato t2 ON t1.intIdContrato_ctr = t2.intIdContrato_ctr" );
            $query->where( "t1.intId_ugf = {$idFncUG}" );
            $query->where( "t1.intVigencia_ctrFR = 1" );
            $query->where( "t1.published = 1" );
            if ( $tipo != null ){
                $query->where( "t2.intIdTipoContrato_tc = {$tipo}" );
            }
            $db->setQuery( (string) $query );
            $db->query();
            $sinFnc = ($db->getAffectedRows() > 0) ? $db->loadObjectList() : array() ;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.funcionario.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $sinFnc;
    }
    
    /**
     *  Regiastra un funcionario responsable de un Proyecto
     * @param type $idCtrCnv               Id de Proyecto
     * @param type $idFncUG             Id de funcionario responsable id-funcionario-unidadgestion
     * @param type $fechaInicio         fecha de inicio de gestion
     * @return type
     */
    public function registrarFncRspCtrsCnvs( $idCtrCnv, $idFncUG, $fechaInicio )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->insert( "#__ctr_funcionario_responsable" );
            $query->columns( "intId_ctrFR, intIdContrato_ctr, intId_ugf, dteFechaInicio_ctrFR, intVigencia_ctrFR, published" );
            $query->values( "0, {$idCtrCnv}, {$idFncUG}, '{$fechaInicio}', 1, 1" );
            $db->setQuery( (string) $query );
            $result = ($db->query()) ? true : false ;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.funcionario.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $result;
    }
    
    /**
     *   Actualiza la vigencia de un funcionario responsable de un Contrato o Convenio
     * @param type $idCtrCnvFR
     * @param type $fechaFin
     * @param type $vigencia
     * @return type
     */
    public function actualizarFinFncRspCtrsCnvs( $idCtrCnvFR, $fechaFin, $vigencia, $tipo = null )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->update( '#__ctr_funcionario_responsable' );
            $query->set( "dteFechaFin_ctrFR = '{$fechaFin}', intVigencia_ctrFR = {$vigencia}" );
            $query->where( "intId_ctrFR = {$idCtrCnvFR}" );
            $db->setQuery( (string) $query );
            $result = ( $db->query() ) ? true : false ;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.funcionario.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return $result;
    }
    
    /**
     * Retorna el objetoco la informacion del funcionario deacuerdo al id de usuario
     * @param type $id
     * @return type
     */
    public function getDataFncByUsr( $id )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            $query->select( "f.intCodigo_fnc    As fncId,"
                    . "f.intIdentidad_ent       As entFncId,"
                    . "ugf.intId_ugf            As ugFncId,"
                    . "ugf.intCodigo_ug         As ugId,"
                    . "ugc.intId_ugc            As ugCrgId,"
                    . "ugc.intIdGrupo_cargo     As groupId" );
            $query->from( "#__gen_funcionario f" );
            $query->innerJoin( "#__gen_ug_funcionario ugf ON ugf.intCodigo_fnc = f.intCodigo_fnc" );
            $query->innerJoin( "#__gen_ug_cargo ugc ON ugc.intId_ugc = ugf.intId_ugc" );
            $query->where( "f.intIdUser_fnc = {$id}" );
            $query->where( "f.published = 1" );
            $query->where( "ugf.published = 1" );
            $db->setQuery( (string) $query );
            $db->query();
            $result = ( $db->getAffectedRows() > 0 ) ? $db->loadObject() : false ;
            return $result;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.funcionario.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }
    
}

