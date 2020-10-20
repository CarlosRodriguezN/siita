<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder = $this->escape( $this->state->get( 'list.ordering' ) );
$listDirn = $this->escape( $this->state->get( 'list.direction' ) );
//COM_MANTENIMIENTO_ACCIONES
?>

<tr>
    <th> <?php echo JHtml::_( 'grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_MANTENIMIENTO_FIELD_CARGO_FNC_NOMBRE_LABEL', 'strNombre_cargo', $listDirn, $listOrder ); ?> </th>
    <th colspan="2" align="center"> <?php echo JHtml::_( 'grid.sort', 'COM_MANTENIMIENTO_ACCIONES', 'strDescripcion_cargo', $listDirn, $listOrder ); ?> </th>
</tr>