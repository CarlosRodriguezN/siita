<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
            <td>
                <!-- JHtml::_ es una función de ayuda capaz de mostrar varias salidas HTML -->
                <!-- Creo un check box el cual esta tiene como identificador del mismo el codigo de la actividad -->
            <?php echo JHtml::_('grid.id', $i, $item->intCodigo_ins); ?>
        </td>
        
        <td>
            <?php echo strip_tags(trim($item->strNombre_ins)); ?>
        </td>
        
        <td>
            <?php echo strip_tags(trim($item->strRuc_ins)); ?>
        </td>
        
        <td>
            <?php echo strip_tags(trim($item->strAlias_ins)); ?>
        </td>
        
        <td>
            <?php echo strip_tags(trim($item->strFuncionMandato_ins)); ?>
        </td>
        
        <td>
            <?php echo strip_tags(trim($item->strNumNorma_ins)); ?>
        </td>
        
        <td>
            <?php echo strip_tags(trim($item->strRegistroOficial_ins)); ?>
        </td>
        
        <td>
            <?php echo strip_tags(trim($item->dteFechaRegOficial)); ?>
        </td>
        
        <td>
            <?php echo strip_tags(trim($item->strMision)); ?>
        </td>
        
        <td>
            <?php echo strip_tags(trim($item->strVision)); ?>
        </td>
        
        <td>
            <?php echo strip_tags(trim($item->strObservacion_ins)); ?>
        </td>
        
        <td>
            <?php echo strip_tags(trim($item->strDescripcion_depins)); ?>
        </td>
        
        <td>
            <?php echo strip_tags(trim($item->strDescripcion_sec)); ?>
        </td>
        
        <td>
            <?php echo strip_tags(trim($item->strDescripcion_funcion)); ?>
        </td>
        
        <td>
            <?php echo strip_tags(trim($item->strDescripcion_norma)); ?>
        </td>
        
        <td>
            <?php echo strip_tags(trim($item->strDescripcionTipo_ins)); ?>
        </td>
            
            <td class="center">
                <!-- Muestra la opcion para publicar y despublicar -->
                <?php echo JHtml::_( 'jgrid.published', $item->published, $i, 'instituciones.', true, 'cb' ); ?>
            </td>
	</tr>
<?php endforeach;?>
