<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldTiposinsinstitucion extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'tiposinsinstitucion';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $idTipoins = 0;
            $idInstitucion = $this->form->getField('intCodigo_ins')->value;
            
            if ($idInstitucion <> 0) {
                //  Obtengo el valor del subsector
                $idTipoins = $this->_getIdSector($idInstitucion);

                //  Seteo al XML el valor del Sector con la finalidad de obtener una lista de SubSectores
                //  que formen parte del sector seteado
                $this->form->setValue('inpCodigo_sec', null, $idTipoins);
            }

            $query->select('ti.intCodigoTipo_ins AS id, 
                            upper( ti.strDescripcionTipo_ins ) AS nombre');
            $query->from('#__gen_tipo_institucion AS ti');
            $query->where('ti.published = 1');

            $db->setQuery($query);

            $messages = $db->loadObjectList();
            //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
            //  perteneciente a un determinado subsector
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_MANTENIMIENTO_FIELD_INSTITUICION_TIPOINSTITUCION_TITLE') . '</option>';
            foreach ($messages as $value) {
                $selected = ( $idTipoins == $value->id ) ? 'selected' : '';

                $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
            }
            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.tiposinsinstitucion.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    private function _getIdSector($idInstitucion) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("ins.intCodigoTipo_ins as id");
            $query->from("#__gen_institucion ins");
            $query->where("ins.intCodigo_ins = '{$idInstitucion}'");

            $db->setQuery((string) $query);

            $idTipoins = $db->loadObject();

            return $idTipoins->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.tiposinsinstitucion.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}