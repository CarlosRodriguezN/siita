<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">
        <td >
            <?php echo ucwords($item->strNombre); ?>
        </td>
        <td >
            <?php echo ucwords($item->strCopyright); ?>
        </td>

        <td >
            <?php echo ucwords($item->strDescripcion); ?>
        </td>
        <td align="center" >
            
            <a  class="modal" href="<?php echo JURI::root() ?>images/stories/mapImages/<?php echo $item->intCodigo_shp_ecorae . ".png"; ?>"> 
                <img style="width: 40xp; 
                     height: 50px" 
                     src="<?php echo JURI::root() ?>images/stories/mapImages/<?php echo $item->intCodigo_shp_ecorae . ".png"; ?>"
                     >
            </a>
        </td>
        <td align="center" >
            <a href="<?php echo JURI::root() . "images/stories/mapShapes/" . ucwords($item->intCodigo_shp_ecorae) . ".zip" ?>"  title = "Descargar <?php echo ucwords($item->id); ?>Shape">Descargar <?php  echo ucwords($item->strNombre) ?> </a>
        </td>


    </tr>
<?php endforeach; ?>