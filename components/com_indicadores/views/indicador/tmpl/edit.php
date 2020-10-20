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
    <form id="frmIndicador" class="form-validate">

        <!-- Formulario de Seleccion de Indicadores Plantilla -->
        <div id="tabsAttrIndicador" style="position: static; left: 20px; height: auto; width: 100%">
            <ul>
                <li><a href="#generales"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_GENERAL' ) ?></a></li>
                <li><a href="#informacion_complementaria"> <?php echo JText::_( 'COM_INDICADORES_INFORMACION_COMPLEMENTARIA' ) ?></a></li>
                <li><a href="#responsables"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_RESPONSABLES' ) ?></a></li>                

                <?php if( $this->_tpo != 'meta' ): ?>
                    <li><a href="#lineaBase"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_LINEABASE' ) ?></a></li>
                    <li><a href="#formula"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_FORMULA' ) ?></a></li>
                    <li><a href="#unidadTerritorial"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_UNDTERRITORIAL' ) ?></a></li>
                    <li><a href="#rango"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_RANGO' ) ?></a></li>            
                <?php endif; ?>

                <?php if( $this->_tpoIndicador == 'bd' || $this->_tpoIndicador == 'bi' || $this->_tpoIndicador == 'oi' ): ?>
                    <li><a href="#dimensiones"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_DIMENSION' ) ?></a></li>
                <?php endif; ?>

                <li><a href="#planificacion"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_PLANIFICACION' ) ?></a></li>
                <li><a href="#seguimiento"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_SEGUIMIENTO' ) ?></a></li>
            </ul>

            <!-- Pestaña de informacion general del proyecto -->
            <div id="generales" class="m">
                <?php echo $this->loadTemplate( 'general' ); ?>
            </div>

            <!-- Pestaña de informacion general del proyecto -->
            <div id="informacion_complementaria" class="m">
                <?php echo $this->loadTemplate( 'informacion_complementaria' ); ?>
            </div>

            <!-- Pestaña de gestion de variables de un indicador -->
            <div id="responsables" class="m">
                <?php echo $this->loadTemplate( 'responsable' ); ?>
            </div>

            <?php if( $this->_tpo != 'meta' ): ?>

                <!-- Pestaña de gestion de variables de un indicador -->
                <div id="lineaBase" class="m">
                    <?php echo $this->loadTemplate( 'lineabase' ); ?>
                </div>

                <!-- Pestaña de gestion de variables de un indicador -->
                <div id="unidadTerritorial" class="m">
                    <?php echo $this->loadTemplate( 'unidadterritorial' ); ?>
                </div>

                <!-- Pestaña Rangos de Gestion de un indicador -->
                <div id="rango" class="m">
                    <?php echo $this->loadTemplate( 'rango' ); ?>
                </div>

                <!-- Pestaña de gestion de formula de un indicador -->
                <div id="formula" class="m">
                    <?php echo $this->loadTemplate( 'formula' ); ?>
                </div>

            <?php endif; ?>

            <?php if( $this->_tpoIndicador == 'bd' || $this->_tpoIndicador == 'bi' || $this->_tpoIndicador == 'oi' ): ?>
                <!-- Pestaña de gestion de Dimensiones de un indicador -->
                <div id="dimensiones" class="m">
                    <?php echo $this->loadTemplate( 'dimension' ); ?>
                </div>
            <?php endif; ?>

            <!-- Pestaña de gestion de Planificacion de un indicador -->
            <div id="planificacion" class="m">
                <?php echo $this->loadTemplate( 'planificacion' ); ?>
            </div>

            <!-- Pestaña de gestion de Seguimiento de un indicador -->
            <div id="seguimiento" class="m">
                <?php echo $this->loadTemplate( 'seguimiento' ); ?>
            </div>
        </div>
    </form>
</div>

<div>
    <input type="hidden" name="task" value="atributoindicador.edit" />
    <input type="hidden" id="idIndicador" name="idIndicador" value="<?php echo $this->_idIndicador; ?>" />
    <input type="hidden" id="tpoIndicador" name="tpoIndicador" value="<?php echo $this->_tpoIndicador; ?>" />
    <input type="hidden" id="idRegIndicador" name="idRegIndicador" value="<?php echo $this->_idRegIndicador; ?>" />
    <input type="hidden" id="idRegObjetivo" name="idRegObjetivo" value="<?php echo $this->_idRegObjetivo; ?>" />
    <input type="hidden" id="tpo" name="tpo" value="<?php echo $this->_tpo; ?>" />
    <input type="hidden" id="idPlan" name="idPlan" value="<?php echo $this->_idPlan; ?>" />
    <input type="hidden" id="idRegObjetivo" name="idRegObjetivo" value="<?php echo $this->_idRegObjetivo; ?>" />
    <input type="hidden" id="ent" name="ent" value="<?php echo $this->_ent; ?>" />
    
    <input type="hidden" id="dtaRoles" name="dtaRoles" value="<?php print htmlspecialchars( json_encode( $this->canDo ) ); ?>" />
</div>
