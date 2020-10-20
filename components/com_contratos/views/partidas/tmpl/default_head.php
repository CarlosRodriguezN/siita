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

    <th style="width: 5%"> <?php echo JHtml::_('grid.sort', 'COM_CONTRATOS_FIELD_PARTIDA_CODIGOPDA_LABEL', 'codPartida', $listDirn, $listOrder); ?> </th>
    <th style="width: 5%"> <?php echo JHtml::_('grid.sort', 'COM_CONTRATOS_FIELD_PARTIDA_NUMERO_LABEL', 'numero', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONTRATOS_FIELD_PARTIDA_DESCPARTIDA_LABEL', 'desripcion', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?></th>
<tr>
