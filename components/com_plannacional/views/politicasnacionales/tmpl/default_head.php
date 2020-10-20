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
    <th> <?php echo JHtml::_('grid.sort', 'COM_PLANNACIONAL_FIELD_POLITICANACIONAL_DESCRIPCION_LABEL', 'strDescripcion_pln', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_PLANNACIONAL_FIELD_POLITICANACIONAL_PLANNACIONAL_LABEL', 'strDescripcion_pn', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_PLANNACIONAL_FIELD_POLITICANACIONAL_OBJNACIONAL_LABEL', 'strDescripcion_on', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?> </th>
</tr>
