<?php
defined('_JEXEC') or die('Restricted access');

?>
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_MANTENIMIENTO_LST_CARGOS') ?> </legend>
        <div class="fltrt">
            <input id="cargos" class="addCargo" type="button" value=" &nbsp;<?php echo JText::_('BTN_AGREGAR') ?> &nbsp;">
        </div> 
        <table id="tbLstCargos" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"><?php echo JText::_('COM_MANTENIMIENTO_FIELD_CARGO_FNC_NOMBRE_LABEL') ?></th>
                    <th colspan="2" align="center"><?php echo JText::_('COM_MANTENIMIENTO_ACCIONES') ?></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <div id="srCargos" align="center" class="hide"> <p> <?php echo JText::_( 'COM_MANTENIMIENTO_SIN_REGISTROS' ); ?> </p> </div>
    </fieldset>
</div>
<div class="width-50 fltrt">
    <div id="imgCargo" class="editbackground">

    </div>
    <div id="frmCargo" class ="hide" >
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_('COM_MANTENIMIENTO_DATA_GEN'); ?>&nbsp;</legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('cargofnc') as $field): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr"></div>
            <input class="btnAdd" id="btnAdd" type="button" value=" &nbsp;<?php echo JText::_('BTN_GUARDAR') ?> &nbsp;">
            <input class="btnCancel" id="btnCancel" type="button" value=" &nbsp;<?php echo JText::_('BTN_CANCELAR') ?> &nbsp;">
            <div class="clr"></div>
        </fieldset>
    </div>
</div>