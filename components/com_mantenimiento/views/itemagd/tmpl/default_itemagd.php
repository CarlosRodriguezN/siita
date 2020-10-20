<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-60 fltlft" style="position: static; left: 20px; height: auto; width: 100%" >
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_MANTENIMIENTO_DATA_GEN'); ?></legend>
        <ul class="adminformlist">
            <?php foreach ($this->form->getFieldset('itemagd') as $field): ?>
                <li>
                    <?php echo $field->label; ?>
                    <?php echo $field->input; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="clr"></div>
        <?php echo $this->form->getLabel('descripcion'); ?>
        <div class="clr"></div>
        <?php echo $this->form->getInput('descripcion'); ?>
    </fieldset>
</div>
