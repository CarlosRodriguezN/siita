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
            <h2> <?php  echo JText::_( 'COM_APIREST_LOG' ); ?> </h2>
        </div>
    </div>
</div>

<div id="element-box">

    <form id="frmUrl" action="<?php echo JRoute::_('index.php?option=com_apirest&amp;task=url.edit' ); ?>" method="post" name="adminForm" id="proyecto-form" class="form-validate">
        <div class="adminform">
            <div class="m" id="actGrafica">
                <?php echo $this->loadTemplate('apilog'); ?>
            </div>
            
            <div>
                <input type="hidden" name="task" value="apilog.edit" />
                <?php echo JHtml::_( 'form.token' ); ?>
            </div>
        </div>
    </form>

</div>
