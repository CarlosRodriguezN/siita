<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class ProyectosTableTgCoordenada extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    private $_idProyecto;
    private $_dataCoordendas;

    //  Bandera que gestiona si un proyecto tiene registrados Graficos
    private $_banIsNew;

    function __construct(&$db)
    {
        parent::__construct('#__pfr_coordenadas', 'intRel_Tg_Coordenada', $db);
    }

    /**
     * 
     * Seteo informacion de Coordenadas 
     * 
     * @param type $idProyecto      Identificador Proyecto
     * @param type $dataCoordenadas Datos de coordenadas
     * 
     */
    public function setDataCoordenadas( $dataCoordenadas, $idProyecto )
    {
        $this->_idProyecto = $idProyecto;
        $this->_dataCoordendas = json_decode( $dataCoordenadas );
        $this->_banIsNew = $this->_existeProyecto();
    }

    /**
     * 
     * Busco un determinado proyecto
     * 
     * @param type $idProyecto  Identificador de Proyecto
     * @return type
     */
    private function _existeProyecto()
    {
        try {
            $db = & JFactory::getDbo();

            $sql = "SELECT COUNT(*) as cantidad
                    FROM " . $db->nameQuote('#__tg_coordenadas') . " t1
                    WHERE t1.intCodigo_pry = '{$this->_idProyecto}'";

            $db->setQuery($sql);
            $db->query();

            $rst = ( $db->loadObject()->cantidad > 0 ) ? true : false;

            return $rst;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * Verifico la Existencia de un determinado Grafico
     *  
     * @param type $idGrafico   Identificador de Grafico
     * 
     * @return type
     */
    private function _existeGrafico($idGrafico) {
        try {
            $db = & JFactory::getDbo();

            $sql = "SELECT COUNT(*) as cantidad
                    FROM " . $db->nameQuote('#__tg_coordenadas') . " t1
                    WHERE t1.intCodigo_pry = '{$this->_idProyecto}'
                    AND intRel_Tg_Coordenada = '{$idGrafico}'";

            $db->setQuery($sql);
            $db->query();
            $rst = ( $db->loadObject()->cantidad > 0 ) ? true : false;

            return $rst;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * Setea una lista de graficos ( idGrafico ), del conjunto de registros 
     * con informacion de coordenadas de los graficos pertenecientes a un 
     * determinado proyecto
     * 
     * @return type array
     */
    private function _getLstGraficos() {
        $lstGraficos = "";

        foreach ($this->_dataCoordendas as $val) {
            $lstGraficos[] = $val->idGrafico;
        }

        if (is_array($lstGraficos)) {
            return array_unique($lstGraficos);
        } else {
            return false;
        }
    }

    /**
     * 
     * Retorno el tipo de Grafico al que esta registrado un determinado Grafico
     * 
     * @param type $idGrafico   Identificador Grafico
     * 
     * @return type array   Lista de tipos de graficos
     * 
     */
    private function _getTpoGrafico($idGrafico) {
        foreach ($this->_dataCoordendas as $val) {
            if ($val->idGrafico == $idGrafico) {
                $lstTpoGrafico[] = $val->tpoGrafico;
            }
        }

        $rst = array_unique($lstTpoGrafico);

        return $rst[0];
    }

    /**
     * 
     * Retorna una lista de coordenadas 
     * 
     * @param type $idGrafico   Identificador del Grafico
     * 
     * @return type array
     * 
     */
    private function _getCoordenadas($idGrafico) {
        // $lstCoordenadas = [];
        foreach ($this->_dataCoordendas as $key => $val) {
            
            if ($val->idGrafico == $idGrafico) {
                $lstCoordenadas[$key]["latitud"] = $val->latitud;
                $lstCoordenadas[$key]["longitud"] = $val->longitud;
                $lstCoordenadas[$key]["idCoordenada"] = $val->idCoordenada;
                $lstCoordenadas[$key]["idGrafico"] = $val->idGrafico;
                $lstCoordenadas[$key]["strDescripcionGrafico"] = $val->descGrafico;
            }
        }

        return $lstCoordenadas;
    }

    /**
     * 
     * Registro un tipo de Grafico de un determinado proyecto
     * 
     * @param type $idTipoGrafico   Identificador del Tipo de Grafico
     * @return type Retorno Identificador de Registro del Tipo de Grafico
     * 
     */
    private function _setTpoGraficoCoordenada($idTipoGrafico) {
        try {
            $db = & JFactory::getDbo();
            $sql = "INSERT INTO " . $db->nameQuote('#__tg_coordenadas') . " 
                    ( intCodigo_pry, intId_tg ) 
                    VALUES ( '{$this->_idProyecto}','{$idTipoGrafico}' )";

            $db->setQuery($sql);
            $db->query();

            //  Ejecuto Sentencia Sql
            return $db->insertid();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * Realizo el registro de las coordenadas de un determinado Grafico 
     * 
     * @param type $dataGrafico             Lista de Coordenadas correspondientes a un determinado Grafico
     * @param type $idTpoGraficoCoordenada  Identificador del la relacion Tipo de Grafico Coordenadas
     * @return type
     */
    private function _setDataCoordenadas($dataGrafico, $idTpoGraficoCoordenada) {
        try {
            $db = &JFactory::getDbo();

            $sql = "INSERT INTO " . $db->nameQuote('#__pfr_coordenadas') .
                    "( intRel_Tg_Coordenada, fltLatitud_cord, fltLongitud_cord ) VALUES " .
                    "( '{$idTpoGraficoCoordenada}', '" . $dataGrafico["latitud"] . "', '" . $dataGrafico["longitud"] . "' )";

            $db->setQuery(trim($sql, ', '));

            //  Ejecuto Sentencia Sql
            return $db->query();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * Gestiono la Actualizacion de las Coordenadas de un determinado Grafico, 
     * pertenteniente a un proyecto
     * 
     * @param type $dataGrafico                 Puntos ( coordenadas ) pertenecientes al grafico
     * @param type $idRelTpoGraficoCoordenada   Identificador del Grafico
     * 
     * @return type
     * 
     */
    private function _setUpdDataCoordenadas($dataGrafico, $idRelTpoGraficoCoordenada) {
        try {
            $db = &JFactory::getDbo();

            $sql .= "   UPDATE " . $db->nameQuote('#__pfr_coordenadas') . " 
                        SET fltLatitud_cord = '{$dataGrafico["latitud"]}', 
                            fltLongitud_cord = '{$dataGrafico["longitud"]}'
                        WHERE intCodigo_cordpry = '{$dataGrafico["idCoordenada"]}'
                        AND intRel_Tg_Coordenada = '{$idRelTpoGraficoCoordenada}';";

            $db->setQuery(trim($sql));

            //  Ejecuto Sentencia Sql
            return $db->query();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

  

    /**
     * Verifica si existe una coordenada de un proyecto
     * @param int $intCodigo_cordpry identificador de la coordenada
     * @return int Description 0 si no existe
     *                         x>0 si existe 
     * 
     */
    function _existeCoordenada($dataGrafico) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('   ta.intCodigo_cordpry AS idCoordenada');

            $query->from('#__pfr_coordenadas AS ta');

            $query->where('ta.intCodigo_cordpry    = ' . $dataGrafico["idCoordenada"]);
            $query->where('ta.intRel_Tg_Coordenada = ' . $dataGrafico["idGrafico"]);

            $db->setQuery((string) $query);
            $db->query();
            $coordenadaProyecto = ( $db->getNumRows() > 0 ) ? $dataGrafico["idCoordenada"] : 0;

            return $coordenadaProyecto;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }


    /**
     *
     * Retorna una lista de Graficos que pertenecen a un determinado proyecto
     * 
     * @param type $idProyecto  identificador del proyecto
     * 
     * @return type array
     */
    public function getLstGraficos($idProyecto) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('   ta.intRel_Tg_Coordenada as idGrafico, 
                                ta.intId_tg as idTpoGrafico,
                                ta.strDescripcionGrafico as strDescripcionGrafico,
                                tb.strDescripcion_tg as descripcion');

            $query->from('#__tg_coordenadas ta');
            $query->join('INNER', '#__gen_tipo_grafico tb ON ta.intId_tg = tb.intId_tg');

            $query->where('ta.intCodigo_pry = ' . $idProyecto);

            $db->setQuery((string) $query);
            $db->query();

            $lstGraficos = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : FALSE;

            return $lstGraficos;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     *
     * Retorno una lista de coordendas ( puntos ) de los graficos 
     * registrados en el sistema
     * 
     * @param type $idProyecto  Identificador del Proyecto
     * 
     * @return type Array
     */
    public function getLstPuntos($idProyecto) {
        try {

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('ta.intRel_Tg_Coordenada as idGrafico, 
                            ta.intId_tg as idTpoGrafico,
                            ta.strDescripcionGrafico as descripcion,
                            tb.strDescripcion_tg as grafico,
                            tc.intCodigo_cordpry as coordGraf,
                            tc.fltLatitud_cord as latitud,
                            tc.fltLongitud_cord as longitud');

            $query->from('#__tg_coordenadas ta');
            $query->join('INNER', '#__gen_tipo_grafico tb ON ta.intId_tg = tb.intId_tg');
            $query->join('INNER', '#__pfr_coordenadas tc ON ta.intRel_Tg_Coordenada = tc.intRel_Tg_Coordenada');

            $query->where('ta.intCodigo_pry = ' . $idProyecto);
            $db->setQuery((string) $query);
            $db->query();

            $lstGraficos = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : FALSE;

            return $lstGraficos;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function addGrafico($dataForm) {
        //  Instancio Tabla Indicadores
        $tbTgCoorde = JTable::getInstance('TipoGraficoCoordenadas', 'ProyectosTable', array());

        return $tbTgCoorde->saveUpdateGraficoCoordenada($dataForm);
    }

}