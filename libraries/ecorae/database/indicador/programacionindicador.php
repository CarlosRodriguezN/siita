<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla Funcionario Responsable ( tb_ind_funcionario_responsable )
 * 
 */
class jTableProgramacionIndicador extends JTable
{
    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct('#__ind_programacion_indicador', 'intId_prgInd', $db);
    }

    public function registroProgramacionInd($idIndEntidad, $idProgramacion)
    {
        if( !$this->save( $dtaPrgInd ) ) {
            throw new Exception(JText::_('COM_PROYECTOS_REGISTRO_PROGRAMACION_INDICADOR'));
        }

        return $this->intId_prgInd;
    }
}