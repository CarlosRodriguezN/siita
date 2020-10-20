<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
   <tr class="row<?php echo $i % 2; ?>">
      <td>
         <!-- JHtml::_ es una función de ayuda capaz de mostrar varias salidas HTML -->
         <!-- Creo un check box el cual esta tiene como identificador del mismo el codigo de la actividad -->
         <?php echo JHtml::_('grid.id', $i, $item->intCodigo_per); ?>
      </td>
      <td>
         <?php echo trim($item->strDescripcion_per); ?>
      </td>
      <td>
         <?php echo trim($item->idFecha); ?>
      </td>
      <td class="center">
         <!-- Muestra la opcion para publicar y despublicar -->
         <?php echo JHtml::_('jgrid.published', $item->published, $i, 'periodicidades.', true, 'cb'); ?>
      </td>
   </tr>
<?php endforeach; ?>