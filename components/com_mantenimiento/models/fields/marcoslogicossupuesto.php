<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldMarcoslogicossupuesto extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'marcoslogicossupuesto';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $idObjetivoml = 0;
            $idSupuesto = $this->form->getField('intCodigo_susp')->value;
            
            if ($idSupuesto <> 0) {
                //  Obtengo el valor del subsector
                $idObjetivoml = $this->_getIdMarcologico($idSupuesto);
                //  Seteo al XML el valor del Sector con la finalidad de obtener una lista de SubSectores
                //  que formen parte del sector seteado
                $this->form->setValue('intCodigo_ojtoml', null, $idObjetivoml);
            }


            $query->select('oml.intcodigo_ojtoml AS id, 
                         upper( oml.strnombre_ojtoml ) AS nombre');
            $query->from('#__pfr_objetoml AS oml');
            $query->where('oml.published = 1');
            $db->setQuery($query);
            $messages = $db->loadObjectList();
            //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
            //  perteneciente a un determinado subsector
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_MANTENIMIENTO_FIELD_SUPUESTO_OJTOML_TITLE') . '</option>';
            foreach ($messages as $value) {
                $selected = ( $idObjetivoml == $value->id ) ? 'selected' : '';

                $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
            }
            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.marcoslogicossupuesto.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    private function _getIdMarcologico($idSupuesto) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("spu.intCodigo_ojtoml as id");
            $query->from("#__gen_supuestos spu");
            $query->where("spu.intCodigo_susp = '{$idSupuesto}'");

            $db->setQuery((string) $query);

            $idObjetivoml = $db->loadObject();
            return $idObjetivoml->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.marcoslogicossupuesto.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}