<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<!-- Lista de Lineas Base Registradas -->
<div class="width-55 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_ALINEACION_OPERATIVA') ?> </legend>
        <div class="fltrt">
            <input id="btnNewAln" type="button" value="<?php echo JText::_('BTN_AGREGAR') ?>">
        </div>
        <table id="lstAlineacion" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_('COM_ALINEACION_OPERATIVA_LABEL') ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_('TL_ACCIONES') ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>

<!-- Formulario de registro de Lineas Base -->
<div class="width-45 fltrt">
    <div id="imgAln" class="editbackground">
    </div>
    <div id="frmAln" class ="hide" >
        <fieldset class="adminform">
            <legend> <?php echo JText::_('COM_ALINEACION_OPERATIVA_FRM') ?> </legend>
            <ul id="lstAgendas" class="adminformlist">
                <?php foreach ($this->form->getFieldset('operativa') as $field): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
                <li>
                    <div id="contenedor"></div>
                </li>
            </ul>

            <div class="clr"></div>
            <div class="fltlft">
                <input id="btnAddAln" type="button" value="<?php echo JText::_('BTN_GUARDAR') ?>">
                <input id="btnClnAln" type="button" value="<?php echo JText::_('BTN_CANCELAR') ?>">
            </div>
            <div class="clr"></div>
        </fieldset>
    </div>
</div>

<div class="clr"></div>