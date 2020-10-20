<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldFuncionarioUg extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'funcionarioug';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $idFnci = 0;
            $idUgFnci = $this->form->getField('intId_ugf')->value;
            
            if ($idUgFnci <> 0) {
                //  Obtengo el ID del tipo de objetivo
                $idFnci = $this->_getIdFuncionario($idUgFnci);

                //  Seteo al XML el valor del tipo de objetivo con la finalidad de obtener
                //  una lista de tipos de objetivos que formen parte del tipo seteado
                $this->form->setValue('intCodigo_fnc', null, $idFnci);
            }
            
            $query->select('f.intCodigo_fnc AS id, 
                            upper( CONCAT(f.strApellido_fnc, " ", f.strNombre_fnc)) AS nombre');
            $query->from('#__gen_funcionario f');
            $query->leftJoin( '#__gen_ug_funcionario ugf ON ugf.intCodigo_fnc = f.intCodigo_fnc' );
            $query->where('ugf.intCodigo_ug = 0 AND f.intCodigo_fnc > 0');
            $query->where('ugf.published = 1 AND f.published = 1');

            $db->setQuery($query);
            $messages = $db->loadObjectList();
            
            //  Creo el combo de Tipos de Objetivos y pongo en la opcion "selected"
            //  el tipo de un determinado objetivo
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            if (!empty($messages)) {
                $options .= '   <option value="0">' . JText::_('COM_UNIDAD_GESTION_FIELD_FUNCIONARIO_TITLE') . '</option>';
                foreach ($messages as $value) {
                    $selected = ( $idFnci == $value->id ) ? 'selected' : '';

                    $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
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

    private function _getIdFuncionario($idUgf) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("ugf.intCodigo_fnc as id");
            $query->from("#__gen_ug_funcionario ugf");
            $query->where("ugf.intId_ugf = '{$idUgf}'");

            $db->setQuery((string) $query);

            $idTpoObj = $db->loadObject();

            return $idTpoObj->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.models.fiels.funcionario.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}