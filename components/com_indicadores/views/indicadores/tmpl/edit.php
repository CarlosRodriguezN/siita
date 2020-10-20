<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_( 'behavior.tooltip' );
JHtml::_( 'behavior.formvalidation' );
?>

<div id="toolbar-box">

    <div id="tbIndicadoresObjetivo">
        <div class="m">
            <?php echo $this->getToolbarLista();?>
            <div class="pagetitle icon-48-contact">
                <h2> <?php echo $this->titleLst;?> </h2>
            </div>
        </div>
    </div>

    <div id="tbFrmIndicador">
        <div class="m">
            <?php echo $this->getToolbarFrm();?>
            <div class="pagetitle icon-48-contact">
                <h2> <?php echo $this->titleFrm;?> </h2>
            </div>
        </div>
    </div>

</div>


<div id="element-box">

    <form class="form-validate">


        <!-- Lista de Indicadores asociados a un objetivo -->
        <div id="lstIndicadoresObjetivo">

            <div class="fltrt">
                <?php if ( $this->_tpo != 1 ):?>
                    <?php if ( $this->canDo->get( 'core.create' ) ): ?>
                        <input id="addIndObjetivo" type="button" value=" &nbsp;<?php echo JText::_( 'COM_INDICADORES_ADD' )?> &nbsp;"> 
                    <?php endif;?>
                <?php endif;?>
            </div>
            <div class="clr"></div>

            <table id="lstObjetivosInd" width="100%" class="tablesorter" cellspacing="1">
                <thead>
                    <tr>
                        <th align="center"> <?php echo JText::_( 'COM_INDICADORES_TIPO_INDICADOR' )?> </th>
                        <th align="center"> <?php echo JText::_( 'COM_INDICADORES_FIELD_INDICADOR_NOMBREOTROIND_TITLE' )?> </th>
                        <th align="center"> <?php echo JText::_( 'COM_INDICADORES_FIELD_INDICADOR_DESCOTROIND_TITLE' )?> </th>
                        <th align="center"> <?php echo JText::_( 'COM_INDICADORES_FIELD_INDICADOR_VALOROTROIND_TITLE' )?> </th>                                
                        <th align="center"> <?php echo JText::_( 'COM_INDICADORES_FIELD_INDICADOR_FORMULAOTROIND_TITLE' )?> </th>
                        <th colspan="3" align="center"> <?php echo JText::_( 'COM_INDICADORES_OPCION' )?> </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <!-- Formulario de Gestion de atributos de un indicador -->
        <div id="frmIndicador">

            <div id="tabsAttrIndicador" style="position: static; left: 20px; height: auto; width: 100%">
                <ul>
                    <li><a href="#generales"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_GENERAL' )?></a></li>
                    <li><a href="#informacion_complementaria"> <?php echo JText::_( 'COM_INDICADORES_INFORMACION_COMPLEMENTARIA' )?></a></li>                    
                    <li><a href="#responsables"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_RESPONSABLES' )?></a></li>                                
                    <li><a href="#lineaBase"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_LINEABASE' )?></a></li>
                    <li><a href="#formula"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_FORMULA' )?></a></li>
                    <li><a href="#unidadTerritorial"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_UNDTERRITORIAL' )?></a></li>
                    <li><a href="#rango"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_RANGO' )?></a></li>            
                    <li><a href="#dimensiones"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_DIMENSION' )?></a></li>
                    <li><a href="#planificacion"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_PLANIFICACION' )?></a></li>
                    <li><a href="#seguimiento"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_SEGUIMIENTO' )?></a></li>
                </ul>

                <!-- Pestaña de informacion general del proyecto -->
                <div id="generales" class="m">
                    <?php echo $this->loadTemplate( 'general' );?>
                </div>

                <!-- Pestaña de informacion general del proyecto -->
                <div id="informacion_complementaria" class="m">
                    <?php echo $this->loadTemplate( 'informacion_complementaria' );?>
                </div>

                <!-- Pestaña de gestion de variables de un indicador -->
                <div id="responsables" class="m">
                    <?php echo $this->loadTemplate( 'responsable' );?>
                </div>

                <!-- Pestaña de gestion de variables de un indicador -->
                <div id="lineaBase" class="m">
                    <?php echo $this->loadTemplate( 'lineabase' );?>
                </div>

                <!-- Pestaña de gestion de variables de un indicador -->
                <div id="unidadTerritorial" class="m">
                    <?php echo $this->loadTemplate( 'unidadterritorial' );?>
                </div>

                <!-- Pestaña Rangos de Gestion de un indicador -->
                <div id="rango" class="m">
                    <?php echo $this->loadTemplate( 'rango' );?>
                </div>

                <!-- Pestaña de gestion de formula de un indicador -->
                <div id="formula" class="m">
                    <?php echo $this->loadTemplate( 'formula' );?>
                </div>

                <!-- Pestaña de gestion de Enfoques de un indicador -->
                <div id="dimensiones" class="m">
                    <?php echo $this->loadTemplate( 'dimension' );?>
                </div>

                <!-- Pestaña de gestion de Planificacion de un indicador -->
                <div id="planificacion" class="m">
                    <?php echo $this->loadTemplate( 'planificacion' );?>
                </div>

                <!-- Pestaña de gestion de Seguimiento de un indicador -->
                <div id="seguimiento" class="m">
                    <?php echo $this->loadTemplate( 'seguimiento' );?>
                </div>
            </div>
        </div>

        <div>
            <input type="hidden" name="task" value="indicadores.edit" />
            <input type="hidden" id="tpoPlan" name="tpoPlan" value="<?php echo $this->_tpoPlan;?>" />
            <input type="hidden" id="idPlan" name="idPlan" value="<?php echo $this->_idPlan;?>" />
            <input type="hidden" id="idPoa" name="idPoa" value="<?php echo $this->_idPoa;?>" />
            <input type="hidden" id="idRegObjetivo" name="idRegObjetivo" value="<?php echo $this->_idRegObjetivo;?>" />
            <input type="hidden" id="idIndicador" name="idIndicador" value="<?php echo $this->_idIndicador;?>" />
            <input type="hidden" id="idTpo" name="idTpo" value="<?php echo $this->_idTpo;?>" />
            <input type="hidden" id="tpoIndicador" name="tpoIndicador" value="<?php echo $this->_tpoIndicador;?>" />
            <input type="hidden" id="dtaRoles" name="dtaRoles" value="<?php print htmlspecialchars( json_encode( $this->canDo ) ); ?>" />
        </div>

    </form>
</div>