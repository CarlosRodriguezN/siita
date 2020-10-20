<?php
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">
            <h2> <?php if ($this->item->inpCodigo_cargo == null): ?>
                    <?php echo JText::_('COM_MANTENIMIENTO_CARGO_FNC_CREATING'); ?>
                <?php else: ?>
                    <?php echo JText::_('COM_MANTENIMIENTO_CARGO_FNC_EDITING'); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <div class="m">
        <form action="<?php echo JRoute::_('index.php?option=com_mantenimiento&layout=edit&inpCodigo_cargo=' . (int) $this->item->inpCodigo_cargo); ?>" method="post" name="adminForm" id="cargofnc-form" >

            <!-- Registro de los datos generales -->
            <?php echo $this->loadTemplate('datageneral'); ?>
                
            <div>
                <input type="hidden" name="task" value="cargofnc.edit" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>
    </div>
</div>
    
<?php echo $this->loadTemplate('progressblock'); ?>