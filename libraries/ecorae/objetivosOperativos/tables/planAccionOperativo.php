<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
//jimport( 'joomla.database.table' );
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class jTablePlanAccionOperativo extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__gen_pln_accion_obj_operativo', 'intId_plnAcc_objOpr', $db);
    }

    /**
     *  Registra las relacion de la accion con el objetivo operativo
     * @param type $data
     * @return type
     */
    public function savePlnAccOperativo( $data ) 
    {

        if (!$this->save($data)) {
            echo 'error al guardar una acción de un objetivo';
            exit;
        }

        return $this->intId_plnAcc_objOpr;
    }

}
