<?php
    // No direct access to this file
    defined('_JEXEC') or die('Restricted Access');

    $listOrder = $this->escape( $this->state->get( 'list.ordering' ) );
    $listDirn = $this->escape( $this->state->get( 'list.direction' ) );
?>

<tr>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_INDICADOR_NOMBRE_LABEL', 'strDescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_INDICADOR_ENTIDAD_LABEL', 'strDescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_INDICADOR_DESCRIPCION_LABEL', 'strDescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_INDICADOR_UMBRAL_LABEL', 'strDescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_PROYECTO_FORMULA_LABEL', 'strDescripcion_cb', $listDirn, $listOrder ); ?> </th>
</tr>