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
class ProgramaTableSubPrograma extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__pfr_sub_programa', 'intId_SubPrograma', $db);
    }

    /**
     * Recupera una lista de subprogramas de un programa
     * @param int $idPrograma       Identificador del programa
     * @return array    
     */
    function getSubProgramas($idPrograma) {
        try {
            $db = & JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->select('intId_SubPrograma       AS  idSubPrograma,
                            strCodigo_sprg          AS  codigoSubPrograma,
                            strAlias_sprg           AS  alias, 
                            strDescripcion_sprg     AS  descripcion,
                            idMenu                  AS  idMenu,
                            published               AS  estadoSubPrograma');
            $query->from('#__pfr_sub_programa ');
            $query->where('intCodigo_prg = ' . $idPrograma);
            $query->where('published = 1');
            $db->setQuery($query);
            $db->query();
            
            $retval = ( $db->loadObjectList() ) ? $db->loadObjectList() : array();
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_programas.tables.subprograma.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Gestiona la informacion de un sub programa
     * @param object $dataSubPrograma       Objeto con la informacion dr el programa.
     * @param int $idPrograma               Identificador de el programa.
     * @return int                          Identificador de el sub programa.
     */
    public function saveSubPrograma($dataSubPrograma, $idPrograma) {

        $subPrograma["intId_SubPrograma"]   = (int) $dataSubPrograma->idSubPrograma;
        $subPrograma["intCodigo_prg"]       = (int) $idPrograma;
        $subPrograma["strCodigo_sprg"]      = $dataSubPrograma->codigoSubPrograma;
        $subPrograma["strDescripcion_sprg"] = $dataSubPrograma->descripcion;
        $subPrograma["strAlias_sprg"]       = $dataSubPrograma->alias;
        $subPrograma["idMenu"]              = (int) $dataSubPrograma->idMenu;
        $subPrograma["published"]           = $dataSubPrograma->published;
        
        $idSubPrograma = false;
        if ( $this->save($subPrograma) ) {
            $idSubPrograma = $this->intId_SubPrograma;
        }

        return $idSubPrograma;
    }

    /**
     * Asigma el identificador de menu que le corresponde
     * @param int   $idSubPrograma          Identificador del Sub Programa.
     * @param int   $idMenuSubPrograma      Identificador del menu.
     * @return int  $idSubPrograma          Identificador del Sub Programa que fue actualizado.  
     */
    public function setIdMenuToSubPrograma($idSubPrograma, $idMenuSubPrograma) {
        $subPrograma["intId_SubPrograma"] = (int) $idSubPrograma;
        $subPrograma["idMenu"] = (int) $idMenuSubPrograma;

        if ($this->save($subPrograma)) {
            $idSubPrograma = $this->intId_SubPrograma;
        }
        return $idSubPrograma;
    }

    /**
     * Recupera un sub programa dado su identificador.
     * @param int       $idSubPrograma      Identificador de el sub Programa.        
     * @return object                       Obejto Sub Programa.
     */
    public function getSubProgramaById($idSubPrograma) {
        try {
            $db = & JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->select('intId_SubPrograma   AS  idSubPrograma,
                            strCodigo_sprg      AS  codigoSubPrograma,
                            strAlias_sprg       AS  alias, 
                            strDescripcion_sprg AS  descripcion,
                            idMenu              AS  idMenu,
                            published           AS  estadoSubPrograma'
            );

            $query->from('#__pfr_sub_programa ');
            $query->where('intId_SubPrograma = ' . $idSubPrograma);

            $db->setQuery($query);

            $db->query();
            $retval = ($db->loadObjectList() ) ? $db->loadObject() : false;
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_programas.tables.subprograma.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}