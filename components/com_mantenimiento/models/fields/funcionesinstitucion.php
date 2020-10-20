<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldFuncionesinstitucion extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'funcionesinstitucion';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $idFuncion = 0;
            $idInstitucion = $this->form->getField('intCodigo_ins')->value;
            
            if ($idInstitucion <> 0) {
                //  Obtengo el valor del subsector
                $idFuncion = $this->_getIdFuncion($idInstitucion);

                //  Seteo al XML el valor del Sector con la finalidad de obtener una lista de SubSectores
                //  que formen parte del sector seteado
                $this->form->setValue('intCodigofuncion', null, $idFuncion);
            }

            $query->select( 'sec.intCodigofuncion AS id, 
                            upper( sec.strDescripcion_funcion ) AS nombre');
            $query->from(   '#__pei_funcion AS sec');
            $query->where(  'sec.published = 1');

            $db->setQuery($query);

            $messages = $db->loadObjectList();
            //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
            //  perteneciente a un determinado subsector
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_MANTENIMIENTO_FIELD_INSTITUICION_FUNCION_TITLE') . '</option>';
            foreach ($messages as $value) {
                $selected = ( $idFuncion == $value->id ) ? 'selected' : '';

                $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
            }
            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.funcionesinstitucion.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    private function _getIdFuncion($idInstitucion) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("ins.intCodigoFuncion as id");
            $query->from("#__gen_institucion ins");
            $query->where("ins.intCodigo_ins = '{$idInstitucion}'");

            $db->setQuery((string) $query);

            $idFuncion = $db->loadObject();

            return $idFuncion->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.funcionesinstitucion.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}