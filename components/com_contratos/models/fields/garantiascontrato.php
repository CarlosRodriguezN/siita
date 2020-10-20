<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldGarantiasContrato extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'garantiascontrato';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getOptions() {
        $idContrato = JRequest::getVar("intIdContrato_ctr");
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('grt.intIdGarantia_gta AS id, 
                         upper( grt.intCodGarantia_gta ) AS  nombre');
        $query->from('#__ctr_garantia AS grt');
        $query->where('grt.published = 1');
        $query->where('grt.intIdContrato_ctr=' . $idContrato);

        $db->setQuery((string) $query);
        $messages = $db->loadObjectList();
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