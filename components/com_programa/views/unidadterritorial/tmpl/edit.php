<?php
    // No direct access
    defined('_JEXEC') or die('Restricted access');
    JHtml::_('behavior.tooltip');
    JHtml::_('behavior.formvalidation');
?>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
    </div>
</div>

<div id="element-box">
    <form action="?" method="post" name="adminForm" id="cobertura-form" class="form-validate">
        <!-- Lista de Unidades Territoriales Registradas -->
        <div class="width-50 fltlft">
            <fieldset class="adminform">
                <legend> <?php echo JText::_('COM_PROGRAMA_FIELD_PROYECTO_LSTUNDTERRITORIAL_TITLE') ?> </legend>
                <table id="lstUndTerritorialesInd" width="100%" class="tablesorter" cellspacing="1">
                    <thead>
                        <tr>
                            <th align="center"> <?php echo JText::_('COM_PROGRAMA_INDICADOR_PROVINCIA_LABEL') ?> </th>
                            <th align="center"> <?php echo JText::_('COM_PROGRAMA_INDICADOR_CANTON_LABEL') ?> </th>
                            <th align="center"> <?php echo JText::_('COM_PROGRAMA_INDICADOR_PARROQUIA_LABEL') ?> </th>                                
                            <th colspan="2" align="center"> <?php echo JText::_('COM_PROGRAMA_FIELD_INDICADOR_OPERACION_FUENTE') ?> </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </fieldset>
        </div>

        <!-- Formulario de registro de provincias -->
        <div class="width-50 fltrt">
            <fieldset class="adminform">
                <legend> <?php echo JText::_('COM_PROGRAMA_FIELD_PROYECTO_UNDTERRITORIAL_TITLE') ?> </legend>
                <ul class="adminformlist">
                    <?php foreach ($this->form->getFieldset('unidadterritorial') as $field): ?>
                        <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="fltlft">
                    <input id="btnAddUndTerritorial" type="button" value="<?php echo JText::_('COM_PROGRAMA_INDICADOR_ADDUNDTERRITORIAL_TITLE') ?>">
                </div>
                <div class="clr"></div>
            </fieldset>
        </div>

        <div>
            <input type="hidden" name="task" value="unidadterritorial.edit" />
            <input type="hidden" id="tpoIndicador" name="tpoIndicador" value="<?php echo $this->_tpoIndicador; ?>" />
            <input type="hidden" id="idRegIndicador" name="idRegIndicador" value="<?php echo $this->_idRegIndicador; ?>" />
            <input type="hidden" id="tpo" name="tpo" value="<?php echo $this->_tpo; ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
</div>