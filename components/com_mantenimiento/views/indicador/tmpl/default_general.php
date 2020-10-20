<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Informacion General del Indicador -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_MANTENIMIENTO_FIELD_ATRIBUTO_GENERAL' ) ?>&nbsp;&nbsp;</legend>
        <ul class="adminformlist">
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'InformacionGeneral' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

        </ul>
    </fieldset>
</div>

<div class="clr"></div>
