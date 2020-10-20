<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_( 'behavior.tooltip' );
JHtml::_( 'behavior.formvalidation' );
?>

<div id="toolbar-box">
    <div id="tbFrmIndicador">
        <div class="m">
            <?php echo $this->getToolbar();?>
            <div class="pagetitle icon-48-contact">
                <h2> <?php echo $this->title;?> </h2>
            </div>
        </div>
    </div>
</div>

<div id="element-box">
    <!-- Formulario de Registro de atributos de un indicador -->
    <form action="<?php echo JRoute::_( 'index.php?option=com_mantenimiento&amp;task=indicador.edit&amp;intId_pi=' . (int)$this->item->intId_pi );?>" method="post" name="adminForm" id="frmIndicadorPtlla" class="form-validate">

        <!-- Formulario de Seleccion de Indicadores Plantilla -->
        <div id="tabsAttrIndicador" style="position: static; left: 20px; height: auto; width: 100%">
            <ul>
                <li><a href="#generales"> <?php echo JText::_( 'COM_MANTENIMIENTO_FIELD_ATRIBUTO_GENERAL' )?></a></li>
                <li><a href="#formula"> <?php echo JText::_( 'COM_MANTENIMIENTO_FIELD_ATRIBUTO_FORMULA' )?></a></li>
            </ul>

            <!-- Pestaña de informacion general del proyecto -->
            <div id="generales" class="m">
                <?php echo $this->loadTemplate( 'general' );?>
            </div>

            <!-- Pestaña de gestion de formula de un indicador -->
            <div id="formula" class="m">
                <?php echo $this->loadTemplate( 'formula' );?>
            </div>

        </div>

        <div>
            <input type="hidden" name="task" value="indicador.edit" />
            <input type="hidden" id="ptllaInd" name="ptllaInd" value="<?php echo (int)$this->item->intId_pi;?>">
            
            <?php echo JHtml::_( 'form.token' );?>
        </div>

    </form>
</div>


