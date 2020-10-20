<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>
<div>
    <!-- Lista de Lineas Base Registradas -->
    <div class="width-50 fltlft">
        <fieldset class="adminform">
            <legend> <?php echo JText::_( 'COM_PROGRAMA_ATRIBUTO_RANGOGESTION_LST' ) ?> </legend>
            <table id="lstRangos" width="100%" class="tablesorter" cellspacing="1">
                <thead>
                    <tr>
                        <th align="center"> <?php echo JText::_( 'COM_PROGRAMA_ATRIBUTO_RANGOGESTION_COLOR' ) ?> </th>
                        <th align="center"> <?php echo JText::_( 'COM_PROGRAMA_ATRIBUTO_RANGOGESTION_VALMINIMO' ) ?> </th>
                        <th align="center"> <?php echo JText::_( 'COM_PROGRAMA_ATRIBUTO_RANGOGESTION_VALMAXIMO' ) ?> </th>
                        <th colspan="2" align="center"> <?php echo JText::_( 'COM_PROGRAMA_FIELD_ATRIBUTO_OPERACION' ) ?> </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </fieldset>
    </div>

    <!-- Formulario de registro de Lineas Base -->
    <div class="width-50 fltlft">
        <fieldset class="adminform">

            <legend> <?php echo JText::_( 'COM_PROGRAMA_ATRIBUTO_RANGOGESTION_FRM' ) ?> </legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'rangosGestion' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="clr"></div>
            <div class="fltlft">
                <input id="btnAddRango" type="button" value="<?php echo JText::_( 'COM_PROGRAMA_ATRIBUTO_RANGOGESTION_ADD' ) ?>">
            </div>
            <div class="clr"></div>
        </fieldset>
    </div>
</div>
<div class="clr"></div>