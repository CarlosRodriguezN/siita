<?php

// No direct access to this file
defined('_JEXEC') or die;
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'planinstitucion' . DS . 'planinstitucion.php';

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * 
 *  HelloWorld Form Field class for the HelloWorld component
 * 
 */
class JFormFieldAgendas extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'agendas';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getOptions() {
        $datePeiVgt = $this->getDatePeiVegente();
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('    agd.intIdAgenda_ag      AS id, 
                            agd.strDescripcion_ag   AS nombre');
        $query->from(' #__agd_agenda AS agd ');
        $query->where(' agd.published = 1 ');

        $db->setQuery((string) $query);

        $messages = $db->loadObjectList();
        $options = array();
        if ($messages) {
            foreach ($messages as $message) {
                $options[] = JHtml::_('select.option', $message->id, $message->nombre);
            }
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

    /**
     *  Retorna el un objeto con las fecha de inicio y fin del PEI vigente
     * @return type
     */
    protected function getDatePeiVegente() {
        $modPlanInstitucion = new PlanInstitucion();
        $pei = $modPlanInstitucion->getPeiVigente();
        $result = new stdClass();
        $result->fechaIni = $pei->fechaInicioPln;
        $result->fechaFin = $pei->fechaFinPln;
        return $result;
    }

    
    
}
