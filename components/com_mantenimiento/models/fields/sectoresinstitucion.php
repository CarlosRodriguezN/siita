<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldSectoresinstitucion extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'sectoresinstitucion';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $idSector = 0;
            $idInstitucion = $this->form->getField('intCodigo_ins')->value;
            
            if ($idInstitucion <> 0) {
                //  Obtengo el valor del subsector
                $idSector = $this->_getIdSector($idInstitucion);

                //  Seteo al XML el valor del Sector con la finalidad de obtener una lista de SubSectores
                //  que formen parte del sector seteado
                $this->form->setValue('inpCodigo_sec', null, $idSector);
            }

            $query->select('sec.inpCodigo_sec AS id, 
                            upper( sec.strDescripcion_sec ) AS nombre');
            $query->from('#__gen_sector AS sec');
            $query->where('sec.published = 1');

            $db->setQuery($query);

            $messages = $db->loadObjectList();
            //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
            //  perteneciente a un determinado subsector
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_MANTENIMIENTO_FIELD_INSTITUICION_SECTOR_TITLE') . '</option>';
            foreach ($messages as $value) {
                $selected = ( $idSector == $value->id ) ? 'selected' : '';

                $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
            }
            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.sectoresinstitucion.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    private function _getIdSector($idInstitucion) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("ins.inpCodigo_sec as id");
            $query->from("#__gen_institucion ins");
            $query->where("ins.intCodigo_ins = '{$idInstitucion}'");

            $db->setQuery((string) $query);

            $idSector = $db->loadObject();

            return $idSector->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.sectoresinstitucion.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}