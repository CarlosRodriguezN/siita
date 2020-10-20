<?php
    // No direct access
    defined('_JEXEC') or die('Restricted access');
    JHtml::_('behavior.tooltip');
    JHtml::_('behavior.formvalidation');
?>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">
            <h2> <?php echo $this->title; ?> </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <form action="?" method="post" name="adminForm" id="cobertura-form" class="form-validate">
        <!-- Lista de Planificacion de Indicadores -->
        <div class="width-50 fltlft">
            <fieldset class="adminform">
                <legend> <?php echo JText::_('COM_PROYECTOS_SEGUIMIENTO_VARIABLE_TITLE') ?> </legend>
                <table id="lstSegVariablesIndicadores" width="100%" class="tablesorter" cellspacing="1">
                    <thead>
                        <tr>
                            <th align="center"> <?php echo JText::_('COM_PROYECTOS_SEGUIMIENTO_VARIABLE_LABEL') ?> </th>
                            <th align="center"> <?php echo JText::_('COM_PROYECTOS_SEGUIMIENTO_FECHA_LABEL') ?> </th>
                            <th align="center"> <?php echo JText::_('COM_PROYECTOS_SEGUIMIENTO_VALOR_LABEL') ?> </th>
                            <th colspan="2" align="center"> <?php echo JText::_('COM_PROYECTOS_SEGUIMIENTO_OPERACION') ?> </th>
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
                <legend> <?php echo JText::_('COM_PROYECTOS_SEGUIMIENTO_LSTVARIABLE_TITLE') ?> </legend>
                <ul class="adminformlist">
                    <?php foreach ($this->form->getFieldset('seguimientoVariable') as $field): ?>
                        <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="clr"></div>
                <div class="fltlft">
                    <input id="btnAddSeguimiento" type="button" value="<?php echo JText::_('COM_PROYECTOS_ADD_SEGUIMIENTO') ?>">
                </div>
                <div class="clr"></div>
            </fieldset>
        </div>

        <div>
            <input type="hidden" name="task" value="planificacionvariable.edit" />
            <input type="hidden" id="tpoIndicador" name="tpoIndicador" value="<?php echo $this->_tpoIndicador; ?>" />
            <input type="hidden" id="idRegIndicador" name="idRegIndicador" value="<?php echo $this->_idRegIndicador; ?>" />
            <input type="hidden" id="tpo" name="tpo" value="<?php echo $this->_tpo; ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
</div>