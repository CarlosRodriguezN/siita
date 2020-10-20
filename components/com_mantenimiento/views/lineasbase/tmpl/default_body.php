<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">

        <td class="center">
            <!-- Muestra la opcion para publicar y despublicar -->
            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'lineasbase.', true, 'cb'); ?>
        </td>

        <td>
            <a href=" <?php echo $item->url; ?>">
                <?php echo ucwords($item->strDescripcion_lbind); ?>
            </a>
        </td>

        <td>
            <?php echo ucwords($item->strObservacion_fuente); ?>
        </td>

        <td>
            <?php echo ucwords($item->strdescripcion_per); ?>
        </td>

        <td>
            <?php echo ucwords($item->dcmValor_lbind); ?>
        </td>

        <td>
            <?php echo ucwords($item->dteFechaInicio_lbind); ?>
        </td>

        <td>
            <?php echo ucwords($item->dteFechaFin_lbind); ?>
        </td>

        <td>
            <?php echo ucwords($item->dteFechaRegistro_lbind); ?>
        </td>

    </tr>
<?php endforeach; ?>
