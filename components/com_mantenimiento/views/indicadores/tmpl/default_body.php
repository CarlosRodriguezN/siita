<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">

        <td class="center" style="width: 20px;">
            <!-- Muestra la opcion para publicar y despublicar -->
            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'indicadores.', true, 'cb'); ?>
        </td>

        <!-- Nombre del Indicador -->
        <td>
            <a href=" <?php echo $item->url; ?>">
                <?php echo ucwords($item->nombreIndicador ); ?>
            </a>
        </td>

        <!-- Descripcion del Indicador -->
        <td>
            <?php echo ucwords($item->descripcionIndicador ); ?>
        </td>

        <!-- Clase del Indicador -->
        <td>
            <?php echo ucwords($item->claseInd ); ?>
        </td>

        <!-- Unidad de Medida del Indicador -->
        <td>
            <?php echo ucwords($item->undMedida ); ?>
        </td>

        <!-- Unidad de Analisis del Indicador -->
        <td>
            <?php echo ucwords($item->undAnalisis ); ?>
        </td>

        <!-- Formula del Indicador -->
        <td>
            <?php echo ucwords($item->formula ); ?>
        </td>

        <!-- Fecha Modificacion del Indicador -->
        <td>
            <?php echo ucwords($item->fchModificacion ); ?>
        </td>

    </tr>
<?php endforeach; ?>
