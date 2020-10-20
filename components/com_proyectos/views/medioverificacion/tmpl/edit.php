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
            <h2> <?php echo $this->title; ?> </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <div class="m">
        <div class="adminform">
            <div class="cpanel-page">
                <div class="cpanel">
                    <div id="tabsMediosVerificacion" style="position: static; left: 20px; height: auto; width: 100%">
                        <ul>
                            <li><a href="#medVerificacion"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_MLMEDIOSVERIFICACION_DESC') ?></a></li>
                            <li><a href="#supuestos"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_MLSUPUESTOS_DESC') ?></a></li>
                        </ul>

                        <!-- Pestaña de Medios de verificacion -->
                        <div id="medVerificacion" class="m">
                            <?php echo $this->loadTemplate('medverificacion'); ?>
                        </div>

                        <!-- Pestaña Supuestos -->
                        <div id="supuestos" class="m">
                            <?php echo $this->loadTemplate('supuesto'); ?>
                        </div>
                    </div>

                    <div>
                        <input type="hidden" id="idProyecto" name="idProyecto" value="<?php echo $this->_idProyecto; ?>" />
                        <input type="hidden" id="idTipoML" name="idTipoML" value="<?php echo $this->_idTipoML; ?>" />
                        <input type="hidden" id="idRegML" name="idRegML" value="<?php echo $this->_idRegML; ?>" />
                        <input type="hidden" id="idML" name="idML" value="<?php echo $this->_idML; ?>" />
                        <input type="hidden" id="idCmp" name="idCmp" value="<?php echo $this->_idCmp; ?>" />
                        <input type="hidden" id="idAct" name="idAct" value="<?php echo $this->_idAct; ?>" />
                        <input type="hidden" id="dtaRoles" name="dtaRoles" value="<?php print htmlspecialchars( json_encode( $this->canDo ) ); ?>" />
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>