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
            <h2> <?php echo JText::_( 'COM_FUNCIONARIOS_PASSWORD_UPD' ); ?> </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <form action="<?php echo JRoute::_( 'index.php?option=com_funcionarios&layout=edit&id=' . (int) $this->item->id ); ?>" method="post" id="passwordForm" >
        <div class="width-100">
            <!-- Registro de los datos generales de un nuevo plan -->
            <?php echo $this->loadTemplate( 'dtageneral' ); ?>
        </div>
        <div>
            <input type="hidden" name="task" value="password.edit" />
            <input type="hidden" id="idUsrFnc" value="<?php echo $this->idUsrFnc; ?>" />
            <?php echo JHtml::_( 'form.token' ); ?>
        </div>
    </form>
</div>

<?php echo $this->loadTemplate('progressblock'); ?>