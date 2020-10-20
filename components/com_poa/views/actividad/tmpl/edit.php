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

            <h2> <?php if( $this->item->intId_plnAccion == null ): ?>
                    <?php echo JText::_( 'COM_POA_ACTIVIDAD_CREATING' ); ?>
                <?php else: ?>
                    <?php echo JText::_( 'COM_POA_ACTIVIDAD_EDITING' ); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>


<div id="element-box">
    <form action="" >
        <div class="adminform">
            <div class="adminlist">
                <?php echo $this->loadTemplate( 'actividades' ); ?>
            </div>
            <div>
                <?php echo JHtml::_( 'form.token' ); ?>
            </div>
        </div>
    </form>
</div>