<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">

        <td class="center" >
            <!-- Muestra la opcion para publicar y despublicar -->
            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'poas.', true, 'cb'); ?>
        </td>

        <td>
            <a href=" <?php echo $item->url; ?>">
                <?php echo ucwords($item->strDescripcion_pi); ?>
            </a>
        </td>

        <td>
            <?php echo ucwords($item->strAlias_pi); ?>
        </td>

        <td class="center" >
            <div id="ingVigencia-<?php echo $item->intId_pi; ?>">
                <a href="javascript:vigencia(<?php echo $item->blnVigente_pi . ", " . $item->intId_pi . ", " . $item->published?>)" > 
                    <?php if ($item->blnVigente_pi == 0) { ?>
                        <img src = "images/ico_facebook.png" title="POA no vigente">  
                    <?php } else { ?>
                        <img src = "images/ico_twitter.png" title="POA vigente">  
                    <?php } ?>
                </a>
            </div>
        </td>

    </tr>
<?php endforeach; ?>