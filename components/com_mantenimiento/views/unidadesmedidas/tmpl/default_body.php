<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
            <td>
                <!-- JHtml::_ es una función de ayuda capaz de mostrar varias salidas HTML -->
                <!-- Creo un check box el cual esta tiene como identificador del mismo el codigo de la actividad -->
                <?php echo JHtml::_( 'grid.id', $i, $item->intCodigo_unimed ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->strCodigo_unimed ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->strDescripcion_unimed ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->strDescripcion_tum ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->strSimbolo_unimed ); ?>
            </td>
            
            <td class="center">
                <!-- Muestra la opcion para publicar y despublicar -->
                <?php echo JHtml::_( 'jgrid.published', $item->published, $i, 'unidadmedida.', true, 'cb' ); ?>
            </td>
	</tr>
<?php endforeach;?>
