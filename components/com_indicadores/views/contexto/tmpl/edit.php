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
    <!-- Formulario de Registro de atributos de un indicador -->
    <div id="frmIndicador">
        
        <!-- Formulario de Seleccion de Indicadores Plantilla -->
        <?php if( $this->_tpoIndicador == 'oi' ):?>
            <div id="frmPlantilla">
                <!-- Pestaña de informacion general del proyecto -->
                <?php echo $this->loadTemplate( 'plantilla' ); ?>
            </div>
        <?php endif;?>
        
        <div id="tabsAttrIndicador" style="position: static; left: 20px; height: auto; width: 100%">
            <ul>
                <li><a href="#generales"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_GENERAL' ) ?></a></li>
                <li><a href="#rango"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_RANGO' ) ?></a></li>            
                <li><a href="#formula"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_FORMULA' ) ?></a></li>
           </ul>

            <!-- Pestaña de informacion general del proyecto -->
            <div id="generales" class="m">
                <?php echo $this->loadTemplate( 'general' ); ?>
            </div>

            <!-- Pestaña Rangos de Gestion de un indicador -->
            <div id="rango" class="m">
                <?php echo $this->loadTemplate( 'rango' ); ?>
            </div>

            <!-- Pestaña de gestion de formula de un indicador -->
            <div id="formula" class="m">
                <?php echo $this->loadTemplate( 'formula' ); ?>
            </div>
        </div>
    </div>

    <div>
        <input type="hidden" name="task" value="contexto.edit" />
        <input type="hidden" id="idRegContexto" name="idRegContexto" value="<?php echo $this->_idRegContexto; ?>" />
        <input type="hidden" id="dtaRoles" name="dtaRoles" value="<?php print htmlspecialchars( json_encode( $this->canDo ) ); ?>" />
    </div>
</div>