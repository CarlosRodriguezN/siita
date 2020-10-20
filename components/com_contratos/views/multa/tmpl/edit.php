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
            <h2> <?php echo ($this->item->intIdMulta_mta == 0) ? JText::_('COM_CONTRATOS_GESTION_ADD_MULTA') : JText::_('COM_CONTRATOS_GESTION_EDIT_MULTA') ?> </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <div class="m">

        <form action="<?php echo JRoute::_('index.php?option=com_contratos&layout=edit&intIdMulta_mta=' . (int) $this->item->intIdMulta_mta); ?>" method="post" name="adminForm" id="atributo-form" class="form-validate" enctype="multipart/form-data" >
            <div class="width-50 fltlft">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_ATRIBUTO_MULTA'); ?>&nbsp;</legend>
                    <ul class="adminformlist">
                        <?php foreach ($this->form->getFieldset('atributo') as $field): ?>
                            <li>
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </fieldset>
            </div>
            <div>
                <input type="hidden" name="task" value="multa.edit" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>

    </div>
</div>