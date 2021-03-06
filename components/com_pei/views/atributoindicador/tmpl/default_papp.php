<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Lista de Lineas Base Registradas -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_PEI_ATRIBUTO_PAPP_LST' ) ?> </legend>
        <table id="lstPAPP" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_( 'COM_PEI_ATRIBUTO_PAPP_FECHA' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_PEI_ATRIBUTO_PAPP_VALOR' ) ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_( 'COM_PEI_FIELD_ATRIBUTO_OPERACION' ) ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>

<!-- Formulario de registro de Lineas Base -->
<fieldset class="adminform">
    <legend> <?php echo JText::_( 'COM_PEI_ATRIBUTO_PAPP_FRM' ) ?> </legend>
    <ul class="adminformlist">
        <?php foreach( $this->form->getFieldset( 'pppp' ) as $field ): ?>
            <li>
                <?php echo $field->label; ?>
                <?php echo $field->input; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="clr"></div>
    <div class="fltlft">
        <input id="btnAddPAPP" type="button" value="<?php echo JText::_( 'COM_PEI_ATRIBUTO_PAPP_ADD' ) ?>">
    </div>
    <div class="clr"></div>
</fieldset>