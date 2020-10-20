<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">

        <td class="center" style = "width: 50px;" >
            <!-- Muestra la opcion para publicar y despublicar -->
            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'unidadesgestion.', true, 'cb'); ?>
        </td>

        <td>
            <?php if( $this->canDo->get( 'core.edit' ) ): ?>
                <a href=" <?php echo $item->url; ?>">
                    <?php echo ucwords(($item->strNombre_ug)? $item->strNombre_ug : "Sin descripción") ?>
                </a>
            <?php else: ?>
                <?php   echo ucwords(($item->strNombre_ug)? $item->strNombre_ug : "Sin descripción") ?>
            <?php endif;?>
        </td>

        <td>
            <?php echo ucwords($item->strAlias_ug); ?>
        </td>

    </tr>
<?php endforeach; ?>