<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div class="m">
    <div id="lstPryUG">
        <?php if( !empty( $this->lstProyectos ) ): ?>
            <table class="adminlist">
                <thead> 
                    <tr>
                        <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?> </th>
                        <th> <?php echo JHtml::_('grid.sort', 'COM_UNIDAD_GESTION_FIELD_UG_NOMBRE_LABEL', 'nombrePry', $listDirn, $listOrder); ?> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->lstProyectos as $i => $item): ?>
                        <tr class="row<?php echo $i % 2; ?>">

                            <td class="center" style = "width: 50px;">
                                <!-- Muestra la opcion para publicar y despublicar -->
                                <?php echo JHtml::_('jgrid.published', $item->published, $i, 'unidadesgestion.', true, 'cb'); ?>
                            </td>

                            <td>
                                <a href="<?php echo JRoute::_( 'index.php?option=com_proyectos&view=proyecto&layout=edit&intCodigo_pry=' . $item->idProyecto ) ?>">
                                    <?php echo ($item->nombrePry != '') ? $item->nombrePry : 'Sin descripción'; ?>
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?> 
            <div align="center"><p><?php echo JText::_( 'COM_UNIDAD_GESTION_SIN_REGISTROS' ); ?></p></div>
        <?php endif; ?>
    </div>
</div>