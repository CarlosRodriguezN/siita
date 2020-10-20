<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100">
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_MANTENIMIENTO_DATA_GEN'); ?></legend>
        <ul class="adminformlist">
            <?php foreach ($this->form->getFieldset('cargoug') as $field): ?>
                <li>
                    <?php echo $field->label; ?>
                    <?php echo $field->input; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="clr"></div>
        <div class="width-100">
            <fieldset class="adminform" style="padding: 5px 5px 0px 5px; margin: 0px;">
                <legend>&nbsp;<?php echo JText::_('COM_MANTENIMIENTO_PERMISOS'); ?>&nbsp;</legend>
                <table id="tbPermisosByCom" width="100%" class="tablesorter adminlist" cellspacing="1" >
                    <thead>
                        <tr>
                            <th align="center"><?php echo JText::_( 'COM_MANTENIMIENTO_FIELD_CARGO_FNC_COMPONENTE_LABEL' ) ?></th>
                            <th align="center" width="15px" ><?php echo JText::_( 'COM_MANTENIMIENTO_FIELD_CARGO_FNC_ACCESO_LABEL' ) ?></th>
                            <th align="center" width="15px" ><?php echo JText::_( 'COM_MANTENIMIENTO_FIELD_CARGO_FNC_AGREGAR_LABEL' ) ?></th>
                            <th align="center" width="15px" ><?php echo JText::_( 'COM_MANTENIMIENTO_FIELD_CARGO_FNC_ACTUALIZAR_LABEL' ) ?></th>
                            <th align="center" width="15px" ><?php echo JText::_( 'COM_MANTENIMIENTO_FIELD_CARGO_FNC_ELIMINAR_LABEL' ) ?></th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
                <div id="srEtr" align="center" class="hide"> <p> <?php echo JText::_( 'COM_MANTENIMIENTO_SIN_REGISTROS' ); ?> </p> </div>
            </fieldset>
        </div>
        <?php echo $this->form->getLabel('descripcion'); ?>
        <div class="clr"></div>
        <?php echo $this->form->getInput('descripcion'); ?>
    </fieldset>
</div>
