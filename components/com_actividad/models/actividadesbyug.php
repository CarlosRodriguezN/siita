<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

JTable::addIncludePath(JPATH_BASE . DS . 'components' . DS . 'com_actividad' . DS . 'tables');
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'funcionario.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'unidadgestion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'actividad.php';

/**
 * Modelo Plan Estratégico Institucional
 */
class ActividadModelActividadesByUG extends JModelAdmin {

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Actividad', $prefix = 'ActividadTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param	array	$data		Data for the form.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	mixed	A JForm object on success, false on failure
     * @since	1.6
     */
    public function getForm($data = array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm('com_actividad.actividad', 'actividad', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData() {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_actividad.edit.actividad.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }


    /**
     * 
     * @param type $idUnidadGestion
     */
    public function getActByFnc($idUnidadGestion) 
    {
        $lstFuncionarios = $this->getFunionarios($idUnidadGestion);
        
        if ( !empty($lstFuncionarios) ){
            //  Ontengo el plan vigente de la unidad de gestion
            $plnVigente = $this->getPlanVigenteUG($idUnidadGestion);
            //  Creo una intancia del table de actividades
            $db = JFactory::getDBO();
            $tbActividad = new jTableActividad( $db );
            //  Obtengo la lista de activiades por cada funcionario 
            foreach ( $lstFuncionarios as $fnc ){
                $fnc->lstActividades = $tbActividad->getLstActividadesByFnc( $fnc->idUgFnci, $plnVigente->dtaInicioPln );
            }
        }
        return $lstFuncionarios;
    }
    
    /**
     * 
     * @param type $idUnidadGestion
     */
    public function getFunionarios($idUnidadGestion) {
        $db = JFactory::getDBO();
        $tbFuncionarop  = new JTableUnidadFuncionario($db);
        $lstResponsables = $tbFuncionarop->lstFuncionariosByUG($idUnidadGestion);
        return $lstResponsables;
    }
    
    public function getPlanVigenteUG($idUG)
    {
        $db = JFactory::getDBO();
        $tbUndGst  = new JTableUnidadGestion($db);
        $plnVigente = $tbUndGst->getPlnVigenteUG($idUG);
        return $plnVigente;
    }
}