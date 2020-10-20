<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

JHtml::_('behavior.formvalidation');
?>

<div id="content-box">

    <div id="toolbar-box">
        <div class="m">
            <?php echo $this->getToolbar(); ?>
            <div class="pagetitle icon-48-contact">
                <h2> <?php echo JText::_('COM_APIREST_ADMINISTRACION_PLAN') ?> </h2>
            </div>
        </div>
    </div>

    <div id="submenu-box">
        
        
        <div class="m">
            <div id="element-box">                    
                <div class="m">
                    <label id="jform_strIPInstitucion_api-lbl" for="jform_strIPInstitucion_api" class="hasTip" title="<?php echo jText::_( 'COM_APIREST_FIELD_URL_DESC' ); ?>" aria-invalid="false" style="min-width: 119px;"> <?php echo JText::_( 'COM_APIREST_FIELD_URL_LABEL' ); ?> </label>
                    <textarea name="jform[strIPInstitucion_api]" id="jform_strIPInstitucion_api" cols="2" rows="2" class="" aria-invalid="false" style="width: 90%; font-size: 15pt !important;"> <?php echo JURI::root(). 'index.php?option=com_apirest&task=urls.indicadores&token='; ?> </textarea>
                </div>                
            </div>
            
            <form action="" method="post" name="adminForm" id="adminForm" class="form-validate">
                <div id="element-box">
                    <div class="m">
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
                            <tbody> <?php echo $this->loadTemplate('body'); ?> </tbody>
                            <tfoot> <?php echo $this->loadTemplate('foot'); ?> </tfoot>
                        </table>

                        <div>
                            <input type="hidden" name="task" value="" />
                            <input type="hidden" name="boxchecked" value="0" />
                            <input type="hidden" id="lstPlanes" name="lstPlanes" value="<?php echo htmlspecialchars(json_encode($this->_lstPlanes)); ?>" />

                            <?php echo JHtml::_('form.token'); ?>
                        </div>



                        </form>
                    </div>
                </div>
        </div>

        <?php echo $this->loadTemplate('progressblock'); ?>