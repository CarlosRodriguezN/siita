<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder = $this->escape( $this->state->get( 'list.ordering' ) );
$listDirn = $this->escape( $this->state->get( 'list.direction' ) );

?>

<tr>
    <th style="width: 15px"> <?php echo JHtml::_( 'grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROGRAMA_FIELD_PROGRAMA_PROGRAMA_LABEL', 'tipoEntidad', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROGRAMA_FIELD_PROGRAMA_ENTIDAD_LABEL', 'strDescripcion_prg', $listDirn, $listOrder ); ?> </th>
</tr> 