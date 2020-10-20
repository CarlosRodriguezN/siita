<?php

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldLineaBaseFuente extends JFormFieldList {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'lineabasefuente ';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput() {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $idFuente = 0;
            $idLineaBase = $this->form->getField('intCodigo_lbind')->value;

            if ($idLineaBase <> 0) {
                //  Obtengo el valor del subsector
                $idFuente = $this->_getIdFuente($idLineaBase);

                //  Seteo al XML el valor del Sector con la finalidad de obtener una lista de SubSectores
                //  que formen parte del sector seteado
                $this->form->setValue('intCodigo_fuente', null, $idFuente);
            }


            $query->select('fun.intCodigo_fuente AS id, 
                         upper( fun.strObservacion_fuente ) AS nombre');
            $query->from('#__ind_fuente AS fun');
            $query->where('fun.published = 1');
            $db->setQuery($query);

            $messages = $db->loadObjectList();
            //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
            //  perteneciente a un determinado subsector
            $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
            $options .= '   <option value="0">' . JText::_('COM_MANTENIMIENTO_FIELD_LINEA_BASE_FUENTE_TITLE') . '</option>';
            foreach ($messages as $value) {
                $selected = ( $idFuente == $value->id ) ? 'selected' : '';

                $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
            }
            $options .= "</select>";

            return $options;
            
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.lineabasefuente.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    private function _getIdFuente($idLineaBase) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("lb.intCodigo_fuente as id");
            $query->from("#__ind_linea_base as lb");
            $query->where("lb.intCodigo_lbind = '{$idLineaBase}'");

            $db->setQuery((string) $query);

            $idFuente = $db->loadObject();

            return $idFuente->id;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('mod_mantenimiento.models.fiels.lineabasefuente.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}