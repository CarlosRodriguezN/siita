<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
//  JHTML::_('behavior.modal');
?>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">
            <h2> 
                <?php echo JText::_('COM_MANTENIMIENTO_ASIGNAR_CARGO'); ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <form action="" method="post" name="adminForm" id="frmCargoUG">
        <!-- Registro de los datos generales de un nuevo plan -->
        <div class="width-100">
            <?php echo $this->loadTemplate('dtageneral'); ?>
        </div>

        <div>
            <input type="hidden" name="task" value="cargoug.edit" />
            <input type="hidden" id="idUG" name="idUG" value="<?php echo $this->idUG ?>" />
            <input type="hidden" id="idGrupo" name="idGrupo" value="<?php echo $this->idGrpCargo; ?>" />
            <input type="hidden" id="indexGrp" name="indexGrp" value="<?php echo $this->idRegCargo; ?>" />
            <input type="hidden" id="infoUG" name="infoUG" value="<?php echo base64_encode(json_encode($this->infoUG)); ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>

    </form>
</div>