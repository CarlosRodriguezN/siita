<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Formulario de registro de Lineas Rango -->
<div class="width-100 fltleft">
    <div id="frmAccesoTableu">
        <fieldset class="adminform">
            <legend> <?php echo JText::_('COM_INDICADORES_ACCESO_TABLEU_LABEL') ?> </legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset('accesoTableu') as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr"></div>
        </fieldset>
    </div>
</div>

<div class="clr"></div>