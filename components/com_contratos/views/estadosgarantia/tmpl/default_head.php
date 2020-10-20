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

    <th style="width: 10%"> <?php echo JHtml::_('grid.sort', 'COM_CONTRATOS_FIELD_ESTADOGARANTIA_CODIGO_LABEL', 'codEstadoGarantia', $listDirn, $listOrder); ?> </th>
    <th > <?php echo JHtml::_('grid.sort', 'COM_CONTRATOS_FIELD_ESTADOGARANTIA_NOMBRE_LABEL', 'nombre', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONTRATOS_FIELD_ESTADOGARANTIA_DESCESTADOGARANTIA_LABEL', 'descripcion', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?></th>
<tr>
