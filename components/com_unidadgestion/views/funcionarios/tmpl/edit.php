<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.formvalidation');
JHTML::_('behavior.modal');
?>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">
            <h2> 
                <?php echo JText::_('COM_UNIDAD_GESTION_DETALLES_FUNCIONARIO'); ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    
    <!-- Div/Tab de Unidad de Gestión -->
    <div id="detallesFnc" style="position: static; left: 20px; height: auto; width: 100%">
        <ul>
            <li><a href="#fncActividades" > <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_FNC_ACT_TITLE') ?></a></li>
            <li><a href="#fncPlanAccion" > <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_FNC_ACC_TITLE')  ?></a></li>
            <li><a href="#fncIndicadores" > <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_FNC_IND_TITLE') ?></a></li>
            <li><a href="#fncData" > <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_UG_DATOSGRLS_TITLE')  ?></a></li>
        </ul>

        <!--Panel de control de una unidad de gestion--> 
        <div class="m" id="fncData">
            <?php echo $this->loadTemplate('datafuncionario'); ?>
        </div>
        
        <!--Panel de control de una unidad de gestion--> 
        <div class="m" id="fncPlanAccion">
            <?php echo $this->loadTemplate('planaccion'); ?>
        </div>

         <!--Registro de los datos generales de un nuevo plan--> 
        <div class="m" id="fncIndicadores">
            <?php echo $this->loadTemplate('indicadores'); ?>
        </div>

        <!-- Registro de los datos generales de un nuevo plan -->
        <div class="m" id="fncActividades">
            <?php echo $this->loadTemplate('actividades'); ?>
        </div>
    </div>

    <div>
        <input type="hidden" name="task" value="funcionario.detalles" />
        <input type="hidden" id="idFncUG" name="idFncUG" value="<?php echo $this->idFncUG ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
    
</div>