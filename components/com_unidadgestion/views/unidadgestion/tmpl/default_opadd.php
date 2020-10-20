<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div class="width-60 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_OPS_ADDS_LABEL' ) ?> </legend>

        <?php if( $this->canDo->get( 'core.create' ) ): ?>
            <div class="fltrt">
                <input id="newOpAdd" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_AGREGAR' ) ?> &nbsp;">
            </div>
        <?php endIf; ?>

        <table id="tbLstOpsAdds" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"><?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_OP_ADD_NOMBRE_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_OP_ADD_DESCRIPCION_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_OP_ADD_URL_LABEL' ) ?></th>
                    <th colspan="2" align="center"><?php echo JText::_( 'ACCIONES' ) ?></th>
                </tr>
            </thead>

            <tbody>
                
            </tbody>
        </table>
    </fieldset>
</div>

<!-- fomulario para registrar una nueva unidad de gestión -->
<div class="width-40 fltrt" >
    <div id="imgOpAdd" class="editbackground">

    </div>
    
    <?php if( $this->item->intCodigo_ug != 0 ): ?>
        <div id="frmOpAdd" class ="hide" >
            <fieldset class="adminform">
                <legend> <?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_DATA_GENERAL_LABEL' ) ?> </legend>
                <ul class="adminformlist">
                    <?php foreach( $this->form->getFieldset( 'opAdicionales' ) as $field ): ?>
                        <li id="li-<?php echo $field->id; ?>">
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="clr"></div>
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                    <input id="btnAddOpAdd" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_GUARDAR' ) ?> &nbsp;">
                <?php endIf; ?>

                <input id="btnCancelOpAdd" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_CANCELAR' ) ?> &nbsp;">
                <div class="clr"></div>
            </fieldset>
        </div>
    <?php else: ?>
        <div id="frmOpAdd" class ="hide" >
            <h5>
                <?php echo JText::_( 'SMS_SAVE_UG' ) ?>
            </h5>
            <input id="btnCancelOpAdd" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_CANCELAR' ) ?> &nbsp;">
        </div>
    <?php endif;?>
    
</div>