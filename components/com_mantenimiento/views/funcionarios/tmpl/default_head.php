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

    <th> <?php echo JHtml::_( 'grid.sort', 'COM_MANTENIMIENTO_FIELD_FUNCIONARIO_APELLIDO_LABEL', 'strApellido_fnc', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_MANTENIMIENTO_FIELD_FUNCIONARIO_NOMBRE_LABEL', 'strNombre_fnc', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_MANTENIMIENTO_FIELD_FUNCIONARIO_EMAIL_LABEL', 'strCorreoelec_fnc', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_MANTENIMIENTO_FIELD_FUNCIONARIO_TELEFONO_LABEL', 'strTelefono_fnc', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_MANTENIMIENTO_FIELD_FUNCIONARIO_CI_LABEL', 'strCi_fnc', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder ); ?> </th>
</tr>