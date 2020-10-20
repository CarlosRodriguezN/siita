<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>
<?php foreach( $this->items as $i => $item ): ?>
    <tr class="row<?php echo $i % 2; ?>">
        <td class = "center" style = "width: 50px;">
            <!--Muestra la opcion para publicar y despublicar-->
            <?php echo JHtml::_( 'jgrid.published', $item->published, $i, 'actores.', true, 'cb' );
            ?>
        </td>   
        <td >
            <?php if($this->canDo->get( 'core.edit' )): ?>
                <a href="<?php echo $item->url; ?>">
                    <?php echo ucwords( $item->strNombre_act.' '.$item->strApellido_act  ); ?>
                </a>
            <?php  else:?>
                    <?php echo ucwords( $item->strNombre_act.' '.$item->strApellido_act  ); ?>
            <?php endif; ?>
        </td>
        <td >
            <?php echo ( $item->strCorreo_act ) ? $item->strCorreo_act : '-----' ; ?>
        </td>

    </tr>
<?php endforeach; ?>