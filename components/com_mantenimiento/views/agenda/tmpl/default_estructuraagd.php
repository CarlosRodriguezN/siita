<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-60 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_MANTENIMIENTO_FIELD_AGD_ESTRUCTURA_LABEL') ?> </legend>
        <div class="fltrt">
            <?php if ( $this->avalibleUpdEst == true ):?>
                <input id="addEstructuraTable" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_AGREGAR' ) ?> &nbsp;">
            <?php endif?>
        </div>
        <table id="tbEstructura" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"><?php echo JText::_( 'COM_MANTENIMIENTO_FIELD_AGD_ESTRUCTURA_DESCRIPCION_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_MANTENIMIENTO_FIELD_AGD_ESTRUCTURA_PADRE_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_MANTENIMIENTO_FIELD_AGD_ESTRUCTURA_NIVEL_LABEL' ) ?></th>
                    <th colspan="2" align="center"><?php echo JText::_( 'COM_MANTENIMIENTO_ACCIONES' ) ?></th>
                </tr>
            </thead>

            <tbody>
                
            </tbody>
        </table>
        <div id="srEtr" align="center" class="hide"> <p> <?php echo JText::_( 'COM_MANTENIMIENTO_SIN_REGISTROS' ); ?> </p> </div>
    </fieldset>
</div>

<div class="width-40 fltrt">
    <div id="imgEstructuraAgd" class="editbackground" >

    </div>
    <div id="frmEstructuraAgd" class ="hide" >
        <fieldset class="adminform">
            <legend> <?php echo JText::_( 'COM_MANTENIMIENTO_DATA_GEN' ) ?> </legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'estructuraagd' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr"></div>
            <input id="btnSaveEtr" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_GUARDAR' ) ?> &nbsp;">
            <input id="btnCancelEtr" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_CANCELAR' ) ?> &nbsp;">
            <div class="clr"></div>
        </fieldset>
    </div>
</div>