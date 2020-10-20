<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla CONTRATOS ( #__ctr_contrato )
 * 
 */
class ContratosTableContrato extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_contrato', 'intIdContrato_ctr', $db);
    }

    function saveData($idEntidad, $dataGeneral) {
        $data["intIdContrato_ctr"] = $dataGeneral["intIdContrato_ctr"];
        $data["intIdentidad_ent"] = $idEntidad;
        $data["intCodigo_pry"] = $dataGeneral["intCodigo_pry"];
        $data["intIdTipoContrato_tc"] = $dataGeneral["intIdTipoContrato_tc"];
        $data["intIdPartida_pda"] = $dataGeneral["intIdPartida_pda"];
        $data["strCodigoContrato_ctr"] = $dataGeneral["strCodigoContrato_ctr"];
        $data["strCUR_ctr"] = $dataGeneral["strCUR_ctr"];
        $data["dcmMonto_ctr"] = $dataGeneral["dcmMonto_ctr"];
        $data["intNumContrato_ctr"] = $dataGeneral["intNumContrato_ctr"];
        $data["strDescripcion_ctr"] = $dataGeneral["strDescripcion_ctr"];
        $data["strObservacion_ctr"] = $dataGeneral["strObservacion_ctr"];
        $data["published"] = $dataGeneral["published"];
        $data["dteFechaInicio_ctr"] = $dataGeneral["dteFechaInicio_ctr"];
        $data["dteFechaFin_ctr"] = $dataGeneral["dteFechaFin_ctr"];
        $data["intPlazo_ctr"] = $dataGeneral["intPlazo_ctr"];
        $data["intcodigo_unimed"] = $dataGeneral["intcodigo_unimed"];

        if ($this->bind($data)) {
            if ($this->save($data)) {
                $idContrato = $this->intIdContrato_ctr;
            }
        }
        return $idContrato;
    }

    /**
     * Recupera las garantias de un contrato dado el identificador.
     * @param type $idContrato  Identificador del contrato.
     * @return type
     */
    function getGarantiasContrato($idContrato) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('    grt.intIdGarantia_gta       AS idGarantia,
                                grt.intIdTpoGarantia_tg     AS idTipoGarantia,
                                grt.intIdFrmGarantia_fg     AS idFormaGarantia,
                                grt.intCodGarantia_gta      AS codGarantia,
                                grt.dcmMonto_gta            AS monto,
                                grt.dteFechaDesde_gta       AS fchDesde,
                                grt.dteFechaHasta_gta       AS fchHasta,
                                grt.published               AS published ');
            $query->from('#__ctr_garantia AS grt');
            $query->where('grt.intIdContrato_ctr = ' . $this->_db->quote($idContrato));
            $db->setQuery($query);
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();

            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.tiposcontratista.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Recupera las las garantias de un estado de un contratoz XF
     * @param type $idGarantia
     */
    public function getGarantiaEstadoContrato($idGarantia) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('t2.intIdGarantiaEstado_ge   AS idGarantiaEstado,
                            t2.intIdEstadoGarantia_eg   AS idEstadoGarantia,
                            t1.strNombre_eg             AS nmbEstadoGarantia,
                            t2.dteFechaRegistro_ge      AS fchRegistro,
                            t2.strObservasion_ge        AS observacion,
                            t2.intEstadoActGarantia_ge  AS estadoAct,
                            t2.published                AS published
                                    ');
            $query->from('#__ctr_estado_garantia AS t1');
            $query->join('INNER', "#__ctr_garantia_estado AS t2 ON t2.intIdEstadoGarantia_eg=t1.intIdEstadoGarantia_eg");
            $query->where('t2.intIdGarantia_gta = ' . $this->_db->quote($idGarantia));
            $query->order('t2.dteFechaRegistro_ge  DESC');
            $db->setQuery($query);
            $db->query();
            return ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.tiposcontratista.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Recupera la descripcion del programa al que pertenece el contrato.
     * @param type $idContrato
     * @return type
     */
    public function getProgramaContrato($idContrato) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('
                        prg.intCodigo_prg AS idPrograma
                ');
            $query->from('#__ctr_contrato AS ctr');
            $query->join('INNER', "#__pfr_proyecto_frm AS pry ON pry.intCodigo_pry=ctr.intCodigo_pry");
            $query->join('INNER', "#__pfr_programa AS prg ON prg.intCodigo_prg=pry.intCodigo_prg");
            $query->where('ctr.intIdContrato_ctr = ' . $idContrato);
            //echo $query->__toString();exit();
            $db->setQuery($query);
            $db->query();
            $retval = ($db->loadObject()) ? $db->loadObject()->idPrograma : false;
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.tiposcontratista.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * RECUPERA la informacion de un contrato dado el identificador
     * @param type $idContrato  Identificador del contrato.
     * @return Object
     */
    public function getContratoById($idContrato) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('
                ctr.intIdContrato_ctr,	
		ctr.intIdContrato_padre,	
		ctr.intIdTipoContrato_tc,	
		ctr.intIdentidad_ent,	
		ctr.intCodigo_pry,
		ctr.intIdPartida_pda,	
		ctr.strCodigoContrato_ctr,
		ctr.strCUR_ctr,
		ctr.intPlazo_ctr,
		ctr.dcmMonto_ctr,
		ctr.intNumContrato_ctr,	
		ctr.strDescripcion_ctr,	
		ctr.strObservacion_ctr,	
		ctr.published,
		ctr.checked_out,
		ctr.checked_out_time,  
                prg.strAlias_prg AS prgAlias,
                prg.strNombre_prg AS prgNombre,
                prg.intCodigo_prg AS idPrograma
                ');
            $query->from('#__ctr_contrato AS ctr');
            $query->join('INNER', "#__pfr_proyecto_frm AS pry ON pry.intCodigo_pry=ctr.intCodigo_pry");
            $query->join('INNER', "#__pfr_programa AS prg ON prg.intCodigo_prg=pry.intCodigo_prg");
            $query->where('ctr.intIdContrato_ctr = ' . $idContrato);
            $db->setQuery($query);
            $db->query();
            return $db->loadObject();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.tiposcontratista.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Utilizada para la ALINEACION
     * 
     * RECUPERA la informacion de un contrato dado el identificador
     * @param type $idContrato  Identificador del contrato.
     * @return Object
     */
    public function getConvenios() {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('
                ctr.intIdContrato_ctr       AS idContrato,	
		ctr.intIdContrato_padre,	
		ctr.intIdTipoContrato_tc,	
		ctr.intIdentidad_ent        AS idEntidad,	
		ctr.intCodigo_pry,
		ctr.intIdPartida_pda,	
		ctr.strCodigoContrato_ctr,
		ctr.strCUR_ctr,
		ctr.intPlazo_ctr,
		ctr.dcmMonto_ctr,
		ctr.intNumContrato_ctr,	
		ctr.strDescripcion_ctr      AS  grDescripcion,	
		ctr.strObservacion_ctr,	
		ctr.published,
		ctr.checked_out,
		ctr.checked_out_time  
                ');

            $query->from('#__ctr_contrato AS ctr');
            $query->where('ctr.intIdTipoContrato_tc = 2');

            $db->setQuery($query);
            $db->query();
            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.tiposcontratista.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    /**
     *  Eliminacion logica de un contrato
     * @param type $id
     */
    public function deleteLogicoCnt( $id ) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->update( " #__ctr_contrato " );
            $query->set(' published = 0 ');
            $query->where( "intIdContrato_ctr = {$id}");
            $db->setQuery($query);
            $retval = ( $db->query() ) ? true : false;
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.contrato.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    

}
