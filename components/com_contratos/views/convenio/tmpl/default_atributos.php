<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!--lISTA DE ATRIBUTOS-->
<div class="width-50 fltrt">
    <div id="imagenEcuAmaVidaAtributos" class="editbackground"></div>
    <div id="editAtributosForm" class="hide">
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_ATRIBUTOS'); ?>&nbsp;</legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('atributo') as $field): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr">
            </div>
            <input id="addAritbuto" type="button" value="<?php echo JText::_('BTN_GUARDAR'); ?>">
            <input id="cancelarAtributo" type="button" value="<?php echo JText::_('BTN_CANCELAR'); ?>">
        </fieldset>
    </div>
</div>
<div class="width-50">
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_ATRIBUTOS'); ?>&nbsp;</legend>
        <div class="fltrt">
            <input id="addAritbutoTable" type="button" value="<?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_ATRIBUTOS_ADD_ATT'); ?>">
        </div>
        <ul>
            <li>
                <table  id="tbLtsAtribsContrato" class="tablesorter" cellspacing="1" >
                    <thead>
                        <tr>
                            <th align="center" width="15%">
                                <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_ATRIBUTOS_CODIGO'); ?>
                            </th>
                            <th align="center" width="15%">
                                <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_ATRIBUTOS_NOMBRE'); ?>
                            </th>
                            <th align="center">
                                <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_ATRIBUTOS_VALOR'); ?>
                            </th>
                            <th  colspan="2" align="center" width="15">
                                <?php echo JText::_('ANY_ACCION_TABLA'); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($this->atributos): ?>
                            <?php foreach ($this->atributos AS $atributo): ?>
                                <tr id="<?php echo $atributo->idAtributo ?>">
                                    <td style="width:6%">
                                        <?php echo $atributo->codAtributo ?>
                                    </td>
                                    <td>
                                        <?php echo $atributo->nombre ?>
                                    </td>
                                    <td>
                                        <?php echo "$ " . number_format($atributo->valor, 2, '.', '') ?>
                                    </td>
                                    <td style="width: 5%">
                                        <a class="editAttributo">
                                            <?php echo JText::_('LB_EDITAR'); ?>
                                        </a>
                                    </td>
                                    <td  style="width: 5%">
                                        <a class="delAttributo">
                                            <?php echo JText::_('LB_ELIMINAR'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </li>
        </ul>
    </fieldset>
</div>