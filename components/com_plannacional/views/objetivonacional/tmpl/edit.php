<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div id="toolbar-box">
   <div class="m">
      <?php echo $this->getToolbar(); ?>
   </div>
</div>
 
<div id="element-box">
   <div class="m">
      <form action="<?php echo JRoute::_('index.php?option=com_plannacional&layout=edit&intCodigo_on=' . (int) $this->item->intCodigo_on); ?>" method="post" name="adminForm" id="fase-form" class="form-validate">
         <div class="width-60 fltlft">
            <fieldset class="adminform">
               <ul class="adminformlist">
                  <?php foreach ($this->form->getFieldset('essential') as $field): ?>
                     <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                     </li>
                  <?php endforeach; ?>
               </ul>
               <div class="clr"></div>
               <?php echo $this->form->getLabel('descripcion'); ?>
               <div class="clr"></div>
               <?php echo $this->form->getInput('descripcion'); ?>
            </fieldset>
         </div>
         <div>
            <input type="hidden" name="task" value="objetivonacional.edit" />
            <?php echo JHtml::_('form.token'); ?>
         </div>
      </form>
   </div>
</div>