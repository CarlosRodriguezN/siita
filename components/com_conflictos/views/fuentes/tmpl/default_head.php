<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<tr>
    <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONFLICTOS_FIELD_FUENTE_DESCRICION_LABEL', 'strDescripcion_fte', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONFLICTOS_FIELD_FUENTE_OBSERVACION_LABEL', 'strObservaciones_fte ', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONFLICTOS_FIELD_FUENTE_TIPO_FUENTE_LABEL', 'strDescripcion_tf', $listDirn, $listOrder); ?> </th>
</tr>