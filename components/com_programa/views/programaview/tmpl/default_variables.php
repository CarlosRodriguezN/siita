<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div  class="adminform">

    <div class="width-25 fltrt">
        <div id="chart_var_div" style="width: 100%; height: 300px;"></div>
    </div>

    <div  class="width-50 fltleft">
        <fieldset class="adminformlist">
            <legend><?php echo JText::_('PUBLIC_VIEW_INDICADORES_PROGRAMAS'); ?></legend>
            <div style="height:400px; overflow:auto;">
                <?php if ($this->efoques): ?>
                    <?php foreach ($this->efoques AS $enfoque): ?>
                        <div class="clr"></div>
                        <table id='<?php echo $enfoque ?>' class="tablesorter varTable" cellspacing="1">
                            <thead>
                                <tr>
                                    <th  style="text-align: center"><?php echo $this->getNameEnfoque($enfoque) ?></th>
                                </tr>
                            </thead>
                            <tbody style="height:100px; overflow:auto;" >
                                <?php $cnt = 0 ?>
                                <?php $dimensiones = $this->getDimencionesEnfoque($enfoque); ?>
                                <?php foreach ($dimensiones AS $dimension): ?>
                                    <tr>
                                        <td align="center"><b><?php echo $this->getNombreDimension($dimension) ?></b></td>
                                    </tr>
                                    <?php $indicadores = $this->getIndicadoresDimencion($dimension) ?>
                                    <?php foreach ($indicadores AS $indicador): ?>
                                        <tr>
                                            <td>
                                                <?php $nombre = $indicador->nombre ?>
                                                <?php if (strlen($indicador->nombre) == 0): ?>
                                                    <?php $nombre = $indicador->undAnalisis ?>
                                                <?php endif; ?>
                                                <?php if ($indicador->variables) : ?>
                                                    <table  class="tablesorter" cellspacing="1">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="4"><?php echo $nombre ?></th>
                                                            </tr>
                                                            <tr>
                                                                <th align="center" colspan="2" style="width: 50%"><?php echo JText::_('SEG_VARIABLE') ?></th>
                                                                <th align="center" colspan="2" style="width: 50%"><?php echo JText::_('SEG_SEGUIMIENTO') ?></th>
                                                            </tr>
                                                            <tr>
                                                                <th><?php echo JText::_('VAR_DESCR_VARIABLE') ?></th>
                                                                <th><?php echo JText::_('VAR_FECHA_VARIABLE') ?></th>
                                                                <th><?php echo JText::_('VAR_FECHA_SEGUIMINETO') ?></th>
                                                                <th><?php echo JText::_('VAR_VALOR_SEGUIMIENTO') ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($indicador->variables AS $variable): ?>
                                                                <tr>
                                                                    <td><?php echo $variable->descripcionVariable; ?></td>
                                                                    <td><?php echo $variable->fchRegVariable; ?></td>
                                                                    <td><?php echo $variable->seguimineto->fchSeguimiento; ?></td>
                                                                    <td><?php echo $variable->seguimineto->valor; ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                <?php else: ?>
                                                    <?php echo $nombre ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php $cnt++ ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </fieldset>
    </div>
</div>