<?php
    // No direct access to this file
    defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Informacion General del Indicador -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_GENERAL' ) ?>&nbsp;&nbsp;</legend>
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

<!-- Horizonte por Default -->
<div class="width-50 fltrt">
    <fieldset class="adminform">
        <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_INDICADORES_FIELD_HORIZONTE_UGESTION' ) ?>&nbsp;&nbsp;</legend>
        <ul class="adminformlist">
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'horizonte' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

        </ul>
    </fieldset>
</div>

<!-- Tipo de Indicador Grupo -->
<div class="width-50 fltrt">
    <fieldset class="adminform">
        <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_INDICADORES_FIELD_GRUPO_INDICADOR' ) ?>&nbsp;&nbsp;</legend>
        <ul class="adminformlist">
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'grupo' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </ul>
    </fieldset>
</div>

<!-- SENPLADES -->
<div class="width-50 fltrt">
    <fieldset class="adminform">
        <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_INDICADORES_SENPLADES_INDICADOR' ) ?>&nbsp;&nbsp;</legend>
        <ul class="adminformlist">
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'senplades' ) as $field ): ?>
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