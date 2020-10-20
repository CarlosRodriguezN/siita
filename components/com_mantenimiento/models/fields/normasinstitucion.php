<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldNormasinstitucion extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'normasinstitucion';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $idNorma = 0;
            $idInstitucion = $this->form->getField('intCodigo_ins')->value;
            
            if ($idInstitucion <> 0) {
                //  Obtengo el valor del subsector
                $idNorma = $this->_getIdNorma($idInstitucion);

                //  Seteo al XML el valor del Sector con la finalidad de obtener una lista de SubSectores
                //  que formen parte del sector seteado
                $this->form->setValue('inpCodigo_sec', null, $idNorma);
            }

            $query->select('nor.intCodigoNorma AS id, 
                            upper( nor.strDescripcion_norma ) AS nombre');
            $query->from('#__pei_tipo_norma AS nor');
            $query->where('nor.published = 1');

            $db->setQuery($query);

            $messages = $db->loadObjectList();
            //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
            //  perteneciente a un determinado subsector
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_MANTENIMIENTO_FIELD_INSTITUICION_NORMA_TITLE') . '</option>';
            foreach ($messages as $value) {
                $selected = ( $idNorma == $value->id ) ? 'selected' : '';

                $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
            }
            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.normasinstitucion.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    private function _getIdNorma($idInstitucion) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("ins.intCodigoNorma as id");
            $query->from("#__gen_institucion ins");
            $query->where("ins.intCodigo_ins = '{$idInstitucion}'");

            $db->setQuery((string) $query);

            $idNorma = $db->loadObject();

            return $idNorma->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.normasinstitucion.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}