<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<div class="width-50 fltrt">
    <div id="imgPlanPagosForm"  class="editbackground"></div>
    <div id="editPlanPagosForm" class="hide" >
        <fieldset class="adminform" id="formPlnPgoCnt">
            <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_PLANPAGO' ); ?>&nbsp;</legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'planPago' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr"></div>

            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                <input id="addPlanPagos" type="button" value="<?php echo JText::_( 'BTN_GUARDAR' ); ?>">
            <?php endIf; ?>

            <input id="cancelPlanPagos" type="button" value="<?php echo JText::_( 'BTN_CANCELAR' ); ?>">
        </fieldset>
    </div>
</div>
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_LTSPLANPAGOS'); ?>&nbsp;</legend>
        
        <?php if( $this->canDo->get( 'core.create' ) ): ?>
            <div class="fltrt">
                <input id="addPlanPagosTable" type="button" value="&nbsp;<?php echo JText::_('BTN_AGREGAR'); ?>&nbsp;">
            </div>
        <?php endIf; ?>

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
                        <?php echo JText::_('TL_ACCIONES'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </fieldset>
</div>