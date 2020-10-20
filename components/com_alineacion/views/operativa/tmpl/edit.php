<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<div id="toolbar-box">
    <div id="tbFrmIndicador">
        <div class="m">
            <?php echo $this->getToolbar(); ?>
            <div class="pagetitle icon-48-contact">
                <h2> <?php echo $this->title; ?> </h2>
            </div>
        </div>
    </div>
</div>


<div id="element-box">

    <!-- Pestaña de informacion general del proyecto -->
    <div id="pnbv">
        <?php echo $this->loadTemplate('operativa'); ?>
    </div>
    <div>
        <?php echo $this->loadTemplate('hidenattr'); ?>
    </div>
</div>