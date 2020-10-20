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
                    <?php echo JText::_('COM_FUNCIONARIOS_POA_CREATING'); ?>
                <?php else: ?>
                    <?php echo JText::_('COM_FUNCIONARIOS_POA_EDITING'); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <form action="<?php echo JRoute::_('index.php?option=com_funcionarios&layout=edit&intId_pi=' . (int) $this->item->intId_pi); ?>" method="post" name="adminForm" >
        <!-- Registro de los datos generales de un nuevo plan -->
        <div class="width-100">
            <?php echo $this->loadTemplate('dtageneral'); ?>
        </div>

        <div>
            <input type="hidden" name="task" value="poa.edit" />
            <input type="hidden" id="idEntidad" name="idEntidad" value="<?php echo $this->idEntidad ?>" />
            <input type="hidden" id="idRegPoa" name="idRegPoa" value="<?php echo $this->idRegPoa ?>" />
            <input type="hidden" id="opPoa" name="opPoa" value="<?php echo $this->opPoa ?>" />
            <input type="hidden" id="padrePoa" name="padrePoa" value="<?php echo $this->padrePoa ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>

    </form>
</div>