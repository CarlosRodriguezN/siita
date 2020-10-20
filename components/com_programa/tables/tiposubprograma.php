<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

//  Import Joomla JUser Library
jimport('joomla.user.user');

/**
 * 
 * Clase que gestiona informacion de la tabla TIPO SUB PROGRAMA ( #__pfr_tipo_sub_programa )
 * 
 */
class ProgramaTableTipoSubPrograma extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__pfr_tipo_sub_programa', 'intId_tsprg', $db);
    }

    /**
     * Recupera una lista de los tipos de sub programas de un programa
     * @param int       $idSubPrograma  Identificado del el subprograma
     * @return array                    Lista de tipos de sub Programa.                         
     */
    public function getTiposSubPrograma($idSubPrograma) {
        try {
            $db = & JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('intId_tsprg AS idTipoSubPrograma, 
                            strCodigo_tsprg AS codigoTipoSubPrograma, 
                            strDescripcion_tsprg AS descripcion,
                            published AS estadoTipoSubPrograma');
            $query->from('#__pfr_tipo_sub_programa ');
            $query->where("intId_SubPrograma = " . $idSubPrograma);
            $query->where("published = 1");
            $db->setQuery($query);

            $db->query();

            $retval = ( $db->loadObjectList() ) ? $db->loadObjectList() : array();
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_rdsTree.tables.tipoSubprograma.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Gestiona la informacion de un "TIPO DE SUB PROGRAMA" 
     * @param array $tpoSubPrograma     Un array de objetos Tipos Sub Programa
     * @param int $idSubPrograma        Identificador de un Sub Programa
     * @return int                      Identificador del tipo de sub Programa.
     */
    public function saveTipoSubPrograma($tpoSubPrograma, $idSubPrograma) {
        try {

            $data["intId_tsprg"] = (int) $tpoSubPrograma->idTipoSubPrograma;
            $data["strCodigo_tsprg"] = $tpoSubPrograma->codigoTipoSubPrograma;
            $data["intId_SubPrograma"] = (int) $idSubPrograma;
            $data["strDescripcion_tsprg"] = $tpoSubPrograma->descripcion;
            $data["published"] = $tpoSubPrograma->published;

            if ($this->save($data)) {
                $idTipoSubPrograma = $this->intId_tsprg;
            }

            return $idTipoSubPrograma;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_rdsTree.tables.tipoSubprograma.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}