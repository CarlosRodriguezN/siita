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
            <li><a id="ubicacionGeografircaTab" href="#ubicacionGeografica"><?php echo JText::_('COM_CONTRATOS_TAB_UBICACIONGEOGRAFICA'); ?></a></li>
        </ul>


        <!-- UNIDADES TERRITORIALES -->
        <div id="unidadTerritorial">
            <div class="width-50 fltrt">
                <div id="ieavUnidadTerritorial" class="editbackground"></div>
                <div id="editUnidadTerritorialForm" class="hide">
                    <fieldset class="adminform">
                        <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_FACTURA_UNIDADTERRITORIAL'); ?>&nbsp;</legend>
                        <ul class="adminformlist">
                            <?php foreach ($this->form->getFieldset('unidadTerritorial') as $field): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clr">
                        </div>
                        <input id="addUnidadTerritorial" type="button" value="<?php echo JText::_('BTN_GUARDAR'); ?>">
                        <input id="cancelUnidadTerritorial" type="button" value="<?php echo JText::_('BTN_CANCELAR'); ?>">
                    </fieldset>
                </div>
            </div>
            <div class="width-50">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_UNIDADESTERRITORIALES'); ?>&nbsp;</legend>
                    <div class="fltrt">
                        <input id="addUnidadTerritorialTable" type="button" value="<?php echo JText::_('BTN_AGREGAR'); ?>">
                    </div>
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
                                            <?php echo JText::_('ANY_ACCION_TABLA'); ?>
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
                                                    <a class="editUndTrr">
                                                        <?php echo JText::_('LB_EDITAR'); ?>
                                                    </a>
                                                </td>
                                                <td  style="width: 15px">
                                                    <a class="delUndTrr">
                                                        <?php echo JText::_('LB_ELIMINAR'); ?>
                                                    </a>
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
            <!-- Edicion de un grafico -->
            <div id="filEditGrafico" class="width-50 fltrt">
                <div id="ieavGraficoForm" class="editbackground">
                </div>
                <div id="editGraficoContent" class="hide">
                    <div id="editGraficoForm">
                        <fieldset class="adminform">
                            <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_UBICACION_GRAFICO'); ?>&nbsp;</legend>
                            <ul class="adminformlist">
                                <?php foreach ($this->form->getFieldset('graficoContrato') as $field): ?>
                                    <li>
                                        <?php echo $field->label; ?>
                                        <?php echo $field->input; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="clr">
                            </div>
                            <input id="addCoordenadasFormGrafico" type="button" value="<?php echo JText::_('INGRESAR_VER_BTN'); ?>">
                            <div class="clr">
                            </div>

                        </fieldset>
                    </div>
                    <!-- COORDENADAS -->
                    <div id='coordendasGrafico' class="hide">
                        <fieldset class="adminform">
                            <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_UBICACION_COORDENADAS'); ?>&nbsp;</legend>
                            <div id="coordenasForm" class="width-100">
                                <ul class="adminformlist">
                                    <?php foreach ($this->form->getFieldset('coordenadas') as $field): ?>
                                        <li>
                                            <?php echo $field->label; ?>
                                            <?php echo $field->input; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="clr">
                                </div>
                                <input id="addCoordenadaGrafico" type="button" value="<?php echo JText::_('BTN_GUARDAR'); ?>">
                                <input id="cancelCoordenadaGrafico" type="button" value="<?php echo JText::_('BTN_CANCELAR'); ?>">
                            </div>
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
                                                <?php echo JText::_('ANY_ACCION_TABLA'); ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div>
                                <input class="saveGrafico" type="button" value="<?php echo JText::_('BTN_GUARDAR'); ?>">
                                <input class="cancelGrafico" type="button" value="<?php echo JText::_('BTN_CANCELAR'); ?>">
                            </div>
                        </fieldset>
                    </div>

                    <!--CIRCULO-->
                    <div id='circuloGrafico' class="hide">
                        <fieldset class="adminform">
                            <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_UBICACION_COORDENADAS'); ?>&nbsp;</legend>
                            <div id="coordenasCirculoForm" class="width-100">
                                <ul class="adminformlist">
                                    <?php foreach ($this->form->getFieldset('coordenadasCirculo') as $field): ?>
                                        <li>
                                            <?php echo $field->label; ?>
                                            <?php echo $field->input; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="clr">
                                </div>
                                <input id="addCirculoGrafico" type="button" value="<?php echo JText::_('BTN_GUARDAR'); ?>">
                                <input id= "cancelCirculoGrafico" type="button" value="<?php echo JText::_('BTN_CANCELAR'); ?>">
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
                                                <?php echo JText::_('ANY_ACCION_TABLA'); ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div>
                                    <input class="saveGrafico" type="button" value="<?php echo JText::_('BTN_GUARDAR'); ?>">
                                    <input class="cancelGrafico" type="button" value="<?php echo JText::_('BTN_CANCELAR'); ?>">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>

            <!-- Lista de Graficos -->
            <div id="filGraficos" class="width-50">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_LISTA_GRAFICOS'); ?>&nbsp;</legend>
                    <div class="fltrt">
                        <input id="addGraficoTable" type="button" value="<?php echo JText::_('BTN_AGREGAR'); ?>">
                    </div>
                    <ul>
                        <li>
                            <table id="tbLtsGraficos" class="tablesorter" cellspacing="1" >
                                <thead>
                                    <tr>
                                        <th align="center">
                                            <?php echo JText::_('COM_CONTRATOS_UBICACION_DESCRIPCION'); ?>
                                        </th>
                                        <th align="center">
                                            <?php echo JText::_('COM_CONTRATOS_UBICACION_TIPO'); ?>
                                        </th>
                                        <th  colspan="3" align="center" width="15">
                                            <?php echo JText::_('ANY_ACCION_TABLA'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($this->graficos): ?>
                                        <?php foreach ($this->graficos AS $key => $grafico): ?>
                                            <tr id="<?php echo $key + 1 ?>">
                                                <td><?php echo ($grafico->descripcion)?$grafico->descripcion:"-----"; ?></td>
                                                <td><?php echo ($grafico->nmbTipoGrafico)?$grafico->nmbTipoGrafico:"-----" ?></td>
                                                <td style="width: 15px">
                                                    <a id="g-<?php echo $key + 1 ?>"class="verGrafico">
                                                        <?php echo JText::_('LB_VER'); ?>
                                                    </a>
                                                </td>
                                                <td style="width: 15px">
                                                    <a class="editGrafico">
                                                        <?php echo JText::_('LB_EDITAR'); ?>
                                                    </a>
                                                </td>
                                                <td  style="width: 15px">
                                                    <a class="delGrafico">
                                                        <?php echo JText::_('LB_ELIMINAR'); ?>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </li>
                    </ul>
                </fieldset>
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('MAPA_SIITA'); ?>&nbsp;</legend> 
                    <div id="mapaContratos" class="width-100" style="height: 300px">
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>
