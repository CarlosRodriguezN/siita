<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">

        <td class="center" style="width: 20px;">
            <!-- Muestra la opcion para publicar y despublicar -->
            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'agendas.', true, 'cb'); ?>
        </td>

        <td>
            <a href=" <?php echo $item->url; ?>">
                <?php echo ucwords($item->strDescripcion_ag); ?>
            </a>
        </td>
        <td>
            <?php echo ($item->dteFechaInicio_ag) ? $item->dteFechaInicio_ag : "-----"; ?>
        </td>
        <td>
            <?php echo ($item->dteFechaFin_ag) ? $item->dteFechaFin_ag : "-----"; ?>
        </td>
        
    </tr>
<?php endforeach; ?>
