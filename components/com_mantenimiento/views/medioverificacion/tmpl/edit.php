<?php
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">
            <h2> <?php if ($this->item->intCodigo_medver == null): ?>
                    <?php echo JText::_('COM_MANTENIMIENTO_MEDIOVERIFICACION_CREATING'); ?>
                <?php else: ?>
                    <?php echo JText::_('COM_MANTENIMIENTO_MEDIOVERIFICACION_EDITING'); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <div class="m">
        <form action="<?php echo JRoute::_('index.php?option=com_mantenimiento&layout=edit&intCodigo_medver=' . (int) $this->item->intCodigo_medver); ?>" method="post" name="adminForm" id="programa-form" class="form-validate" enctype="multipart/form-data" >
            <div class="width-50 fltlft">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('COM_MANTENIMIENTO_MEDIOVERIFICACION_GEN'); ?>&nbsp;</legend>
                    <ul class="adminformlist">
                        <?php foreach ($this->form->getFieldset('medioverificacion') as $field): ?>
                            <li>
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </fieldset>
            </div>
            <div>
                <input type="hidden" name="task" value="medioverificacion.edit" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>

    </div>
</div>