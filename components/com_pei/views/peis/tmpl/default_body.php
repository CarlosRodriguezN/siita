<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
<tr class="row<?php echo $i % 2;?>">

        <td class = "center" style = "width: 50px;" >
            <!-- Muestra la opcion para publicar y despublicar -->
            <?php echo JHtml::_( 'jgrid.published', $item->published, $i, 'peis.', true, 'cb' ); ?>
        </td>

        <td>
            <?php if( $this->canDo->get( 'core.edit' ) ): ?>
                <a href=" <?php echo $item->url; ?>" title="<?php echo JText::_( 'COM_PEI_EDITAR' ); ?>">
                    <?php echo ucwords( ( $item->strDescripcion_pi )? $item->strDescripcion_pi : "Sin descripción" ); ?>
                </a>
            <?php else:?>
                <?php echo ucwords( ( $item->strDescripcion_pi )? $item->strDescripcion_pi : "Sin descripción" ); ?>
            <?php endif;?>
        </td>

        <td>
            <?php echo ucwords($item->strAlias_pi); ?>
        </td>

        <td class="center" style = "width: 50px;" >
            <div id="ingVigencia-<?php echo $item->intId_pi; ?>">
                <a href="javascript:vigenciaPei(<?php echo $item->intId_pi . ", " . $item->blnVigente_pi ?>)" > 
                    <?php if ($item->blnVigente_pi == 0) { ?>
                        <img src = "media/system/images/siitaGestion/btnIndicadores/atributo/attrRojoSmall.png" title="PEI no vigente" >  
                    <?php } else { ?>
                        <img src = "media/system/images/siitaGestion/btnIndicadores/atributo/attrVerdeSmall.png" title="PEI vigente" >  
                    <?php } ?>
                </a>
            </div>
        </td>

    </tr>
<?php endforeach; ?>