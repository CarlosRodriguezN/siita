<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldUnidadesGestion extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'unidadesgestion';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $idUgPadre = 0;
            $idUgHija = $this->form->getField('intCodigo_ug')->value;
            
            if ($idUgHija <> 0) {
                //  Obtengo el ID del tipo de objetivo
                $idUgPadre = $this->_getIdUGPadre($idUgHija);

                //  Seteo al XML el valor del tipo de objetivo con la finalidad de obtener
                //  una lista de tipos de objetivos que formen parte del tipo seteado
                $this->form->setValue('tb_intCodigo_ug', null, $idUgPadre);
            }
            
            $query->select('ug.intCodigo_ug AS id, 
                            ug.strNombre_ug AS nombre');
            $query->from('#__gen_unidad_gestion AS ug');
            $query->where('ug.published = 1');
            if ( $idUgHija <> 0 ) {
                $query->where('ug.intCodigo_ug != ' . $idUgHija);
            }
            
            $db->setQuery($query);
            $messages = $db->loadObjectList();
            
            //  Creo el combo de Tipos de Objetivos y pongo en la opcion "selected"
            //  el tipo de un determinado objetivo
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_UNIDAD_GESTION_FIELD_UG_PADRE_TITLE') . '</option>';
            foreach ($messages as $value) {
                $selected = ( $idUgPadre == $value->id ) ? 'selected' : '';
                $options .= '<option value="' . $value->id . '" ' . $selected . '>' . strtoupper($value->nombre) . '</option>';
            }
            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.models.fiels.ugpadre.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    private function _getIdUGPadre($idUgf) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("ug.tb_intCodigo_ug as id");
            $query->from("#__gen_unidad_gestion ug");
            $query->where("ug.intCodigo_ug = '{$idUgf}'");

            $db->setQuery((string) $query);

            $idTpoObj = $db->loadObject();

            return $idTpoObj->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.models.fiels.ugpadre.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}