<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<div class="width-50 fltrt">
    <div id="imgMultaForm"  class="editbackground"></div>
    <div id="editMultaForm" class="hide">
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_MULTA'); ?>&nbsp;</legend>
            <ul class="adminformlist" id="formMultasCnt">
                <?php foreach ($this->form->getFieldset('multas') as $field): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr">
            </div>

            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                <input id="addMulta" type="button" value="<?php echo JText::_('BTN_GUARDAR'); ?>">
            <?php endIf; ?>
                
            <input id="cancelMulta" type="button" value="<?php echo JText::_('BTN_CANCELAR'); ?>">
        </fieldset>
    </div>
</div>
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_LTSMULTAS'); ?>&nbsp;</legend>
        <div class="fltrt">
            <input id="addMultaTable" type="button" value="&nbsp;<?php echo JText::_('BTN_AGREGAR_MULTA'); ?>&nbsp;">
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
                        <?php echo JText::_('TL_ACCIONES'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </fieldset>
</div>