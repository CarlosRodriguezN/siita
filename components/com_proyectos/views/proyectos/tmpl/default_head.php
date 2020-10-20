<?php
    // No direct access to this file
    defined('_JEXEC') or die('Restricted Access');

    $listOrder = $this->escape( $this->state->get( 'list.ordering' ) );
    $listDirn = $this->escape( $this->state->get( 'list.direction' ) );
?>

<tr>
    <th> <?php echo JHtml::_( 'grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder ); ?> </th>
    
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_PROYECTO_PROYECTO_LABEL', 'strDescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_PROYECTO_PROGRAMA_LABEL', 'strDescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_PROYECTO_FCHINICIO_LABEL', 'strDescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_PROYECTO_FCHFIN_LABEL', 'strDescripcion_cb', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROYECTOS_FIELD_PROYECTO_MONTO_LABEL', 'strDescripcion_cb', $listDirn, $listOrder ); ?> </th>
</tr>