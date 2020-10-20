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
class ProgramaTablePrgObjInd extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__prg_prg_objetivos_indicador', 'intId_objInd_prg', $db);
    }

    /**
     * Registro de la tabla PROYECTO, OBJETIVO, INDICADOR.
     * @param type $idObjPrgInd     Identificador de la relación
     * @param type $idEntPrg        Identificador de la entidad del proyecto.
     * @param type $idIndEnt        Identificador de la entidad indicador.
     * @param type $idObjPrg        Identificador del OBJETIVO del PROYECTO.
     * @return type     int         Identificador de la relación 
     * @throws Exception            Cuando no se puede guardar la información.
     */
    public function savePrgObjInd($idObjPrgInd, $idEntPrg, $idIndEnt, $idObjPrg) {
        $data = array();
        $data["intId_objInd_prg"]   = ($idObjPrgInd) ? $idObjPrgInd : 0;
        $data["intIdIndEntidad"]    = $idIndEnt;
        $data["intID_objprg"]       = $idObjPrg;
        $data["intIdEnt_ent"]       = $idEntPrg;
        if (!$this->save($data)) {
            throw new Exception(JText::_('COM_PROGRAMAS_REGISTRO_ENTIDAD_INDICADOR_PRG'));
        }
        return $this->intId_objInd_prg;
    }
    
    
    /**
     * 
     * Retorna lista de Indicador Entidad Asociado a un Objetivo
     * 
     * @param type $idEntObj  Identificador del Objetivo
     * @return type
     * 
     */
    public function getIndEntByObjetivo( $idEntObj )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('    t1.intIdIndEntidad  AS  idIndEnt,
                                t1.intId_objInd_prg AS  idOIP'
                            );
            $query->from('#__prg_prg_objetivos_indicador AS t1');
            $query->where( 't1.intID_objprg =' . $idEntObj);
            $query->where( 't1.intIdIndEntidad > 0' );
            
            $db->setQuery((string) $query);
            $db->query();

            $result = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : FALSE;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}
