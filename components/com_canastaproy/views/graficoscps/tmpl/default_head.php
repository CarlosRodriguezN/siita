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

    <th> <?php echo JHtml::_( 'grid.sort', 'COM_CANASTAPROY_FIELD_GRAFICO_CP_DESCRIPCION_LABEL', 'strDescripcionGrafico_gcp', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_CANASTAPROY_FIELD_GRAFICO_CP_TIPO_GRAF_LABEL', 'strDescripcion_tg', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_CANASTAPROY_FIELD_GRAFICO_CP_PROPUESTA_LABEL', 'intIdPropuesta_cp', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder ); ?> </th>
</tr>