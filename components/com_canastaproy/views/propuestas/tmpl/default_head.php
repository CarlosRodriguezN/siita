<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder = $this->escape( $this->state->get( 'list.ordering' ) );
$listDirn = $this->escape( $this->state->get( 'list.direction' ) );

?>

<tr>
    <th> <?php echo JHtml::_( 'grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_CANASTAPROY_FIELD_PROPUESTA_NOMBRE_LABEL', 'strNombre_cp', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_CANASTAPROY_FIELD_PROPUESTA_INSTITUCION_LABEL', 'strNombre_ins', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_CANASTAPROY_FIELD_PROPUESTA_MONTO_DCM_LABEL', 'dcmMonto_cp', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_CANASTAPROY_FIELD_PROPUESTA_BENEFICIARIOS_LABEL', 'intNumeroBeneficiarios', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_CANASTAPROY_FIELD_PROPUESTA_PRIORIDAD_LABEL', 'strPrioridad', $listDirn, $listOrder ); ?> </th>
    <th> <?php echo JHtml::_( 'grid.sort', 'COM_CANASTAPROY_FIELD_PROPUESTA_FECHA_REGISTO_LABEL', 'dteFechaRegistro_cp', $listDirn, $listOrder ); ?> </th>
</tr>