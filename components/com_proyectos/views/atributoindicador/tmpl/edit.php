<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_( 'behavior.tooltip' );
JHtml::_( 'behavior.formvalidation' );
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
    <div id="tabsAttrIndicador" style="position: static; left: 20px; height: auto; width: 100%">
        <ul>
            <li><a href="#generales"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_GENERAL' ) ?></a></li>
            <li><a href="#lineaBase"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_LINEABASE' ) ?></a></li>
            <li><a href="#unidadTerritorial"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_UNDTERRITORIAL' ) ?></a></li>
            <li><a href="#rango"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_RANGO' ) ?></a></li>            
            <li><a href="#variables"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_VARIABLE' ) ?></a></li>
            
            <!-- DISPONIBLE SOLO PARA OTROS INDICADORES - Pestaña de Gestion de Dimensiones -->
            <?php if( $this->_tpoIndicador == 'oi' ): ?>

            <li><a href="#dimensiones"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_DIMENSION' ) ?></a></li>

            <?php endif;?>
            
        </ul>

        <!-- Pestaña de informacion general del proyecto -->
        <div id="generales" class="m">
            <?php echo $this->loadTemplate( 'general' ); ?>
        </div>

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
        
        <!-- Pestaña de gestion de variables de un indicador -->
        <div id="variables" class="m">
            <?php echo $this->loadTemplate( 'variable' ); ?>
        </div>
        
        <!-- DISPONIBLE SOLO PARA OTROS INDICADORES - Pestaña de Gestion de Dimensiones -->
        <?php if( $this->_tpoIndicador == 'oi' ): ?>
        
        <!-- Pestaña de gestion de variables de un indicador -->
        <div id="dimensiones" class="m">
            <?php echo $this->loadTemplate( 'dimension' ); ?>
        </div>
        
        <?php endif;?>
        
    </div>

    <div>
        <input type="hidden" name="task" value="atributoindicador.edit" />
        <input type="hidden" id="idIndicador" name="idIndicador" value="<?php echo $this->_idIndicador; ?>" />
        <input type="hidden" id="tpoIndicador" name="tpoIndicador" value="<?php echo $this->_tpoIndicador; ?>" />
        <input type="hidden" id="idRegIndicador" name="idRegIndicador" value="<?php echo $this->_idRegIndicador; ?>" />
        <input type="hidden" id="tpo" name="tpo" value="<?php echo $this->_tpo; ?>" />
    </div>
</div>