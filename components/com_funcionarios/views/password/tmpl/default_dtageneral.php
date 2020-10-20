<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<div class="width-100" style="text-align: center;" >
    <fieldset class="adminform">
        <legend> </legend>
        <ul class="adminformlist">
            <?php foreach( $this->form->getFieldset( 'password' ) as $field ): ?>
                <li>
                    <?php echo $field->label; ?>
                    <?php echo $field->input; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
</div>
