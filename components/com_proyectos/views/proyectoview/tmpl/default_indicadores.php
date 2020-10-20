<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div  class="adminform">

    <div class="width-50 fltrt">
        <div id="chart_div" style="width: 100%; height: 300px;"></div>
    </div>

    <div  class="width-50 fltleft">
        <fieldset class="adminformlist">
            <legend><?php echo JText::_('PUBLIC_VIEW_INDICADORES_PROYECTOS'); ?></legend>
            <div style="height:400px; overflow:auto;">
                <?php if ($this->efoques): ?>
                    <?php foreach ($this->efoques AS $enfoque): ?>
                        <div class="clr"></div>
                        <table id='<?php echo $enfoque ?>' class="tablesorter indTable" cellspacing="1">
                            <thead>
                                <tr>
                                    <th colspan="3" style="text-align: center"><?php echo $this->getNameEnfoque($enfoque) ?></th>
                                </tr>
                                <tr>
                                    <th style="width: 3%;text-align: center"><?php echo JText::_('NUMERO'); ?></th>
                                    <th align="center" style="width: 50%"><?php echo JText::_('PLANIFICACION_INDICADOR_NOMBRE'); ?></td>
                                    <th align="center" style="width: 50%"><?php echo JText::_('PLANIFICACION_INDICADOR_VALOR'); ?></td>
                                </tr>
                            </thead>
                            <tbody style="height:100px; overflow:auto;" >
                                <?php $cnt = 0 ?>
                                <?php $dimensiones = $this->getDimencionesEnfoque($enfoque); ?>
                                <?php foreach ($dimensiones AS $dimension): ?>
                                    <tr>
                                        <td colspan="4" align="center"><b><?php echo $this->getNombreDimension($dimension) ?></b></td>
                                    </tr>
                                    <?php $indicadores = $this->getIndicadoresDimencion($dimension) ?>
                                    <?php foreach ($indicadores AS $indicador): ?>
                                        <tr>
                                            <td><?php echo $cnt ?></td>
                                            <td><?php echo ($indicador->nombre) ? $indicador->nombre : $indicador->undAnalisis ?></td>
                                            <td><?php echo number_format($indicador->valor, 2, '.', '') . ' ' . $indicador->simbolo ?></td>
                                        </tr>
                                        <?php $cnt++ ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="2"align=right><b><?php echo JText::_('TOTAL'); ?></b></td>
                                    <td><?php echo number_format($this->totalValueIndicador($enfoque), 2, '.', '') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </fieldset>
    </div>
</div>