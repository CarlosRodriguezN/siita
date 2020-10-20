<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Informacion General del Indicador -->
<div class="width-100">
    <fieldset class="adminform">
        <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_INDICADORES_INFORMACION_COMPLEMENTARIA' ) ?>&nbsp;&nbsp;</legend>
        <ul class="adminformlist">
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'informacionComplementaria' ) as $field ): ?>
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
