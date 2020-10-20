<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">

            <h2> <?php if ($this->item->intIdPropuesta_cp == null): ?>
                    <?php echo JText::_('COM_CANASTAPROY_PROPUESTA_CREATING'); ?>
                <?php else: ?>
                    <?php echo JText::_('COM_CANASTAPROY_PROPUESTA_EDITING'); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <div class="m">
            <!-- Div/Tab de Propuestas -->
            <div id="tabsPropuesta" style="position: static; left: 20px; height: auto; width: 100%">
                <ul>
                    <li><a href="#cpPropuesta"> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_DATOSGRLS_CP_TITLE') ?></a></li>
                    <li><a href="#cpObras"> <?php echo JText::_('COM_CANASTAPROY_FIELD_OBRAS_RESUMEN_CP_TITLE') ?></a></li>
                    <li><a href="#cpAliniacionProy"> <?php echo JText::_('COM_CANASTAPROY_FIELD_ALINEACION_PROYECTO_CP_TITLE') ?></a></li>
                    <li><a href="#pryIndicadores"> <?php echo JText::_( 'COM_CANASTAPROY_FIELD_PROYECTO_INDICADORES_TITLE' ) ?> </a></li>
                </ul>

                <!-- Registro de Propuesta -->
                <div class="m" id="cpPropuesta">
                    <?php echo $this->loadTemplate('propuesta'); ?>
                </div>

                <!-- Registro de Coordenadas y DPA -->
                <div id="cpObras">
                    <div id="tabsUbicacion" style="position: static; left: 20px; height: auto; width: 100%">
                        <ul>
                            <li><a href="#cpUbTerritorial"> <?php echo JText::_('COM_CANASTAPROY_FIELD_UBICACION_TERRITORIAL_TITLE') ?></a></li>
                            <li><a href="#cpUbGeografica" id="ugCoordenadas"> <?php echo JText::_('COM_CANASTAPROY_FIELD_UBICACION_GEOGRAFICA_TITLE') ?></a></li>
                        </ul>

                        <!-- Registro de Ubicacion Territorial -->
                        <div class="m" id="cpUbTerritorial">
                            <?php echo $this->loadTemplate('ubicacionterritorial'); ?>
                        </div>

                        <!-- Registro de Ubicacion Geografica -->
                        <div class="m" id="cpUbGeografica">
                            <?php echo $this->loadTemplate('ubicaciongeografica'); ?>
                        </div>
                    </div>
                </div>
                
                <!-- Aliniacion del Proyecto -->
                <div class="m" id="cpAliniacionProy">
                    <?php echo $this->loadTemplate('alineacion'); ?>
                </div>

                <!-- INDICADORES -->
                <div id="pryIndicadores">
                    <?php echo $this->loadTemplate( 'indicadores' ); ?>
                </div>

            </div>
            
            <div>
                <input type="hidden" name="task" value="propuesta.edit" />
                <input type="hidden" id="idObjetivo" name="idObjetivo" value="" />
                <input type="hidden" id="idAlineacion" name="idAlineacion" value="" />
                <input type="hidden" id="dataLineasBase" name="dataLineasBase" value="" />
                <input type="hidden" id="dtaRoles" name="dtaRoles" value="<?php print htmlspecialchars( json_encode( $this->canDo ) ); ?>" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
    </div>
</div>

<?php echo $this->loadTemplate('progressblock'); ?>