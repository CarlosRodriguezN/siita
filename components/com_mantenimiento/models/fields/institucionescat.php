<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldInstitucionescat extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'institucionescat ';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $idInstitucion = 0;
            $idCategoria = $this->form->getField('strCodigoCategoria')->value;

            if ($idCategoria <> 0) {
                //  Obtengo el valor del subsector
                $idInstitucion = $this->_getIdCategoria($idCategoria);

                //  Seteo al XML el valor del Sector con la finalidad de obtener una lista de SubSectores
                //  que formen parte del sector seteado
                $this->form->setValue('intCodigo_ins', null, $idInstitucion);
            }


            $query->select('ins.intCodigo_ins AS id, 
                         upper( ins.strNombre_ins ) AS nombre');
            $query->from('#__gen_institucion AS ins');
            $query->where('ins.published = 1');
            $db->setQuery($query);

            $messages = $db->loadObjectList();
            //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
            //  perteneciente a un determinado subsector
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_MANTENIMIENTO_FIELD_CATEGORIA_INSTITUCION_TITLE') . '</option>';
            foreach ($messages as $value) {
                $selected = ( $idInstitucion == $value->id ) ? 'selected' : '';

                $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
            }
            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.institucionescat.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    private function _getIdCategoria($idCategoria) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("cat.intCodigo_ins as id");
            $query->from("#__gen_categoria cat");
            $query->where("cat.strCodigoCategoria = '{$idCategoria}'");

            $db->setQuery((string) $query);

            $idInstitucion = $db->loadObject();

            return $idInstitucion->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.institucionescat.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}