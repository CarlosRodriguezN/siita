<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<!-- Tabla Supuestos -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend>&nbsp;&nbsp;<?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_LSTSUPUESTOS_TITLE') ?>&nbsp;&nbsp;</legend>
        <table id="lstSupuestos" width="100%" class="tablesorter" cellspacing="1">

            <div class="fltrt">
                <?php if( $this->canDo->get( 'core.create' ) ): ?>
                    <input id="btnAddTableSupuesto" type="button" value="<?php echo JText::_('BTN_AGREGAR') ?>"></div>
                <?php endIf; ?>
            <div class="clr"></div>
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_MLFIN_LABEL') ?> </th>
                    <th align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_MLPROPOSITO_LABEL') ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_INDICADOR_MLOPERACION_FUENTE') ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>

<div class="width-50 fltrt">
    <div id="imgSupuesto" class="editbackground">
    </div>
    <div id="frmSupuesto" class="hide">
        <fieldset class="adminform">
            <legend>&nbsp;&nbsp;<?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_LSTSUPUESTOS_TITLE') ?>&nbsp;&nbsp;</legend>
            <ul class="adminform">
                <!-- textArea de tipo Fin -->
                <?php $txtSupuestos = $this->form->getField('txtSupuestos'); ?>
                <li>
                    <?php echo $txtSupuestos->label; ?>
                    <?php echo $txtSupuestos->input; ?>
                </li>

                <div class="clr"></div>
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                    <input id="btnAddSupuesto" type="button" value="<?php echo JText::_('BTN_GUARDAR') ?>">
                <?php endIf; ?>
                <input id="btnClnSupuesto" type="button" value="<?php echo JText::_('BTN_CANCELAR') ?>">
                <div class="clr"></div>
            </ul>
        </fieldset>
    </div>
</div>
<div class="clr"></div>