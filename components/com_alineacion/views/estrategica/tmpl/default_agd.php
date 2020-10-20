<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<!-- Lista de Lineas Base Registradas -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_ALINEACION_AGENDA_LST') ?> </legend>
        <div class="fltrt">
            <input id="btnNewAgnd" type="button" value="<?php echo JText::_('BTN_AGREGAR') ?>">
        </div>
        <table id="lstAgnd" class="tablesorter width-100" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_('COM_ALINEACION_AGD_DESCRIPCION_LABLE') ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_('TL_ACCIONES') ?> </th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
            <tfoot>
            </tfoot>
        </table>
    </fieldset>
</div>

<!-- Formulario de registro de Lineas Base -->
<div class="width-50 fltrt">
    <div id="imgAgd" class="editbackground">
    </div>
    <div id="frmAgd" class ="hide" >
        <fieldset class="adminform">
            <legend> <?php echo JText::_('COM_ALINEACION_AGENDA_FRM') ?> </legend>
            <ul id="lstAgendas"  class="adminformlist">
                <?php foreach ($this->form->getFieldset('agenda') as $field): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr"></div>
            <div class="fltlft">
                <input id="btnAddAgnd" type="button" value="<?php echo JText::_('BTN_GUARDAR') ?>">
                <input id="btnClnAgnd" type="button" value="<?php echo JText::_('BTN_CANCELAR') ?>">
            </div>
            <div class="clr"></div>
        </fieldset>
    </div>
</div>

<div class="clr"></div>