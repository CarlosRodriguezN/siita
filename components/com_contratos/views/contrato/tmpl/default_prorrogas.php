<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!--lISTA DE PRORROGAS-->
<div class="width-50 fltrt">
    <div id="ieavProrroga" class="editbackground"></div>
    <div id="editProrrogaForm" class="hide">
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PRORROGAS' ); ?>&nbsp;</legend>
            <ul class="adminformlist" id="formProrrogaCnt">
                <?php foreach( $this->form->getFieldset( 'prorroga' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr"></div>
            
            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                <input id="addProrroga" type="button" value="&nbsp;<?php echo JText::_( 'BTN_GUARDAR' ); ?>&nbsp;">
            <?php endIf; ?>

            <input id="cancelarProrroga" type="button" value="&nbsp;<?php echo JText::_( 'BTN_CANCELAR' ); ?>&nbsp;">
        </fieldset>
    </div>
</div>
<div class="width-50">
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PRORROGAS' ); ?>&nbsp;</legend>
        
        <?php if( $this->canDo->get( 'core.create' ) ): ?>
            <div class="fltrt">
                <input id="addProrrogaTable" type="button" value="&nbsp;<?php echo JText::_('BTN_AGREGAR_PRRG'); ?>&nbsp;">
            </div>
        <?php endIf; ?>

        <ul>
            <li>
                <table  id="tbProrrogaContrato" class="tablesorter" cellspacing="1" >
                    <thead>
                        <tr>
                            <th align="center" width="10%" >
                                <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PRORROGAS_CODIGO' ); ?>
                            </th>
                            <th align="center" width="15%">
                                <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PRORROGAS_MORA' ); ?>
                            </th>
                            <th align="center" width="10%">
                                <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PRORROGAS_PLAZO' ); ?>
                            </th>
                            <th align="center" >
                                <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PRORROGAS_DOCUMENTO' ); ?>
                            </th>
                            <th align="center" >
                                <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PRORROGAS_OBSERVACION' ); ?>
                            </th>
                            <th  colspan="2" align="center">
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