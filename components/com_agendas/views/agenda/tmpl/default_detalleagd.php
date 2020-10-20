<?php
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>
<!-- Tabla con la lista de detalles de la agenda -->
<div class="width-60 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_AGENDAS_FIELD_AGD_DETALLES_LABEL') ?> </legend>
        <div class="fltrt">
            <input id="addDetalleTable" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_AGREGAR' ) ?> &nbsp;">
        </div>
        <table id="tbLstDetalles" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"><?php echo JText::_( 'COM_AGENDAS_FIELD_AGD_DETALLE_CAMPO_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_AGENDAS_FIELD_AGD_DETALLE_VALOR_LABEL' ) ?></th>
                    <th colspan="2" align="center"><?php echo JText::_( 'COM_AGENDAS_ACCIONES' ) ?></th>
                </tr>
            </thead>

            <tbody>
                
            </tbody>
        </table>
        <div id="srDtll" align="center" class="hide"> <p> <?php echo JText::_( 'COM_AGENDAS_SIN_REGISTROS' ); ?> </p> </div>
    </fieldset>
</div>

<!-- fomulario para registrar un nuevo objetivo del PEI -->
<div class="width-40 fltrt" >
    <div id="imgDetalleAgd" class="editbackground" >

    </div>
    <div id="frmDetalleAgd" class ="hide" >
        <fieldset class="adminform">
            <legend> <?php echo JText::_( 'COM_AGENDAS_DATA_GEN' ) ?> </legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'detalleagd' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr"></div>
            <input id="btnSaveDtll" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_GUARDAR' ) ?> &nbsp;">
            <input id="btnCancelDtll" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_CANCELAR' ) ?> &nbsp;">
            <div class="clr"></div>
        </fieldset>
    </div>
</div>

