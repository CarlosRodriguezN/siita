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
            
            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                <input id="addAritbuto" type="button" value="<?php echo JText::_('BTN_GUARDAR'); ?>">
            <?php endIf; ?>            
            <input id="cancelarAtributo" type="button" value="<?php echo JText::_('BTN_CANCELAR'); ?>">
        </fieldset>
    </div>
</div>
<div class="width-50">
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_ATRIBUTOS'); ?>&nbsp;</legend>
        
        <?php if( $this->canDo->get( 'core.create' ) ): ?>
            <div class="fltrt">
                <input id="addAritbutoTable" type="button" value="&nbsp;<?php echo JText::_('BTN_AGREGAR'); ?>&nbsp;">
            </div>
        <?php endIf; ?>
        
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
                                <?php echo JText::_('TL_ACCIONES'); ?>
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
                                        
                                        <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                            <a class="editAttributo">
                                                <?php echo JText::_('LB_EDITAR'); ?>
                                            </a>
                                        <?php else: ?>
                                            <?php echo JText::_('LB_EDITAR'); ?>
                                        <?php endIf; ?>

                                    </td>
                                    <td  style="width: 5%">
                                        
                                        <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                            <a class="delAttributo">
                                                <?php echo JText::_('LB_ELIMINAR'); ?>
                                            </a>
                                        <?php else: ?>
                                            <?php echo JText::_('LB_ELIMINAR'); ?>
                                        <?php endIf; ?>
                                        
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