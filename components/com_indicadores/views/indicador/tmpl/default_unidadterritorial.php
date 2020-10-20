<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Lista de Unidades Territoriales Registradas -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_INDICADORES_FIELD_LSTUNDTERRITORIAL_TITLE' ) ?> </legend>
        <div class="fltrt">
            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                <input id="addLnUndTerrTable" type="button" value=" &nbsp;<?php echo JText::_( 'COM_INDICADORES_FIELD_ADD_UNDTERRITORIAL_TITLE' ) ?> &nbsp;">
            <?php endIf; ?>                
        </div>
        <table id="lstUndTerritorialesInd" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_( 'COM_INDICADORES_INDICADOR_PROVINCIA_LABEL' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_INDICADORES_INDICADOR_CANTON_LABEL' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_INDICADORES_INDICADOR_PARROQUIA_LABEL' ) ?> </th>                                
                    <th colspan="2" align="center"> <?php echo JText::_( 'COM_INDICADORES_OPERACION' ) ?> </th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </fieldset>
</div>

<div class="width-50 fltlft">
    <div id="imgUndTerr" class="editbackground">
    </div>
    <div id="frmUndTerr" class="hide">
        <fieldset class="adminform">
            <legend> <?php echo JText::_('COM_INDICADORES_FIELD_UNDTERRITORIAL_TITLE') ?> </legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('unidadterritorial') as $field): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="clr"></div>
            <div class="fltlft">
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                    <input id="btnAddUndTerritorial" type="button" value="<?php echo JText::_('BTN_GUARDAR') ?>">
                <?php endIf; ?> 

                <input id="btnClnUndTerritorial" type="button" value="<?php echo JText::_('BTN_CANCELAR') ?>">
            </div>
            <div class="clr"></div>
        </fieldset>
    </div>
</div>

<div class="clr"></div>