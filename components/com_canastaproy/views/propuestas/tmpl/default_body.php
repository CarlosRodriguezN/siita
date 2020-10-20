<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">
        
        <td class="center">
            <!-- Muestra la opcion para publicar y despublicar -->
            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'propuestas.', true, 'cb'); ?>
        </td>

        <td>
            <a href=" <?php echo $item->url; ?>">
                <?php echo ucwords($item->strNombre_cp); ?>
            </a>
        </td>

        <td>
            <?php echo ucwords($item->strNombre_ins); ?>
        </td>

        <td>
            <?php echo '$ ' . number_format($item->dcmMonto_cp, 2, ',', '.') . ' Usd.'; ?>
        </td>

        <td>
            <?php echo ucwords($item->intNumeroBeneficiarios); ?>
        </td>

        <td>
            <?php echo ucwords($item->strDescripcion_estado); ?>
        </td>

        <td>
            <?php echo ucwords($item->dteFechaRegistro_cp); ?>
        </td>

    </tr>
<?php endforeach; ?>
