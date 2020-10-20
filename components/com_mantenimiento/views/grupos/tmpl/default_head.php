<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder = $this->escape( $this->state->get( 'list.ordering' ) );
$listDirn = $this->escape( $this->state->get( 'list.direction' ) );

?>

<tr>
    <th width="1%">
        <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
    </th>

    <th> <?php echo JHtml::_( 'grid.sort', 'COM_MANTENIMIENTO_FIELD_GRUPO_DESCRIPCION_LABEL', 'strDescripcion_grp', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_MANTENIMIENTO_FIELD_GRUPO_CATEGORIA_LABEL', 'strDescripcion_categoria', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder ); ?> </th>
</tr>