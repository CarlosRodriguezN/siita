<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
jimport('ecorae.uploadfile.upload');
JTable::addIncludePath(JPATH_BASE . DS . 'components' . DS . 'com_contratos' . DS . 'tables');

/**
 * Modelo tipo obra
 */
class contratosModelEstadoGarantia extends JModelAdmin {

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'EstadoGarantia', $prefix = 'contratosTable', $config = array()) {
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
        $form = $this->loadForm('com_contratos.estadogarantia', 'estadogarantia', array('control' => 'jform', 'load_data' => $loadData));

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
        $data = JFactory::getApplication()->getUserState('com_contratos.edit.estadogarantia.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }

    /**
     * accede a la tabla y elimina un contratista.
     * @return type
     */
    public function deleteEstadoGarantia($idEstadoGarantia) {
        $tbEstadoGarantia = $this->getTable();
        return $tbEstadoGarantia->deleteEstadoGarantia($idEstadoGarantia);
    }

}