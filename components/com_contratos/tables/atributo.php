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
class contratosTableAtributo extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_atributo', 'intIdAtributo_attr', $db);
    }

    public function deleteAtributo($idAtributo) {
        try {
            if (!$this->relacionadaTo($idAtributo)) {
                $db = & JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->delete($db->nameQuote('#__ctr_atributo'));
                $query->where($db->nameQuote('intIdAtributo_attr') . '=' . $db->quote($idAtributo));

                $db->setQuery($query);
                $db->query();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.atributo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * verifica si tiene relacion con otras tablas
     */
    public function relacionadaTo($idCargo) {
        if ($this->relacionada($idCargo, 'ctr_contrato', 'intIdContrato_ctr')) {
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
            $log = &JLog::getInstance('com_contratos.table.atributo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * 
     * @param type $idContrato identificador del contrato
     */
    public function getAtributosContrato($idContrato) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("intIdAtributo_attr AS   idAtributo,
                            intCodAtributo_attr AS codAtributo,
                            strNombre_attr AS nombre,
                            dcmValor_attr AS valor,
                            published 
                            ");
            $query->from("#__ctr_atributo");
            $query->where('intIdContrato_ctr=' . $db->quote($idContrato));
            $query->order('strNombre_attr');
            
            $db->setQuery($query);
            $db->query();
            return ( $db->getAffectedRows() > 0 ) ? $db->loadObjectList() : array();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.atributo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Guarda o edita un atributo cuando viene desde el formulario contratos
     * @param type $idContrato
     * @param type $atributo
     */
    public function saveAtributos( $idContrato, $atributo ){
        
        if ($atributo->published != 0) {
            $data = false;

            //  recupero tipo de datos del campo atributo
            if( gettype($atributo->idAtributo) == "string" ){
                $atributo->idAtributo = 0;
            }
            
            $data["intIdAtributo_attr"] = $atributo->idAtributo;
            $data["intIdContrato_ctr"] = $idContrato;
            $data["intCodAtributo_attr"] = $atributo->codAtributo;
            $data["strNombre_attr"] = $atributo->nombre;
            $data["dcmValor_attr"] = $atributo->valor;

            if ($this->bind($data)) {

                if ($this->save($data)) {
                    return $this->intIdAtributo_attr;
                }
            }
        } else {

            $this->delete($atributo->idAtributo);
        }
    }

    /**
     * borra cuando es eliminado desde el formunlari
     * @param type $idAtributo
     * @return boolean
     */
    public function delete($idAtributo) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->delete($db->nameQuote('#__ctr_atributo'));
            $query->where($db->nameQuote('intIdAtributo_attr') . '=' . $db->quote($idAtributo));

            $db->setQuery($query);
            $db->query();
            return true;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.atributo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}