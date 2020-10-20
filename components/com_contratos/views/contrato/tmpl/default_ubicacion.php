<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!--lISTA DE UNIDADES TERRITORIALES-->


<div id="ubicacionTab" class="width-100 fltlft">
    <div class="width-100 fltleft">
        <ul>
            <li><a href="#unidadTerritorial"><?php echo JText::_('COM_CONTRATOS_TAB_UNIDADTERRITORIAL'); ?></a></li>
            <li><a href="#ubicacionGeografica" id="ubicacionGeografircaTab" ><?php echo JText::_('COM_CONTRATOS_TAB_UBICACIONGEOGRAFICA'); ?></a></li>
        </ul>

        <!-- UNIDADES TERRITORIALES -->
        <div id="unidadTerritorial">
            <div class="width-50 fltrt">
                <div id="ieavUnidadTerritorial" class="editbackground"></div>
                <div id="editUnidadTerritorialForm" class="hide">
                    <fieldset class="adminform">
                        <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_FACTURA_UNIDADTERRITORIAL'); ?>&nbsp;</legend>
                        <ul class="adminformlist" id="formUbcTerritorialCnt">
                            <?php foreach ($this->form->getFieldset('unidadTerritorial') as $field): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clr"></div>
                        
                        <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                            <input id="addUnidadTerritorial" type="button" value="&nbsp;<?php echo JText::_('BTN_GUARDAR'); ?>&nbsp;">
                        <?php endIf; ?>

                        <input id="cancelUnidadTerritorial" type="button" value="&nbsp;<?php echo JText::_('BTN_CANCELAR'); ?>&nbsp;">
                    </fieldset>
                </div>
            </div>
            <div class="width-50">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_UNIDADESTERRITORIALES'); ?>&nbsp;</legend>
                    
                    <?php if( $this->canDo->get( 'core.create' ) ): ?>
                        <div class="fltrt">
                            <input id="addUnidadTerritorialTable" type="button" value="&nbsp;<?php echo JText::_('BTN_AGREGAR_UND_TRR'); ?>&nbsp;">
                        </div>
                    <?php endIf; ?>

                    <ul>
                        <li>
                            <table id="tbLtUnidadTerritorialContrato" class="tablesorter" cellspacing="1" >
                                <thead>
                                    <tr>
                                        <th align="center">
                                            <?php echo JText::_('COM_CONTRATOS_TAB_UBICACION_TB_UNIDADTERRITORIAL_PROVINCIA'); ?>
                                        </th>
                                        <th align="center">
                                            <?php echo JText::_('COM_CONTRATOS_TAB_UBICACION_TB_UNIDADTERRITORIAL_CANTON'); ?>
                                        </th>
                                        <th align="center">
                                            <?php echo JText::_('COM_CONTRATOS_TAB_UBICACION_TB_UNIDADTERRITORIAL_PARROQUIA'); ?>
                                        </th>
                                        <th  colspan="3" align="center" width="15">
                                            <?php echo JText::_('TL_ACCIONES'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($this->unidadesTerritoriales): ?>
                                        <?php foreach ($this->unidadesTerritoriales AS $key => $undTerr): ?>
                                            <tr id="<?php echo $key + 1 ?>">
                                                <td><?php echo ($undTerr->provincia) ? $undTerr->provincia : "---" ?></td>
                                                <td><?php echo ($undTerr->canton) ? $undTerr->canton : "---" ?></td>
                                                <td><?php echo ($undTerr->parroquia) ? $undTerr->parroquia : "---" ?></td>
                                                
                                                <td style="width: 15px">
                                                    <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                                        <a class="editUndTrr">
                                                            <?php echo JText::_('LB_EDITAR'); ?>
                                                        </a>
                                                    <?php else: ?>
                                                            <?php echo JText::_('LB_EDITAR'); ?>
                                                    <?php endIf; ?>
                                                </td>
                                                
                                                <td  style="width: 15px">
                                                    <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                                        <a class="delUndTrr">
                                                            <?php echo JText::_('LB_ELIMINAR'); ?>
                                                        </a>
                                                    <?php else:?>
                                                            <?php echo JText::_('LB_ELIMINAR'); ?>
                                                    <?php endIf;?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </li>
                    </ul>
                </fieldset>
            </div>
        </div>

        <!-- UBICACION GEOGRAFICA OBRAS -->
        <div id="ubicacionGeografica"  >
            <!-- Lista de Graficos -->
            <div id="filGraficos" class="width-50 fltlft">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_LISTA_GRAFICOS'); ?>&nbsp;</legend>
                    
                    <?php if( $this->canDo->get( 'core.create' ) ): ?>
                        <div class="fltrt">
                            <input id="addGraficoTable" type="button" value="<?php echo JText::_('BTN_AGREGAR_UBC_GEO'); ?>">
                        </div>
                    <?php endIf; ?>

                    <table id="tbLtsGraficos" class="tablesorter" cellspacing="1" >
                        <thead>
                            <tr>
                                <th align="center" >
                                    <?php echo JText::_('COM_CONTRATOS_UBICACION_DESCRIPCION'); ?>
                                </th>
                                <th align="center" width="20%" >
                                    <?php echo JText::_('COM_CONTRATOS_UBICACION_TIPO'); ?>
                                </th>
                                <th  colspan="3" align="center" width="20">
                                    <?php echo JText::_('TL_ACCIONES'); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('MAPA_SIITA'); ?>&nbsp;</legend> 
                    <div id="mapaContratos" class="width-100" style="height: 300px">
                    </div>
                </fieldset>
            </div>
            
            <!-- Edicion de un grafico -->
            <div id="filEditGrafico" class="width-50 fltrt">
                <div id="imgGraficoForm" class="editbackground"> </div>
                <div id="editGraficoContent" class="hide">
                    <fieldset class="adminform" id="formGraficoCnt">
                        <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_UBICACION_GRAFICO'); ?>&nbsp;</legend>
                        <div id="frmTipoGraficoUG" class="width-100" >
                            <ul class="adminformlist" >
                                <?php foreach ($this->form->getFieldset('graficoContrato') as $field): ?>
                                    <li>
                                        <?php echo $field->label; ?>
                                        <?php echo $field->input; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div id="sumitDtaGeneralUG" class ="width-100">
                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                    <input id="addCoordenadasFormGrafico" type="button" value="<?php echo JText::_('BTN_ADD_COORDENADAS') ?>">
                                <?php endIf; ?>

                                <input id="btnCancelarLstCoor" type="button" value="<?php echo JText::_('BTN_CANCELAR') ?>">
                            </div>
                        </div>
                        <div class="clr"> </div>
                        
                        <!-- COORDENADAS DEL GRAFICO -->
                        <div id='contentCorGrf' class="hide">
                            <!-- COORDENADAS -->
                            <div id='coordendasGrafico' class="hide">
                                <fieldset class="adminform">
                                    <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_UBICACION_COORDENADAS'); ?>&nbsp;</legend>
                                    <ul class="adminformlist" id="formCoorGraficoCnt">
                                        <?php foreach ($this->form->getFieldset('coordenadas') as $field): ?>
                                            <li>
                                                <?php echo $field->label; ?>
                                                <?php echo $field->input; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <div class="clr"></div>
                                    <div>
                                        <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                            <input id="addCoordenadaGrafico" type="button" value="<?php echo JText::_('BTN_ADD_COORDENADA'); ?>">
                                        <?php endIf; ?>

                                        <input id="cancelCoordenadaGrafico" type="button" value="<?php echo JText::_('BTN_LIMPIAR'); ?>">
                                    </div>
                                    <div class="clr"></div>

                                    <div id="coordenadasTable"class="width-100">
                                        <table id="tbLstCoordenadas" class="tablesorter" cellspacing="1" >
                                            <thead>
                                                <tr>
                                                    <th align="center">
                                                        <?php echo JText::_('COM_CONTRATOS_TAB_UBICACION_TB_COORDENADAS_LATITUD'); ?>
                                                    </th>
                                                    <th align="center">
                                                        <?php echo JText::_('COM_CONTRATOS_TAB_UBICACION_TB_COORDENADAS_LONGITUD'); ?>
                                                    </th>
                                                    <th  colspan="3" align="center" width="15">
                                                        <?php echo JText::_('TL_ACCIONES'); ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </fieldset>
                            </div>

                            <!--CIRCULO-->
                            <div id='circuloGrafico' class="hide">
                                <fieldset class="adminform">
                                    <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_UBICACION_COORDENADAS'); ?>&nbsp;</legend>
                                    <div id="coordenasCirculoForm" class="width-100">
                                        <ul class="adminformlist" id="formCoorCirculoCnt">
                                            <?php foreach ($this->form->getFieldset('coordenadasCirculo') as $field): ?>
                                                <li>
                                                    <?php echo $field->label; ?>
                                                    <?php echo $field->input; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <div class="clr"></div>

                                        <?php if( $this->canDo->get( 'core.create' ) ): ?>
                                            <input id="addCirculoGrafico" type="button" value="<?php echo JText::_('BTN_ADD_COORDENADA'); ?>">
                                        <?php endIf; ?>
                                            
                                        <input id="cancelCirculoGrafico" type="button" value="<?php echo JText::_('BTN_LIMPIAR'); ?>">
                                    </div>

                                    <div id="coordenadasCirculoTable" class="width-100">
                                        <table id="tbLstCoordenadasCirculo" class="tablesorter" cellspacing="1" >
                                            <thead>
                                                <tr>
                                                    <th align="center">
                                                        <?php echo JText::_('COM_CONTRATOS_TAB_UBICACION_TB_COORDENADAS_LATITUD'); ?>
                                                    </th>
                                                    <th align="center">
                                                        <?php echo JText::_('COM_CONTRATOS_TAB_UBICACION_TB_COORDENADAS_LONGITUD'); ?>
                                                    </th>
                                                    <th align="center">
                                                        <?php echo JText::_('COM_CONTRATOS_TAB_UBICACION_TB_COORDENADAS_RADIO'); ?>
                                                    </th>
                                                    <th  colspan="3" align="center" width="15">
                                                        <?php echo JText::_('TL_ACCIONES'); ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="clr">  </div>

                            <div>
                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                    <input class="saveGrafico" type="button" value="<?php echo JText::_('BTN_GUARDAR'); ?>">
                                <?php endIf; ?>
                                    
                                <input class="cancelGrafico" type="button" value="<?php echo JText::_('BTN_CANCELAR'); ?>">
                            </div>
                        </div>
                    </fieldset>
                    
                </div>
                
            </div>
            <div class="clr"></div>
            
        </div>
    </div>
</div>
