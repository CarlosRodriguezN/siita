<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldTiposunidadmedida extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'tiposunidadmedida';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $idTipounidadmed = 0;
            $idUnidadmed = $this->form->getField('intCodigo_unimed')->value;

            if ($idUnidadmed <> 0) {
                //  Obtengo el valor del subsector
                $idTipounidadmed = $this->_getIdTipounidadmed($idUnidadmed);
                //  Seteo al XML el valor del Sector con la finalidad de obtener una lista de SubSectores
                //  que formen parte del sector seteado
                $this->form->setValue('intId_tum', null, $idTipounidadmed);
            }


            $query->select('tum.intId_tum AS id, 
                         upper( tum.strDescripcion_tum ) AS nombre');
            $query->from('#__gen_tipo_unidad_medida AS tum');
            $query->where('tum.published = 1');
            $db->setQuery($query);

            $messages = $db->loadObjectList();
            //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
            //  perteneciente a un determinado subsector
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_MANTENIMIENTO_FIELD_UNIDADMEDIDA_TIPOUNIDADMEDIDA_TITLE') . '</option>';
            foreach ($messages as $value) {
                $selected = ( $idTipounidadmed == $value->id ) ? 'selected' : '';

                $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
            }
            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.unidadmedida.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    private function _getIdTipounidadmed($idUnidadmed) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("um.intId_tum as id");
            $query->from("#__gen_unidad_medida um");
            $query->where("um.intCodigo_unimed = '{$idUnidadmed}'");

            $db->setQuery((string) $query);

            $idCategoria = $db->loadObject();
            return $idCategoria->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.unidadmedida.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}