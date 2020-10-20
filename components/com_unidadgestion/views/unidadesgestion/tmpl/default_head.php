<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder = $this->escape( $this->state->get( 'list.ordering' ) );
$listDirn = $this->escape( $this->state->get( 'list.direction' ) );

?>

<tr>
    <th> <?php echo JHtml::_( 'grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_UNIDAD_GESTION_FIELD_UG_NOMBRE_LABEL', 'strNombre_ug', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_UNIDAD_GESTION_FIELD_UG_ALIAS_LABEL', 'strAlias_ug', $listDirn, $listOrder ); ?> </th>
</tr>