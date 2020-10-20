<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<tr>
    <th width="1%">
        <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
    </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_PLANNACIONAL_FIELD_LINEABASE_DESCRIPCION_LABEL', 'strDescripcion_lb', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_PLANNACIONAL_FIELD_PLANNACIONAL_FCHINICIO_LABEL', 'dteFechaIni_lb', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_PLANNACIONAL_FIELD_PLANNACIONAL_FCHFIN_LABEL', 'dteFechaFin_lb', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_PLANNACIONAL_FIELD_LINEABASE_FUENTE_LABEL', 'strFuente_lb', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_PLANNACIONAL_FIELD_LINEABASE_INDICADORNACIONAL_LABEL', 'strDescripcion_in', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_PLANNACIONAL_FIELD_LINEABASE_PERIODO_LABEL', 'strDescripcion_per', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_PLANNACIONAL_FIELD_LINEABASE_UNIDADMEDIDA_LABEL', 'strDescripcion_uniMed', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_PLANNACIONAL_FIELD_LINEABASE_VALOR_LABEL', 'dcmValor_lb', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?> </th>
</tr>
