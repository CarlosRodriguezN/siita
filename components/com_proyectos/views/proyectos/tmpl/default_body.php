<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
            <td class="center">
                <!-- Muestra la opcion para publicar y despublicar -->
                <?php echo JHtml::_( 'jgrid.published', $item->published, $i, 'proyectos.', true, 'cb' ); ?>
            </td>
            
            <td>
                <a href="<?php echo $item->url; ?>">
                    <?php echo ucwords( $item->strNombre_pry ); ?>
                </a>
            </td>
            
            <td>
                <?php echo ucwords( $item->strNombre_prg ); ?>
            </td>
            
            <td align="center">
                <?php echo ucwords( $item->dteFechaInicio_stmdoPry ); ?>
            </td>
            
            <td align="center">
                <?php echo ucwords( $item->dteFechaFin_stmdoPry ); ?>
            </td>
            
            <td>
                <?php echo '$ '. number_format( $item->dcmMonto_total_stmdoPry, 2, ',', '.' ) .' Usd.' ?>
            </td>
	</tr>
<?php endforeach; ?>
