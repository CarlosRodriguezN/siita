<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div class="m" >
    <br>
    <div class="admin">
        <h1><?php echo $this->general->strNombre_prg . " ( " . $this->general->strAlias_prg . " )" ?></h1>
    </div>
    <br>
    <div id="tabs" class="adminform">
        <div class="clr"></div>
        <ul>
            <li><a href="#datosGenerales"> <?php echo JText::_('COM_PROGRAMA_VIEW_DATOSGRLS_TITLE') ?></a></li>
            <li><a href="#indicadores"> <?php echo JText::_('COM_PROGRAMA_VIEW_INDICADORES_TITLE') ?></a></li>
            <li><a href="#variables"> <?php echo JText::_('COM_PROGRAMA_VIEW_VARIABLES_TITLE') ?></a></li>
        </ul> 
        <!-- Datos Generales-->
        <div id="datosGenerales">
            <?php echo $this->loadTemplate('general'); ?>
        </div>  
        <div id="indicadores" style="height: 430px">
            <?php echo $this->loadTemplate('indicadores'); ?>
        </div>  
        <div id="variables" style="height: 430px">
            <?php echo $this->loadTemplate('variables'); ?>
        </div>  
    </div>
    <br>
</div>