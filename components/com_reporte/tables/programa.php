<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

//  Import Joomla JUser Library
jimport('joomla.user.user');

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class ProgramaTablePrograma extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__pfr_programa', 'intCodigo_prg', $db);
    }

    /**
     * Registo un programa.
     * @param int $info         Objeto con la información a editar o almacenar.
     * @param int $idEntidad    Identificador de la entidad.
     * @return int              Idetificador del registro.
     */
    public function savePrograma($info, $idEntidad) {
        $idPrograma = 0;
        $data["intCodigo_prg"] = $info->idPrg;
        $data["strNombre_prg"] = $info->nombrePrg;
        $data["strAlias_prg"] = $info->alias;
        $data["strDescripcion_prg"] = $info->descripcionPrg;
        $data["intIdEntidad_ent"] = $idEntidad;
        $data["idMenu"] = $info->idMenu;
        $data["published"] = $info->estadoPrg;

        if ($this->save($data)) {
            $idPrograma = $this->intCodigo_prg;
        }
        return $idPrograma;
    }

    /**
     * Asigma el identificador de menu que le corresponde
     * @param int $idPrograma       Identificador del programa.
     * @param int $idMenuPrograma   Identificador del menu.
     * @return int $idSubPrograma   Identificador del programa que fue actualizado.  
     */
    public function setIdMenuToPrograma($idPrograma, $idMenuPrograma) {
        $subPrograma["intCodigo_prg"] = (int) $idPrograma;
        $subPrograma["idMenu"] = (int) $idMenuPrograma;

        $idSubPrograma = $idPrograma;

        if ($this->bind($subPrograma)) {
            if ($this->save($subPrograma)) {
                $idSubPrograma = $this->intCodigo_prg;
            }
        }
        return $idSubPrograma;
    }

    /**
     * 
     * inserta los campos en la tabla programa.
     * @param int $idPrograma   Identificador del programa
     * @param int $idArticulo   Identificador del articulo
     * @param int $idContenido  Identificador del contenido
     */
    public function setMenuPrograma($idPrograma, $idMenu, $idContenido) {
        try {

            $programaArray['intcodigo_prg'] = $idPrograma;
            $programaArray['idMenu'] = $idMenu;
            $programaArray['idContent'] = $idContenido;

            if ($this->bind($programaArray)) {
                return $this->store();
            } else {
                return false;
            }
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_adminmapa.table.programa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Recupera los sub programas de un programa
     * @param int $idPrograma   Identificador del programa.
     * @return Array            Lista de Sub Programas.
     */
    function getSubProgramasByProgranaId($idPrograma) {
        try {
            $db = &JFactory::getDBO();
            $db->getQuery(true);
            $query = $db->getQuery(true);
            // Armo la sentencia SQL para INSERTAR los valores
            $query->select('intId_SubPrograma AS idSubPrograma,
                            strCodigo_sprg AS codigoSubPrograma, 
                            strDescripcion_sprg AS descripcionSubPrograma, 
                            strAlias_sprg AS aliasSubPrograma');
            $query->from('#__pfr_sub_programa');
            $query->where('intCodigo_prg=' . $idPrograma);
            //  Ejecución
            $db->setQuery($query);
            $db->query();
            return ($db->loadObjectList())?$db->loadObjectList():false;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_adminmapa.table.programa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Recupera el programa por el identificador del programa.
     * @param int   $idPrograma      Identificador del programa.
     * @return Object                Objeto del programa
     */
    function getProgramaByID($idPrograma) {
        try {
            $db = &JFactory::getDBO();
            $db->getQuery(true);
            $query = $db->getQuery(true);
            // Armo la sentencia SQL para INSERTAR los valores
            $query->select('*');
            $query->from('#__pfr_programa');
            $query->where("intCodigo_prg =" . $idPrograma);
            //  Ejecución
            $db->setQuery($query);
            $db->query();
            $retval = ($db->loadObject()) ? $db->loadObject() : false;
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_adminmapa.table.programa.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}