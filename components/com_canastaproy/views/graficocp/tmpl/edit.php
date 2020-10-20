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
            <h2> <?php if ($this->item->intId_gcp == null): ?>
                    <?php echo JText::_('COM_CANASTAPROY_GRAFICO_CP_CREATING'); ?>
                <?php else: ?>
                    <?php echo JText::_('COM_CANASTAPROY_GRAFICO_CP_EDITING'); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <div class="m">
        <form action="<?php echo JRoute::_('index.php?option=com_canastaproy&layout=edit&intId_gcp=' . (int) $this->item->intId_gcp); ?>" method="post" name="adminForm" id="fase-form" class="form-validate">

            <div class="width-60 fltlft">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('COM_CANASTAPROY_GRAFICO_CP_GEN'); ?>&nbsp;</legend>
                    <ul class="adminformlist">
                        <?php foreach ($this->form->getFieldset('graficocp') as $field): ?>
                            <li>
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="clr"></div>
                    <?php echo $this->form->getLabel('graficocp'); ?>
                    <div class="clr"></div>
                    <?php echo $this->form->getInput('graficocp'); ?>
                </fieldset>
            </div>
            <div>
                <input type="hidden" name="task" value="graficocp.edit" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>
    </div>
</div>