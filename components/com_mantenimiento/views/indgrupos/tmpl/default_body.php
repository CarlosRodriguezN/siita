<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">

        <td class="center">
            <!-- Muestra la opcion para publicar y despublicar -->
            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'indgrupo.', true, 'cb'); ?>
        </td>

        <td>
            <a href=" <?php echo $item->url; ?>">
                <?php echo ucwords($item->strDescripcion_gpo); ?>
            </a>
        </td>

        <td>
            <?php echo ($item->intId_gpo_padre ) ?  ucwords($item->descPadre) : "-----" ; ?>
        </td>

    </tr>
<?php endforeach; ?>
