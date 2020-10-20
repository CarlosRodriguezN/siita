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
                    <?php echo JText::_( 'COM_FUNCIONARIOS_PLAN_ACCION_CREATING' ); ?>
                <?php else: ?>
                    <?php echo JText::_( 'COM_FUNCIONARIOS_PLAN_ACCION_EDITING' ); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <form action="<?php echo JRoute::_( 'index.php?option=com_funcionarios&layout=edit&intId_plnAccion=' . (int) $this->item->intId_plnAccion ); ?>" method="post" name="adminForm" >
        <div class="width-100">
            <!-- Registro de los datos generales de un nuevo plan -->
            <?php echo $this->loadTemplate( 'dtageneral' ); ?>
        </div>
        <div>
            <input type="hidden" name="task" value="plnaccion.edit" />
            <input type="hidden" id="idRegObjetivo" value="<?php echo $this->idRegObjetivo; ?>" />
            <input type="hidden" id="idRegPoa" value="<?php echo $this->idRegPoa; ?>" />
            <input type="hidden" id="idUG" value="<?php echo $this->idUG; ?>" />
            <input type="hidden" id="idFnc" value="<?php echo $this->idFnc; ?>" />
            <?php echo JHtml::_( 'form.token' ); ?>
        </div>
    </form>
</div>
