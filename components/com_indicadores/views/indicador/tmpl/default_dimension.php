<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Lista de Lineas Base Registradas -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_INDICADORES_FIELD_LST_ENFOQUE_TITLE' ) ?> </legend>
        <div class="fltrt">
            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                <input id="addLnDimTable" type="button" value=" &nbsp;<?php echo JText::_( 'COM_INDICADORES_FIELD_ADD_ENFOQUE_TITLE' ) ?> &nbsp;">
            <?php endIf; ?>
                
        </div>
        <table id="lstDimensiones" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_( 'COM_INDICADORES_INDICADOR_DIMENSION_LABEL' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_INDICADORES_INDICADOR_ENFOQUE_LABEL' ) ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_( 'COM_INDICADORES_OPERACION' ) ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>


<!-- Formulario de registro de las diferentes dimensiones que puede adoptar un indicador -->
<div class="width-50 fltlft">
    <div id="imgDim" class="editbackground">
    </div>
    <div id="frmDim" class="hide">
        <fieldset class="adminform">
            <legend> <?php echo JText::_('COM_INDICADORES_FIELD_LST_ENFOQUE_FRM') ?> </legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('enfoque') as $field): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="clr"></div>
            <div class="fltlft">
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.editar' ) ): ?>
                    <input id="btnAddDim" type="button" value="&nbsp;<?php echo JText::_('BTN_GUARDAR') ?>&nbsp;">
                <?php endIf; ?>

                <input id="btnCnlDim" type="button" value="&nbsp;<?php echo JText::_('BTN_CANCELAR') ?>&nbsp;">
            </div>
            <div class="clr"></div>
        </fieldset>
    </div>
</div>
    
    
<div class="clr"></div>