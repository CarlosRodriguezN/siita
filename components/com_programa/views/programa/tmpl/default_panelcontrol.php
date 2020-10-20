<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>


<?php if ($this->items): ?>
    <div id="accordion">
        <?php foreach ($this->items as $item): ?>
            <div class="group">
                <h3> <a href="#">  <?php echo $item["nombre"]; ?> </a> </h3>
                <div class="adminform">
                    <div class="cpanel-page">
                        <div class="cpanel">
                            <?php if (!empty($item["lstIndicadores"])): ?>
                                <?php foreach ($item["lstIndicadores"] as $indicador): ?>
                                    <div class="icon-wrapper">
                                        <div class="icon">
                                            <a href="#">
                                                <img src="media/system/images/siitaGestion/chart_default.png" alt="<?php echo $indicador->descripcion; ?>">
                                                <span> <?php echo $indicador->nombreIndicador; ?> </span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php echo '<p>Sin Registros</p>'; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="clr"> &nbsp; </div>
<?php else: ?>
    <div class="m" align="center"><p><?php echo JText::_( 'COM_PROGRAMA_SIN_REGISTROS' ); ?></p></div>
<?php endif; ?>

