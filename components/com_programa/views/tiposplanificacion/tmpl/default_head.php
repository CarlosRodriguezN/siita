<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder = $this->escape( $this->state->get( 'list.ordering' ) );
$listDirn = $this->escape( $this->state->get( 'list.direction' ) );

?>

<tr>
    <th width="1%">&nbsp;</th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_PROGRAMA_FIELD_TIPOPLANIFICACION_DESCRIPCION_LABEL', 'strdescripcion_cb', $listDirn, $listOrder ); ?> </th>
</tr>