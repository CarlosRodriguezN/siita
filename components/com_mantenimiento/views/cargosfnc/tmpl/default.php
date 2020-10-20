<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');

?>

<div id="content-box">
    
    <div id="toolbar-box">
        <div class="m">
            <?php echo $this->getToolbar(); ?>
            <div class="pagetitle icon-48-contact">
                <h2> <?php echo JText::_( 'COM_MANTENIMIENTO_ADMINISTRACION_CARGOS' ) ?> </h2>
            </div>
        </div>
    </div>
    
    <div id="element-box">
        <div class="m">
            <form action="<?php echo JRoute::_( 'index.php?option=com_mantenimiento&amp;view=cargosfnc' ); ?>" method="post" name="adminForm" id="adminForm">
    
                <fieldset id="filter-bar">
                    <!-- Filtro para una busqueda de un nombre o frase en particular -->
                    <div class="filter-search fltlft">
                        <label for="filter_search"> <?php echo JText::_( 'JSEARCH_FILTER_LABEL' ); ?> </label>
                        <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape( $this->state->get( 'filter.search' ) ); ?>" title="Buscar">

                        <button type="submit" class="btn"> <?php echo JText::_( 'JSEARCH_FILTER_SUBMIT' ); ?> </button>
                        <button type="button" onclick="document.id( 'filter_search' ).value='';this.form.submit();">
                            <?php echo JText::_( 'JSEARCH_FILTER_CLEAR' ); ?>
                        </button>
                    </div>

                    <!-- Filtro para registros "Publicados" y "No Publicados" -->
                    <div class="filter-select fltrt">
                        <select name="filter_published" class="inputbox" onchange="this.form.submit()">
                            <option value=""> <?php echo JText::_( 'JOPTION_SELECT_PUBLISHED' ); ?> </option>
                            <?php echo JHtml::_( 'select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get( 'filter.published' ), true ); ?>
                        </select>
                    </div>
                </fieldset>
    
                <div id="acordionUG">
                    <?php $numUG = ($this->lstCargosUg) ? count($this->lstCargosUg) : 0 ; ?>
                    <?php if ($numUG > 0): ?>
                        <?php foreach ($this->lstCargosUg as $ug): ?>
                        <div class="group">
                            <h3> <a href="#" id="aUG-<?php echo $ug->idReg; ?>"> <?php echo "[" . count($ug->lstCargosUG) . "]  " . $ug->nombreUG;?> </a> </h3>
                            <?php $this->idUgReg = $ug->idReg;?>
                            <div class="m">
                                <div style="padding-bottom: 5px;"> 
                                    <a href= "index.php?option=com_mantenimiento&view=cargoug&layout=edit&id=<?php echo $ug->idUG; ?>&grupoCrg=0&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x:1024, y:500}}">
                                        <input class="fltrt" type="button" value=" &nbsp;<?php echo JText::_('BTN_ASIGNAR_CARGO_UG') ?> &nbsp;">
                                    </a>
                                </div>
                                <br>
                                <table id="ug-<?php echo $this->idUgReg; ?>" class="adminlist">
                                    <thead> <?php echo $this->loadTemplate('head');?> </thead>
                                    <tbody> <?php // echo $this->loadTemplate('body');?> </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
    
                <div>
                    <input type="hidden" name="task" value="" />
                    <input type="hidden" name="boxchecked" value="0" />
                    <?php echo JHtml::_('form.token'); ?>
                </div>
            </form>
        </div>
    </div>
</div>
