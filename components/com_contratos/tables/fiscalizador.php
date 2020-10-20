<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla cargo ( #__ctr_cargo )
 * 
 */
class contratosTableFiscalizador extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_fiscalizador', 'intIdFiscalizador_fc', $db);
    }

    public function deleteFiscalizador($idFiscalizador) {
        try {
            if (!$this->relacionadaTo($idFiscalizador)) {
                $db = & JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->delete($db->nameQuote('#__ctr_fiscalizador'));
                $query->where($db->nameQuote('intIdFiscalizador_fc') . '=' . $db->quote($idFiscalizador));

                $db->setQuery($query);
                $db->query();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.fiscalizador.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * verifica si tiene relacion con otras tablas
     */
    public function relacionadaTo($idFiscalizador) {
        if ($this->relacionada($idFiscalizador, 'ctr_contrato', 'intIdFiscalizador_fc')) {
            return true;
        }
        return false;
    }

    /**
     * verifica que no este relacionada el registro con otro en otra tabla.
     * @param type $idTipo
     * @param string $tabla nombre de la tabla que sera comparada, sin en prefijo
     * @param string $clmName nombre de la columna que sera comparada.
     */
    function relacionada($idTipo, $tabla, $clmName) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("COUNT(" . $clmName . ") AS cantidad");
            $query->from("#__" . $tabla);
            $query->where($clmName . '=' . $db->quote($idTipo));

            $db->setQuery($query);
            $db->query();
            $num = $db->loadObject()->cantidad;
            return ($num > 0) ? true : false;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.cargo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function getFiscalizadoresContratos($idContrato) {

        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("fico.intIdFisCrto_fctr       AS     idFiscaContrato,
                            fico.intIdContrato_ctr       AS     idContrato,
                            fico.intIdFiscalizador_fc    AS     idFiscalizador,
                            fico.dteFechaInicio_fctr     AS     fchIncio,
                            fico.dteFechaFin_fctr        AS     fchFin,
                            fico.dteFechaRegistro_fc     AS     fschRegisto,
                            fizc.intIdPersona_pc         AS     idPersona,
                            fizc.strRUC_fc               AS     ruc,
                            fizc.dteFechaRegistro_fc     AS     fchRegistoPersona,
                            pers.strApellidos_pc         AS     apellidos ,
                            pers.strNombres_pc           AS     nombres,
                            pers.strCedula_pc            AS     cedula,
                            pers.strCorreoElectronico_pc AS     correo,
                            pers.strTelefono_pc          AS     telefono,
                            pers.strCelular_pc           AS     celular,
                            fico.published               AS     published
                            ");

            $query->from("#__ctr_contrato AS ctro");

            $query->join('INNER', "#__ctr_fiscalizador_contrato AS fico ON fico.intIdContrato_ctr= ctro.intIdContrato_ctr");
            $query->join('INNER', "#__ctr_fiscalizador AS fizc ON fico.intIdFiscalizador_fc= fizc.intIdFiscalizador_fc");
            $query->join('INNER', "#__ctr_persona AS pers ON pers.intIdPersona_pc= fizc.intIdPersona_pc");
            $query->where("ctro.intIdContrato_ctr=" . $idContrato);
            $query->where(" fico.published=1");
            $db->setQuery($query);
            $db->query();
            return ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : array();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.fiscalizador.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     */
    public function getDataPersonaFicalizador($idFiscalizador) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("
                            fizc.intIdPersona_pc         AS     idPersona,
                            fizc.strRUC_fc               AS     ruc,
                            fizc.dteFechaRegistro_fc     AS     fchRegistoPersona,
                            pers.strApellidos_pc         AS     apellidos,
                            pers.strNombres_pc           AS     nombres,
                            pers.strCedula_pc            AS     cedula,
                            pers.strCorreoElectronico_pc AS     correo,
                            pers.strTelefono_pc          AS     telefono,
                            pers.strCelular_pc           AS     celular,
                            pers.published               AS     published
                            ");

            $query->from("#__ctr_fiscalizador AS fizc");

            $query->join('INNER', "#__ctr_persona AS pers ON pers.intIdPersona_pc= fizc.intIdPersona_pc");
            $query->where("fizc.intIdFiscalizador_fc=" . $idFiscalizador);
            $db->setQuery($query);
            $db->query();
            return $db->loadObjectList();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.fiscalizador.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}