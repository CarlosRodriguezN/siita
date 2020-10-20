<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Lista de Lineas Base Registradas -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_LINEABASE' ) ?> </legend>
        <table id="lstLineasBase" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_LINEABASE' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_VALLINEABASE_LABLE' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_FUENTELINEABASE_LABLE' ) ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_OPERACION' ) ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>

<!-- Formulario de registro de Lineas Base -->
<fieldset class="adminform">

    <legend> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_LINEABASE' ) ?> </legend>
    <ul class="adminformlist">
        <?php foreach( $this->form->getFieldset( 'lineasBase' ) as $field ): ?>
            <li>
                <?php echo $field->label; ?>
                <?php echo $field->input; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="clr"></div>
    <div class="fltlft">
        <input id="btnAddLineaBase" type="button" value="<?php echo JText::_( 'COM_PROYECTOS_INDICADOR_ADDVARIABLE' ) ?>">
    </div>
    <div class="clr"></div>
</fieldset>