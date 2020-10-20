<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<form action="<?php echo JRoute::_('index.php?option=com_plannacional&layout=edit&INTCODIGO_PN='.(int) $this->item->INTCODIGO_PN ); ?>" method="post" name="adminForm" id="politicanacional-form" class="form-validate">
    
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
            <?php echo $this->form->getLabel( 'STRDESCRIPCION_PLN' ); ?>
            <div class="clr"></div>
            <?php echo $this->form->getInput( 'STRDESCRIPCION_PLN' ); ?>
            
        </fieldset>
    </div>
    
    <div>
        <input type="hidden" name="task" value="politicanacional.edit" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
    
</form>