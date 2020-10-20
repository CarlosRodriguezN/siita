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

<?php if ( ($this->canDo->get( 'core.delete' ) && $this->canDo->get( 'core.create' ) && $this->canDo->get( 'core.edit' )) || (JFactory::getUser()->id == $this->item->intIdUser_fnc) ): ?>
    <div id="toolbar-box">
       <div class="m">
            <?php echo $this->getToolbar(); ?>
            <div class="pagetitle icon-48-contact">

                <h2> <?php if ($this->item->intCodigo_fnc == null): ?>
                        <?php echo JText::_('COM_FUNCIONARIOS_FNC_CREATING'); ?>
                    <?php else: ?>
                        <?php echo JText::_('COM_FUNCIONARIOS_FNC_EDITING'); ?>
                    <?php endif; ?>
                </h2>
            </div>
        </div>
    </div>
    <div id="element-box">
       <div class="m">
          <form action="<?php echo JRoute::_('index.php?option=com_funcionarios&layout=edit&idFuncionario=' . (int) $this->item->idFuncionario); ?>" method="post" name="adminForm" id="funcionario-form" class="form-validate" >

            <!-- Div/Tab de Unidad de Gestión -->
            <div id="tabsFuncionario" style="position: static; left: 20px; height: auto; width: 100%">
                <ul>
                    <li><a href="#fncPanelControl"> <?php echo JText::_('COM_FUNCIONARIOS_FIELD_FNC_PANEL_CONTROL_TITLE') . ' (' . $numInds . ')' ?></a></li>
                    <li><a href="#fncDatosGenerales"> <?php echo JText::_('COM_FUNCIONARIOS_FIELD_FNC_DATOSGRLS_TITLE') ?></a></li>
                    <li><a href="#fncObjetivos"> <?php echo JText::_('COM_FUNCIONARIOS_FIELD_FNC_OBJETIVOS_TITLE') . ' (' . count($this->items) . ')' ?></a></li>
                    <li><a href="#fncLstPoas" id="poas"> <?php echo JText::_('COM_FUNCIONARIOS_FIELD_FNC_POAS_TITLE') . ' (' . count($this->lstPoasFnc) . ')' ?></a></li>
                    <li><a href="#fncProgramas"> <?php echo JText::_('COM_FUNCIONARIOS_FIELD_FNC_PROGRAMAS_TITLE') . ' (' . count($this->lstProgramas) . ')' ?></a></li>
                    <li><a href="#fncProyectos"> <?php echo JText::_('COM_FUNCIONARIOS_FIELD_FNC_PROYECTOS_TITLE') . ' (' . count($this->lstProyectos) . ')' ?></a></li>
                    <li><a href="#fncContratos"> <?php echo JText::_('COM_FUNCIONARIOS_FIELD_FNC_CONTRATOS_TITLE') . ' (' . count($this->lstContratos) . ')' ?></a></li>
                    <li><a href="#fncConvenios"> <?php echo JText::_('COM_FUNCIONARIOS_FIELD_FNC_CONVENIOS_TITLE') . ' (' . count($this->lstConvenios) . ')' ?></a></li>
                </ul>

                <!-- Panel de control de una unidad de gestion -->
                <div class="m" id="fncPanelControl">
                    <?php echo $this->loadTemplate('panelcontrol'); ?>
                </div>

                <!-- Registro de los datos generales de una unidad de gestion -->
                <div class="m" id="fncDatosGenerales">
                    <?php echo $this->loadTemplate('dtageneral'); ?>
                </div>

                <!-- Lista de funcionarios de una unidad de gestion -->
                <div class="m" id="fncObjetivos">
                    <?php echo $this->loadTemplate('objetivos'); ?>
                </div>

                <!-- Lista de poas de un funcionario -->
                <div class="m" id="fncLstPoas">
                    <?php echo $this->loadTemplate('poas'); ?>
                </div>

                <!-- Programas de una unidad de gestion -->
                <div class="m" id="fncProgramas">
                    <?php echo $this->loadTemplate('programasfnc'); ?>
                </div>

                <!-- Proyectos de una unidad de gestion -->
                <div class="m" id="fncProyectos">
                    <?php echo $this->loadTemplate('proyectosfnc'); ?>
                </div>

                <!-- contratos de una unidad de gestion -->
                <div class="m" id="fncContratos">
                    <?php echo $this->loadTemplate('contratosfnc'); ?>
                </div>

                <!-- Convenios de una unidad de gestion -->
                <div class="m" id="fncConvenios">
                    <?php echo $this->loadTemplate('conveniosfnc'); ?>
                </div>

            </div>

            <div>
                <input type="hidden" name="task" value="funcionario.edit" />
                <input type="hidden" id="anioVigente" name="anioVigente" value="<?php echo $this->anioVigente; ?>" />
                <input type="hidden" id="idUsr" name="idUsr" value="<?php echo $this->_idUsr; ?>" />
                <input type="hidden" id="editarData" name="editarData" value="<?php echo $this->canDo->get( 'core.edit' ); ?>" />
                <input type="hidden" id="dtaRoles" name="dtaRoles" value="<?php print htmlspecialchars( json_encode( $this->canDo ) ); ?>" />                
                
                <?php echo JHtml::_('form.token'); ?>
            </div>

          </form>
       </div>
    </div>

    <?php echo $this->loadTemplate('progressblock'); ?>
<?php else:?>
    <?php JError::raiseWarning( 404, JText::_( 'JERROR_ALERTNOAUTHOR' ) ); ?>
<?php endif?>