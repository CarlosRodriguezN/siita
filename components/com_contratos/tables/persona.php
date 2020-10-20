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
class contratosTablePersona extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_persona', 'intIdPersona_pc', $db);
    }

    public function deletePersona($idPersona) {
        try {
            if (!$this->relacionadaTo($idPersona)) {
                $db = & JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->delete($db->nameQuote('#__ctr_persona'));
                $query->where($db->nameQuote('intIdPersona_pc') . '=' . $db->quote($idContratista));

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
    public function relacionadaTo($idPersona) {
        if ($this->relacionada($idPersona, 'ctr_contrato', 'intIdPersona_pc')) {
            return true;
        }
        if ($this->relacionada($idPersona, 'tb_ctr_fiscalizador', 'intIdPersona_pc')) {
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
            $log = &JLog::getInstance('com_contratos.table.tiposcontratista.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    function getDataPersona($idPersona) {

        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("intIdPersona_pc         AS  idPersona,
                            strApellidos_pc         AS  apellido,
                            strNombres_pc           AS  nombre,
                            strCedula_pc            AS  cedula,
                            strCorreoElectronico_pc AS  correo,
                            strTelefono_pc          AS  telefono,
                            strCelular_pc           AS  celular,
                            published               AS  published
                            ");
            $query->from("#__ctr_persona");
            $query->where("intIdPersona_pc=" . $db->quote($idPersona));

            $db->setQuery($query);
            $db->query();

            return $db->loadObjectList();
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.persona.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}