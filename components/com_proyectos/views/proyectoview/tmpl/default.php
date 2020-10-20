<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div class="m">
    <br>
    <div class="admin">
        <?php if ($this->proyecto->idPrograma): ?>
            <h3>  <a href="index.php?option=com_programa&view=programaview&idPrograma=<?php echo $this->proyecto->idPrograma ?>" 
                     title="<?php echo $this->proyecto->nomPrograma ?>"
                     >
                    <?php echo $this->proyecto->prgAlias . " /" ?></a></h3>
            <br>
        <?php endif; ?>
        <h1><?php echo $this->proyecto->nomProyecto ?></h1>
    </div>
    <br>
    <div id="tabs">
        <ul>
            <li><a href="#datosGenerales"> <?php echo JText::_('COM_PROYECTO_VIEW_DATOSGRLS_TITLE') ?></a></li>
            <li><a href="#indicadores"> <?php echo JText::_('COM_PROYECTO_VIEW_INDICADORES_TITLE') ?></a></li>
            <li><a href="#variables"> <?php echo JText::_('COM_PROYECTO_VIEW_VARIABLES_TITLE') ?></a></li>
        </ul> 
        <div id="datosGenerales" style="height: 430px">
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