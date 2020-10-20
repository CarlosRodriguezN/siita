<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!--lISTA DE FACTURAS-->


<div id="facturasTab" class="width-100 fltlft">
    <div class="width-100 fltleft">
        <ul>
            <li><a href="#facturasPendientes"><?php echo JText::_( 'COM_CONTRATOS_TAB_PENDIENTES' ); ?></a></li>
            <li><a href="#facturasAdelantos"><?php echo JText::_( 'COM_CONTRATOS_TAB_ADELANTOS' ); ?></a></li>
        </ul>
        <!--FACTURAS PENDIENTES -->
        <div id="facturasPendientes">
            <div class="width-50 fltrt">
                <div id="ieavFactura"  class="editbackground"></div>
                <div id="editFacturaForm" class="hide">
                    <fieldset class="adminform">
                        <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_FACTURA_PENDIENTE' ); ?>&nbsp;</legend>
                        <ul class="adminformlist">
                            <?php foreach( $this->form->getFieldset( 'facturaPendiente' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clr">
                        </div>
                        <input id="addPlanillaFacturaFactura" class="hide" type="button" value="&nbsp;<?php echo JText::_('BTN_AGREGAR'); ?>&nbsp;">
                        <div class="clr"></div>
                        
                        <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                            <input id="addFactura" type="button" value="&nbsp;<?php echo JText::_( 'BTN_GUARDAR' ); ?>&nbsp;">
                        <?php endIf; ?>
                            
                        <input id="cancelarFactura" type="button" value="&nbsp;<?php echo JText::_( 'BTN_CANCELAR' ); ?>&nbsp;">
                    </fieldset>
                </div>
                <div id="pagarFacturaForm" class="hide">
                    <fieldset class="adminform">
                        <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_FACTURA_PAGOPENDIENTE' ); ?>&nbsp;</legend>
                        <ul class="adminformlist">
                            <?php foreach( $this->form->getFieldset( 'pago' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clr"></div>
                        
                        <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                            <input id="addPagoFactura" type="button" value="&nbsp;<?php echo JText::_( 'BTN_GUARDAR' ); ?>&nbsp;">
                        <?php endIf; ?>

                        <input id="cancelarPagoFactura" type="button" value="&nbsp;<?php echo JText::_( 'BTN_CANCELAR' ); ?>&nbsp;">
                    </fieldset>
                </div>
                <div id="pagarPlanillaFacturaForm" class="hide" >
                    <fieldset class="adminform">
                        <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_FACTURA_PLANILLAFACTURA' ); ?>&nbsp;</legend>
                        <ul class="adminformlist">
                            <?php foreach( $this->form->getFieldset( 'planilla' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clr"></div>
                        
                        <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                            <input id="addPlanillaFactura" type="button" value="&nbsp;<?php echo JText::_( 'BTN_GUARDAR' ); ?>&nbsp;">
                        <?php endIf; ?>

                        <input id="cancelarPlanillaFactura" type="button" value="&nbsp;<?php echo JText::_( 'BTN_CANCELAR' ); ?>&nbsp;">
                    </fieldset>
                </div>
            </div>

            <div class="width-50">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_FACTURAS' ); ?>&nbsp;</legend>
                    <div class="fltrt">
                        <?php if( $this->canDo->get( 'core.create' ) ): ?>
                            <input id="addFacturaTable" type="button" value="&nbsp;<?php echo JText::_( 'BTN_AGREGAR_FACTURA' ); ?>&nbsp;">
                        <?php endIf; ?>
                    </div>

                    <ul>
                        <li>
                            <table id="tbLtsFacturasContrato" class="tablesorter" cellspacing="1" >
                                <thead>
                                    <tr>
                                        <th align="center">
                                            <?php echo JText::_( 'COM_CONTRATOS_TAB_FACTURA_TB_NUMERO' ); ?>
                                        </th>
                                        <th align="center">
                                            <?php echo JText::_( 'COM_CONTRATOS_TAB_FACTURA_TB_CODIGO' ); ?>
                                        </th>
                                        <th align="center">
                                            <?php echo JText::_( 'COM_CONTRATOS_TAB_FACTURA_TB_MONTO' ); ?>
                                        </th>
                                        <th align="center">
                                            <?php echo JText::_( 'COM_CONTRATOS_TAB_FACTURA_TB_FECHA' ); ?>
                                        </th>
                                        <th  colspan="3" align="center" width="15">
                                            <?php echo JText::_( 'TL_ACCIONES' ); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </li>
                    </ul>
                </fieldset>
            </div>
        </div>



        <!--FACTURAS ADELANTO -->
        <div id="facturasAdelantos">
            <div class="width-50 fltrt">
                <div id="ieavAdelantoFactura"  class="editbackground"></div>
                <div id="editPlanPagoForm" class="hide">
                    <fieldset class="adminform">
                        <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PAGO_FACTURA' ); ?>&nbsp;</legend>
                        <ul class="adminformlist">
                            <?php foreach( $this->form->getFieldset( 'adelantoFactura' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clr"></div>
                        <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                            <input id="addFacturaAdelanto" type="button" value="<?php echo JText::_( 'BTN_GUARDAR' ); ?>">
                        <?php endIf; ?>

                        <input id="cancelFacturaAdelanto" type="button" value="<?php echo JText::_( 'BTN_CANCELAR' ); ?>">
                    </fieldset>
                </div>
            </div>
            <div class="width-50">
                <div id="editPlanPagoForm">
                    <fieldset class="adminform">
                        <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_ADELANTO' ); ?>&nbsp;</legend>
                        <ul class="adminformlist">
                            <?php foreach( $this->form->getFieldset( 'adelanto' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clr"></div>
                        
                        <?php if( $this->canDo->get( 'core.create' ) ): ?>
                            <input id="addFacturaAdelantoContrato" type="button" value="<?php echo JText::_( 'BTN_ADDFACTURA' ); ?>">
                        <?php endIf; ?>

                        <div class="clr">
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>