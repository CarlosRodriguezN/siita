<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
<tr class="row<?php echo $i % 2;?>">

        <td class = "center" style = "width: 50px;" >
            <!-- Muestra la opcion para publicar y despublicar -->
            <?php echo JHtml::_( 'jgrid.published', $item->published, $i, 'listaurl.', true, 'cb' ); ?>
        </td>

        <td>

            <a href="<?php echo $item->url; ?>">
                <?php echo ucwords( ( $item->institucion )  ? $item->institucion 
                                                            : "Sin descripción" ); ?>
            </a>
            
        </td>

        <td>
            <?php echo $item->token; ?>
        </td>

        <td class="center" style = "width: 50px;" >
            <div id="ingVigencia-<?php echo $item->id; ?>">
                <a id="<?php echo $item->idUrl ?>" class="apiVigencia" >
                    <?php if ($item->vigente == 0): ?>
                        <img src = "media/system/images/siitaGestion/btnIndicadores/atributo/attrRojoSmall.png" title="<?php echo JText::_( 'COM_APIREST_FIELD_NO_VIGENTE_LABEL' ). $item->institucion;?>" >
                    <?php else: ?>
                        <img src = "media/system/images/siitaGestion/btnIndicadores/atributo/attrVerdeSmall.png" title="<?php echo JText::_( 'COM_APIREST_FIELD_VIGENTE_LABEL' ). $item->institucion;?>" >
                    <?php endif; ?>
                </a>
            </div>
        </td>

    </tr>
<?php endforeach; ?>