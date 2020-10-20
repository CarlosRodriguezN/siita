<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHTML::_('behavior.modal');

$numInds = 0;
if( !empty($this->items) ){
    foreach( $this->items as $item ){
        if( !empty($item->lstIndicadores) ){
            foreach( $item->lstIndicadores as $indicador ){
                $numInds ++;
            }
        }
    }
}

if( $this->_fchInicio != '' && $this->_fchFin != '' ){
    $msm = JText::_('COM_PEI_PLAN_EDITING') . ' / ' . date('Y', strtotime($this->_fchInicio)) . ' - ' . date('Y', strtotime($this->_fchFin));
} else{
    $msm = JText::_('COM_PEI_PLAN_EDITING');
}
?>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">
            <h2> 
                <?php echo $msm; ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <div class="m">
        <form action="<?php echo JRoute::_('index.php?option=com_pei&layout=edit&intId_pi=' . (int)$this->item->intId_pi); ?>" method="post" name="adminForm" class="form-validate">

            <!-- Div/Tab de Plan Estratégico Institucional -->
            <div id="tabsPlaEstIns" style="position: static; left: 20px; height: auto; width: 100%">
                <ul>
                    <li><a href="#peiPanelControl" title="<?php echo JText::_('COM_UNIDAD_GESTION_FIELD_UG_PANEL_CONTROL_DESC'); ?>"> <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_UG_PANEL_CONTROL_TITLE') . ' (' . $numInds . ')' ?></a></li>
                    <li><a href="#peiDatosGenerales" title="<?php echo JText::_('COM_PEI_FIELD_PLAN_DATOSGRLS_PEI_DESC'); ?>"> <?php echo JText::_('COM_PEI_FIELD_PLAN_DATOSGRLS_PEI_TITLE') ?></a></li>
                    <li><a href="#peiObjetivos" title="<?php echo JText::_('COM_PEI_FIELD_PLAN_OBJETIVOS_PEI_TITLE'); ?>"> <?php echo JText::_('COM_PEI_FIELD_PLAN_OBJETIVOS_PEI_TITLE') . ' (' . count($this->lstObjetivos) . ')' ?></a></li>
                    <li><a href="#PPPP" id="pPPPP" title="<?php echo JText::_('COM_PEI_FIELD_PROGRAMA_PLURIANUAL_POLITICA_PUBLICA_DESC'); ?>"> <?php echo JText::_('COM_PEI_FIELD_PROGRAMA_PLURIANUAL_POLITICA_PUBLICA_TITLE') ?></a></li>
                    <li><a href="#peiContextos" title="<?php echo JText::_('COM_PEI_FIELD_PLAN_CONTEXTOS_DESC'); ?>"> <?php echo JText::_('COM_PEI_FIELD_PLAN_CONTEXTOS_DESC') ?></a></li>
                </ul>

                <!-- Registro de los datos generales de un nuevo plan -->
                <div class="m" id="peiPanelControl">
                    <?php echo $this->loadTemplate('pei'); ?>
                </div>

                <!-- Registro de los datos generales de un nuevo plan -->
                <div class="m" id="peiDatosGenerales">
                    <?php echo $this->loadTemplate('dtageneral'); ?>
                </div>

                <!-- Registro de objetivos y aciones de un nuvo plan estratégico institucional -->
                <div class="m" id="peiObjetivos">
                    <?php echo $this->loadTemplate('objetivos'); ?>
                </div>

                <!-- Planificacion Plurianual de la Politica Publica -->
                <div class="m" id="PPPP">
                    <?php echo $this->loadTemplate('pppp'); ?>
                </div>
                
                <!-- Gestion de Contextos --> 
                <div class="m" id="peiContextos">
                    <?php echo $this->loadTemplate('contextos'); ?>
                </div>
            </div>


            <div>
                <input type="hidden" name="task" value="pei.edit" />
                <input type="hidden" id="idInstitucion" name="idInstitucion" value="" />
                <input type="hidden" id="idTpoPlan" name="idTpoPlan" value="" />
                <input type="hidden" id="dtaContextos" name="dtaContextos" value="<?php echo htmlspecialchars($this->_lstContextos); ?>" />
                <input type="hidden" id="oldFchInicio" name="oldFchInicio" value="<?php echo $this->_fchInicio; ?>" />
                <input type="hidden" id="oldFchFin" name="oldFchFin" value="<?php echo $this->_fchFin; ?>" />
                <input type="hidden" id="dtaRoles" name="dtaRoles" value="<?php print htmlspecialchars( json_encode( $this->canDo ) ); ?>" />

                <?php echo JHtml::_('form.token'); ?>
            </div>

        </form>
    </div>
</div>

<?php echo $this->loadTemplate('progressblock'); ?>