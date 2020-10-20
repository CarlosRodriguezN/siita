<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * 
 *  HelloWorld Form Field class for the HelloWorld component
 * 
 */
class JFormFieldOperativos extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'objetivopnbv';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getOptions() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('DISTINCT(aln.intId_tpoEntidad)       AS id, 
                         tpe.strDescripcion_te AS nombre');
        $query->from('#__gen_objetivo_entidad aln');
        $query->join('inner', '#__gen_tipo_entidad tpe ON tpe.intIdtipoentidad_te=aln.intId_tpoEntidad');
        $query->where('aln.published = 1');
        $db->setQuery((string) $query);
        $messages = false;
        $options = array();
        if ($messages) {
            foreach ($messages as $message) {
                $options[] = JHtml::_('select.option', $message->id, $message->nombre);
            }
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

}
