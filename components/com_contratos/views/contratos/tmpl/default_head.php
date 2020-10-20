<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<tr>
    <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONTRATOS_FIELD_CONTRATO_DESCRIPCION_LABEL', 'descripcionCtr', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONTRATOS_FIELD_CONTRATO_NOMBRE_LABEL', 'curCtr', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONTRATOS_FIELD_CONTRATO_MONTO_LABEL', 'montoCtr', $listDirn, $listOrder); ?> </th>
</tr>