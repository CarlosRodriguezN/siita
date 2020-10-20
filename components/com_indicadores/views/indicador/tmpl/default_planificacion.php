<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Lista de Lineas Base Registradas -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_INDICADORES_FIELD_PLANIFICACION_INDICADOR_TITLE') ?> </legend>
        
        <div class="fltrt">
            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                <input id="addPlanificacionTable" type="button" value=" &nbsp;<?php echo JText::_( 'COM_INDICADORES_FIELD_ADD_PLANIFICACION_INDICADOR_TITLE' ) ?> &nbsp;">
            <?php endif; ?>            
        </div>
        
        <table id="lstPlanificacionIndicadores" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_('COM_INDICADORES_FIELD_PLANIFICACION_FECHA_LABEL') ?> </th>
                    <th align="center"> <?php echo JText::_('COM_INDICADORES_FIELD_PLANIFICACION_VALOR_LABEL') ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_('COM_INDICADORES_OPCION') ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>


<!-- Formulario de registro de Lineas Base -->
<div class="width-50 fltrt">
    
    <div id="imgPlanificacion" class="editbackground"></div>
    <div id="frmPlanificacion" class="hide">
    
        <fieldset class="adminform">
            <legend> <?php echo JText::_('COM_INDICADORES_FIELD_DATOS_PLANIFICACION_TITLE') ?> </legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('planificacionIndicador') as $field): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="clr"></div>
            <div class="fltlft">
                <input id="btnAddPlanIndicador" type="button" value="<?php echo JText::_('COM_INDICADORES_FIELD_GUARDAR_PLANIFICACION_TITLE') ?>">
                <input id="btnCancelPlanIndicador" type="button" value="<?php echo JText::_('COM_INDICADORES_FIELD_CANCEL_PLANIFICACION_TITLE') ?>">
            </div>
            <div class="clr"></div>
        </fieldset>

    </div>

</div>

<div class="clr"></div>