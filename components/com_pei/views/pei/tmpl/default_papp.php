<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// Obtengo el id de registro del plan vigente
$regPAPP = '';
if ( $this->lstPAPPs ) {
    foreach ($this->lstPAPPs as $i => $plan) {
        if ($plan->vigentePln == 1) {
            $regPAPP = (int) $plan->idRegPln;
        }
    }
}
    
?>

<!-- Lista de planes de tipo PAPP -->
<div class="width-30 fltlft">
    <div class="m">
        <div>
            <table id="lstPAPPs" class="adminlist">
                <thead> 
                    <tr>
                        <th> <?php echo JHtml::_('grid.sort', 'COM_PEI_FIELD_PLAN_VIGENCIA_LABEL', 'vigentePln' ); ?> </th>
                        <th> <?php echo JHtml::_('grid.sort', 'COM_PEI_FIELD_NOMBRE_PLN_LABEL', 'descripcionPln' ); ?> </th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <div id="srPapp" class='hide' align="center">
                <p><?php echo JText::_('COM_PEI_SIN_REGISTROS'); ?></p>
            </div>
            <!-- Id de registro del PAPP para Gestion -->
            <input id="regPAPP" type="hidden" name="regPAPP"  value="<?php echo $regPAPP; ?>" />
        </div>
    </div>
</div>

<!-- Lista de objetivos de los planes de tipo PAPP -->
<div class="width-70 fltrt"> 
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_PEI_FIELD_PLAN_OBJETIVOS_TITLE') ?> </legend>
        <table id="tbLstObjetivosPAPP" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"><?php echo JText::_('COM_PEI_FIELD_OBJETIVO_DESCRIPCION_LABEL') ?></th>
                    <th align="center"><?php echo JText::_('COM_PEI_FIELD_OBJETIVO_PRIORIDAD_LABEL') ?></th>
                    <th align="center"><?php echo JText::_('COM_PEI_FIELD_OBJETIVO_ALINEACION_LABEL') ?></th>
                    <th colspan="3" align="center"><?php echo JText::_('COM_PEI_FIELD_OBJETIVO_PLAN_ACCION_LABEL') ?></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <div id="srObjPapp" class='hide' align="center">
            <p><?php echo JText::_('COM_PEI_SIN_REGISTROS'); ?></p>
        </div>
    </fieldset>
</div>