<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldGrupos extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'grupos';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $idCategoria = $this->form->getField('strCodigoCategoria')->value;

            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_MANTENIMIENTO_FIELD_BENEFICIARIO_GRUPO_TITLE') . '</option>';

            if ($idCategoria > 0) {
                $query->select('   gp.intCodigo_grp AS id, 
                                upper( gp.strDescripcion_grp ) AS nombre');
                $query->from('#__gen_grupo AS gp');
                $query->where('gp.strCodigoCategoria = ' . $this->form->getField('strCodigoCategoria')->value);
                $query->where('gp.published = 1');

                $db->setQuery($query);

                $messages = $db->loadObjectList();
                //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
                //  perteneciente a un determinado subsector

                foreach ($messages as $value) {
                    $selected = ( $idCategoria == $value->id ) ? 'selected' : '';

                    $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
                }
            }

            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.grupos.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}