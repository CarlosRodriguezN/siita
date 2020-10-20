<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );

// load tooltip behavior
JHtml::_( 'behavior.tooltip' );
?>

<div id="content-box">

    <div id="toolbar-box">
        <div class="m">
            <div class="pagetitle icon-48-contact">
                <h2> <?php echo JText::_( 'COM_PANEL_PROYECTO_TITLE' ) ?> </h2>
            </div>
        </div>
    </div>

    <div class="clr"></div>

    <div id="submenu-box">
        <div class="m">
            <ul id="submenu">
                <!-- PEI -->
                <li>
                    <a class="active" href="index.php?option=com_panel&view=peis" title="<?php echo JText::_( 'COM_PANEL_PEI_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_PEI_TITLE' ) ?> </a>
                </li>

                <!-- Unidad de Gestion -->
                <li>
                    <a class="active" href="index.php?option=com_panel&view=stp" title="<?php echo JText::_( 'COM_PANEL_STP_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_STP_TITLE' ) ?> </a>
                </li>

                <!-- Unidad de Gestion -->
                <li>
                    <a class="active" href="index.php?option=com_panel&view=unidadesgestion" title="<?php echo JText::_( 'COM_PANEL_UNIDAD_GESTION_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_UNIDAD_GESTION_TITLE' ) ?> </a>
                </li>

                <!-- Programa -->
                <li>
                    <a class="active" href="index.php?option=com_panel&view=programas" title="<?php echo JText::_( 'COM_PANEL_PROGRAMA_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_PROGRAMA_TITLE' ) ?> </a>
                </li>

                <!-- Proyecto -->
                <li>
                    <a class="active" href="index.php?option=com_panel&view=proyectos" title="<?php echo JText::_( 'COM_PANEL_PROYECTO_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_PROYECTO_TITLE' ) ?> </a>
                </li>

                <!-- Contrato -->
                <li>
                    <a class="active" href="index.php?option=com_panel&view=contratos" title="<?php echo JText::_( 'COM_PANEL_CONTRATO_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_CONTRATO_TITLE' ) ?> </a>
                </li>

                <!-- Convenio -->
                <li>
                    <a class="active" href="index.php?option=com_panel&view=convenios" title="<?php echo JText::_( 'COM_PANEL_CONVENIO_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_CONVENIO_TITLE' ) ?> </a>
                </li>
                
                <!-- apiRest -->
                <li>
                    <a class="active" href="index.php?option=com_apirest" title="<?php echo JText::_( 'COM_PANEL_CYM' ) .  JText::_( 'COM_PANEL_APIREST_DESC' ); ?>"> <?php echo JText::_( 'COM_PANEL_APIREST' ) ?> </a>
                </li>
                
            </ul>
            <div class="clr"></div>
        </div>
    </div>

    <div id="element-box">

        <div id="system-message-container"></div>

        <?php foreach( $this->items as $item ): ?>
            <div class="m">
                <h2> <?php echo $item["nombre"]; ?> </h2>

                <div class="adminform">
                    <div class="cpanel-page">
                        <div class="cpanel">

                            <script type='text/javascript' src='http://201.218.5.248/javascripts/api/viz_v1.js'></script>
                            <div class='tableauPlaceholder' style='width: 1004px; height: 836px;'>
                                <object class='tableauViz' width='1004' height='836' style='display:none;'>
                                    <param name='host_url' value='http%3A%2F%2F201.218.5.248%2F' /> 
                                    <param name='site_root' value='&#47;t&#47;st' />
                                    <param name='name' value='AnlisisdeProyectos&#47;ANALISISDEPROYECTOS' />
                                    <param name='tabs' value='no' /><param name='toolbar' value='yes' />
                                </object>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="clr"> &nbsp; </div>

    </div>
</div>

