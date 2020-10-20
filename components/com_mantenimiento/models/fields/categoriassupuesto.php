<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldCategoriassupuesto extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'categoriassupuesto';

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
            $idSupuesto = $this->form->getField('intCodigo_susp')->value;

            if ($idSupuesto <> 0) {
                //  Obtengo el valor del subsector
                $idCategoria = $this->_getIdCategoria($idSupuesto);

                //  Seteo al XML el valor del Sector con la finalidad de obtener una lista de SubSectores
                //  que formen parte del sector seteado
                $this->form->setValue('intCodigoCategoria', null, $idCategoria);
            }


            $query->select('c.strCodigoCategoria AS id, 
                         upper( c.strDescripcionCategoria ) AS nombre');
            $query->from('#__gen_categoria AS c');
            $query->where('c.published = 1');
            $db->setQuery($query);

            $messages = $db->loadObjectList();
            //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
            //  perteneciente a un determinado subsector
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_MANTENIMIENTO_FIELD_SUPUESTO_CATEGORIA_TITLE') . '</option>';
            foreach ($messages as $value) {
                $selected = ( $idCategoria == $value->id ) ? 'selected' : '';

                $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
            }
            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.categoriassupuesto.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    private function _getIdCategoria($idSupuesto) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("spu.strCodigoCategoria as id");
            $query->from("#__gen_supuestos spu");
            $query->where("spu.intCodigo_susp = '{$idSupuesto}'");

            $db->setQuery((string) $query);

            $idCategoria = $db->loadObject();

            return $idCategoria->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.categoriassupuesto.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}