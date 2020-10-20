<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla Programacion Indicador Detalle ( tb_ind_prgind_prgdet )
 * 
 */
class jTablePrgIndDetalle extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ind_prgind_prgdet', 'intId_proInd_prgdet', $db);
    }

    
    public function registroPrgIndDetalle($dtaPrgIndDetalle) {
        
        if (!$this->save($dtaPrgIndDetalle)) {
            throw new Exception(JText::_('COM_PROYECTOS_REGISTRO_PROGRAMACION_INDICADOR'));
        }
        
        return $this->intId_proInd_prgdet;
    }
    
}