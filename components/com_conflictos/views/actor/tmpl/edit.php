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

            <h2> <?php if( $this->item->intId_act > 0 ): ?>
                    <?php echo JText::_( 'COM_CONFLICTOS_ACTOR_EDITING' ); ?>
                <?php else: ?>
                    <?php echo JText::_( 'COM_CONFLICTOS_ACTOR_CREATING' ); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>
<div id="element-box">
    <div class="m">

        <form action="<?php echo JRoute::_( 'index.php?option=com_conflictos&layout=edit&intId_act=' . (int) $this->item->intId_act ); ?>" method="post" id="actores-form" >

            <div id="temaTab" class="width-100 fltlft">
                <ul>
                    <li><a href="#general"><?php echo JText::_( 'COM_CONFLICTOS_TAB_GENERAL' ); ?></a></li>
                    <li><a href="#incidencia"><?php echo JText::_( 'COM_CONFLICTOS_TAB_INCIDENCIAL' ); ?></a></li>
                    <li><a href="#legitimidad"><?php echo JText::_( 'COM_CONFLICTOS_TAB_LEGITIMIDAD' ); ?></a></li>
                    <li><a href="#funcion"><?php echo JText::_( 'COM_CONFLICTOS_TAB_FUNCION' ); ?></a></li>
                </ul>
                <!--INFORMACION GENERAL-->
                <div id="general">
                    <?php echo $this->loadTemplate( 'general' ); ?>
                </div>
                <!-- INCIDENCIA -->
                <div id="incidencia">
                    <?php echo $this->loadTemplate( 'incidencia' ); ?>
                </div>
                <!-- LEGITIMIDAD -->
                <div id="legitimidad">
                    <?php echo $this->loadTemplate( 'legitimidad' ); ?>
                </div>
                <!-- LEGITIMIDAD -->
                <div id="funcion">
                    <?php echo $this->loadTemplate( 'funcion' ); ?>
                </div>
            </div>
        </form>

    </div>
</div>

<?php echo $this->loadTemplate('progressblock'); ?>
