<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Lista de Lineas Base Registradas -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_LSTLINEABASE' ) ?>&nbsp;</legend>
        <div class="fltrt">
            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                <input id="addLnBaseTable" type="button" value=" &nbsp;<?php echo JText::_( 'COM_INDICADORES_FIELD_ADD_LINEABASE_LABLE' ) ?> &nbsp;">
            <?php endif; ?>
        </div>
        <table id="lstLineasBase" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_LINEABASE' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_VALLINEABASE_LABLE' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_FUENTE_LABLE' ) ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_( 'COM_INDICADORES_OPCION' ) ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>

<!-- Formulario de registro de Lineas Base -->
<div class="width-50 fltrt">
    <div id="imgLnBase" class="editbackground"></div>
    <div id="frmLnBase" class="hide">
        <fieldset class="adminform">
            <legend> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_LINEABASE' ) ?> </legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'lineasBase' ) as $field ): ?>
                    <?php $op = substr( $field->id, -3, 3 ); ?>
                    <?php if( $op == "New" || $op == "Upd" ): ?>
                        <li id="li-<?php echo $field->id; ?>" style="display: none;">
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                            <span>
                                <a href="#" class="saveReg" >
                                    <img src="/media/system/images/mantenimiento/save.png" title="Guardar">
                                </a>
                            </span>
                            <span>
                                <a href="#" class="cancelReg">
                                    <img src="/media/system/images/mantenimiento/close.png" title="Cancelar">
                                </a>
                            </span>
                        </li>
                    <?php elseif( $field->id == "jform_valorLineaBase" ): ?>
                        <li id="li-<?php echo $field->id; ?>" >
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                            <span>
                                <a href="#" class="updReg" >
                                    <img src="/media/system/images/mantenimiento/edit.png" title="Editar">
                                </a>
                            </span>
                        </li>
                    <?php else: ?>
                        <li id="li-<?php echo $field->id; ?>">
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                            <span>
                                <a href="#" class="newReg" >
                                    <img src="/media/system/images/mantenimiento/new.png" title="Nuevo">
                                </a>
                            </span>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <div class="clr"></div>
            <div class="fltlft">
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                    <input id="btnAddLineaBase" type="button" value="&nbsp;<?php echo JText::_( 'BTN_GUARDAR' ) ?>&nbsp;">
                <?php endIf; ?>

                <input id="btnCnlLineaBase" type="button" value="&nbsp;<?php echo JText::_( 'BTN_CANCELAR' ) ?>&nbsp;">
            </div>
            <div class="clr"></div>
        </fieldset>

    </div>
</div>

<div class="clr"></div>