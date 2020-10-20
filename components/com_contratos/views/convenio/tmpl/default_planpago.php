<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<div class="width-50 fltrt">
    <div id="imgPlanPagosForm"  class="editbackground"></div>
    <div id="editPlanPagosForm" class="hide" >
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_PLANPAGO' ); ?>&nbsp;</legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'planPago' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr">
            </div>
            <input id="addPlanPagos" type="button" value="<?php echo JText::_( 'BTN_GUARDAR' ); ?>">
            <input id="cancelPlanPagos" type="button" value="<?php echo JText::_( 'BTN_CANCELAR' ); ?>">
        </fieldset>
    </div>
</div>
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_LTSPLANPAGOS'); ?>&nbsp;</legend>
        <div class="fltrt">
            <input id="addPlanPagosTable" type="button" value="<?php echo JText::_('BTN_AGREGAR'); ?>">
        </div>
        <table id="tblstPlanPagos" class="tablesorter" cellspacing="1" >
            <thead>
                <tr>
                    <th align="center" width="10%">
                        <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_PLANPAGO_CODIGO'); ?>
                    </th>
                    <th align="center" >
                        <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_PLANPAGO_PRODUCTO'); ?>
                    </th>
                    <th align="center">
                        <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_PLANPAGO_MONTO'); ?>
                    </th>
                    <th align="center" >
                        <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_PLANPAGO_FECHA'); ?>
                    </th>
                    <th align="center" width="10%">
                        <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_PLANPAGO_PORCIENTO'); ?>
                    </th>
                    <th  colspan="2" align="center" width="10%">
                        <?php echo JText::_('ANY_ACCION_TABLA'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($this->planesPagos): ?>
                    <?php foreach ($this->planesPagos AS $key => $planPago): ?>
                        <tr id="<?php echo $key + 1 ?>">
                            <td><?php echo $planPago->codPlanPago ?></td>
                            <td><?php echo $planPago->producto ?></td>
                            <td><?php echo "$ " . number_format($planPago->monto, 2, ',', '.') ?></td>
                            <td><?php echo $planPago->fecha ?></td>
                            <td><?php echo $planPago->porcentaje . " %" ?></td>
                            <td style="width: 15px">
                                <a class="editPlanPago">
                                    <?php echo JText::_('LB_EDITAR'); ?>
                                </a>
                            </td>
                            <td  style="width: 15px">
                                <a class="delPlanPago">
                                    <?php echo JText::_('LB_ELIMINAR'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </fieldset>
</div>