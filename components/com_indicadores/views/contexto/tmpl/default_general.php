<?php
    // No direct access to this file
    defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Informacion General del Indicador -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_INDICADORES_CONTEXTO_ATRIBUTO_GENERAL' ) ?>&nbsp;&nbsp;</legend>
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

<!-- Unidad de Gestion Responsable -->
<div class="width-50 fltrt">
    <fieldset class="adminform">
        <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_INDICADORES_CONTEXTO_ATRIBUTO_UGESTION' ) ?>&nbsp;&nbsp;</legend>
        <ul class="adminformlist">
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'UnidadGestion' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </ul>
    </fieldset>
</div>

<!-- Funcionario Responsable -->
<div class="width-50 fltrt">
    <fieldset class="adminform">
        <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_INDICADORES_CONTEXTO_ATRIBUTO_RESPONSABLE' ) ?>&nbsp;&nbsp;</legend>
        <ul class="adminformlist">
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'Responsable' ) as $field ): ?>
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