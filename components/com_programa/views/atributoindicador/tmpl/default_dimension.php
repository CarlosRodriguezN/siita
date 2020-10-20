<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_PROGRAMA_FIELD_PROYECTO_ENFOQUE_TITLE' ) ?> </legend>
        <table id="lstDimensiones" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_( 'COM_PROGRAMA_INDICADOR_DIMENSION_LABEL' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_PROGRAMA_INDICADOR_ENFOQUE_LABEL' ) ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_( 'COM_PROGRAMA_FIELD_INDICADOR_OPERACION_FUENTE' ) ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>

<!-- Formulario de registro de las diferentes dimensiones que puede adoptar un indicador -->
<fieldset class="adminform">
    <legend> <?php echo JText::_( 'COM_PROGRAMA_FIELD_PROYECTO_ENFOQUE_TITLE' ) ?> </legend>
    <ul class="adminformlist">
        <?php foreach( $this->form->getFieldset( 'enfoque' ) as $field ): ?>
            <li>
                <?php echo $field->label; ?>
                <?php echo $field->input; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="fltlft">
        <input id="btnAddDimension" type="button" value="<?php echo JText::_( 'COM_PROGRAMA_INDICADOR_ADDDIMENSION_TITLE' ) ?>">
    </div>
    <div class="clr"></div>
</fieldset>
