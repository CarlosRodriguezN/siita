<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<?php if ($this->item->intIdContrato_ctr): ?>
    <div id="accordion">
        <div class="group">
            <h3> <a href="#">  <?php echo $this->item->strDescripcion_ctr; ?> </a> </h3>
            <div class="adminform">
                <div class="cpanel-page">
                    <div class="cpanel">
                        <?php if (!empty($this->lstIndicadores)): ?>
                            <?php foreach ($this->lstIndicadores as $indicador): ?>
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
                            <?php echo JText::_( 'COM_CONTRATO_SIN_REGISTROS' ); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clr"> &nbsp; </div>
<?php else: ?>
    <div align="center"><p><?php echo JText::_( 'COM_CONTRATO_SIN_REGISTROS' ); ?></p></div>
<?php endif; ?>

