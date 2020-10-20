<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Funcionario Responsable -->
<div class="width-50 fltrt">
    <fieldset class="adminform">
        <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_RESPONSABLE' ) ?>&nbsp;&nbsp;</legend>
        <ul class="adminformlist">
            <?php foreach( $this->form->getFieldset( 'Responsable' ) as $field ): ?>
                <li>
                    <?php echo $field->label; ?>
                    <?php echo $field->input; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
</div>

<!-- Unidad de Gestion Responsable -->
<div class="width-50 fltrt">
    <fieldset class="adminform">
        <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_UGESTION' ) ?>&nbsp;&nbsp;</legend>
        <ul class="adminformlist">
            <?php foreach( $this->form->getFieldset( 'UnidadGestion' ) as $field ): ?>
                <li>
                    <?php echo $field->label; ?>
                    <?php echo $field->input; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
</div>

<div class="clr"></div>