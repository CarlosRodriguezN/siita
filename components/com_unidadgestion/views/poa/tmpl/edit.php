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

            <h2> <?php if ($this->item->intId_pi == null): ?>
                    <?php echo JText::_('COM_UNIDAD_GESTION_POA_CREATING'); ?>
                <?php else: ?>
                    <?php echo JText::_('COM_UNIDAD_GESTION_POA_EDITING'); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <form action="<?php echo JRoute::_('index.php?option=com_unidadgestion&layout=edit&intId_pi=' . (int) $this->item->intId_pi); ?>" method="post" name="adminForm" >
        <!-- Registro de los datos generales de un nuevo plan -->
        <div class="width-100">
            <?php echo $this->loadTemplate('dtageneral'); ?>
        </div>

        <div>
            <input type="hidden" name="task" value="poa.edit" />
            <input type="hidden" id="idEntidadUG" name="idEntidadUG" value="<?php echo $this->idEntidadUG ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>

    </form>
</div>