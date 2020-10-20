<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
            <td>
                <!-- JHtml::_ es una función de ayuda capaz de mostrar varias salidas HTML -->
                <!-- Creo un check box el cual esta tiene como identificador del mismo el codigo de la actividad -->
                <?php echo JHtml::_( 'grid.id', $i, $item->idLineaBase ); ?>
                
            </td>
            
            <td>
                <?php echo ucwords( $item->descripcion ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->valor ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->fuente ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->periodicidad ); ?>
            </td>

            <td>
                <?php echo ucwords( $item->institucion ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->fchInicio ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->fchFin ); ?>
            </td>

	</tr>
<?php endforeach; ?>

<div>
    <input type="hidden" id="idProyecto" name="task" value="<?php echo $this->_idProyecto; ?>" />
    <input type="hidden" id="idDimension" name="task" value="<?php echo $this->_idDimension; ?>" />
    <input type="hidden" id="idEnfoque" name="task" value="<?php echo $this->_idEnfoque; ?>" />
</div>