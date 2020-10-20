<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php $ugLstCrg = $this->lstCargosUg[$this->idUgReg]->lstCargosUG; ?>
<?php if( count($ugLstCrg) > 0 ): ?>
<?php foreach ($ugLstCrg as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">

        <td class="center" style="width: 20px;">
            <!-- Muestra la opcion para publicar y despublicar -->
            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'cargosfnc.', true, 'cb'); ?>
        </td>

        <td>
            <a href=" <?php echo $item->url . '&amp;id=' . $item->intCodigo_ug . '&amp;grupoCrg=' . $item->intIdGrupo_cargo . '&amp;tmpl=component&amp;task=preview'; ?>" class="modal" rel="{handler: 'iframe', size: {x:1024, y:500}}">
                <?php 
                    $nombre = ( $item->strDescripcion_cargo ) ? $item->strDescripcion_cargo : '-----';
                    echo ucwords($nombre); 
                ?>
            </a>
        </td>
        
        <td class="center" style="width: 20px;" >
            <?php echo JText::_('COM_MANTENIMIENTO_ELIMINAR')?>
        </td>
        
    </tr>
<?php endforeach; ?>
<?php endif; ?>
