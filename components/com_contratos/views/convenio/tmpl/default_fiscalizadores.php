<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>
<div class="width-50 fltrt">
    <div id="imgFiscalizadorForm"  class="editbackground"></div>
    <div id="editFiscalizadorForm"  class="hide" >
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_FISCALIZADOR' ); ?>&nbsp;</legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'fiscalizador' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'personafiscalizador' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr">
            </div>
            <input id="addFiscalizador" type="button" value="<?php echo JText::_( 'BTN_GUARDAR' ); ?>">
            <input id="cancelFiscalizador" type="button" value="<?php echo JText::_( 'BTN_CANCELAR' ); ?>">
        </fieldset>
    </div>
</div>
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_LTSFISCALIZADORES' ); ?>&nbsp;</legend>
        <div class="fltrt">
            <input id="addFiscalizadorTable" type="button" value="<?php echo JText::_( 'BTN_AGREGAR' ); ?>">
        </div>
        <table id="tblstFiscalizadores" class="tablesorter" cellspacing="1" >
            <thead>
                <tr>
                    <th align="center" width="10%">
                        <?php echo JText::_( 'COM_CONTRATOS_TAB_FISCALIZADORE_CEDULA' ); ?>
                    </th>
                    <th align="center" >
                        <?php echo JText::_( 'COM_CONTRATOS_TAB_FISCALIZADORE_NOMBRESAPELLIDOS' ); ?>
                    </th>
                    <th align="center" width="10%">
                        <?php echo JText::_( 'COM_CONTRATOS_TAB_FISCALIZADORE_FECHAINICIO' ); ?>
                    </th>
                    <th align="center" width="10%">
                        <?php echo JText::_( 'COM_CONTRATOS_TAB_FISCALIZADORE_FECHAFIN' ); ?>
                    </th>
                    <th align="center" width="10%">
                        <?php echo JText::_( 'COM_CONTRATOS_TAB_FISCALIZADORE_CONTACTOS' ); ?>
                    </th>
                    <th  colspan="2" align="center" width="10%">
                        <?php echo JText::_( 'ANY_ACCION_TABLA' ); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if( $this->fiscalizadores ): ?>
                    <?php foreach( $this->fiscalizadores AS $fiscalizador ): ?>
                        <tr id="<?php echo $fiscalizador->idFiscaContrato ?>">
                            <td><?php echo $fiscalizador->cedula ?></td>
                            <td><?php echo $fiscalizador->apellidos . ' ' . $fiscalizador->nombres ?></td>
                            <td><?php echo $fiscalizador->fchIncio ?></td>
                            <td><?php echo $fiscalizador->fchFin ?></td>
                            <td><?php echo $fiscalizador->celular . '/' . $fiscalizador->telefono . '/' . $fiscalizador->correo ?></td>
                            <td style="width: 15px">
                                <a class="editFiscalizador">
                                    <?php echo JText::_( 'LB_EDITAR' ); ?>
                                </a>
                            </td>
                            <td  style="width: 15px">
                                <a class="delFiscalizador">
                                    <?php echo JText::_( 'LB_ELIMINAR' ); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </fieldset>
</div>