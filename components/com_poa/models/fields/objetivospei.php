<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldObjetivospei extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'objetivospei';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getOptions() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $idPei = JRequest::getVar("idPadre");

        $query->select('obj.intId_ob AS id, 
                        obj.strDescripcion_ob  AS  nombre');
        $query->from('#__pln_objetivo_institucion AS obj');
        $query->where('obj.intId_pi =' . $idPei);
        $query->where('obj.published = 1');
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