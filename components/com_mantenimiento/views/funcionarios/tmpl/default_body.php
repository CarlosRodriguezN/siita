<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
            <td>
                <!-- JHtml::_ es una función de ayuda capaz de mostrar varias salidas HTML -->
                <!-- Creo un check box el cual esta tiene como identificador del mismo el codigo de la actividad -->
                <?php echo JHtml::_( 'grid.id', $i, $item->intCodigo_fnc ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->strApellido_fnc ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->strNombre_fnc ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->strCorreoelec_fnc ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->strTelefono_fnc ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->strCi_fnc ); ?>
            </td>
            
            <td class="center">
                <!-- Muestra la opcion para publicar y despublicar -->
                <?php echo JHtml::_( 'jgrid.published', $item->published, $i, 'funcionarios.', true, 'cb' ); ?>
            </td>
	</tr>
<?php endforeach;?>
