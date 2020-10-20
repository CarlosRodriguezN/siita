<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldEstructura extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'estructura';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $idPadreEtr = 0;
            $idEstructura = $this->form->getField('intIdEstructura_es')->value;

            if ($idEstructura <> 0) {
                //  Obtengo el valor del subsector
                $idPadreEtr = $this->_getEstructuraPadre($idEstructura);

                //  Seteo al XML el valor del Sector con la finalidad de obtener una lista de SubSectores
                //  que formen parte del sector seteado
                $this->form->setValue('intIdEstuctura_padre_es', null, $idPadreEtr);
            }

            $query->select('e.intIdEstructura_es AS id, 
                         upper( e.strDescripcion_es ) AS nombre');
            $query->from('#__agd_estructura AS e');
            $query->where('e.intIdAgenda_ag = ' . $this->form->getField('intIdAgenda_ag')->value);
            $query->where('e.published = 1');
            $db->setQuery($query);

            $messages = $db->loadObjectList();
            //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
            //  perteneciente a un determinado subsector
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_MANTENIMIENTO_FIELD_AGD_ESTRUCTURA_PADRE_TITLE') . '</option>';
            if (count($messages) > 0){
                foreach ($messages as $value) {
                    $selected = ( $idPadreEtr == $value->id ) ? 'selected' : '';

                    $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
                }
            }
            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.estructura.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    private function _getEstructuraPadre($idEstr) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("intIdEstuctura_padre_es as id");
            $query->from("#__agd_estructura");
            $query->where("intIdEstructura_es = '{$idEstr}'");

            $db->setQuery((string) $query);

            $idCategoria = $db->loadObject();

            return $idCategoria->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.estructura.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}