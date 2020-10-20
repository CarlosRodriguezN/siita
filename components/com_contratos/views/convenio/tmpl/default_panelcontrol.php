<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>


<div id="accordion">
    <?php $item = $this->item; ?>
        <div class="group">
            <h3> <a href="#">  <?php echo $item->strDescripcion_ctr; ?> </a> </h3>
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
                            <?php echo '<p>Sin Registros</p>'; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
</div>

<div class="clr"> &nbsp; </div>
