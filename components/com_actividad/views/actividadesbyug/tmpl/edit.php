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
            <h2> 
                <?php echo JText::_('COM_ACTIVIDAD_ACTIVIDADES_FNC'); ?>
            </h2>
        </div>
    </div>
</div>
<div id="element-box">
    <div class="adminform">
        <?php echo $this->loadTemplate('timeline'); ?>
    </div>
</div>