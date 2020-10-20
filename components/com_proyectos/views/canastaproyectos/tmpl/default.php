<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div id="content-box">

    <div id="toolbar-box">
        <div class="m">
            <?php echo $this->getToolbar(); ?>
            <div class="pagetitle icon-48-contact">
                <h2> <?php echo $this->title; ?> </h2>
            </div>
        </div>
    </div>

    <div id="element-box">
        <div class="m">
            <form action="" method="post" name="adminForm" id="adminForm">
                <fieldset id="filter-bar">
                    <!-- Filtro para una busqueda de un nombre o frase en particular -->
                    <div class="filter-search fltlft">
                        <label for="filter_search"> <?php echo JText::_('JSEARCH_FILTER_LABEL'); ?> </label>
                        <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="Buscar">

                        <button type="submit" class="btn"> <?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?> </button>
                        <button type="button" onclick="document.id('filter_search').value = '';
                                this.form.submit();">
                            <?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
                        </button>
                    </div>

                    <!-- Lista de Proyectos -->
                    <div class="filter-select fltrt">
                        <select id="cbLstProyectos" name="cbLstProyectos" class="inputbox"></select>
                        <option value="0"> <?php echo JText::_('COM_PROYECTOS_FIELD_CANASTA_LSTPROYECTOS_LABEL'); ?> </option>
                    </div>
                    
                    <!-- Lista de Programas -->
                    <div class="filter-select fltrt">
                        <select id="cbLstProgramas" name="cbLstProgramas" class="inputbox">
                            <?php echo JHtml::_('select.options', $this->_programas, 'value', 'text', $this->state->get('filter.published'), true); ?>
                            <option value="0"> <?php echo JText::_('COM_PROYECTOS_FIELD_CANASTA_LSTPROGRAMAS_LABEL'); ?> </option>                            
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
</div>