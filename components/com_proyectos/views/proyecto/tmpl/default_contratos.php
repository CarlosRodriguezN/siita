<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div id="content-box">
    <div id="element-box">
        <div class="m">
            <table class="adminlist">
                <thead> 
                    <tr>
                        <th> <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?> </th>
                        <th> <?php echo JHtml::_('grid.sort', 'COM_PROYECTOS_GESTION_FIELD_CONTRATOS_LABEL', 'nombreCnv', $listDirn, $listOrder); ?> </th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php if( !empty( $this->lstContratos ) ):?>
                    
                    <?php foreach ($this->lstContratos as $i => $item): ?>
                        <tr class="row<?php echo $i % 2; ?>">

                            <td class="center" style = "width: 50px;">
                                <!-- Muestra la opcion para publicar y despublicar -->
                                <?php echo JHtml::_('jgrid.published', $item->published, $i, 'unidadesgestion.', true, 'cb'); ?>
                            </td>

                            <td>
                                <a href="<?php echo JRoute::_('index.php?option=com_contratos&view=contrato&layout=edit&intIdContrato_ctr=' . $item->idContrato) ?>">
                                    <?php echo $item->nombreCtr ?>
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_SINREGISTROS_TITLE' ); ?> </td>
                        </tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

