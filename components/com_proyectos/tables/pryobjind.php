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
class ProyectosTablePryObjInd extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__pfr_pry_objetivos_indicador', 'intId_objInd_pry', $db);
    }

    /**
     * Registro de la tabla PROYECTO, OBJETIVO, INDICADOR.
     * @param type $idObjpryInd     Identificador de la relación
     * @param type $idEntPry        Identificador de la entidad del proyecto.
     * @param type $idIndEnt        Identificador de la entidad indicador.
     * @param type $idObjPry        Identificador del OBJETIVO del PROYECTO.
     * @return type     int         Identificador de la relación 
     * @throws Exception            Cuando no se puede guardar la información.
     */
    public function savePryObjInd($idObjpryInd, $idEntPry, $idIndEnt, $idObjPry) {
        $data = array();
        $data["intId_objInd_pry"] = ($idObjpryInd) ? $idObjpryInd : 0;
        $data["intIdIndEntidad"] = $idIndEnt;
        $data["intID_objpry"] = $idObjPry;
        $data["intIdentidad_ent"] = $idEntPry;
        if (!$this->save($data)) {
            throw new Exception(JText::_('COM_PROYECTOS_REGISTRO_ENTIDAD_INDICADOR_PRY'));
        }
        return $this->intId_objInd_pry;
    }
    
    
    /**
     * 
     * Retorna lista de Indicador Entidad Asociado a un Objetivo
     * 
     * @param type $idObjetivo  Identificador del Objetivo
     * @return type
     * 
     */
    public function getIndEntByObjetivo( $idObjetivo )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('    t1.intIdIndEntidad  AS  idIndEnt,
                                t1.intId_objInd_pry AS  idOIP'
                            );
            $query->from('#__pfr_pry_objetivos_indicador AS t1');
            $query->where( 't1.intID_objpry =' . $idObjetivo);
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
