<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<div class="width-50 fltrt">
    <div id="imgMultaForm"  class="editbackground"></div>
    <div id="editMultaForm" class="hide">
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_MULTA'); ?>&nbsp;</legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('multas') as $field): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr">
            </div>
            <input id="addMulta" type="button" value="<?php echo JText::_('BTN_GUARDAR'); ?>">
            <input id="cancelMulta" type="button" value="<?php echo JText::_('BTN_CANCELAR'); ?>">
        </fieldset>
    </div>
</div>
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_LTSMULTAS'); ?>&nbsp;</legend>
        <div class="fltrt">
            <input id="addMultaTable" type="button" value="<?php echo JText::_('BTN_AGREGAR'); ?>">
        </div>
        <table id="tblstMultas" class="tablesorter" cellspacing="1" >
            <thead>
                <tr>
                    <th align="center" width="10%">
                        <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_MULTA_CODIGO'); ?>
                    </th>
                    <th align="center" >
                        <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_MULTA_OBSERVACION'); ?>
                    </th>
                    <th align="center" width="15%">
                        <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_MULTA_MONTO'); ?>
                    </th>
                    <th  colspan="2" align="center" width="10%">
                        <?php echo JText::_('ANY_ACCION_TABLA'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($this->multas): ?>
                    <?php foreach ($this->multas AS $multa): ?>
                        <tr id="<?php echo $multa->idMulta ?>">
                            <td><?php echo $multa->codMulta ?></td>
                            <td><?php echo $multa->observacion ?></td>
                            <td><?php echo "$ " . number_format($multa->monto, 2, '.','') ?></td>
                            <td style="width: 15px">
                                <a class="editMulta">
                                    <?php echo JText::_('LB_EDITAR'); ?>
                                </a>
                            </td>
                            <td  style="width: 15px">
                                <a class="delMulta">
                                    <?php echo JText::_('LB_ELIMINAR'); ?>
                                </a>
                            </td>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </fieldset>
</div>