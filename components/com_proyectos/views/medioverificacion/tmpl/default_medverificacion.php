<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>


<!-- Tabla medio de verificacion -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend>&nbsp;&nbsp;<?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_LSTMEDVERIFICACION_TITLE') ?>&nbsp;&nbsp;</legend>

        <?php if( $this->canDo->get( 'core.create' ) ): ?>
            <div class="fltrt">
            <input id="btnAddTableMedVerificacion" type="button" value="<?php echo JText::_('BTN_AGREGAR') ?>"></div>
            <div class="clr"></div>
        <?php endIf; ?>

        <table id="lstMedVertificacion" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_MLFIN_LABEL') ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_INDICADOR_MLOPERACION_FUENTE') ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>


<div class="width-50 fltrt">
    <div id="imgMedioVerificacion" class="editbackground">
    </div>
    <div id="frmMedioVerificacion" class="hide">
        <fieldset class="adminform">
            <legend>&nbsp;&nbsp;<?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_LSTMEDVERIFICACION_DATA') ?>&nbsp;&nbsp;</legend>
            <ul class="adminformlist">
                <!-- textArea de tipo Fin -->
                <?php $txtMedVerificacion = $this->form->getField('txtMedVerificacion'); ?>
                <li>
                    <?php echo $txtMedVerificacion->label; ?>
                    <?php echo $txtMedVerificacion->input; ?>
                </li>

                <div class="clr"></div>
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                    <input id="btnAddMedVerificacion" type="button" value="<?php echo JText::_('BTN_GUARDAR') ?>">
                <?php endIf; ?>

                <input id="btnCLnMedVerificacion" type="button" value="<?php echo JText::_('BTN_CANCELAR') ?>">
                <div class="clr"></div>
            </ul>
        </fieldset>
    </div>
</div>
<div class="clr"></div>
