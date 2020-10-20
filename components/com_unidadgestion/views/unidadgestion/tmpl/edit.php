<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHTML::_('behavior.modal');

$numInds = 0;
if( !empty( $this->items ) ) {
    foreach ($this->items as $item) {
        if (!empty($item->lstIndicadores)){
            foreach ($item->lstIndicadores as $indicador){
                $numInds ++;
            }
        }
    }
}

?>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">

            <h2> <?php if ($this->item->intCodigo_ug == null): ?>
                    <?php echo JText::_('COM_UNIDAD_GESTION_UG_CREATING'); ?>
                <?php else: ?>
                    <?php echo JText::_('COM_UNIDAD_GESTION_UG_EDITING') . $this->item->strNombre_ug; ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<div id="submenu-box" class="hide" >
    <div class="m">
        <ul id="submenu">
            
        </ul>
        <div class="clr"></div>
    </div>
</div>

<div id="element-box">
    <div class="m">
        <form action="<?php echo JRoute::_('index.php?option=com_unidadgestion&layout=edit&intCodigo_ug=' . (int) $this->item->intCodigo_ug); ?>" method="post" name="adminForm" id="unidadgestion-form">

            <!-- Div/Tab de Unidad de Gestión -->
            <div id="tabsUndGes" style="position: static; left: 20px; height: auto; width: 100%">
                <ul>
                    <li><a href="#ugPanelControl"> <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_UG_PANEL_CONTROL_TITLE') . ' (' . $numInds . ')' ?></a></li>
                    <li><a href="#ugDatosGenerales"> <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_UG_DATOSGRLS_TITLE') ?></a></li>
                    <li><a href="#ugFuncionarios" id="fncs"> <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_UG_FUNCIONARIOS_TITLE') . ' (' . count($this->lstFuncionarios) . ')' ?></a></li>
                    <li><a href="#ugObjetivos" id="objsPoa"> <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_UG_OBJETIVOS_TITLE') . ' (' . count($this->items) . ')' ?></a></li>
                    <li><a href="#ugPoas" id="poas"> <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_UG_POAS_TITLE') . ' (' . count($this->lstPoasUG) . ')' ?></a></li>
                    <li><a href="#ugProgramas"> <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_UG_PROGRAMAS_TITLE') . ' (' . count($this->lstProgramas) . ')' ?></a></li>
                    <li><a href="#ugProyectos"> <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_UG_PROYECTOS_TITLE') . ' (' . count($this->lstProyectos) . ')' ?></a></li>
                    <li><a href="#ugContratos"> <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_UG_CONTRATOS_TITLE') . ' (' . count($this->lstContratos) . ')' ?></a></li>
                    <li><a href="#ugConvenios"> <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_UG_CONVENIOS_TITLE') . ' (' . count($this->lstConvenios) . ')' ?></a></li>
                    <?php if ($this->opAdd):?>
                        <li><a href="#ugOpAdd" id="numOpAdd"> <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_UG_OP_ADD_TITLE') . ' (' . count($this->opsAdds) . ')' ?> </a></li>
                    <?php endif;?>
                </ul>
                
                <!-- Opciones Adicioneales deacuerdo a los permisos -->
                <?php if ($this->opAdd):?>
                    <div class="m" id="ugOpAdd">
                        <?php echo $this->loadTemplate('opadd'); ?>
                    </div>
                <?php endif;?>
                
                <!-- Panel de control de una unidad de gestion -->
                <div class="m" id="ugPanelControl">
                    <?php echo $this->loadTemplate('panelcontrol'); ?>
                </div>
                
                <!-- Registro de los datos generales de una unidad de gestion -->
                <div class="m" id="ugDatosGenerales">
                    <?php echo $this->loadTemplate('dtageneral'); ?>
                </div>

                <!-- Lista de funcionarios de una unidad de gestion -->
                <div class="m" id="ugFuncionarios">
                    <?php echo $this->loadTemplate('funcionarios'); ?>
                </div>

                <!-- Lista de objetivos de una unidad de gestion -->
                <div class="m" id="ugObjetivos">
                    <?php echo $this->loadTemplate('objetivos'); ?>
                </div>

                <!-- Lista de POA's de una unidad de gestion -->
                <div class="m" id="ugPoas">
                    <?php echo $this->loadTemplate('poas'); ?>
                </div>
                
                <!-- Programas de una unidad de gestion -->
                <div class="m" id="ugProgramas">
                    <?php echo $this->loadTemplate('programasug'); ?>
                </div>
                
                <!-- Proyectos de una unidad de gestion -->
                <div class="m" id="ugProyectos">
                    <?php echo $this->loadTemplate('proyectosug'); ?>
                </div>
                
                <!-- contratos de una unidad de gestion -->
                <div class="m" id="ugContratos">
                    <?php echo $this->loadTemplate('contratosug'); ?>
                </div>
                
                <!-- Convenios de una unidad de gestion -->
                <div class="m" id="ugConvenios">
                    <?php echo $this->loadTemplate('conveniosug'); ?>
                </div>
                
            </div>
            <div>
                <input type="hidden" name="task" value="unidadgestion.edit" />
                <input type="hidden" id="idInstitucion" name="idInstitucion" value="" />
                <input type="hidden" id="anioVigente" name="anioVigente" value="<?php echo $this->anioVigente; ?>" />
                <input type="hidden" id="intNumPoas" name="intNumPoas" value="<?php echo $numPoa; ?>" />
                <input type="hidden" id="editarData" name="editarData" value="<?php echo $this->canDo->get( 'core.edit' ); ?>" />
                <input type="hidden" id="dtaRoles" name="dtaRoles" value="<?php print htmlspecialchars( json_encode( $this->canDo ) ); ?>" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>
    </div>
</div>

<?php echo $this->loadTemplate('progressblock'); ?> 