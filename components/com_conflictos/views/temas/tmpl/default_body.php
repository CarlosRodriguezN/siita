<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>
<?php foreach( $this->items as $i => $item ): ?>
    <tr class="row<?php echo $i % 2; ?>">
        <td class = "center" style = "width: 50px;">
            <!--Muestra la opcion para publicar y despublicar-->
            <?php echo JHtml::_( 'jgrid.published', $item->published, $i, 'contratos.', true, 'cb' );
            ?>
        </td>   
        <td >
            <a href="<?php echo $item->url; ?>">
                <?php echo ucwords( $item->strTitulo_tma ); ?></a>
        </td>
        <td >
            <?php echo ucwords( $item->strResumen_tma ); ?>
        </td>

        <td >
            <?php 
            switch ( $item->intId_ni ) {
                case 1:
                    echo ucwords( "Alto" ); 
                    break;
                case 2:
                    echo ucwords( "Medio" ); 
                    break;
                case 3:
                    echo ucwords( "Bajo" ); 
                    break;
                default :
                    echo "-----"; 
                    break;
                    
            }
            ?>
        </td>

    </tr>
<?php endforeach; ?>