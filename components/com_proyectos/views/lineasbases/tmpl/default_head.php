<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder = $this->escape( $this->state->get( 'list.ordering' ) );
$listDirn = $this->escape( $this->state->get( 'list.direction' ) );

?>

<tr>
    <th width="1%">
        &nbsp;
    </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_LINEABASE_DESCRIPCION_LABEL', 'strdescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_LINEABASE_VALOR_LABEL', 'strdescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_LINEABASE_FUENTE_LABEL', 'strdescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_LINEABASE_INSTITUCION_LABEL', 'strdescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_LINEABASE_PERIODICIDAD_LABEL', 'strdescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_LINEABASE_FCHINICIO_LABEL', 'strdescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_LINEABASE_FCHFIN_LABEL', 'strdescripcion_cb', $listDirn, $listOrder ); ?> </th>
</tr>