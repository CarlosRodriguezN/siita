<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder = $this->escape( $this->state->get( 'list.ordering' ) );
$listDirn = $this->escape( $this->state->get( 'list.direction' ) );

?>

<tr>
    <th> <?php echo JHtml::_( 'grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PEI_FIELD_PLAN_DESCRIPCION_LABEL', 'strDescripcion_pi', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PEI_FIELD_PLAN_ALIAS_LABEL', 'strAlias_pi', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PEI_FIELD_PLAN_VIGENCIA_LABEL', 'blnVigente_pi', $listDirn, $listOrder ); ?> </th>
</tr>