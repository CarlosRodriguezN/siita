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

    <th> <?php echo JHtml::_('grid.sort', 'COM_MANTENIMIENTO_FIELD_INSTITUICION_NOMBRE_LABEL', 'strNombre_ins', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_MANTENIMIENTO_FIELD_INSTITUICION_RUC_LABEL', 'strRuc_ins', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_MANTENIMIENTO_FIELD_INSTITUICION_ALIAS_LABEL', 'strAlias_ins', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_MANTENIMIENTO_FIELD_INSTITUICION_MANINSTITUCION_LABEL', 'strFuncionMandato_ins', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_MANTENIMIENTO_FIELD_INSTITUICION_NUMNORMA_LABEL', 'strNumNorma_ins', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_MANTENIMIENTO_FIELD_INSTITUICION_REGOFICIAL_LABEL', 'strRegistroOficial_ins', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_MANTENIMIENTO_FIELD_INSTITUICION_FCHREGOFICIAL_LABEL', 'dteFechaRegOficial', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_MANTENIMIENTO_FIELD_INSTITUICION_MISION_LABEL', 'strMision', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_MANTENIMIENTO_FIELD_INSTITUICION_VISION_LABEL', 'strVision', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_MANTENIMIENTO_FIELD_INSTITUICION_OBSERVACION_LABEL', 'strObservacion_ins', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_MANTENIMIENTO_FIELD_INSTITUICION_DEPENDENCIA_LABEL', 'strDescripcion_depins', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_MANTENIMIENTO_FIELD_INSTITUICION_SECTOR_LABEL', 'strDescripcion_sec', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_MANTENIMIENTO_FIELD_INSTITUICION_FUNCION_LABEL', 'strdescripcionfuncion', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_MANTENIMIENTO_FIELD_INSTITUICION_NORMA_LABEL', 'strdescripcionnorma', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_('grid.sort', 'COM_MANTENIMIENTO_FIELD_INSTITUICION_TIPOINSTITUCION_LABEL', 'strDescripcionTipo_ins', $listDirn, $listOrder); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder ); ?> </th>
</tr>