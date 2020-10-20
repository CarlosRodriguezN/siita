<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<tr>
    <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONTRATOS_FIELD_CONVENIO_DESCRIPCION_LABEL', 'descripcionCtr', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONTRATOS_FIELD_CONVENIO_CODIGO_LABEL', 'curCtr', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONTRATOS_FIELD_CONVENIO_OBSERVACION_LABEL', 'observacionCtr', $listDirn, $listOrder); ?> </th>
</tr>