<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div class="m">
    <div id="lstPrgFnc">
        <?php if( !empty( $this->lstProgramas ) ): ?>
            <table class="adminlist">
                <thead> 
                    <tr>
                        <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?> </th>
                        <th> <?php echo JHtml::_('grid.sort', 'COM_FUNCIONARIOS_FIELD_NOMBRE_ENTIDAD_LABEL', 'nombrePrg', $listDirn, $listOrder); ?> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->lstProgramas as $i => $item): ?>
                        <tr class="row<?php echo $i % 2; ?>">

                            <td class="center" style = "width: 50px;">
                                <!-- Muestra la opcion para publicar y despublicar -->
                                <?php echo JHtml::_('jgrid.published', $item->published, $i, 'funcionarios.', true, 'cb'); ?>
                            </td>

                            <td>
                                <a href="<?php echo JRoute::_( 'index.php?option=com_programa&view=programa&layout=edit&intCodigo_prg=' . $item->idPrograma ) ?>">
                                    <?php echo ($item->nombrePrg != '') ? $item->nombrePrg : 'Sin descripción'; ?>
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?> 
            <div align="center"><p><?php echo JText::_( 'COM_FUNCIONARIOS_SIN_REGISTROS' ); ?></p></div>
        <?php endif; ?>
    </div>
        
</div>


