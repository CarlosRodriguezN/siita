<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<div class="width-100">
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_INDICADORES_FIELD_LST_PLANTILLA_TITLE' ) ?> </legend>
        <ul class="adminformlist">
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'plantilla' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </ul>
    </fieldset>
</div>