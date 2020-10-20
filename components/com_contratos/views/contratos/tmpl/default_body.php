<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">
        <td class = "center" style = "width: 50px;">
            <!--Muestra la opcion para publicar y despublicar-->
            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'contratos.', true, 'cb'); ?>
        </td>   
        
        <!-- Nombre del contrato -->
        <td>
            <a href="<?php echo $item->url; ?>">
                <?php echo ($item->descripcionCtr) ? ucwords($item->descripcionCtr) : "-----"; ?>
            </a>
        </td>
        
        <!-- Cur del contrato -->
        <td >
            <?php echo ($item->curCtr) ? ucwords($item->curCtr) : "-----"; ?>
        </td>

        <!-- Monto de Gestion de contrato -->
        <td >
            <?php echo ($item->montoCtr) ? '$ '. number_format( $item->montoCtr, 2, ',', '.' ) : "-----"; ?>
        </td>

    </tr>
<?php endforeach; ?>