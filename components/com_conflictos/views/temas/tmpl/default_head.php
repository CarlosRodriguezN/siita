<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<tr>
    <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONFLICTOS_FIELD_TEMA_TITULO_TEMA_LABEL', 'strTitulo_tma', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONFLICTOS_FIELD_TEMA_RESUMEN_LABEL', 'strResumen_tma', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_CONFLICTOS_FIELD_NIVEL_IMPACTO_LABEL', 'intId_ni', $listDirn, $listOrder); ?> </th>
</tr>