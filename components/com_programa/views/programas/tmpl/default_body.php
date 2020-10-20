<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">
        <td class="center">
            <!-- Muestra la opcion para publicar y despublicar -->
            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'programas.', true, 'cb'); ?>
        </td>
        <td>
            <?php $nombre = ( $item->nombre ) ? strtoupper( ucwords( $item->nombre ) ) : "-----"; ?>
            <?php if( $this->canDo->get( 'core.edit' ) ): ?>
                <a href="<?php echo $item->url; ?>">
                    <?php echo $nombre; ?>
                </a>
            <?php else: ?>
            <?php   echo $nombre; ?>
            <?php endif;?>
        </td>
        <td>
            <?php echo ucwords($item->tipoEntidad); ?>
        </td>
    </tr>
<?php endforeach; ?> 