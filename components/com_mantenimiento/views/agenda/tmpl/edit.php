<?php
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">
            <h2> <?php if ($this->item->intIdAgenda_ag == null): ?>
                    <?php echo JText::_('COM_MANTENIMIENTO_AGENDA_CREATING'); ?>
                <?php else: ?>
                    <?php echo JText::_('COM_MANTENIMIENTO_AGENDA_EDITING'); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <div class="m">
        <form action="<?php echo JRoute::_('index.php?option=com_mantenimiento&layout=edit&intIdAgenda_ag=' . (int) $this->item->intIdAgenda_ag); ?>" method="post" name="adminForm" id="agenda-form" >
            
            <!-- Div/Tab de Plan Estratégico Institucional -->
            <div id="tabsAgenda" style="position: static; left: 20px; height: auto; width: 100%">
                <ul>
                    <li><a href="#dtaAgenda" title="<?php echo JText::_( 'COM_COM_MANTENIMIENTO_FIELD_AGENDA_DATA_GENERAL_DESC' ); ?>"> <?php echo JText::_('COM_COM_MANTENIMIENTO_FIELD_AGENDA_DATA_GENERAL_LABEL') ?></a></li>
                    <li><a href="#detalleAgd" title="<?php echo JText::_( 'COM_COM_MANTENIMIENTO_FIELD_AGENDA_DETALLE_DESC' ); ?>"> <?php echo JText::_('COM_COM_MANTENIMIENTO_FIELD_AGENDA_DETALLE_LABEL') ?></a></li>
                    <li><a href="#estructuraAgd" title="<?php echo JText::_( 'COM_COM_MANTENIMIENTO_FIELD_AGENDA_ESTRUCTURA_DESC' ); ?>"> <?php echo JText::_('COM_COM_MANTENIMIENTO_FIELD_AGENDA_ESTRUCTURA_LABEL') ?></a></li>
                    <li><a href="#itemsAgd" id="controlItmEtr" title="<?php echo JText::_( 'COM_COM_MANTENIMIENTO_FIELD_AGENDA_ITEM_DESC' ); ?>"> <?php echo JText::_('COM_COM_MANTENIMIENTO_FIELD_AGENDA_ITEM_LABEL') ?></a></li>
                </ul>

                <!-- Registro de los datos generales de una agenda -->
                <div class="m" id="dtaAgenda">
                    <?php echo $this->loadTemplate('agenda'); ?>
                </div>
                
                <!-- Registro de los detalles de una agenda -->
                <div class="m" id="detalleAgd">
                    <?php echo $this->loadTemplate('detalleagd'); ?>
                </div>
                
                <!-- Registro de la estructura de un agenda -->
                <div class="m" id="estructuraAgd">
                    <?php echo $this->loadTemplate('estructuraagd'); ?>
                </div>
                <!-- Registro de los datos generales de un nuevo plan -->
                <div class="m" id="itemsAgd">
                    <?php echo $this->loadTemplate('itemsagd'); ?>
                </div>
            
            </div>
            
            <div>
                <input type="hidden" name="task" value="agenda.edit" />
                <?php echo JHtml::_('form.token'); ?>
            </div>

    </form>
</div>
    
<?php echo $this->loadTemplate('progressblock'); ?>