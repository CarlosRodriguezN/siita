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

    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_CANASTA_NOMBRE_LABEL', 'strdescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_CANASTA_INSTITUCION_LABEL', 'strdescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_CANASTA_MONTO_LABEL', 'strdescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_CANASTA_PRIORIDAD_LABEL', 'strdescripcion_cb', $listDirn, $listOrder ); ?> </th>
    
    <th> <?php echo JHtml::_( 'grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder ); ?> </th>
</tr>