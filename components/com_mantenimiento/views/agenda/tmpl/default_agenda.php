<?php
defined('_JEXEC') or die('Restricted access');

?>
<div class="width-100 fltlft">
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_('COM_MANTENIMIENTO_DATA_GEN'); ?>&nbsp;</legend>
        <ul class="adminformlist">
            <?php foreach ($this->form->getFieldset('agenda') as $field): ?>
                <li>
                    <?php echo $field->label; ?>
                    <?php echo $field->input; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
</div>