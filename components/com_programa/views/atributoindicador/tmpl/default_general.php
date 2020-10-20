<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>
<div>
    <div class="width-50 fltlft">
        <!-- Unidad de Gestion -->
        <fieldset class="adminform">
            <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_PROGRAMA_FIELD_ATRIBUTO_GENERAL' ) ?>&nbsp;&nbsp;</legend>
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
    <div class="width-50 fltlft">
        <fieldset class="adminform">
            <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_PROGRAMA_FIELD_HORIZONTE_UGESTION' ) ?>&nbsp;&nbsp;</legend>
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
    <!-- Rangos por Default -->
    <div class="width-50 fltlft">
        <fieldset class="adminform">
            <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_PROGRAMA_FIELD_RANGOS_UGESTION' ) ?>&nbsp;&nbsp;</legend>
            <ul class="adminformlist">
                <ul class="adminformlist">
                    <?php foreach( $this->form->getFieldset( 'rangos' ) as $field ): ?>
                        <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

            </ul>
        </fieldset>
    </div>
    <!-- Unidad de Gestion -->
    <div class="width-50 fltlft">
        <fieldset class="adminform">
            <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_PROGRAMA_FIELD_ATRIBUTO_UGESTION' ) ?>&nbsp;&nbsp;</legend>
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
    <!-- Responsable -->
    <div class="width-50 fltlft">
        <fieldset class="adminform">
            <legend>&nbsp;&nbsp;<?php echo JText::_( 'COM_PROGRAMA_FIELD_ATRIBUTO_RESPONSABLE' ) ?>&nbsp;&nbsp;</legend>
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
</div>
<div class="clr"></div>
