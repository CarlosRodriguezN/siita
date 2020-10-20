<?php
    // No direct access to this file
    defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100 fltlft">
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_TAB_GENERAL_TITLE" ) ?>&nbsp;</legend>
        <!-- DATOS GENERALES. -->
        <div class="width-100 fltlft">
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'formActor' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </fieldset>
</div>
<div class="clr"> </div>
