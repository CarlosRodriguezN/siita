<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">
        <td class = "center" style = "width: 50px;">
            <!--Muestra la opcion para publicar y despublicar-->
            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'mapas.', true, 'cb');
            ?>
        </td>   

        <td>
            <a href="<?php echo $item->url; ?>">
                <?php echo ucwords($item->strNombre); ?></a>
        </td>
        <td >
            <?php echo ucwords($item->strCopyright); ?>
        </td>

        <td >
            <?php echo ucwords($item->strDescripcion); ?>
        </td>
        <td >
            <a  class="modal" href="<?php echo JURI::root() ?>images/stories/mapImages/<?php echo $item->intCodigo_shp_ecorae . ".png"; ?>"> 
                <img style="width: 40xp; 
                     height: 50px" 
                     src="<?php echo JURI::root() ?>images/stories/mapImages/<?php echo $item->intCodigo_shp_ecorae . ".png"; ?>"
                     >
            </a>
        </td>
        <td >
            <a href="<?php echo JURI::root() . "images/stories/mapShapes/" . ucwords($item->intCodigo_shp_ecorae) . ".zip" ?>"  title = "Descargar <?php echo ucwords($item->id); ?>Shape">Descargar archivo</a>
        </td>


    </tr>
<?php endforeach; ?>