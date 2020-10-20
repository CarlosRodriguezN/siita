<?php

// No direct access to this file
defined('_JEXEC') or die;
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'tipogestion.php';
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldTiposGestion extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'tiposgestion';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getOptions() {
        $db = JFactory::getDBO();

        $tbTipoGestion = new JTableTipoGestion($db);

        $messages = $tbTipoGestion->getTiposGestion();
       
        $options = array();
        if ($messages) {
            foreach ($messages as $message) {
                $options[] = JHtml::_('select.option', strip_tags($message->id), strip_tags($message->nombre));
            }
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

}