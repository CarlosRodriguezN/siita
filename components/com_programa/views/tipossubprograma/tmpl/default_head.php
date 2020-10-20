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

    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROGRAMA_FIELD_TIPO_SUB_PROGRAMA_CODIGOTIPOSUBPROGRAMA_LABEL', 'strCodigoTipoSubPrograma', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROGRAMA_FIELD_TIPO_SUB_PROGRAMA_SUBPROGRAMA_LABEL', 'aliasSubPrograma', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROGRAMA_FIELD_TIPO_SUB_PROGRAMA_DESCRIPCION_LABEL', 'strDescripcion', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder ); ?> </th>
</tr> 