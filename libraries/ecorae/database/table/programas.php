<?php

/**
 * @package     Joomla.Platform
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('JPATH_PLATFORM') or die;
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'dpa.php';
require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'models' . DS . 'mapaDPA.php';

jimport('joomla.database.tablenested');

/**
 * Category table 
 *
 * @package     Joomla.Platform
 * @subpackage  Table
 * @since       11.1
 */
class JTableProgramas extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabase  &$db  A database connector object
     *
     * @since   11.1
     */
    public function __construct(&$db) {
        parent::__construct('#__pfr_programa', 'id', $db);

        $this->access = (int) JFactory::getConfig()->get('access');
    }

    /**
     * 
     * Retora informacion en formato JSON, que sirve para armar el arbol de programas 
     * que se muestra en la webPublica
     * 
     * @param int $id      Identificador de la Unidad Territorial
     * @param int $tut     Itentificador de Unidad Territorial, p.e.: 3 - provincia
     * 
     * @return type 
     * 
     */
    function getProgramas( $id, $tut )
    {
        $result = array();
        try{

            $hashKey = hash("md5", "Programa".$id . $tut);
            
            $memcache = new Memcached();
            $memcache->addServer( 'localhost', '11211' );
            $result = $memcache->get( $hashKey );
            
            if( $result == null ){
                $db = JFactory::getDbo();
                $query = $db->getQuery( true );

                $query->select( '   prg.intcodigo_prg AS id,     
                                    prg.intIdEntidad_ent AS entidad,
                                    IF( prg.strNombre_prg IS NULL, "", UPPER( prg.strNombre_prg ) ) AS nombre,
                                    IF( prg.strdescripcion_prg IS NULL, "", prg.strdescripcion_prg ) AS descripcion' );
                $query->from( '#__pfr_programa AS prg ' );
                $query->where( 'prg.strNombre_prg IS NOT NULL ' );
                $query->where( 'prg.strdescripcion_prg IS NOT NULL' );
                $query->where( 'prg.intIdEntidad_ent > 0' );
                $query->order( 'prg.strdescripcion_prg ASC' );

                $db->setQuery( $query );

                $programas = ( $db->loadObjectList() )  ? $db->loadObjectList() 
                                                        : false;

                $result = $this->_programasObjectConvert( $id, $tut, $programas );
                
                //  Quita los nodos que no tiene hijos.
                for ($i = 0; $i < count($result); $i++) {
                    if (count($result[$i]->children) == 0) {
                        unset($result[$i]);
                        $result = array_values($result);
                        $i--;
                    }
                }

                $memcache->set($hashKey, $result, 1);  //900seg=15 minutos*60segundos
            }

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.programas.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * Retorna informacion de programas con sus respectivos proyectos asociados
     * 
     * @param int       $id         Identificador de la Unidad Territorial
     * @param int       $tut        Itentificador de Unidad Territorial, p.e.: 3 - provincia
     * @param object    $programas  Datos del Programa
     * @return type
     */
    function _programasObjectConvert($id, $tut, $programas) {
        $programasJSON = array();
        try {
            if ($programas) {
                foreach ($programas as $index => $programa) {
                    $programaJSON                       = (object) null;
                    $programaJSON->property             = (object) null;
                    $programaJSON->property->name       = $programa->nombre;
                    $programaJSON->property->hasCheckbox= true;
                    $programaJSON->property->id         = 'pr-'.$programa->id;
                    $programaJSON->type                 = "programa";
                    $programaJSON->data                 = (object) null;
                    $programaJSON->data->entidad        = $programa->entidad;
                    $programaJSON->data->id             = $programa->id;

                    //insertamos a los programas proyectos
                    $programaJSON->children             = $this->programasSetProyectos( $id, $tut, $programa->id );
                    $programasJSON[$index]              = $programaJSON;
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return (array) ($programasJSON);
    }

    /**
     * 
     * Retorna informacion de proyectos que estan asociados a un determinado Programa
     * 
     * @param int $id               Identificador de la Unidad Territorial
     * @param int $tut              Itentificador de Unidad Territorial, p.e.: 3 - provincia
     * @param int $idprograma       Identificador del Programa
     * 
     * @return type
     */
    function programasSetProyectos($id, $tut, $idprograma )
    {
        try {// creamos un objeto de el modelo DPA
            $proyectos = new mapaDPAModel();
            //$id=unidad territorial
            //$tut=tipo unidad territorial
            //$programa=identificador de el programa del cual queremos el proyecto
            $result = $proyectos->getListaProyectos( $id, $tut, $idprograma );
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return (array) $result;
    }
    
    /**
     *  Retorna la lista de programas de una determinada unidad de gestión responsable
     * 
     * @param int      $idEntidadUG        Id de entidad de unidad de gestión
     * @return type
     */
    function getLstProgramasUG( $idEntidadUG )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select("prg.intCodigo_prg AS idPrograma, 
                            prg.intIdEntidad_ent AS idEntidadPrg,
                            prg.strNombre_prg AS nombrePrg, 
                            prg.published");
            $query->from('#__pfr_programa prg');
            $query->innerJoin( "#__prg_ug_responsable pug ON pug.intCodigo_prg = prg.intCodigo_prg");
            $query->innerJoin( "#__gen_unidad_gestion ug ON ug.intCodigo_ug = pug.intCodigo_ug");
            $query->where( "ug.intIdentidad_ent = {$idEntidadUG}");
            $query->where( "pug.intVigencia_prgUGR = 1");
            $query->order("prg.intCodigo_prg DESC");
            
            $db->setQuery( (string) $query );
            $db->query();
            
            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList(): array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.programas.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Retorna la lista de programas de un Funcionario
     * 
     * @param int      $idFnc        Id del funcionario
     * @return type
     */
    function getLstProgramasFnc( $idFnc )
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select("prg.intCodigo_prg AS idPrograma, 
                            prg.intIdEntidad_ent AS idEntidadPrg,
                            prg.strNombre_prg AS nombrePrg, 
                            prg.published");
            $query->from('#__pfr_programa prg');
            $query->innerJoin( "#__prg_funcionario_responsable pfr ON pfr.intCodigo_prg = prg.intCodigo_prg");
            $query->innerJoin( "#__gen_ug_funcionario ugf ON ugf.intId_ugf = pfr.intId_ugf");
            $query->where( 'ugf.intCodigo_fnc = '. $idFnc );
            $query->where( 'pfr.intVigencia_prgFR = 1' );
            $query->order( 'prg.intCodigo_prg DESC' );

            $db->setQuery( (string) $query );
            $db->query();
            
            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList(): array();
            return $result;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('ecorae.database.tables.programas.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}
