<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldCargoFuncionario extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'cargofuncionario';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $idCargo = 0;
            $idUgFnci = $this->form->getField('intId_ugf')->value;
            
            if ($idUgFnci <> 0) {
                //  Obtengo el ID del tipo de objetivo
                $idCargo = $this->_getIdCargo($idUgFnci);

                //  Seteo al XML el valor del tipo de objetivo con la finalidad de obtener
                //  una lista de tipos de objetivos que formen parte del tipo seteado
                $this->form->setValue('inpCodigo_cargo', null, $idCargo);
            }

            $query->select('c.inpCodigo_cargo AS id, 
                            upper( c.strNombre_cargo ) AS nombre');
            $query->from('#__gen_cargo_funcionario AS c');
            $query->where('c.published = 1');

            $db->setQuery($query);
            $db->query();

            $messages = $db->loadObjectList();
            //  Creo el combo de Tipos de Objetivos y pongo en la opcion "selected"
            //  el tipo de un determinado objetivo
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_FUNCIONARIOS_FIELD_CARGO_FUNCIONARIO_TITLE') . '</option>';
            foreach ($messages as $value) {
                $selected = ( $idCargo == $value->id ) ? 'selected' : '';

                $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
            }
            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_funcionarios.models.fiels.cargo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    private function _getIdCargo($idFnc) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("ugf.inpCodigo_cargo as id");
            $query->from("#__gen_ug_funcionario ugf");
            $query->where("ugf.intId_ugf = '{$idFnc}'");

            $db->setQuery((string) $query);

            $idTpoObj = $db->loadObject();

            return $idTpoObj->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_funcionarios.models.fiels.cargo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}