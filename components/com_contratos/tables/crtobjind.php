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
class ContratosTableCrtObjInd extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__crt_crt_objetivos_indicador', 'intId_objInd_crt', $db);
    }

    /**
     * Registro de la tabla PROYECTO, OBJETIVO, INDICADOR.
     * @param type $idObjCrtInd     Identificador de la relación
     * @param type $idEntCrt        Identificador de la entidad del proyecto.
     * @param type $idIndEnt        Identificador de la entidad indicador.
     * @param type $idObjCrt        Identificador del OBJETIVO del PROYECTO.
     * @return type     int         Identificador de la relación 
     * @throws Exception            Cuando no se puede guardar la información.
     */
    public function saveCrtObjInd($idObjCrtInd, $idEntCrt, $idIndEnt, $idObjCrt) {
        $data = array();
        $data["intId_objInd_crt"]   = ($idObjCrtInd) ? $idObjCrtInd : 0;
        $data["intIdIndEntidad"]    = $idIndEnt;
        $data["intID_objcrt"]       = $idObjCrt;
        $data["intIdEnt_ent"]   = $idEntCrt;
        if (!$this->save($data)) {
            throw new Exception(JText::_('COM_PROYECTOS_REGISTRO_ENTIDAD_INDICADOR_PRY'));
        }
        return $this->intId_objInd_crt;
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
                                t1.intId_objInd_crt AS  idOIP'
                            );
            $query->from('#__crt_crt_objetivos_indicador AS t1');
            $query->where( 't1.intID_objcrt =' . $idEntObj);
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
