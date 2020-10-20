<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!--Lista de sub Programas -->
<div class="width-100 ">    
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_('COM_PROGRAMA_INFGENERAL_PROGRAMA'); ?>&nbsp;</legend>
        <ul class="adminformlist">
            <?php foreach ($this->form->getFieldset('programa') as $field): ?>
                <li>
                    <?php echo $field->label; ?>
                    <?php echo $field->input; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
    <div>
        <input type="hidden" name="task" value="programa.edit" />
        <input type="hidden" name="idPrg" value="<?php echo (int) $this->item->intCodigo_prg; ?>" />
        <input type="hidden" id="articlePrg" value="<?php echo $this->articlePrg; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</div>