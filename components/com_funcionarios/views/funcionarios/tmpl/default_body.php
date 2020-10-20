<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php $ug = $this->lstFncUg[$this->idUg]->lstaFncUG; ?>
<?php foreach ($ug as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">

        <td>
            <a href=" <?php echo $item->url; ?>">
                <?php echo ($item->CIFnc) ? $item->CIFnc : "Sin descripción"; ?>
            </a>
        </td>

        <td>
            <?php echo ucwords(($item->apellidosFnc)? $item->apellidosFnc : "-----"); ?>
        </td>

        <td>
            <?php echo ucwords(($item->nombresFnc) ? $item->nombresFnc : "-----") ; ?>
        </td>

        <td>
            <?php echo ($item->correoFnc) ? $item->correoFnc : "-----"; ?>
        </td>

        <td>
            <?php echo ($item->telefonoFnc) ? $item->telefonoFnc : "-----"; ?>
        </td>

        <td>
            <?php echo ($item->celularFnc) ? $item->celularFnc : "-----"; ?>
        </td>

    </tr>
<?php endforeach; ?>