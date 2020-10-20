<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_( 'behavior.tooltip' );
JHtml::_( 'behavior.formvalidation' );
?>
<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">

            <h2> <?php if( $this->item->intId_tma > 0 ): ?>
                    <?php echo JText::_( 'COM_CONFLICTOS_TEMA_EDITING' ); ?>
                <?php else: ?>
                    <?php echo JText::_( 'COM_CONFLICTOS_TEMA_CREATING' ); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<?php if ($this->admin): ?>
    <div id="submenu-box" >
        <div class="m">
            <ul id="submenu">
                <li><a href="index.php?option=com_conflictos&view=actores" ><?php echo JText::_( 'COM_CONFLICTOS_SUBMENU_ACTORES' ); ?></a></li>
                <li><a href="index.php?option=com_conflictos&view=fuentes" ><?php echo JText::_( 'COM_CONFLICTOS_SUBMENU_FUENTES' ); ?></a></li>
            </ul>
            <div class="clr"></div>
        </div>
    </div>
<?php endif; ?>

<div id="element-box">
    <div class="m">

        <form action="<?php echo JRoute::_( 'index.php?option=com_conflictos&layout=edit&intId_tma=' . (int) $this->item->intId_tma ); ?>" method="post" name="adminForm" id="conflictos-form" >

            <div id="temaTab" class="width-100 fltlft">
                <ul>
                    <li><a href="#general"><?php echo JText::_( 'COM_CONFLICTOS_TAB_GENERAL' ); ?></a></li>
                    <li><a href="#fuentes"><?php echo JText::_( 'COM_CONFLICTOS_TAB_FUENTES' ); ?></a></li>
                    <li><a href="#actores"><?php echo JText::_( 'COM_CONFLICTOS_TAB_ACTORES' ); ?></a></li>
                    <li><a href="#estados"><?php echo JText::_( 'COM_CONFLICTOS_TAB_ESTADOS' ); ?></a></li>
                    <li><a href="#undterr"><?php echo JText::_( 'COM_CONFLICTOS_TAB_UNDTERR' ); ?></a></li>
                </ul>
                <!--INFORMACION GENERAL-->
                <div id="general">
                    <?php echo $this->loadTemplate( 'general' ); ?>
                </div>
                <!-- FUENTES DE INFORMACION -->
                <div id="fuentes">
                    <?php echo $this->loadTemplate( 'fuentes' ); ?>
                </div>
                <!-- ACTORES -->
                <div id="actores">
                    <?php echo $this->loadTemplate( 'actores' ); ?>
                </div>
                <!-- ESTADOS -->
                <div id="estados">
                    <?php echo $this->loadTemplate( 'estados' ); ?>
                </div>
                <!-- UNIDAD TERRITORIAL -->
                <div id="undterr">
                    <?php echo $this->loadTemplate( 'unidaterritorial' ); ?>
                </div>
            </div>
        </form>

    </div>
</div>

<?php echo $this->loadTemplate('progressblock'); ?>


