<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">
            <h2> <?php if ($this->item->intCodigo_tipovar == null): ?>
                    <?php echo JText::_('COM_MANTENIMIENTO_TIPOVARIABLE_CREATING'); ?>
                <?php else: ?>
                    <?php echo JText::_('COM_MANTENIMIENTO_TIPOVARIABLE_EDITING'); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <div class="m">
        <form action="<?php echo JRoute::_('index.php?option=com_mantenimiento&layout=edit&inpCodigo_tipovar='.(int) $this->item->inpCodigo_tipovar ); ?>" method="post" name="adminForm" id="tipovariable-form" class="form-validate">
            <div class="width-60 fltlft">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('COM_MANTENIMIENTO_TIPOVARIABLE_GEN'); ?>&nbsp;</legend>
                    <ul class="adminformlist">
                        <?php foreach ($this->form->getFieldset('tipovariable') as $field): ?>
                            <li>
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </fieldset>
            </div>
            <div>
                <input type="hidden" name="task" value="tipovariable.edit" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>
        
    </div>
</div>