<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<form action="<?php echo JRoute::_('index.php?option=com_plannacional&layout=edit&intCodigo_in='.(int) $this->item->intCodigo_in ); ?>" method="post" name="adminForm" id="indicadornacional-form" class="form-validate">
    
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
            <?php echo $this->form->getLabel( 'STRDESCRIPCIONCATEGORIA' ); ?>
            <div class="clr"></div>
            <?php echo $this->form->getInput( 'STRDESCRIPCIONCATEGORIA' ); ?>
            
        </fieldset>
    </div>
    
    <div>
        <input type="hidden" name="task" value="categoria.edit" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
    
</form>