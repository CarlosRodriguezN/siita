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
                <h2> <?php echo JText::_('COM_PANEL_UNIDAD_GESTION_TITLE') . ' : ' . JRequest::getVar('nombre'); ?> </h2>
            </div>
        </div>
    </div>

    <div class="clr"></div>

    <form id="adminForm" action="<?php echo 'http://local.siita.ecorae/index.php?option=com_panel' ?>" name="adminForm">
        <div id="submenu-box">
            <div class="m">
                <ul id="submenu">

                <!-- PEI -->
                <?php if( $this->canDo->get( 'core.admin' ) ): ?>
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=peis" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_PEI_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_PEI_TITLE' ) ?> </a>
                    </li>
                <?php endIf; ?>

                <!-- Unidad de Gestion -->
                <?php if( $this->canDo->get( 'core.admin' ) ): ?>
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=stp" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_STP_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_STP_TITLE' ) ?> </a>
                    </li>
                <?php endif; ?>                    

                <!-- Unidad de Gestion -->
                <?php if( $this->canDo->get( 'core.admin' ) ): ?>
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=unidadesgestion" title="<?php echo JText::_( 'COM_PANEL_CYM' ) . JText::_( 'COM_PANEL_UNIDAD_GESTION_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_UNIDAD_GESTION_TITLE' ) ?> </a>
                    </li>
                <?php endif; ?>
                
                <!-- Funcionarios -->
                <?php if( $this->canDo->get( 'core.admin' ) ): ?>
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=funcionarios" title="<?php echo JText::_( 'COM_PANEL_CYM' ) . JText::_( 'COM_PANEL_UNIDAD_GESTION_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_FUNCIONARIOS_TITLE' ) ?> </a>
                    </li>
                <?php endif; ?>

                    
                <!-- Programa -->
                <?php if( $this->canDo->get( 'core.admin' ) ): ?>
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=programas" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_PROGRAMA_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_PROGRAMA_TITLE' ) ?> </a>
                    </li>
                <?php endIf; ?>

                <!-- Proyecto -->
                <?php if( $this->canDo->get( 'core.admin' ) ): ?>
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=proyectos" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_PROYECTO_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_PROYECTO_TITLE' ) ?> </a>
                    </li>
                <?php endIf; ?>                    

                <!-- Contrato -->
                <?php if( $this->canDo->get( 'core.admin' ) ): ?>
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=contratos" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_CONTRATO_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_CONTRATO_TITLE' ) ?> </a>
                    </li>
                <?php endIf; ?>                    

                <!-- Convenio -->
                <?php if( $this->canDo->get( 'core.admin' ) ): ?>                    
                    <li>
                        <a class="active" href="index.php?option=com_panel&view=convenios" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_CONVENIO_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_CONVENIO_TITLE' ) ?> </a>
                    </li>
                <?php endIf; ?>                    

                <!-- apiRest -->
                <?php if( $this->canDo->get( 'core.admin' ) ): ?>                    
                    <li>
                        <a class="active" href="index.php?option=com_apirest" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_APIREST_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_APIREST' ) ?> </a>
                    </li>
                <?php endIf; ?>                    

                </ul>
                <div class="clr"></div>
            </div>
        </div>

        <div id="element-box">

            <div id="system-message-container"></div>

                <div class="m">
                    <div id="accordion">
                        
                        <div class="group">
                            <h3> Pestaña 001 </h3>
                        </div>
                        
                        <div class="group">
                            <h3> Pestaña 002 </h3>
                        </div>
                        
                    </div>
                </div>

            <div class="clr"> &nbsp; </div>

        </div>
    </form>

    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</div>

