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

    <th> <?php echo JHtml::_('grid.sort', 'COM_CONTRATOS_FIELD_CONTRATO_CONTRATISTA_CTA_LABEL', 'strContratista_cta', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONTRATOS_FIELD_CONTRATO_RUP_LABEL', 'strRUP', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONTRATOS_FIELD_CONTRATO_FECHAREGISTRO_LABEL', 'dteFechaRegistro_cta', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?></th>
<tr>
