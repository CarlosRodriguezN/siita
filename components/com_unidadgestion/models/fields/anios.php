<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldAnios extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'anios';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $messages = array();
            $anio = date('Y');
            for ($i=$anio; $i < ($anio+ 10) ; $i++) {
                $messages[] = $i;
            }
            
            //  Creo el combo de Tipos de Objetivos y pongo en la opcion "selected"
            //  el tipo de un determinado objetivo
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            if (!empty($messages)) {
                foreach ($messages as $key=>$value) {
                    $options .= '<option value="' . $key . '" >' . $value . '</option>';
                }
            } else {
                $options .= '   <option value="0">' . JText::_('COM_UNIDAD_GESTION_SIN_REGISTROS') . '</option>';
            }
            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.models.fiels.funcionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}