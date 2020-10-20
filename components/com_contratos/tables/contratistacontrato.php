<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Atribtuo ( #__ctr_atributo )
 * 
 */
class contratosTableContratistaContrato extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_contratista_contrato', 'intIdCC_cctr', $db);
    }

    function getContratistaContrato($idContrato) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("coco.intIdCC_cctr               AS  idContratistaContrato,
                            coco.intIdContratista_cta       AS  idContratista,
                            con.strContratista_cta          AS  strContratista,
                            coco.intIdContrato_ctr          AS  idContrato,
                            coco.dteFechaInicio_cctr        AS  fechaInicio,
                            coco.dteFechaFin_cctr           AS  fechaFin,
                            coco.dteFechaRegistro_cctr      AS  fechaRegistro,
                            coco.strObservacion_cctr        AS  observacion,
                            coco.published                  AS  published
                            ");
            $query->from( "#__ctr_contratista_contrato AS coco" );
            $query->join( 'inner', "#__ctr_contratista AS con ON con.intIdContratista_cta=coco.intIdContratista_cta" );
            $query->where( "intIdContrato_ctr=" . $idContrato );
            $query->where( "coco.published=1");
            $db->setQuery($query);
            $db->query();
            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.atributo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * alamacen la relación contratista contrato
     * @param int $idContrato identificador del contrato
     * @param int $contratista Identificador del contratista
     * @return int identificador de la linea contrato contratista.
     */
    public function saveContratistasContrato($idContrato, $contratista) {
        $idContratistaContrato = 0;
        if (gettype($contratista->idContratistaContrato) == "string") {
            $contratista->idContratistaContrato = 0;
        }

        $data["intIdCC_cctr"] = $contratista->idContratistaContrato;
        $data["intIdContratista_cta"] = $contratista->idContratista;
        $data["intIdContrato_ctr"] = $idContrato;
        $data["dteFechaInicio_cctr"] = $contratista->fechaInicio;
        $data["dteFechaFin_cctr"] = $contratista->fechaFin;
        $data["dteFechaRegistro_cctr"] = $contratista->fechaRegistro;
        $data["strObservacion_cctr"] = $contratista->observacion;
        $data["published"] = $contratista->published;


        if ($this->bind($data)) {
            if ($this->save($data)) {
                $idContratistaContrato = $this->intIdCC_cctr;
            }
        }
        return $idContratistaContrato;
    }

}
