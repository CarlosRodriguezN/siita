<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );

// load tooltip behavior
JHtml::_( 'behavior.tooltip' );
?>

<div id="content-box">

    <div id="toolbar-box">
        <div class="m">
            <?php echo $this->getToolbar();?>
            <div class="pagetitle icon-48-contact">
                <h2> <?php echo JText::_( 'COM_PANEL_CYM' ) . JText::_( 'COM_PANEL_STP_TITLE' )?> </h2>
            </div>
        </div>
    </div>

    <div class="clr"></div>

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

    <div id="element-box">

        <div id="system-message-container"></div>

        <div class="m">
            <div class="cpanel">
                <iframe src="<?php echo $this->_ticketTableu;?>?:embed=yes&:customViews=no&:tabs=no&:toolbar=no&:refresh" width="100%" height="1000px" frameborder='0'></iframe>
            </div>
        </div>

        <div class="clr"> &nbsp; </div>

    </div>
</div>

