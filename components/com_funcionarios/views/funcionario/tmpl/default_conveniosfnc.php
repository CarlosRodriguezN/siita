<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div class="m">
    <div id="lstCnvFnc">
        <?php if( !empty( $this->lstConvenios ) ): ?>
            <table class="adminlist" >
                <thead> 
                    <tr>
                        <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?> </th>
                        <th> <?php echo JHtml::_('grid.sort', 'COM_FUNCIONARIOS_FIELD_NOMBRE_ENTIDAD_LABEL', 'nombreCnv', $listDirn, $listOrder); ?> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->lstConvenios as $i => $item): ?>
                        <tr class="row<?php echo $i % 2; ?>">

                            <td class="center" style = "width: 50px;">
                                <!-- Muestra la opcion para publicar y despublicar -->
                                <?php echo JHtml::_('jgrid.published', $item->published, $i, 'unidadesgestion.', true, 'cb'); ?>
                            </td>

                            <td>
                                <a href="<?php echo JRoute::_('index.php?option=com_contratos&view=contrato&layout=edit&intIdContrato_ctr=' . $item->idConvenio) ?>">
                                    <?php echo $item->nombreCnv ?>
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
