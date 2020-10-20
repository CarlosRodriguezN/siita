<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_( 'behavior.tooltip' );
JHtml::_( 'behavior.formvalidation' );
?>

<div id="toolbar-box">
    
    <div id="tbIndicadoresObjetivo">
        <div class="m">
            <?php echo $this->getToolbarLista(); ?>
            <div class="pagetitle icon-48-contact">
                <h2> <?php echo $this->titleLst; ?> </h2>
            </div>
        </div>
    </div>
    
    <div id="tbFrmIndicador">
        <div class="m">
            <?php echo $this->getToolbarFrm(); ?>
            <div class="pagetitle icon-48-contact">
                <h2> <?php echo $this->titleFrm; ?> </h2>
            </div>
        </div>
    </div>
</div>


<div id="element-box">

    <!-- Lista de Indicadores asociados a un objetivo -->
    <div id="lstIndicadoresObjetivo">
        
        <div class="fltrt">
            <input id="addIndObjetivo" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_AGREGAR' ) ?> &nbsp;">
        </div>
        <div class="clr"></div>

        <table id="lstObjetivosInd" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_( 'COM_PEI_FIELD_INDICADOR_NOMBREOTROIND_TITLE' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_PEI_FIELD_INDICADOR_DESCOTROIND_TITLE' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_PEI_FIELD_INDICADOR_VALOROTROIND_TITLE' ) ?> </th>                                
                    <th align="center"> <?php echo JText::_( 'COM_PEI_FIELD_INDICADOR_FORMULAOTROIND_TITLE' ) ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_( 'COM_PEI_FIELD_INDICADOR_ACCIONOTROIND_TITLE' ) ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    
    <!-- Formulario de Registro de atributos de un indicador -->
    <div id="frmIndicador">
        <div id="tabsAttrIndicador" style="position: static; left: 20px; height: auto; width: 100%">
            <ul>
                <li><a href="#generales"> <?php echo JText::_( 'COM_PEI_FIELD_ATRIBUTO_GENERAL' ) ?></a></li>
                <li><a href="#lineaBase"> <?php echo JText::_( 'COM_PEI_FIELD_ATRIBUTO_LINEABASE' ) ?></a></li>
                <li><a href="#unidadTerritorial"> <?php echo JText::_( 'COM_PEI_FIELD_ATRIBUTO_UNDTERRITORIAL' ) ?></a></li>
                <li><a href="#rango"> <?php echo JText::_( 'COM_PEI_FIELD_ATRIBUTO_RANGO' ) ?></a></li>            
                <li><a href="#variables"> <?php echo JText::_( 'COM_PEI_FIELD_ATRIBUTO_VARIABLE' ) ?></a></li>
                <li><a href="#dimensiones"> <?php echo JText::_( 'COM_PEI_FIELD_ATRIBUTO_DIMENSION' ) ?></a></li>
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

            <!-- Pestaña de gestion de variables de un indicador -->
            <div id="dimensiones" class="m">
                <?php echo $this->loadTemplate( 'dimension' ); ?>
            </div>
        </div>
    </div>

    <div>
        <input type="hidden" name="task" value="atributoindicador.edit" />
        <input type="hidden" id="idIndicador" name="idIndicador" value="<?php echo $this->_idIndicador; ?>" />
        <input type="hidden" id="tpoIndicador" name="tpoIndicador" value="<?php echo $this->_tpoIndicador; ?>" />
        <input type="hidden" id="idRegObjetivo" name="idRegObjetivo" value="<?php echo $this->_idRegObjetivo; ?>" />
        <input type="hidden" id="tpo" name="tpo" value="<?php echo $this->_tpo; ?>" />
    </div>
</div>