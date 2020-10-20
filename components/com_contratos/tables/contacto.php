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
class contratosTableContacto extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_contacto', 'intIdContacto_cc', $db);
    }

    public function deleteContratista($idContratista) {
        try {
            if (!$this->relacionadaTo($idContratista)) {
                $db = & JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->delete($db->nameQuote('#__ctr_contacto'));
                $query->where($db->nameQuote('intIdContacto_cc') . '=' . $db->quote($idContratista));

                $db->setQuery($query);
                $db->query();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.tiposcontratista.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * verifica si tiene relacion con otras tablas
     */
    public function relacionadaTo($idContratista) {
        if ($this->relacionada($idContratista, 'ctr_cargo', 'intIdCargo_cgo')) {
            return true;
        }
        if ($this->relacionada($idContratista, 'ctr_contratista', 'intIdContratista_cta')) {
            return true;
        }
        if ($this->relacionada($idContratista, 'ctr_persona', 'intIdPersona_pc')) {
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

    /**
     * recupera la lista de contactos de un contratista.
     * @param type $idContratista identificador del contratista.
     */
    public function getContactosContratista($idContratista) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("    cto.intIdContacto_cc            AS  idContacto,
                                cto.intIdCargo_cgo              AS  idCargo,
                                cgo.strDescripcion_cgo          AS  cgoCargo,
                                cto.intIdPersona_pc             AS  idPersona,
                                per.strApellidos_pc             AS  perApellido,
                                per.strNombres_pc               AS  perNombre,
                                per.strCedula_pc                AS  perCedula,
                                per.strCorreoElectronico_pc     AS  perCorreo,
                                per.strTelefono_pc              AS  perTelefono,
                                per.strCelular_pc               AS  perCelular,
                                cto.published                   AS  published
                            ");
            $query->from("#__ctr_contacto AS cto");
            $query->join('inner', "#__ctr_persona AS per ON per.intIdPersona_pc=cto.intIdPersona_pc");
            $query->join('inner', "#__ctr_cargo AS cgo ON cgo.intIdCargo_cgo=cto.intIdCargo_cgo");
            $query->where("cto.published=1");
            $query->where("per.published=1");
            $query->where("cto.intIdContratista_cta=" . $idContratista);

            $db->setQuery($query);
            $db->query();
            return $db->loadObjectList();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.cargo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function saveContactosContratista($idContratista, $contacto) {
        try {
            $idContacto = 0;

            if (gettype($contacto->idContacto) == "string") {
                $contacto->idContacto = 0;
            }

            $data["intIdContacto_cc"] = $contacto->idContacto;
            $data["intIdCargo_cgo"] = $contacto->idCargo;
            $data["intIdContratista_cta"] = $idContratista;
            $data["intIdPersona_pc"] = $contacto->idPersona;
            $data["published"] = $contacto->published;

            if ($this->bind($data)) {
                if ($this->save($data)) {
                    $idContacto = $this->intIdContacto_cc;
                }
            }
            return $idContacto;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.cargo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}