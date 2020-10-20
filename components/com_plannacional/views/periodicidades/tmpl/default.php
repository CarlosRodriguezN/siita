<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<div id="toolbar-box">
   <div class="m">
<?php echo $this->getToolbar(); ?>
   </div>
</div>
<div id="element-box">
   <div class="m">
      <form action="<?php echo JRoute::_('index.php?option=com_plannacional&amp;view=periodicidades'); ?>" method="post" name="adminForm" id="adminForm">

         <fieldset id="filter-bar">
            <!-- Filtro para una busqueda de un nombre o frase en particular -->
            <div class="filter-search fltlft">
               <label for="filter_search"> <?php echo JText::_('JSEARCH_FILTER_LABEL'); ?> </label>
               <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="Buscar">

               <button type="submit" class="btn"> <?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?> </button>
               <button type="button" onclick="document.id( 'filter_search' ).value='';this.form.submit();">
<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
               </button>
            </div>

            <!-- Filtro para registros "Publicados" y "No Publicados" -->
            <div class="filter-select fltrt">
               <select name="filter_published" class="inputbox" onchange="this.form.submit()">
                  <option value=""> <?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?> </option>
<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true); ?>
               </select>
            </div>
         </fieldset>

         <table class="adminlist">
            <thead> <?php echo $this->loadTemplate('head'); ?> </thead>
            <tfoot> <?php echo $this->loadTemplate('foot'); ?> </tfoot>
            <tbody> <?php echo $this->loadTemplate('body'); ?> </tbody>
         </table>

         <div>
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
<?php echo JHtml::_('form.token'); ?>
         </div>
      </form>
   </div>
</div>