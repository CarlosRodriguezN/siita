<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>
<div class="width-50 fltrt">
    <div id="imgFiscalizadorForm"  class="editbackground"></div>
    <div id="editFiscalizadorForm"  class="hide" >
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_FISCALIZADOR' ); ?>&nbsp;</legend>
            <ul class="adminformlist" id="formFiltroFsc">
                <?php foreach( $this->form->getFieldset( 'fiscalizador' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <ul class="adminformlist" id="formDtaFsc">
                <?php foreach( $this->form->getFieldset( 'personafiscalizador' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr"></div>
            
            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                <input id="addFiscalizador" type="button" value="<?php echo JText::_( 'BTN_GUARDAR' ); ?>">
            <?php endIf; ?>
            <input id="cancelFiscalizador" type="button" value="<?php echo JText::_( 'BTN_CANCELAR' ); ?>">
        </fieldset>
    </div>
</div>
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_LTSFISCALIZADORES' ); ?>&nbsp;</legend>
        <?php if( $this->canDo->get( 'core.create' ) ): ?>
            <div class="fltrt">
                <input id="addFiscalizadorTable" type="button" value="&nbsp;<?php echo JText::_('BTN_AGREGAR_FSCA'); ?>&nbsp;">
            </div>
        <?php endIf; ?>
        <table id="tblstFiscalizadores" class="tablesorter" cellspacing="1" >
            <thead>
                <tr>
                    <th align="center" width="60px" >
                        <?php echo JText::_( 'COM_CONTRATOS_TAB_FISCALIZADORE_CEDULA' ); ?>
                    </th>
                    <th align="center" >
                        <?php echo JText::_( 'COM_CONTRATOS_TAB_FISCALIZADORE_NOMBRESAPELLIDOS' ); ?>
                    </th>
                    <th align="center" width="55px" >
                        <?php echo JText::_( 'COM_CONTRATOS_TAB_FISCALIZADORE_FECHAINICIO' ); ?>
                    </th>
                    <th align="center" width="55px" >
                        <?php echo JText::_( 'COM_CONTRATOS_TAB_FISCALIZADORE_FECHAFIN' ); ?>
                    </th>
                    <th align="center" width="20%">
                        <?php echo JText::_( 'COM_CONTRATOS_TAB_FISCALIZADORE_CONTACTOS' ); ?>
                    </th>
                    <th  colspan="2" align="center" >
                        <?php echo JText::_( 'TL_ACCIONES' ); ?>
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
                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                    <a class="editFiscalizador">
                                        <?php echo JText::_( 'LB_EDITAR' ); ?>
                                    </a>
                                <?php else: ?>
                                        <?php echo JText::_( 'LB_EDITAR' ); ?>
                                <?php endIf; ?>
                            </td>
                            <td  style="width: 15px">
                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                    <a class="delFiscalizador">
                                        <?php echo JText::_( 'LB_ELIMINAR' ); ?>
                                    </a>
                                <?php else: ?>
                                        <?php echo JText::_( 'LB_ELIMINAR' ); ?>
                                <?php endIf; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </fieldset>
</div>