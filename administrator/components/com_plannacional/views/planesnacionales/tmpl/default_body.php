<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
            <td>
                <!-- JHtml::_ es una función de ayuda capaz de mostrar varias salidas HTML -->
                <!-- Creo un check box el cual esta tiene como identificador del mismo el codigo de la actividad -->
                <?php echo JHtml::_( 'grid.id', $i, $item->INTCODIGO_PN ); ?>
            </td>
            
            <td>
                <?php echo $item->planNacional; ?>
            </td>
            
            <td>
                <?php echo $item->fchInicio; ?>
            </td>
            
            <td>
                <?php echo $item->fchFin; ?>
            </td>
            
            <td>
                <?php echo ( $item->vigencia == 1 ) ? JTEXT::_( 'COM_PLANNACIONAL_FIELD_PLANNACIONAL_VIGENTE' ) 
                                                    : JTEXT::_( 'COM_PLANNACIONAL_FIELD_PLANNACIONAL_NOVIGENTE' ); ?>
            </td>
            
            <td class="center">
                <!-- Muestra la opcion para publicar y despublicar -->
                <?php echo JHtml::_( 'jgrid.published', $item->published, $i, 'planesnacionales.', true, 'cb' ); ?>
            </td>
	</tr>
<?php endforeach; ?>