<?php
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">
            <h2> <?php if ($this->item->intId_enfoque == null): ?>
                    <?php echo JText::_('COM_MANTENIMIENTO_ENFOQUE_CREATING'); ?>
                <?php else: ?>
                    <?php echo JText::_('COM_MANTENIMIENTO_ENFOQUE_EDITING'); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <div class="m">
        <form action="<?php echo JRoute::_('index.php?option=com_mantenimiento&layout=edit&intId_enfoque=' . (int) $this->item->intId_enfoque); ?>" method="post" name="adminForm" id="programa-form" class="form-validate" enctype="multipart/form-data" >
            <div class="width-100 fltlft">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('COM_MANTENIMIENTO_DATA_GEN'); ?>&nbsp;</legend>
                    <ul class="adminformlist">
                        <?php foreach ($this->form->getFieldset('enfoque') as $field): ?>
                            <li>
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </fieldset>
            </div>
            <div>
                <input type="hidden" name="task" value="enfoque.edit" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>

    </div>
</div>