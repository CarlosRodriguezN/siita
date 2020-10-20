<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
//
//var_dump( $this->items); exit;

?>
<?php foreach( $this->items as $i => $item ): ?>
    <tr class="row<?php echo $i % 2; ?>">
        <td class = "center" style = "width: 50px;">
            <!--Muestra la opcion para publicar y despublicar-->
            <?php echo JHtml::_( 'jgrid.published', $item->published, $i, 'fuentes.', true, 'cb' );
            ?>
        </td>   
        <td >
            <?php if($this->canDo->get( 'core.edit' )): ?>
                <a href="<?php echo $item->url; ?>">
                    <?php echo ucwords( $item->strDescripcion_fte ); ?>
                </a>
            <?php  else:?>
                    <?php echo ucwords( $item->strDescripcion_fte ); ?>
            <?php endif; ?>
        </td>
        <td >
            <?php echo ucwords( $item->strObservaciones_fte ); ?>
        </td>

        <td >
            <?php echo ucwords( $item->strDescripcion_tf ); ?>
        </td>

    </tr>
<?php endforeach; ?>