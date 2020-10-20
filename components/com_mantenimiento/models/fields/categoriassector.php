<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldCategoriassector extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'categoriassector';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $idCategoria = 0;
            $idSector = $this->form->getField('inpCodigo_sec')->value;
            
            if ($idSector <> 0) {
                //  Obtengo el valor del subsector
                $idCategoria = $this->_getIdCategoria($idSector);

                //  Seteo al XML el valor del Sector con la finalidad de obtener una lista de SubSectores
                //  que formen parte del sector seteado
                $this->form->setValue('intCodigoCategoria', null, $idCategoria);
            }

            $query->select('c.strCodigoCategoria AS id, 
                            upper( c.strDescripcion_categoria ) AS nombre');
            $query->from('#__gen_categoria AS c');
            $query->where('c.published = 1');

            $db->setQuery($query);

            $messages = $db->loadObjectList();
            //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
            //  perteneciente a un determinado subsector
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_MANTENIMIENTO_FIELD_SECTOR_CATEGORIA_TITLE') . '</option>';
            foreach ($messages as $value) {
                $selected = ( $idCategoria == $value->id ) ? 'selected' : '';

                $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
            }
            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.categoriassector.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    private function _getIdCategoria($idSector) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("sct.strCodigoCategoria as id");
            $query->from("#__gen_sector sct");
            $query->where("sct.inpCodigo_sec = '{$idSector}'");

            $db->setQuery((string) $query);

            $idCategoria = $db->loadObject();

            return $idCategoria->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.categoriassector.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}