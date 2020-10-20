<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div class="adminform width-100">
    <div class="width-60 fltrt">
        <?php echo $this->loadTemplate('mapa'); ?>
    </div>
    <div class="width-40 fltleft">
        <fieldset class="adminformlist" style="height: 415px">
            <legend><?php echo JText::_('COM_CONTRATO_VIEW_DATOSGRLS_TITLE'); ?></legend>
            <div class="fltleft scrollable ">
                <table>
                    <tbody>
                        <tr>
                            <td> <b>&nbsp;<?php echo JText::_('PUBLIC_VIEW_CONTRATOS_DATA_NUMCONTRATO'); ?>&nbsp</b></td>
                            <td>:</td>
                            <td>&nbsp;<?php echo $this->contratoData->intNumContrato_ctr ?></td>
                        </tr>
                        <tr>
                            <td><b>&nbsp;<?php echo JText::_('PUBLIC_VIEW_CONTRATOS_DATA_CUR'); ?>&nbsp</b></td>
                            <td>:</td>
                            <td>&nbsp;<?php echo $this->contratoData->strCUR_ctr ?></td>
                        </tr>
                        <tr>
                            <td><b>&nbsp;<?php echo JText::_('PUBLIC_VIEW_CONTRATOS_DATA_PLAZO'); ?>&nbsp</b></td>
                            <td>:</td>
                            <td>&nbsp;<?php echo $this->contratoData->intPlazo_ctr ?></td>
                        </tr>
                        <tr>
                            <td><b>&nbsp;<?php echo JText::_('PUBLIC_VIEW_CONTRATOS_DATA_PARTIDA'); ?>&nbsp</b></td>
                            <td>:</td>
                            <td>&nbsp;<?php echo $this->contratoData->intIdPartida_pda ?></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p>
                <p><b><?php echo JText::_('PUBLIC_VIEW_CONTRATOS_DATA_DESCRIPCION'); ?>&nbsp</b></p>
                <p><?php echo $this->contratoData->strDescripcion_ctr ?></p>
                <p><b><?php echo JText::_('PUBLIC_VIEW_CONTRATOS_DATA_OBSERVACION'); ?></b></p>
                <p><?php echo $this->contratoData->strObservacion_ctr ?></td>
            </p>
        </fieldset>
    </div>
</div>