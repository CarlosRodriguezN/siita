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
            <h2> <?php echo ($this->item->intIdCargo_cgo == 0) ? JText::_('COM_CONTRATOS_GESTION_ADD_CARGO') : JText::_('COM_CONTRATOS_GESTION_EDIT_CARGO') ?> </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <div class="m">

        <form action="<?php echo JRoute::_('index.php?option=com_contratos&layout=edit&intIdCargo_cgo=' . (int) $this->item->intIdCargo_cgo); ?>" method="post" name="adminForm" id="cargo-form" class="form-validate" enctype="multipart/form-data" >
            <div class="width-50 fltlft">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_CONTRATISTA_GENERAL'); ?>&nbsp;</legend>
                    <ul class="adminformlist">
                        <?php foreach ($this->form->getFieldset('cargo') as $field): ?>
                            <li>
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </fieldset>
            </div>
            <div>
                <input type="hidden" name="task" value="cargo.edit" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>

    </div>
</div>