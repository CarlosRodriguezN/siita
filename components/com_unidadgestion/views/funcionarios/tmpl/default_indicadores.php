<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100">
    <div id="indFnc">
        <?php if( !empty( $this->lstObjetivos ) ): ?>
            <?php foreach ($this->lstObjetivos as $item): ?>
                <div class="group">
                    <h3> <a href="#">  <?php echo $item->descObjetivo . " (" . count($item->lstIndicadores) . ")"; ?> </a> </h3>
                    <div class="adminform">
                        <div class="cpanel-page">
                            <div class="cpanel">
                                <?php if (!empty($item->lstIndicadores)): ?>
                                    <?php foreach ($item->lstIndicadores as $indicador): ?>
                                        <div class="icon-wrapper">
                                            <a href="#" alt="<?php echo $indicador["descripcion"]; ?>"> 
                                                <?php echo $indicador->accesoTableu; ?>
                                            </a>
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