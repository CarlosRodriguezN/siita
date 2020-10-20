<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">

        <td class="center">
            <!-- Muestra la opcion para publicar y despublicar -->
            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'tposobjsplan.', true, 'cb'); ?>
        </td>

        <td>
            <a href=" <?php echo $item->url; ?>">
                <?php echo ucwords($item->strDescripcion_tpoObj); ?>
            </a>
        </td>

        <td>
            <?php echo ucwords($item->strAliastipo_tpoObj); ?>
        </td>

    </tr>
<?php endforeach; ?>
