<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHTML::_('behavior.modal');
?>
<script type="text/javascript">
<?php echo $this->subProgramas1 ?>
<?php echo $this->tiposSubProgramas ?>
</script>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">
            <h2> <?php if ($this->item->intCodigo_prg == null): ?>
                    <?php echo JText::_('COM_PROGRAMA_PROGRAMA'); ?>
                <?php else: ?>
                    <?php echo JText::_('COM_PROGRAMA_PROGRAMA'); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <form action="<?php echo JRoute::_('index.php?option=com_programa&layout=edit&intCodigo_prg=' . (int) $this->item->intCodigo_prg); ?>" method="post" name="adminForm" id="programa-form" class="form-validate">
        <!-- INICIO DE LOS TABS -->
        <div id="tabs" style="position: static; left: 20px; height: auto; width: 100%">
            <ul>
                <li><a href="#ugPanelControl"> <?php echo JText::_('COM_PROGRAMA_FIELD_PANEL_CONTROL_TITLE') ?></a></li>                
                <li><a href="#datos_generales"> <?php echo JText::_('COM_PROGRAMA_FIELD_PROGRAMA_DATOSGRLS_TITLE') ?></a></li>
                <li><a href="#responsables"> <?php echo JText::_('COM_PROGRAMA_GESTION_FIELD_UG_RESPONSABLES_TITLE') ?></a></li>
                <li><a href="#objetivos"> <?php echo JText::_('COM_PROGRAMA_GESTION_FIELD_UG_OBJETIVOS_TITLE') ?></a></li>
                <li><a href="#sub_programas" id="aSub_programas"> <?php echo JText::_('COM_PROGRAMA_FIELD_PROGRAMA_SUBPROGRAMAS_TITLE') ?></a></li>
                <li><a href="#tiposSubPrograma"> <?php echo JText::_('COM_PROGRAMA_FIELD_PROGRAMA_TIPOSSUBPROGRAMA_TITLE') ?></a></li>
                <li><a href="#indicadores"> <?php echo JText::_('COM_PROGRAMA_FIELD_PROGRAMA_INDICADORES_TITLE') ?></a></li>
                <li><a href="#uploadImages"> <?php echo JText::_('COM_PROGRAMA_FIELD_PROGRAMA_UPLOADIMAGESPROGRAMA_TITLE') ?></a></li>
                <li><a href="#prgProyectos"> <?php echo JText::_('COM_PROGRAMA_GESTION_FIELD_UG_PROYECTOS_TITLE') ?></a></li>
                <li><a href="#prgContratos"> <?php echo JText::_('COM_PROGRAMA_GESTION_FIELD_UG_CONTRATOS_TITLE') ?></a></li>
                <li><a href="#prgConvenios"> <?php echo JText::_('COM_PROGRAMA_GESTION_FIELD_UG_CONVENIOS_TITLE') ?></a></li>
            </ul>

            <!-- Panel de control de una unidad de gestion -->
            <div class="m" id="ugPanelControl">
                <?php echo $this->loadTemplate('panelcontrol'); ?>
            </div>

            <!-- Datos Generales-->
            <div  class="m" id="datos_generales">
                <?php echo $this->loadTemplate('general'); ?>
            </div>

            <!-- Convenios asociados a un programa -->
            <div class="m" id="objetivos">
                <?php echo $this->loadTemplate('objetivos'); ?>
            </div>

            <!-- Sub Programas -->
            <div class="m" id="sub_programas">
                <?php echo $this->loadTemplate('subprograma'); ?>
            </div>

            <!-- Tipos de sub Programas-->
            <div class="m" id="tiposSubPrograma" >
                <?php echo $this->loadTemplate('tipossubprogramas'); ?> 
            </div>

            <!-- Gestion de Indicadores -->
            <div  class="m" id="indicadores">
                <?php echo $this->loadTemplate('indicadores'); ?> 
            </div>

            <!-- Carga de imagenes-->
            <div  class="m" id="uploadImages">
                <?php echo $this->loadTemplate('cargaimagenes'); ?> 
            </div>

            <!-- Proyectos asociados a un programa -->
            <div class="m" id="prgProyectos">
                <?php echo $this->loadTemplate('proyectos'); ?>
            </div>

            <!-- Contratos asociados a un programa -->
            <div class="m" id="prgContratos">
                <?php echo $this->loadTemplate('contratos'); ?>
            </div>

            <!-- Convenios asociados a un programa -->
            <div class="m" id="prgConvenios">
                <?php echo $this->loadTemplate('convenios'); ?>
            </div>

            <!-- Convenios asociados a un programa -->
            <div class="m" id="responsables">
                <?php echo $this->loadTemplate('responsables'); ?>
            </div>
            
            <div>
                <input type="hidden" name="task" value="programa.edit" />
                <input type="hidden" id="idPrograma" name="idPrograma" value="<?php echo ( int ) $this->item->intCodigo_prg; ?>">
                <input type="hidden" id="dtaRoles" name="dtaRoles" value="<?php print htmlspecialchars( json_encode( $this->canDo ) ); ?>" />
                
                <?php echo JHtml::_( 'form.token' ); ?>
            </div>
            
        </div>
    </form>
</div>

<?php echo $this->loadTemplate('progressblock'); ?>