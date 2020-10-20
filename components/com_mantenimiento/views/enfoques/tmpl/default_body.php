<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">

        <td class="center">
            <!-- Muestra la opcion para publicar y despublicar -->
            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'enfoques.', true, 'cb'); ?>
        </td>

        <td>
            <a href=" <?php echo $item->url; ?>">
                <?php echo ucwords($item->nombreEnfoque); ?>
            </a>
        </td>

        <td>
            <?php echo ucwords($item->simboloEnfoque); ?>
        </td>

        <td>
            <?php echo ucwords($item->nombrePadre); ?>
        </td>

    </tr>
<?php endforeach; ?>
