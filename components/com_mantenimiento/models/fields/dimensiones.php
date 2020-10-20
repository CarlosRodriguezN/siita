<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldDimenciones extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'dimenciones';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $dimension = 0;
            $idTipoDimension = $this->form->getField('intId_td')->value;
            
            if ($idTipoDimension <> 0) {
                //  Obtengo el valor del subsector
                $idDimension = $this->_getIdDimension($idTipoDimension);
                //  Seteo al XML el valor del Sector con la finalidad de obtener una lista de SubSectores
                //  que formen parte del sector seteado
                $this->form->setValue('intId_dim', null, $idDimension);
            }
            
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_MANTENIMIENTO_FIELD_BENEFICIARIO_DIMENSION_TITLE') . '</option>';

            if ($idTipoDimension > 0) {
                $query->select('   dim.intId_td AS id, 
                                upper( dim.strDescripcion_dim ) AS nombre');
                $query->from('#__gen_dimension AS dim');
                $query->where('dim.intId_td = ' . $this->form->getField('intId_td')->value);
                $query->where('dim.published = 1');

                $db->setQuery($query);

                $messages = $db->loadObjectList();
                //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
                //  perteneciente a un determinado subsector

                foreach ($messages as $value) {
                    $selected = ( $idTipoDimension == $value->id ) ? 'selected' : '';

                    $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
                }
            }

            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.dimension.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    private function _getIdDimension($idTipoDimension) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("dim.intId_dim as id");
            $query->from("#__gen_Dimension dim");
            $query->where("dim.intId_td = '{$idTipoDimension}'");

            $db->setQuery((string) $query);

            $idCategoria = $db->loadObject();
            return $idCategoria->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.dimension.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
}



