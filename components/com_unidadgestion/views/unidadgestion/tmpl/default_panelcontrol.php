<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div class="m">
    <div id="accUG">
        <?php if( !empty( $this->items ) ): ?>
            <?php foreach ($this->items as $item): ?>
                <div class="group">
                    <h3> <a href="#">  <?php echo $item->descObjetivo; ?> </a> </h3>
                    <div class="adminform">
                        <div class="cpanel-page">
                            <div class="cpanel">
                                <?php if (!empty($item->lstIndicadores)): ?>
                                    <?php foreach ($item->lstIndicadores as $indicador): ?>
                                        <div class="icon-wrapper">
                                            <div class="icon">
                                                <a href="#" alt="<?php echo $indicador["descripcion"]; ?>"> 
                                                    <img src="media/system/images/siitaGestion/chart_default.png" alt=""> 
                                                    <span> <?php echo $indicador["nombreIndicador"]; ?> </span>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?php echo JText::_( 'COM_UNIDAD_GESTION_SIN_REGISTROS' ); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>    
                </div>
            <?php endforeach; ?>
        <?php else:?>
            <div align="center"><p><?php echo JText::_( 'COM_UNIDAD_GESTION_SIN_REGISTROS' ); ?></p></div>
        <?php endif; ?>
    </div>
</div>

