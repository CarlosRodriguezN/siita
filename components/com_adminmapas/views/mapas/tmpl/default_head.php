<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<tr>
    <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_ADMINMAPAS_FIELD_MAPA_NOMBRE_LABEL', 'strNombre', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_ADMINMAPAS_FIELD_MAPA_COPYRIGTH_LABEL', 'strCopyright', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_ADMINMAPAS_FIELD_MAPA_DESCRIPCION_LABEL', 'strDescripcion', $listDirn, $listOrder); ?> </th>
</tr>