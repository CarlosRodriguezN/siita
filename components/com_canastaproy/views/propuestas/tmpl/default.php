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
                <h2> <?php echo JText::_('COM_CANASTAPROY_ADMINISTRACION_PROPUESTA') ?> </h2>
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
            <form action="<?php echo JRoute::_('index.php?option=com_canastaproy&amp;view=propuestas'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
                <div id="tabsLstPropuesta" style="position: static; left: 20px; height: auto; width: 100%">
                    <ul>
                        <li><a href="#cpPropuestasAtender"> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTAS_ATENDER_CP_TITLE') ?></a></li>
                        <li><a href="#cpPropuestasAtendidas"> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTAS_ATENDIDAS_CP_TITLE') ?></a></li>
                    </ul>
                    <!--pestaña de propuestas de proyecto que no estan atendidas o relacionas con un proyecto-->
                    <div class="m" id="cpPropuestasAtender">
                        <?php echo $this->loadTemplate('lista'); ?>
                    </div>
                    <!--pestaña de propuestas de proyecto que ya estan atendidas o relacionas con un proyecto-->
                    <div class="m" id="cpPropuestasAtendidas">
                        ECORAE
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>