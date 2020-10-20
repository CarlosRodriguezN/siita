<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldPrioridadesObj extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'prioridadesobj';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $idPrioridad = 0;
            $idObj = $this->form->getField('intId_ob')->value;
            
            if ($idObj <> 0) {
                //  Obtengo el ID del tipo de objetivo
                $idPrioridad = $this->_getIdPrioridad($idObj);

                //  Seteo al XML el valor del tipo de objetivo con la finalidad de obtener
                //  una lista de tipos de objetivos que formen parte del tipo seteado
                $this->form->setValue('intPrioridad_ob', null, $idPrioridad);
            }

            $query->select('pr.intIdPrioridad_pr AS id, 
                            upper( pr.strNombre_pr ) AS nombre');
            $query->from('#__gen_prioridad AS pr');
            $query->where('pr.published = 1');

            $db->setQuery($query);

            $messages = $db->loadObjectList();
            
            //  Creo el combo de Tipos de Objetivos y pongo en la opcion "selected"
            //  el tipo de un determinado objetivo
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_PROYECTO_FIELD_OBJETIVO_PRIORIDAD_TITLE') . '</option>';
            foreach ($messages as $value) {
                $selected = ( $idPrioridad == $value->id ) ? 'selected' : '';

                $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
            }
            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_pei.models.fiels.objetivos.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    private function _getIdPrioridad($idObj) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("obj.intIdPrioridad_pr as id");
            $query->from("#__pln_objetivo_institucion obj");
            $query->where("obj.intId_ob = '{$idObj}'");

            $db->setQuery((string) $query);

            $idTpoObj = $db->loadObject();

            return $idTpoObj->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_pei.models.fiels.objetivos.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}