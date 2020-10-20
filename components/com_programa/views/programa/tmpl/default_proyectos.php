<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
$i = NULL;
?>

<div id="content-box">

    <div id="element-box">
        <div class="m">
            <table class="adminlist">
                <thead> 
                    <tr>
                        <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published'); ?> </th>
                        <th> <?php echo JHtml::_('grid.sort', 'COM_UNIDAD_GESTION_FIELD_UG_NOMBRE_LABEL', 'nombreCnv'); ?> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($this->lstProyectos): ?>
                        <?php foreach ($this->lstProyectos as $i => $item): ?>
                            <tr class="row<?php echo $i % 2; ?>">

                                <td class="center" style = "width: 50px;">
                                    <!-- Muestra la opcion para publicar y despublicar -->
                                    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'unidadesgestion.', true, 'cb'); ?>
                                </td>

                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_proyectos&view=proyecto&layout=edit&intCodigo_pry=' . $item->idProyecto) ?>">
                                        <?php echo ($item->nombrePry != '') ? $item->nombrePry : 'Sin descripción'; ?>
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                            <tr>
                                <td class="center" style = "width: 100%;" colspan="2">
                                    <strong> <?php echo JText::_( 'COM_PROGRAMA_SIN_REGISTROS' ); ?> </strong>
                                </td>
                            </tr>
                    <?php endif ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

