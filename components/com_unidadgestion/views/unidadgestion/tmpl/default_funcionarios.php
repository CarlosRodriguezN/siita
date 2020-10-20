<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>
<div class="width-60 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_FUNCIONARIOS_UG_LABEL' ) ?> </legend>
        <div class="fltrt">
            <a href= "index.php?option=com_actividad&view=actividadesbyug&layout=edit&idUG=<?php echo $this->item->intCodigo_ug ?>&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x:<?php echo JText::_( 'COM_UNIDAD_GESTION_POPUP_ANCHO' ) ?>, y:<?php echo JText::_( 'COM_UNIDAD_GESTION_POPUP_ALTO' ) ?>}}">
                <input id="verActividades" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_ACTIVIDADES' ) ?> &nbsp;">
            </a>
            
            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                <input id="addFuncionarioTable" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_AGREGAR' ) ?> &nbsp;">
            <?php endIf; ?>
        </div>

        <table id="tbLstFuncionarios" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"><?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_FUNCIONARIO_CARGO_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_FUNCIONARIO_NOMBRE_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_FUNCIONARIO_FECHA_INICIO_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_FUNCIONARIO_FECHA_FIN_LABEL' ) ?></th>
                    <th colspan="3" align="center"><?php echo JText::_( 'ACCIONES' ) ?></th>
                </tr>
            </thead>

            <tbody>

            </tbody>
        </table>
    </fieldset>
</div>

<!-- fomulario para registrar una nueva unidad de gestión -->
<div class="width-40 fltrt" >
    <div id="imgFuncionario" class="editbackground">

    </div>
    <div id="frmFuncionario" class ="hide" >
        <fieldset class="adminform">
            <legend> <?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_DATA_GENERAL_LABEL' ) ?> </legend>
                <ul class="adminformlist">
                    <?php foreach( $this->form->getFieldset( 'funcionario' ) as $field ): ?>
                    <?php $op = substr($field->id,-3, 3);?>
                        <?php  if ( $op == "aux"): ?>
                            <li id="li-<?php echo $field->id; ?>" style="display: none;">
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                            </li>
                        <?php else: ?>
                            <li id="li-<?php echo $field->id; ?>">
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <div class="clr"></div>
            
            <fieldset class="adminform" style="padding: 10px">
                <legend style="font-size: small; font-weight: normal;"> <?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_UG_OP_ADD_TITLE' ) ?> </legend>
                <table id="cheksFncOpsAdds" >
                    <thead>
                        <tr><th></th></tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </fieldset>
            
            <div class="clr"></div>
            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                <input id="btnAddFnci" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_GUARDAR' ) ?> &nbsp;">
            <?php endIf; ?>    
            
            <input id="btnCancel" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_CANCELAR' ) ?> &nbsp;">
            <div class="clr"></div>
        </fieldset>
    </div>
</div>

