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

            <h2> <?php if ($this->item->intId_pi == null): ?>
                    <?php echo JText::_('COM_POA_PLAN_CREATING'); ?>
                <?php else: ?>
                    <?php echo JText::_('COM_POA_PLAN_EDITING'); ?>
                <?php endif; ?>
            </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <div class="m">
        <?php if ($this->pei): ?>
            <h3>  <a href="index.php?option=com_pei&view=pei&layout=edit&intId_pi=<?php echo$this->pei->intId_pi ?>" 
                     title="<?php echo$this->pei->strDescripcion_pi ?>"
                     ><?php echo $this->pei->strAlias_pi . " /" ?></a>
            </h3>
            <br>
        <?php endif; ?>
        <form action="<?php echo JRoute::_('index.php?option=com_poa&layout=edit&intId_pi=' . (int) $this->item->intId_pi); ?>" method="post" name="adminForm" >

            <!-- Div/Tab de Plan Estratégico Institucional -->
            <div id="tabsPlaEstIns" style="position: static; left: 20px; height: auto; width: 100%">
                <ul>
                    <li><a href="#poaDatosGenerales"> <?php echo JText::_('COM_POA_FIELD_PLAN_DATOSGRLS_POA_TITLE') ?></a></li>
                    <li><a href="#poaObjetivos"> <?php echo JText::_('COM_POA_FIELD_PLAN_OBJETIVOS_POA_TITLE') ?></a></li>
                </ul>

                <!-- Registro de los datos generales de un nuevo plan -->
                <div class="m" id="poaDatosGenerales">
                    <?php echo $this->loadTemplate('dtageneral'); ?>
                </div>

                <!-- Registro de objetivos y aciones de un nuvo plan estratégico institucional -->
                <div class="m" id="poaObjetivos">
                    <?php echo $this->loadTemplate('objetivos'); ?>
                </div>

            </div>

            <div>
                <input type="hidden" name="task" value="poa.edit" />
                <input type="hidden" id="idPadrePoa" name="idPadrePoa" value="<?php echo $this->pei->intId_pi ?>" />
                <?php echo JHtml::_('form.token'); ?>
            </div>

        </form>
    </div>
</div>