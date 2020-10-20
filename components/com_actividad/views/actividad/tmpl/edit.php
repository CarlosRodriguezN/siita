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
            <h2> <?php if ($this->item->intId_plnAccion == null): ?>
                    <?php echo JText::_('COM_ACTIVIDAD_ACTIVIDAD_CREATING'); ?>
                <?php else: ?>
                    <?php echo JText::_('COM_ACTIVIDAD_ACTIVIDAD_EDITING'); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>
<div id="element-box">
    <div class="adminform">
        <div id="tabsActividades">
            <ul>
                <li><a href="#actGrafica" id="controlUpcAtc" ><?php echo JText::_('COM_ACTIVIDAD_TAB_TIMELINE'); ?></a></li>
                <li><a href="#actividades" ><?php echo JText::_('COM_ACTIVIDAD_TAB_GENERAL'); ?></a></li>
            </ul>
            <div class="m" id="actGrafica">
                <?php echo $this->loadTemplate('timeline'); ?>
            </div>
            <div class="m" id="actividades">
                <?php echo $this->loadTemplate('actividades'); ?>
            </div>
        </div>

        <div>
            <?php echo $this->loadTemplate( 'hide' ); ?>
        </div>

        <div>
            <input type="hidden" name="task" value="actividad.edit" />
            <input type="hidden" id="dtaRoles" name="dtaRoles" value="<?php print htmlspecialchars( json_encode( $this->canDo ) ); ?>" />

            <?php echo JHtml::_( 'form.token' ); ?>
        </div>
    </div>
</div>