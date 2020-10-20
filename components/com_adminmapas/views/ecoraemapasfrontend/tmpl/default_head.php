<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

//$listOrder = $this->escape( $this->state->get( 'list.ordering' ) );
//$listDirn = $this->escape( $this->state->get( 'list.direction' ) );

?>

<tr>
<!--    <th width="1%">
        <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(<?php //echo count($this->items); ?>);" />
    </th>-->

    <th> <?php echo JHtml::_( 'grid.sort', 'COM_ADMINMAPAS_FIELD_ECORAEMAPA_NOMBRE_LABEL', 'strNombre', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_ADMINMAPAS_FIELD_ECORAEMAPA_COPYRIGTH_LABEL', 'strCopyright', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_ADMINMAPAS_FIELD_ECORAEMAPA_DESCRIPCION_LABEL', 'strDescripcion', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_ADMINMAPAS_FIELD_ECORAEMAPA_VISTAPREVIA_LABEL', 'strDescripcion', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_ADMINMAPAS_FIELD_ECORAEMAPA_DESCARGA_LABEL', 'strDescripcion', $listDirn, $listOrder ); ?> </th>
</tr>