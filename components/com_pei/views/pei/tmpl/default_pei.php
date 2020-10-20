<?php
    // No direct access to this file
    defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<div class="m">
    <div id="accordion">
        <?php if( !empty( $this->items ) ): ?>
            <?php foreach( $this->items as $item ): ?>
                <div class="group">
                    <h3> <a href="#"> <?php echo $item->descObjetivo; ?> </a> </h3>
                    <div class="adminform">
                        <div class="cpanel-page">
                            <div class="cpanel">
                                <?php if( !empty( $item->lstIndicadores ) ): ?>
                                    <?php foreach( $item->lstIndicadores as $indicador ): ?>
                                        <div class="icon-wrapper">
                                            <div class="icon">
                                                <a class="info" href="<?php echo JRoute::_( 'option=com_pei&view=pei&layout=edit&intId_pi=1' ) ?>" title="<?php echo JText::_( 'COM_PANEL_PEI_LINK' ); ?>">
                                                    <img src="<?php echo JText::_( 'COM_PEI_IMG_DEFAULT' ); ?>" alt="">
                                                    <span> <?php echo $indicador["nombreIndicador"]; ?> </span>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?php JText::_( 'COM_PEI_SIN_REGISTROS' ); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div align="center"><p><?php echo JText::_( 'COM_PEI_SIN_REGISTROS' ); ?></p></div>
        <?php endif; ?>
    </div>
</div>
