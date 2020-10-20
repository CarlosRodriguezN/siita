<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
            <td>
                <!-- JHtml::_ es una función de ayuda capaz de mostrar varias salidas HTML -->
                <!-- Creo un radioButton el cual tiene el identificador de la Linea Base -->
                <?php   $entry1 = new stdClass();
                        $entry1->value = $item->idTipoPlanificacion;
                        echo JHtml::_( 'select.radiolist', array( $entry1 ), 'idTipoPlanificacion' );
                ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->tipoPlanificacion ); ?>
            </td>
	</tr>
<?php endforeach; ?>

<div>
    <input type="hidden" id="idProyecto" name="task" value="<?php echo $this->_idProyecto; ?>" />
    <input type="hidden" id="idDimension" name="task" value="<?php echo $this->_idDimension; ?>" />
    <input type="hidden" id="idEnfoque" name="task" value="<?php echo $this->_idEnfoque; ?>" />
</div>