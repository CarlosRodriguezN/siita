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
            <h2> <?php if ($this->item->idUrl == null): ?>
                    <?php echo JText::_('COM_APIREST_CREATING'); ?>
                <?php else: ?>
                    <?php echo JText::_('COM_APIREST_EDITING'); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <form id="frmUrl" action="<?php echo JRoute::_('index.php?option=com_apirest&amp;task=url.edit&amp;intIdApiUrl=' . $item->idUrl); ?>" method="post" name="adminForm" id="proyecto-form" class="form-validate">
        <div class="adminform">
            <div class="m" id="actGrafica">
                <?php echo $this->loadTemplate('url'); ?>
            </div>
            
            
            <div>
                <input type="hidden" name="task" value="url.edit" />
                <?php echo JHtml::_( 'form.token' ); ?>
            </div>
            
            
        </div>
    </form>
</div>
