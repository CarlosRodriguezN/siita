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
                <h2> <?php echo JText::_('COM_FUNCIONARIOS_ADMINISTRACION_FUNCIONARIOS') ?> </h2>
            </div>
        </div>
    </div>
    
    <div id="submenu-box">
        <div class="m">
            <ul id="submenu">

                <!-- PEI -->
                <?php if( JFactory::getUser()->authorise( 'core.admin', 'com_pei' ) ): ?>
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=peis" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_PEI_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_PEI_TITLE' ) ?> </a>
                    </li>
                <?php endIf; ?>

                <!-- Unidad de Gestion -->
                <?php if( JFactory::getUser()->authorise( 'core.admin', 'com_unidadgestion' ) ): ?>
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=stp" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_STP_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_STP_TITLE' ) ?> </a>
                    </li>
                <?php endif; ?>                    

                <!-- Unidad de Gestion -->
                <?php if( JFactory::getUser()->authorise( 'core.admin', 'com_unidadgestion' ) ): ?>
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=unidadesgestion" title="<?php echo JText::_( 'COM_PANEL_CYM' ) . JText::_( 'COM_PANEL_UNIDAD_GESTION_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_UNIDAD_GESTION_TITLE' ) ?> </a>
                    </li>
                <?php endif; ?>
                
                <!-- Funcionarios -->
                <?php if( JFactory::getUser()->authorise( 'core.admin', 'com_funcionarios' ) ): ?>
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=funcionarios" title="<?php echo JText::_( 'COM_PANEL_CYM' ) . JText::_( 'COM_PANEL_UNIDAD_GESTION_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_FUNCIONARIOS_TITLE' ) ?> </a>
                    </li>
                <?php endif; ?>

                    
                <!-- Programa -->
                <?php if( JFactory::getUser()->authorise( 'core.admin', 'com_programa' ) ): ?>
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=programas" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_PROGRAMA_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_PROGRAMA_TITLE' ) ?> </a>
                    </li>
                <?php endIf; ?>

                <!-- Proyecto -->
                <?php if( JFactory::getUser()->authorise( 'core.admin', 'com_proyectos' ) ): ?>
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=proyectos" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_PROYECTO_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_PROYECTO_TITLE' ) ?> </a>
                    </li>
                <?php endIf; ?>                    

                <!-- Contrato -->
                <?php if( JFactory::getUser()->authorise( 'core.admin', 'com_contratos' ) ): ?>
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=contratos" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_CONTRATO_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_CONTRATO_TITLE' ) ?> </a>
                    </li>
                <?php endIf; ?>                    

                <!-- Convenio -->
                <?php if( JFactory::getUser()->authorise( 'core.admin', 'com_contratos' ) ): ?>                    
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=convenios" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_CONVENIO_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_CONVENIO_TITLE' ) ?> </a>
                    </li>
                <?php endIf; ?>                    

                <!-- apiRest -->
                <?php if( JFactory::getUser()->authorise( 'core.admin', 'com_apirest' ) ): ?>
                    <li>
                        <a class="active" href="index.php?option=com_apirest" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_APIREST_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_APIREST' ) ?> </a>
                    </li>
                <?php endIf; ?>

            </ul>
            <div class="clr"></div>
        </div>
    </div>

    <!-- MANTENIMIENTO -->
    <?php if( JFactory::getUser()->authorise( 'core.admin', 'com_mantenimiento' ) ): ?>
        <div id="submenu-box">
            <div class="m">
                <ul id="submenu">
                    <li>
                        <a class="active" href="index.php?option=com_mantenimiento" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_MANTENIMIENTO_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_MANTENIMIENTO_TITLE' ) ?> </a>
                    </li>
                </ul>
                <div class="clr"></div>
            </div>
        </div>
    <?php endIf; ?>
    
    <div id="element-box">
        <div class="m">
            <form action="<?php echo JRoute::_('index.php?option=com_funcionarios&amp;view=funcionarios'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
                
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

                </fieldset>
                
                <div id="acordionUG">
                    <?php $numUG = ($this->lstFncUg) ? count($this->lstFncUg) : 0 ; ?>
                    <?php if ($numUG > 0): ?>
                        <?php foreach ($this->lstFncUg as $ug): ?>
                        <div class="group">
                            <h3> <a href="#"> <?php echo $ug->nombreUG . '  (' . count($ug->lstaFncUG) . ')'; ?> </a> </h3>
                            <?php $this->idUg = $ug->idUG;?>
                            <div class="m">
                                <table class="adminlist">
                                    <thead> <?php echo $this->loadTemplate('head');?> </thead>
                                    <tbody> <?php echo $this->loadTemplate('body');?> </tbody>
                                    <tfoot> <?php echo $this->loadTemplate('foot');?> </tfoot>
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