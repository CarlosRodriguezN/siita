<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder = $this->escape( $this->state->get( 'list.ordering' ) );
$listDirn = $this->escape( $this->state->get( 'list.direction' ) );

?>

<tr>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_FUNCIONARIOS_FIELD_FNC_CI_LABEL', 'CIFnc', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_FUNCIONARIOS_FIELD_FNC_APELLIDOS_LABEL', 'apellidosFnc', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_FUNCIONARIOS_FIELD_FNC_NOMBRES_LABEL', 'nombresFnc', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_FUNCIONARIOS_FIELD_FNC_EMAIL_LABEL', 'correoFnc', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_FUNCIONARIOS_FIELD_FNC_TELEFONO_LABEL', 'telefonoFnc', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_FUNCIONARIOS_FIELD_FNC_CELULAR_LABEL', 'celularFnc', $listDirn, $listOrder ); ?> </th>
</tr>