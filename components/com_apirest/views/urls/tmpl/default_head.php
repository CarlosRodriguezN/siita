<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder  = $this->escape( $this->state->get( 'list.ordering' ) );
$listDirn   = $this->escape( $this->state->get( 'list.direction' ) );

?>

<tr>
    <th> <?php echo JHtml::_( 'grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_APIREST_FIELD_INSTITUCION_LABEL', 'descripcion', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_APIREST_FIELD_TOKEN_LABEL', 'token', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_APIREST_FIELD_VIGENCIA_LABEL', 'vigencia', $listDirn, $listOrder ); ?> </th>
</tr>