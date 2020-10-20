    <?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100" id="funcionarioForm">
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_UNIDAD_GESTION_FIELD_DATA_GENERAL_LABEL'); ?></legend>
        <ul class="adminformlist">
            <?php foreach ($this->form->getFieldset('funcionario') as $field): ?>
                <li>
                    <?php echo $field->label; ?>
                    <?php echo $field->input; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
</div>