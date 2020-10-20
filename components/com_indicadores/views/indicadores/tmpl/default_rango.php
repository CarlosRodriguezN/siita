<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Lista de Lineas Rango Registradas -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_INDICADORES_ATRIBUTO_RANGOGESTION_LST' ) ?> </legend>
        <div class="fltlft">
            <?php   
                $idTendencia = $this->form->getField( 'idTendencia' ); 
                echo $idTendencia->label . $idTendencia->input;
            ?>
        </div>
        <div class="clr"></div>
        <div class="fltrt">
            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                <input id="addLnRangoTable" type="button" value=" &nbsp;<?php echo JText::_( 'COM_INDICADORES_FIELD_ADD_RANGOS' ) ?> &nbsp;">
            <?php endIf; ?>
        </div>
        
        <table id="lstRangos" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_RGCOLOR_LABLE' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_RGVALMINIMO_LABLE' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_RGVALMAXIMO_LABLE' ) ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_( 'TL_ACCIONES' ) ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>

<!-- Formulario de registro de Lineas Rango -->
<div class="width-50 fltrt">
    <div id="imgRango" class="editbackground">
    </div>
    <div id="frmRango" class="hide">
        <fieldset class="adminform">
            <legend> <?php echo JText::_('COM_INDICADORES_ATRIBUTO_RANGOGESTION_FRM') ?> </legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('rangosGestion') as $field): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="clr"></div>
            <div class="fltlft">
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                    <input id="btnAddRango" type="button" value="<?php echo JText::_('BTN_GUARDAR') ?>">
                <?php endIf; ?>

                <input id="btnClnRango" type="button" value="<?php echo JText::_('BTN_CANCELAR') ?>">
            </div>
            <div class="clr"></div>
        </fieldset>
    </div>
</div>

<div class="clr"></div>