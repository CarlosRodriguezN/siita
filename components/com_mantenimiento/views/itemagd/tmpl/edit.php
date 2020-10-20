<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.formvalidation');
JHTML::_('behavior.modal');
?>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">

            <h2> <?php if ($this->idItem == null): ?>
                    <?php echo JText::_('COM_MANTENIMIENTO_AGD_ITEM_CREATING'); ?>
                <?php else: ?>
                    <?php echo JText::_('COM_MANTENIMIENTO_AGD_ITEM_EDITING'); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <form action="<?php echo JRoute::_('index.php?option=com_mantenimiento&layout=edit&intIdItem_it=' . (int) $this->item->intIdItem_it); ?>" method="post" name="adminForm" >
        <!-- Registro de los datos generales de un nuevo plan -->
        <div class="width-100">
            <?php echo $this->loadTemplate('itemagd'); ?>
        </div>

        <div>
            <input type="hidden" name="task" value="poa.edit" />
            <input type="hidden" id="idItem" name="idItem" value="<?php echo $this->idItem ?>" />
            <input type="hidden" id="regEstructura" name="regEstructura" value="<?php echo $this->regEstructura ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>

    </form>
</div>