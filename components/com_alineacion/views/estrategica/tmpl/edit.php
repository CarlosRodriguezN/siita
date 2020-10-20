<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_( 'behavior.tooltip' );
JHtml::_( 'behavior.formvalidation' );
?>

<div id="toolbar-box">
    <div id="tbFrmIndicador">
        <div class="m">
            <?php echo $this->getToolbar(); ?>
            <div class="pagetitle icon-48-contact">
                <h2> <?php echo $this->title; ?> </h2>
            </div>
        </div>
    </div>
</div>


<div id="element-box">
    <form class="form-validate">
        <!-- Formulario de Registro de atributos de un indicador -->
        <div id="tabsAlineacion" style="position: static; left: 20px; height: auto; width: 100%">
            <ul>
                <li><a href="#agnd"> <?php echo JText::_( 'COM_ALINEACION_FIELD_AGENDA' ) ?></a></li>
            </ul>

            <!-- Pestaña de informacion general del proyecto -->
            <div id="agnd" class="m">
                <?php echo $this->loadTemplate( 'agd' ); ?>
            </div>
        </div>

        <div>
            <input type="hidden" name="task" value="atributoindicador.edit" />
            <input type="hidden" id="idIndicador" name="idIndicador" value="<?php echo $this->_idIndicador; ?>" />
            <input type="hidden" id="tpoIndicador" name="tpoIndicador" value="<?php echo $this->_tpoIndicador; ?>" />
            <input type="hidden" id="idRegIndicador" name="idRegIndicador" value="<?php echo $this->_idRegIndicador; ?>" />
            <input type="hidden" id="idRegObjetivo" name="idRegObjetivo" value="<?php echo $this->_idRegObjetivo; ?>" />
            <input type="hidden" id="tpo" name="tpo" value="<?php echo $this->_tpo; ?>" />
            <input type="hidden" id="tpoPln" name="tpoPln" value="<?php echo $this->_tpoPln; ?>" />
            <input type="hidden" id="registroPln" name="registroPln" value="<?php echo $this->_registroPln; ?>" />
        </div>
    </form>    
</div>