<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div class="m">
    <br>
    <div class="admin">
        <?php if ($this->contratoData): ?>
            <h3>  <a href="index.php?option=com_programa&view=programaview&idPrograma=<?php echo $this->contratoData->idPrograma ?>" 
                     title="<?php echo $this->contratoData->prgNombre ?>"
                     ><?php echo $this->contratoData->prgAlias . " /" ?></a>

                <a href="index.php?option=com_proyectos&view=proyectoview&idPrograma=<?php echo $this->contratoData->idPrograma ?>&idProyecto=<?php echo $this->contratoData->intCodigo_pry ?>" 
                   title="<?php echo $this->contratoData->intCodigo_pry ?>"
                   ><?php echo $this->contratoData->intCodigo_pry . " /" ?></a>

            </h3>
            <br>
        <?php endif; ?>
        <h1><?php echo $this->proyecto->nomProyecto ?></h1>
    </div>
    <br>
    <div id="tabs">
        <ul>
            <li><a href="#datosGenerales"> <?php echo JText::_('COM_CONTRATO_VIEW_DATOSGRLS_TITLE') ?></a></li>
            <li><a href="#indicadores"> <?php echo JText::_('COM_CONTRATO_VIEW_INDICADORES_TITLE') ?></a></li>
            <li><a href="#variables"> <?php echo JText::_('COM_CONTRATO_VIEW_VARIABLES_TITLE') ?></a></li>
        </ul> 
        <!-- Datos Generales-->
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