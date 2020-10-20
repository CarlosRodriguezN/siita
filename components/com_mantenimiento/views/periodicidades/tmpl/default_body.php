<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>
<?php foreach( $this->items as $i => $item ): ?>
    <tr class="row<?php echo $i % 2; ?>">
        
        <td class="center">
            <!-- Muestra la opcion para publicar y despublicar -->
            <?php echo JHtml::_( 'jgrid.published', $item->published, $i, 'periodicidades.', true, 'cb' ); ?>
        </td>
        
        <td>
            <a href=" <?php echo $item->url; ?>">
                <?php echo ($item->strdescripcion_per) ? ucwords($item->strdescripcion_per): "-----"; ?>
            </a>
        </td>
        
        <td>
            <?php echo ucwords( $item->idfecha ); ?>
        </td>
    </tr>   
<?php endforeach; ?>
