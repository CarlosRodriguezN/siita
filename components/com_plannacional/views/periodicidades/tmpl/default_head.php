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
    <th> <?php echo JHtml::_('grid.sort', 'COM_PLANNACIONAL_FIELD_PERIODICIDAD_DESCRIPCION_LABEL', 'strDescripcion_per', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_PLANNACIONAL_FIELD_PERIODICIDAD_FECHA_LABEL', 'idFecha', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?> </th>
</tr>
