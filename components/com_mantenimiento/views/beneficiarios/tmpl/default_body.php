<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
            <td>
                <!-- JHtml::_ es una función de ayuda capaz de mostrar varias salidas HTML -->
                <!-- Creo un check box el cual esta tiene como identificador del mismo el codigo de la actividad -->
                <?php echo JHtml::_( 'grid.id', $i, $item->intCodigo_ben ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->strDescripcion_ben ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->strDescripcion_dim ); ?>
            </td>

            <td>
                <?php echo ucwords( $item->strDescripcion_td ); ?>
            </td>
            
            <td class="center">
                <!-- Muestra la opcion para publicar y despublicar -->
                <?php echo JHtml::_( 'jgrid.published', $item->published, $i, 'beneficiarios.', true, 'cb' ); ?>
            </td>
	</tr>
<?php endforeach;?>
