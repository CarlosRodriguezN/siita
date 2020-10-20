 <fgqwertyu987<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
            <td>
                <?php echo ucwords( $item->nombreIndicador ); ?>
            </td>
            
            <td>
                <?php echo ucwords( $item->Proyecto ); ?>
            </td>
            
            <td align="center">
                <?php echo ucwords( $item->descripcion ); ?>
            </td>
            
            <td>
                <?php echo number_format( $item->umbral, 2, ',', '.' ) ?>
            </td>
            
            <td align="center">
                <?php echo ucwords( $item->formula ); ?>
            </td>
            
            <!-- Opcion para exportar a PDF -->
            <td align="center">
                <a href="<?php echo $item->url; ?>">
                    <?php echo JText::_( 'EXPORT_TO_PDF' ); ?>
                </a>
            </td>
            
            <!-- Opcion para exportar a Excel -->
            <td align="center">
                <a href="<?php echo JRoute::_('index.php?option=com_proyectos&amp;view=reportetoexcel&amp;layout=edit&amp;task=reportetoexcel.excel&amp;idIndEntidad='. $item->idIndEntidad ); ?>">
                    <?php echo JText::_( 'EXPORT_TO_EXCEL' ); ?>
                </a>
            </td>
	</tr>
<?php endforeach; ?>
