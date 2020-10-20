<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<form action="<?php echo JRoute::_('index.php?option=com_mantenimiento&layout=edit&id_UBICACIONGEOGRAFICA='.(int) $this->item->id_UBICACIONGEOGRAFICA ); ?>" method="post" name="adminForm" id="dpa-form" class="form-validate">
    
    <div class="width-60 fltlft">
        <fieldset class="adminform">
            
            <ul class="adminformlist">
                <?php 
                    foreach( $this->form->getFieldset( 'essential' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach;?>
            </ul>
            
            <div class="clr"></div>
            <?php echo $this->form->getLabel( 'descripcion' ); ?>
            <div class="clr"></div>
            <?php echo $this->form->getInput( 'descripcion' ); ?>
        </fieldset>
    </div>
    
    
    
    <div>
        <input type="hidden" name="task" value="dpa.edit" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
    
</form>